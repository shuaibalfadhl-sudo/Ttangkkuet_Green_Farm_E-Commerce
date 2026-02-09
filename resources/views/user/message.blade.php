@extends('layouts.apps')

@section('content')
<style>
    /* ===== Full-width Messenger Layout ===== */
    body {
        background-color: #f0f2f5 !important;
    }

    .chat-page {
        display: flex;
        justify-content: center;
        align-items: center;
        height: calc(100vh - 70px);
    }

    .chat-wrapper {
        width: 100%;
        max-width: 1400px; /* covers almost the whole page */
        height: 85vh;
        display: flex;
        flex-direction: column;
        border-radius: 10px;
        overflow: hidden;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .chat-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 24px;
        border-bottom: 1px solid #e0e0e0;
        background-color: #F3FAF2;
    }

    .chat-header img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
    }

    .chat-header .info {
        flex: 1;
    }

    .chat-header .info .name {
        font-weight: 600;
        font-size: 17px;
    }

    .chat-header .info .status {
        font-size: 13px;
        color: #888;
    }

    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 24px 40px;
        background-color: #f0f2f5;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .chat-message {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        max-width: 60%;
    }

    .chat-message.user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .chat-message.admin {
        align-self: flex-start;
        flex-direction: row;
    }

    .chat-bubble {
        padding: 10px 16px;
        border-radius: 20px;
        font-size: 15px;
        line-height: 1.4;
        word-break: break-word;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .chat-message.user .chat-bubble {
        background-color: #299E60;
        color: #fff;
        border-bottom-right-radius: 4px;
    }

    .chat-message.admin .chat-bubble {
        background-color: #e4e6eb;
        color: #000;
        border-bottom-left-radius: 4px;
    }

    .chat-time {
        font-size: 12px;
        color: #888;
        margin-top: 4px;
        text-align: right;
    }

    /* --- Start of New/Modified Input Styles --- */

    .chat-footer {
        border-top: 1px solid #e0e0e0;
        background-color: #F3FAF2;
        /* Padding is now handled by the inner container for alignment */
        padding: 12px 24px; 
    }
    
    .chat-input-wrapper {
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }

    .chat-input {
        display: flex;
        align-items: flex-end; /* Align to the bottom when the preview is shown */
        gap: 10px;
    }

    /* The main input area where text is typed */
    .message-input-area {
        flex: 1;
        border: 1px solid #ccc;
        border-radius: 25px;
        padding: 8px 18px; /* Slightly adjusted padding for the inner div */
        outline: none;
        background-color: #f0f2f5;
        font-size: 15px;
        display: flex;
        align-items: center;
        position: relative;
    }

    .message-input-area input[type="text"] {
        flex: 1;
        border: none;
        padding: 4px 0; /* Adjusted padding */
        outline: none;
        background-color: transparent;
        font-size: 15px;
    }

    .message-input-area:focus-within {
        background-color: #fff;
        border-color: #299E60;
    }

    .chat-input button {
        background-color: #299E60;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .chat-input button:hover {
        background-color: #0073e6;
    }

    /* Image Preview Styles */
    .image-preview-item {
        position: relative;
        width: 60px; 
        height: 60px;
        margin-right: 8px;
        border-radius: 8px;
        overflow: hidden;
        /* Border color similar to the chat bubbles */
        border: 2px solid rgba(0,0,0,0.1); 
        flex-shrink: 0;
        margin-left: 0px; /* Aligns with the attachment icon area */
    }
    
    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .image-preview-item .remove-preview {
        position: absolute;
        top: -4px;
        right: -4px;
        /* Matches the color in your screenshot for the close button */
        background-color: #333; 
        color: white;
        border: none;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 12px;
        line-height: 1;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    
    .attachment-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 45px; /* Matches send button size for better alignment */
        height: 45px; 
        color: #888;
    }

    /* The visual wrapper around the preview and input */
    .preview-and-input-container {
        display: flex;
        align-items: center;
        width: 100%;
        background-color: #f0f2f5;
        border-radius: 25px;
        padding-left: 8px;
    }
    
    /* Container for the preview items */
    #image-preview-container {
        display: none; /* Will be set to 'flex' by JS when an image is selected */
        padding: 8px 8px 8px 0;
    }
    /* --- End of New/Modified Input Styles --- */
</style>

<div class="chat-page">
    <div class="chat-wrapper">
        {{-- Header --}}
        <div class="chat-header">
            @if($admin)
                <img src="{{ $admin->profile_image 
                ? asset('uploads/profile_images/' . $admin->profile_image) 
                : 'https://ui-avatars.com/api/?name=' . urlencode($admin->name ?? 'Admin') . '&background=6c757d&color=fff' }}" alt="Admin">
                <div class="info">
                    <div class="name">{{ $admin->name ?? 'Admin' }}</div>
                </div>
            @else
                <div class="fw-bold">No admin found</div>
            @endif
        </div>
        @if(session('success'))
            <div class="alert alert-success m-3">
                {{ session('success') }}
            </div>
        @endif
        {{-- Messages --}}
        <div id="user-messages" class="chat-body">
            @forelse($messages as $msg)
                @if($msg->sender_id === auth()->id())
                    {{-- User Message (Green Bubble) --}}
                    <div class="chat-message user">
                        <div class="chat-bubble">
                            @if($msg->attachment)
                                <div class="mb-2">
                                    <a href="{{ asset($msg->attachment) }}" target="_blank">
                                        <img src="{{ asset($msg->attachment) }}" alt="attachment" style="max-width:220px;max-height:220px;border-radius:8px;object-fit:cover;">
                                    </a>
                                </div>
                            @endif
                            @if($msg->message)
                                <p class="mb-1 text-white">{{ $msg->message }}</p>
                            @endif
                            <small class="text-white-50 chat-time">{{ $msg->created_at->format('g:i A') }}</small>
                        </div>
                    </div> 
                @else
                    {{-- Admin Message (Gray Bubble) - FIX IS APPLIED HERE --}} 
                    <div class="chat-message admin">
                        <img src="{{ $admin->profile_image 
                        ? asset('uploads/profile_images/' . $admin->profile_image) 
                        : 'https://ui-avatars.com/api/?name=' . urlencode($admin->name ?? 'Admin') . '&background=6c757d&color=fff' }}" alt="Admin" width="36" height="36" style="border-radius:50%;">
                        <div class="chat-bubble"> 
                           @if($msg->attachment)
                                <div class="mb-2">
                                    <a href="{{ asset($msg->attachment) }}" target="_blank">
                                        <img src="{{ asset($msg->attachment) }}" alt="attachment" style="max-width:220px;max-height:220px;border-radius:8px;object-fit:cover;">
                                    </a>
                                </div>
                            @endif
                           @if($msg->message)
                                <p class="mb-1 text-black">{{ $msg->message }}</p>
                           @endif
                            <small class="text-muted chat-time">{{ $msg->created_at->format('g:i A') }}</small>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center text-muted mt-5">No messages yet. Start the conversation!</div>
            @endforelse
        </div>

        {{-- Input (Revised with Preview) --}}
        <div class="chat-footer">
            @if($admin)
                <form method="POST" action="{{ route('user.message.send') }}" id="message-form" class="chat-input-wrapper" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $admin->id }}">
                    <div id="image-preview-container" class="d-flex flex-row flex-wrap align-items-center me-2">
                                {{-- Preview items go here --}}
                            </div>
                    <div class="chat-input">
                        
                        {{-- Attachment Icon --}}
                        <label for="attachment" class="attachment-icon-wrapper" style="cursor:pointer;margin-right:6px;">
                            <input type="file" id="attachment" name="attachment" accept="image/*" style="display:none;">
                            <i class="ph ph-image fs-5" title="Attach image"></i>
                        </label>
                        
                        {{-- Input Field and Preview Area --}}
                        <div class="message-input-area">
                            
                            <input type="text" name="message" placeholder="Aa">
                        </div>
                        
                        {{-- Send Button --}}
                        <button type="submit"><i class="ph ph-paper-plane-tilt fs-5"></i></button>
                    </div>
                </form>
            @else
                <div class="text-center text-muted small">No admin available to chat.</div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('user-messages');
        // Scroll to the bottom on load to show latest messages
        if (container) container.scrollTop = container.scrollHeight;

        // Image Preview Logic
        const fileInput = document.getElementById('attachment');
        const previewContainer = document.getElementById('image-preview-container');
        const messageInputArea = document.querySelector('.message-input-area');

        if (fileInput && previewContainer && messageInputArea) {
            fileInput.addEventListener('change', function(event) {
                // Clear previous previews
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
                            // Restore placeholder text if message is empty
                            if (!messageInputArea.querySelector('input[name="message"]').value) {
                                messageInputArea.querySelector('input[name="message"]').placeholder = "Aa";
                            }
                        });

                        previewItem.appendChild(img);
                        previewItem.appendChild(removeBtn);
                        
                        // Add to container and show
                        previewContainer.appendChild(previewItem);
                        previewContainer.style.display = 'flex';
                        
                        // Hide the placeholder text when an image is attached
                        messageInputArea.querySelector('input[name="message"]').placeholder = "";
                    };

                    reader.readAsDataURL(file);
                } else {
                    // Hide if no file is selected
                    previewContainer.style.display = 'none';
                    messageInputArea.querySelector('input[name="message"]').placeholder = "Aa";
                }
            });
            
            // Re-show placeholder if message input becomes empty and no file is attached
            messageInputArea.querySelector('input[name="message"]').addEventListener('input', function() {
                if (this.value === '' && fileInput.files.length === 0) {
                    this.placeholder = "Aa";
                } else if (this.value !== '') {
                    this.placeholder = "";
                }
            });
        }
    });
</script>
@endpush