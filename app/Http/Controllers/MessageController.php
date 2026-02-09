<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Show conversation between authenticated user and the admin
     */
    public function userConversation()
    {
        $user = Auth::user();

        // Find the admin using utype (ADM)
        $admin = User::where('utype', 'ADM')->first();

        // If no admin exists, return the view with empty messages and null admin
        if (!$admin) {
            $messages = collect();
            return view('user.message', compact('messages', 'admin'));
        }

        // Fetch all messages between this user and the admin
        $messages = Message::where(function ($query) use ($user, $admin) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $admin->id);
            })
            ->orWhere(function ($query) use ($user, $admin) {
                $query->where('sender_id', $admin->id)
                      ->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark admin->user messages as read when the user views the page
        Message::where('sender_id', $admin->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('user.message', compact('messages', 'admin'));
    }

    /**
     * Send message from user to admin
     */
    public function userSend(Request $request)
    {
        
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        // Prevent empty message + no attachment
        if (!$request->message && !$request->hasFile('attachment')) {
            return back()->with('error', 'Please enter a message or attach an image.');
        }

        $user = Auth::user();
        $admin = User::where('utype', 'ADM')->first();

        if (!$admin) {
            return back()->with('error', 'Admin not found.');
        }

        $message = new Message();
        $message->sender_id = Auth::id();
        $message->receiver_id = $request->receiver_id;
        $message->message = $request->message ?? ''; // fallback to empty string

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/messages');
            if (!file_exists($uploadPath)) mkdir($uploadPath, 0775, true);
            $file->move($uploadPath, $filename);
            $message->attachment = 'uploads/messages/' . $filename;
        }

        $message->save();

        return back()->with('success', 'Message sent successfully!'); 
    }

    /**
     * Admin view: show all conversations (list of users)
     */
    public function adminConversations()
    {
        $admin = Auth::user();

        if ($admin->utype !== 'ADM') {
            abort(403, 'Unauthorized');
        }

        // Get all distinct users who have chatted with admin
        $users = Message::where('sender_id', $admin->id)
            ->orWhere('receiver_id', $admin->id)
            ->with(['sender', 'receiver'])
            ->get()
            ->map(function ($message) use ($admin) {
                return $message->sender_id === $admin->id
                    ? $message->receiver
                    : $message->sender;
            })
            ->unique('id')
            ->values();

        return view('admin.message', compact('users'));
    }

    /**
     * Admin index/listing for conversations and open a selected user conversation.
     * This matches the routes used in `web.php` and the admin view.
     */
    public function index(Request $request)
{
    $admin = Auth::user();

    if ($admin->utype !== 'ADM') {
        abort(403, 'Unauthorized');
    }

    // --- Get all users except the admin ---
    $users = User::where('id', '!=', $admin->id)
        ->where('utype', 'USR') // optional: limit to normal users
        ->get();

    // Attach unread_count and latest_message_time for each user
    $users->transform(function ($user) use ($admin) {
        $user->unread_count = Message::where('sender_id', $user->id)
            ->where('receiver_id', $admin->id)
            ->where('is_read', false)
            ->count();

        $user->latest_message_time = Message::where(function ($q) use ($user, $admin) {
                $q->where('sender_id', $user->id)->where('receiver_id', $admin->id);
            })
            ->orWhere(function ($q) use ($user, $admin) {
                $q->where('sender_id', $admin->id)->where('receiver_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->value('created_at');

        return $user;
    });

    // Sort users by latest message (newest first)
    $users = $users->sortByDesc('latest_message_time')->values();

    // --- Search filter (by name or email) ---
    $search = trim((string)$request->input('search', ''));
    if ($search !== '') {
        $lower = mb_strtolower($search);
        $users = $users->filter(function ($user) use ($lower) {
            return str_contains(mb_strtolower($user->name ?? ''), $lower) ||
                   str_contains(mb_strtolower($user->email ?? ''), $lower);
        })->values();
    }

    // --- Handle selected chat user ---
    $selectedUser = null;
    $messages = collect();

    if ($request->has('user_id')) {
        $selectedUser = User::find($request->input('user_id'));

        if ($selectedUser) {
            $messages = Message::where(function ($query) use ($selectedUser, $admin) {
                    $query->where('sender_id', $selectedUser->id)
                          ->where('receiver_id', $admin->id);
                })
                ->orWhere(function ($query) use ($selectedUser, $admin) {
                    $query->where('sender_id', $admin->id)
                          ->where('receiver_id', $selectedUser->id);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            // mark as read
            Message::where('sender_id', $selectedUser->id)
                ->where('receiver_id', $admin->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            // refresh unread counts
            $users = $users->map(function ($u) use ($admin) {
                $u->unread_count = Message::where('sender_id', $u->id)
                    ->where('receiver_id', $admin->id)
                    ->where('is_read', false)
                    ->count();
                return $u;
            });
        }
    }

    return view('admin.message', compact('users', 'selectedUser', 'messages'));
}



    /**
     * Admin sends a message to a specific user (route: admin.message.send)
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        // Prevent empty message + no attachment
        if (!$request->message && !$request->hasFile('attachment')) {
            return back()->with('error', 'Please enter a message or attach an image.');
        }

        $message = new Message();
        $message->sender_id = Auth::id();
        $message->receiver_id = $request->receiver_id;
        $message->message = $request->message ?? ''; // fallback to empty string

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/messages');
            if (!file_exists($uploadPath)) mkdir($uploadPath, 0775, true);
            $file->move($uploadPath, $filename);
            $message->attachment = 'uploads/messages/' . $filename;
        }

        $message->save();

        return back()->with('success', 'Message sent successfully!');
    }



    /**
     * Mark messages from a specific user to the admin as read (AJAX endpoint)
     */
    public function markRead(Request $request)
    {
        $admin = Auth::user();

        if ($admin->utype !== 'ADM') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'user_id required'], 422);
        }

        Message::where('sender_id', $userId)
            ->where('receiver_id', $admin->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Live search endpoint for admin message search (returns JSON suggestions)
     */
    public function liveSearch(Request $request)
    {
        $admin = Auth::user();
        if (!$admin || $admin->utype !== 'ADM') {
            return response()->json([], 403);
        }

        $q = trim((string) $request->input('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $qLower = mb_strtolower($q);

        // Find users who have chatted with admin OR all users — here we prefer users who chatted first
        $chattedUserIds = Message::where('sender_id', $admin->id)
            ->orWhere('receiver_id', $admin->id)
            ->get()
            ->map(function ($m) use ($admin) {
                return $m->sender_id === $admin->id ? $m->receiver_id : $m->sender_id;
            })
            ->unique()
            ->values()
            ->toArray();

        $usersQuery = \App\Models\User::query();

        // If there are chatted users, restrict to those first for relevance
        if (!empty($chattedUserIds)) {
            $usersQuery->whereIn('id', $chattedUserIds);
        }

        $users = $usersQuery->where(function ($qbuild) use ($q) {
                $qbuild->where('name', 'LIKE', "%{$q}%")
                       ->orWhere('email', 'LIKE', "%{$q}%");
            })
            ->select('id', 'name', 'email', 'avatar')
            ->limit(8)
            ->get();

        // Format avatar URL
        $users->transform(function ($u) {
            $u->avatar_url = $u->avatar ? asset('avatars/' . $u->avatar) . '?' . time() : 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=0D8ABC&color=fff';
            return $u;
        });

        return response()->json($users);
    }

    /**
     * Admin opens conversation with a specific user
     */
    public function adminConversation($userId)
    {
        $admin = Auth::user();
        $user = User::findOrFail($userId);

        if ($admin->utype !== 'ADM') {
            abort(403, 'Unauthorized');
        }

        $messages = Message::where(function ($query) use ($user, $admin) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $admin->id);
            })
            ->orWhere(function ($query) use ($user, $admin) {
                $query->where('sender_id', $admin->id)
                      ->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.message', compact('messages', 'user'));
    }

    /**
     * Admin sends message to a specific user
     */
    public function adminSend(Request $request, $userId)
    {
        $request->validate([
            'message' => 'required_without:attachment|string|max:1000',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $admin = Auth::user();
        $user = User::findOrFail($userId);

        if ($admin->utype !== 'ADM') {
            abort(403, 'Unauthorized');
        }

        $data = [
            'sender_id' => $admin->id,
            'receiver_id' => $user->id,
            'message' => $request->message,
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/messages'), $filename);
            $data['attachment'] = $filename;
        }

        Message::create($data);

        return back()->with('success', 'Message sent to user!');
    }
}
