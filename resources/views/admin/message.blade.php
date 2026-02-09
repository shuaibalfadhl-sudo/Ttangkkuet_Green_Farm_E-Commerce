@extends('layouts.admin')

@push('styles')
<style>
    /* Custom Green Theme */
    .bg-custom-green { background-color: #4CAF50 !important; }
    .text-custom-green { color: #4CAF50 !important; }
    .btn-custom-green {
        background-color: #4CAF50; border-color: #4CAF50; color: #fff;
    }
    .btn-custom-green:hover { background-color: #45a049; border-color: #45a049; }

    .bg-message-sent {
        background-color: #4CAF50; color: white;
        border-radius: 1.5rem; padding: 0.75rem 1.25rem;
    }
    .bg-message-received {
        background-color: #e9ecef; color: #333;
        border-radius: 1.5rem; padding: 0.75rem 1.25rem;
    }

    .chat-sidebar-item.active { background-color: #e0f2f1; color: #4CAF50 !important; }
    .chat-sidebar-item:hover { background-color: #e9f5e9; }
    .chat-sidebar-item.active .badge { background-color: #2e7d32 !important; }

    .chat-message-time { font-size: 0.75rem; color: #6c757d; }

    .messages-container {
        /* Adjusted height slightly for better mobile fit, still based on viewport */
        height: calc(100vh - 250px);
        overflow-y: auto;
        background-color: #f0f2f5;
    }
    .chat-sidebar-height {
        height: calc(100vh - 180px);
        overflow-y: auto;
    }
    /* Hide scrollbars */
    .messages-container::-webkit-scrollbar,
    .chat-sidebar-height::-webkit-scrollbar { width: 0; background: transparent; }
    .messages-container, .chat-sidebar-height { -ms-overflow-style: none; scrollbar-width: none; }

    .avatar-icon {
        background-color: #4CAF50; color: white;
        width: 48px; height: 48px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; font-size: 1.2rem; font-weight: bold;
    }
    .chat-header-avatar .avatar-icon {
        width: 40px; height: 40px; font-size: 1rem;
    }
    .chat-message-bubble { max-width: 75%; }

    /* New styles for image preview */
    .image-preview-item {
        position: relative;
        width: 60px; /* Smaller preview size */
        height: 60px;
        margin-right: 8px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #ddd;
        flex-shrink: 0;
    }
    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .image-preview-item .remove-preview {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #333;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        line-height: 12px;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
</style>
{{-- Note: This template assumes an icon library (like Font Awesome) is loaded for 'fas fa-...' icons. --}}
@endpush

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <h3 class="fw-semibold text-dark">
                <a href="{{ route('admin.messages.index') }}" class="text-decoration-none">Chat</a>
            </h3>
            
        </div>

        <div class="row g-3">
            {{-- Sidebar (User List) --}}
            {{-- Hides sidebar on mobile if a user is selected, shows it only on XL screens and up by default --}}
            <div id="sidebar-col" class="col-12 col-xl-4 @if(isset($selectedUser)) d-none d-xl-block @endif">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="p-3 border-bottom">
                        <form method="GET" action="{{ route('admin.messages.index') }}" class="mb-3" autocomplete="off">
                            <div class="position-relative">
                                <div class="input-group">
                                    <input id="admin-msg-search" type="text" name="search" class="form-control rounded-start-pill py-2"
                                        placeholder="Search Username..." value="{{ request('search') }}">
                                </div>
                                <div id="admin-msg-suggestions" class="list-group position-absolute w-100 mt-1" style="z-index:1050; display:none;"></div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body bg-white chat-sidebar-height rounded-bottom-3">
                        @forelse($users as $user)
                            <div class="mb-1">
                                <a href="{{ route('admin.messages.index', ['user_id' => $user->id]) }}"
                                    class="chat-sidebar-item d-flex align-items-center p-2 rounded-3 text-decoration-none
                                    {{ isset($selectedUser) && $selectedUser->id === $user->id ? 'active' : 'text-dark' }}">
                                    <div class="position-relative me-3">
                                        @if($user->profile_image)
                                            <img src="{{ asset('uploads/profile_images/' . $user->profile_image) }}"
                                                    alt="Profile" class="rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="avatar-icon bg-custom-green">
                                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 d-flex flex-column">
                                        <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                                    </div>
                                    @if(isset($user->unread_count) && $user->unread_count > 0)
                                        <span class="badge bg-custom-green rounded-pill ms-2">
                                            {{ $user->unread_count > 9 ? '9+' : $user->unread_count }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                        @empty
                            <p class="text-muted text-center mt-3">No users to display.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Chat area --}}
            {{-- Hides chat on mobile if no user is selected, ensures it's visible on XL screens and up by default --}}
            <div id="chat-col" class="col-12 col-xl-8 @if(!isset($selectedUser)) d-none @endif d-xl-block">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    @if (isset($selectedUser))
                        <div class="card-header bg-white border-bottom d-flex align-items-center py-3"> 
                            <div class="position-relative me-3 chat-header-avatar">
                                @if($selectedUser->avatar)
                                    <img src="{{ asset('avatars/' . $selectedUser->avatar) . '?' . time() }}"
                                            alt="Profile" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="avatar-icon bg-custom-green">
                                        {{ strtoupper(substr($selectedUser->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <h5 class="mb-0 fw-semibold">{{ $selectedUser->name }}</h5>
                            {{-- ✅ Mobile Back Button (d-xl-none hides it on large screens) --}}
                            <button id="back-to-users" type="button" class="btn btn-light d-xl-none me-3 ms-auto" title="Back to Users">
                                Back
                            </button>
                        </div>
                        @if(session('success'))
                            <div class="alert alert-success m-3">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div id="messages-container" class="card-body messages-container d-flex flex-column-reverse">
                            <div class="d-flex flex-column">
                                @forelse(array_reverse($messages->toArray()) as $msg) 
                                    @php
                                        // Cast array to object for consistent access
                                        $msg = (object)$msg;
                                    @endphp
                                    @if ($msg->sender_id == Auth::id())
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="bg-message-sent chat-message-bubble">
                                                @if($msg->attachment)
                                                    <div class="mb-2">
                                                        <a href="{{ asset($msg->attachment) }}" target="_blank">
                                                            <img src="{{ asset($msg->attachment) }}" alt="attachment"
                                                                    style="max-width:300px;max-height:300px;border-radius:8px;object-fit:cover;">
                                                        </a>
                                                    </div>
                                                @endif
                                                @if($msg->message)
                                                    <p class="mb-1 text-white">{{ $msg->message }}</p>
                                                @endif
                                                <small class="text-white-50 chat-message-time">{{ \Carbon\Carbon::parse($msg->created_at)->format('g:i A') }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-start mb-3">
                                            {{-- Use border-success for unread message from user --}}
                                            <div class="bg-message-received chat-message-bubble {{ $msg->is_read ? '' : 'border border-success' }}">
                                                @if($msg->attachment)
                                                    <div class="mb-2">
                                                        <a href="{{ asset($msg->attachment) }}" target="_blank">
                                                            <img src="{{ asset($msg->attachment) }}" alt="attachment"
                                                                    style="max-width:300px;max-height:300px;border-radius:8px;object-fit:cover;">
                                                        </a>
                                                    </div>
                                                @endif
                                                @if($msg->message)
                                                    <p class="mb-1 text-primary">{{ $msg->message }}</p>
                                                @endif
                                                <small class="text-muted chat-message-time">{{ \Carbon\Carbon::parse($msg->created_at)->format('g:i A') }}</small>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <p class="text-center text-muted mt-3">No messages yet.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Attachment + Message Form with Preview --}}
                        <div class="card-footer bg-white border-top py-3">
                            <form id="chat-form" action="{{ route('admin.message.send') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column w-100">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">

                                {{-- Image Preview Container (Hidden by default) --}}
                                <div id="image-preview-container" class="d-flex flex-row flex-wrap align-items-center mb-2" style="display: none;">
                                    {{-- Preview items will be inserted here by JavaScript --}}
                                </div>

                                <div class="d-flex align-items-center w-100">
                                    {{-- Visible attachment button --}}
                                    <label for="admin-attachment" class="btn btn-outline-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" title="Attach Image" style="width:42px;height:42px;cursor:pointer;">
                                        <input type="file" id="admin-attachment" name="attachment" accept="image/*" style="display:none;">
                                        <i class="icon-image fs-100 text-custom-green"></i>
                                    </label>

                                    <input type="text" name="message" class="form-control rounded-pill me-2 py-2"
                                        placeholder="Type a message...">

                                    <button type="submit" class="btn btn-custom-green rounded-pill fw-semibold px-4 py-2">✈︎</button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="card-body text-center text-muted py-5">
                            <p>Select a user from the sidebar to start chatting.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const messagesContainer = document.getElementById('messages-container');
    // Scroll to bottom on load (or use 0 for reverse order display, which is often better for chat)
    if (messagesContainer) messagesContainer.scrollTop = 0;

    @if(isset($selectedUser))
    // Mark messages as read
    fetch("{{ route('admin.messages.markRead') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ user_id: {{ $selectedUser->id }} })
    }).then(res => {
        if (res.ok) {
            const badge = document.querySelector('.chat-sidebar-item.active .badge');
            if (badge) badge.remove();
        }
    });
    @endif

    // 🚀 MOBILE RESPONSIVENESS LOGIC 🚀
    const sidebarCol = document.getElementById('sidebar-col');
    const chatCol = document.getElementById('chat-col');
    const backButton = document.getElementById('back-to-users');
    const xlBreakpoint = 1200; // Bootstrap's 'xl' breakpoint

    // Function to show the sidebar (user list) and hide the chat area on small screens
    function showSidebar() {
        if (window.innerWidth < xlBreakpoint) {
            if (sidebarCol) {
                sidebarCol.classList.remove('d-none'); // Show sidebar
            }
            if (chatCol) {
                chatCol.classList.add('d-none'); // Hide chat area
            }
        }
    }

    // Handle back button click to return to user list on mobile
    if (backButton) {
        backButton.addEventListener('click', (e) => {
            e.preventDefault();
            showSidebar();
            // Clear the user_id from the URL to reflect the state change
            history.pushState(null, null, "{{ route('admin.messages.index') }}");
        });
    }

    // 📸 Image Preview Logic
    const fileInput = document.getElementById('admin-attachment');
    const previewContainer = document.getElementById('image-preview-container');

    if (fileInput && previewContainer) {
        fileInput.addEventListener('change', function(event) {
            // Clear previous previews (only handling one file per message)
            previewContainer.innerHTML = '';

            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Create the preview structure
                    const previewItem = document.createElement('div');
                    previewItem.className = 'image-preview-item';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Image Preview';

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-preview';
                    removeBtn.type = 'button';
                    removeBtn.innerHTML = '&times;';

                    // Event listener to remove the preview and clear the file input
                    removeBtn.addEventListener('click', function() {
                        previewContainer.innerHTML = '';
                        previewContainer.style.display = 'none';
                        fileInput.value = ''; // Clear the actual file input
                    });

                    previewItem.appendChild(img);
                    previewItem.appendChild(removeBtn);
                    previewContainer.appendChild(previewItem);

                    // Show the container
                    previewContainer.style.display = 'flex';
                };

                reader.readAsDataURL(file);
            } else {
                // Hide if no file is selected
                previewContainer.style.display = 'none';
            }
        });
    }
});
</script>

<script>
// Autocomplete Search Logic
(function(){
    const input = document.getElementById('admin-msg-search');
    const container = document.getElementById('admin-msg-suggestions');
    if (!input || !container) return;

    let timeout = null;
    function clearSuggestions(){ container.innerHTML = ''; container.style.display = 'none'; }
    function renderSuggestions(items){
        container.innerHTML = '';
        if (!items || items.length === 0) { clearSuggestions(); return; }
        items.forEach(u => {
            const a = document.createElement('a');
            a.className = 'list-group-item list-group-item-action d-flex align-items-center';
            a.href = '{{ route('admin.messages.index') }}?user_id=' + u.id;
            // Uses ui-avatars.com as a fallback avatar
            const avatar = u.profile_image 
                                    ? `{{ asset('uploads/profile_images/') }}/${u.profile_image}` 
                                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(u.name || 'U')}&background=4CAF50&color=fff`;
            a.innerHTML = `<img src="${avatar}" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;">
                            <div><div class="fw-semibold">${u.name}</div>
                            <div class="text-muted small">${u.email || ''}</div></div>`;
            container.appendChild(a);
        });
        container.style.display = 'block';
    }

    input.addEventListener('input', function(){
        const q = input.value.trim();
        if (timeout) clearTimeout(timeout);
        if (q.length < 2) { clearSuggestions(); return; }
        timeout = setTimeout(() => {
            // Note: Requires a route named 'admin.messages.live_search'
            fetch(`{{ route('admin.messages.live_search') }}?q=${encodeURIComponent(q)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(r => r.json()).then(data => {
                renderSuggestions(data);
            }).catch(() => clearSuggestions());
        }, 250);
    });

    document.addEventListener('click', function(ev){
        if (!container.contains(ev.target) && ev.target !== input) clearSuggestions();
    });
})();
</script>
@endpush