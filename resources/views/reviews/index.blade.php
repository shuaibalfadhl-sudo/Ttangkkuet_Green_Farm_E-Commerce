@extends('layouts.apps')

@section('content')
<div class="breadcrumb py-26 bg-color-one">
    <div class="container container-lg">
        <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
            <h6 class="mb-0">Product Reviews for {{ $product->name }}</h6>
            <ul class="flex-align gap-8 flex-wrap">
                <li class="text-sm"><a href="{{ route('home.index') }}" class="text-main-600">Home</a></li>
                <li class="flex-align text-gray-500"><i class="ph ph-caret-right"></i></li>
                <li class="text-sm"><a href="{{ route('shop.index') }}" class="text-main-600">Shop</a></li>
                <li class="flex-align text-gray-500"><i class="ph ph-caret-right"></i></li>
                <li class="text-sm"><a href="{{ route('shop.product.details', $product->slug) }}" class="text-main-600">{{ $product->name }}</a></li>
                <li class="flex-align text-gray-500"><i class="ph ph-caret-right"></i></li>
                <li class="text-sm text-neutral-600">Reviews</li>
            </ul>
        </div>
    </div>
</div>

<section class="reviews-page">
    <div class="container container-lg">
        <h2 class="mb-32">All Reviews</h2>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('shop.product.reviews', $product->slug) }}" class="mb-40 p-24 border rounded-8 bg-main-50">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="sort" class="form-label">Sort By</label>
                    <select name="sort" id="sort" class="form-select common-input">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest to Oldest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest to Newest</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="rating" class="form-label">Filter by Rating</label>
                    <select name="rating" id="rating" class="form-select common-input">
                        <option value="">All Ratings</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-main w-100">Apply Filters</button>
                </div>
            </div>
        </form>

        <!-- Reviews List -->
        <div class="reviews-list">
            @if($reviews->isEmpty())
                <p class="text-center text-gray-600">No reviews match your criteria.</p>
            @else
                @foreach($reviews as $review)
                    <div class="d-flex align-items-start gap-24 pb-44 border-bottom border-gray-100 mb-44">
                        @if($review->user->profile_image) 
                            <img src="{{ asset('uploads/profile_images/' . $review->user->profile_image) }}" alt="Profile" class="w-52 h-52 object-fit-cover rounded-circle flex-shrink-0" > 
                        @else 
                            <div class="w-52 h-52 object-fit-cover rounded-circle flex-shrink-0 bg-gray-600 text-white flex-center text-lg fw-bold"> 
                                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }} 
                            </div> 
                        @endif
                        <div class="flex-grow-1">
                            <div class="flex-between align-items-start gap-8 ">
                                <div>
                                    <h6 class="mb-12 text-md">{{ $review->user->name}}</h6>
                                    <div class="flex-align gap-8">
                                        {{-- Star Rating Display --}}
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg width="20" height="20" viewBox="0 0 24 24" style="margin-right: 2px;" xmlns="http://www.w3.org/2000/svg" fill="{{ $i <= $review->rating ? '#ffc107' : '#ccc' }}">
                                                <path d="M12 .587l3.668 7.568L24 9.423l-6 5.846 1.417 8.254L12 18.897l-7.417 4.626L6 15.269 0 9.423l8.332-1.268z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-gray-800 text-xs">{{ $review->created_at->format('F d, Y') }}</span>
                            </div>
                            
                            <p class="text-gray-700">{{ $review->comment }}</p>
                            
                            @if ($review->image)
                                <div class="review-image mt-3">
                                    <img src="{{ asset('uploads/reviews/' . $review->image) }}" alt="Review Image"
                                        class="review-thumb"
                                        onclick="openImageModal('{{ asset('uploads/reviews/' . $review->image) }}')">
                                </div>
                            @endif 
                            
                            <div class="flex-align gap-20 mt-44">
                                @auth
                                    {{-- Like/Unlike Functionality --}}
                                    @if (auth()->user()->isLikedBy($review))
                                        {{-- User has already liked it (Show Unlike button) --}}
                                        <form method="POST" action="{{ route('reviews.unlike', $review->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex-align gap-12 text-main-600 fw-bold">
                                                <i class="ph-fill ph-thumbs-up"></i>
                                                Liked ({{ $review->likes_count }})
                                            </button>
                                        </form>
                                    @else
                                        {{-- User has not liked it (Show Like button) --}}
                                        <form method="POST" action="{{ route('reviews.like', $review->id) }}">
                                            @csrf
                                            <button type="submit" class="flex-align gap-12 text-gray-700 hover-text-main-600">
                                                <i class="ph-bold ph-thumbs-up"></i>
                                                Like ({{ $review->likes_count }})
                                            </button>
                                        </form>
                                    @endif

                                    {{-- 🗑️ ADMIN-ONLY Delete Button --}}
                                    {{-- Check if the current user's utype is ADM --}}
                                    @if (auth()->user()->utype === 'ADM')
                                        <form method="POST" action="{{ route('reviews.delete', $review->id) }}" onsubmit="return confirm('ADMIN ACTION: Are you sure you want to delete this review?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex-align gap-12 text-danger hover-text-danger fw-bold">
                                                <i class="ph-bold ph-trash"></i> 
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    {{-- Not logged in (Show static count and a link to login) --}}
                                    <a href="{{ route('login') }}" class="flex-align gap-12 text-gray-700 hover-text-main-600">
                                        <i class="ph-bold ph-thumbs-up"></i>
                                        Like ({{ $review->likes_count }})
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="mt-40">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</section>

<!-- 🖼️ Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<!-- 💅 CSS -->
<style>
.review-thumb {
    width: 120px;
    height: 120px;
    border-radius: 8px;
    object-fit: cover;
    cursor: pointer;
    transition: transform 0.2s ease;
}
.review-thumb:hover {
    transform: scale(1.05);
}
.image-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    padding-top: 70px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.85);
}
.image-modal img {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 85vh;
    border-radius: 10px;
}
.image-modal .close {
    position: absolute;
    top: 20px;
    right: 40px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}
.image-modal .close:hover {
    color: #ccc;
}
</style>

<!-- ⚙️ JavaScript -->
<script>
function openImageModal(src) {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    modal.style.display = "block";
    modalImg.src = src;
}
function closeImageModal() {
    document.getElementById("imageModal").style.display = "none";
}
</script>
@endsection
