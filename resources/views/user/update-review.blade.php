@extends('layouts.apps')

@section('content')
<div class="container my-5 w-100 p-10">
    <h4 class="fw-bold mb-4 text-center">Edit Your Review</h4>

    <!-- 🛒 Product Info -->
    <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-4 mt-10 p-10">
        <img src="{{ asset('uploads/products/' . $review->product->image) }}" alt="Product" class="rounded me-3" width="60" height="60">
        <div>
            <h6 class="mb-0 fw-semibold">{{ $review->product->name }}</h6>
            <small class="text-muted">{{ $review->product->short_description }}</small>
        </div>
    </div>

    <!-- 📝 Review Form -->
    <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-10 rounded-3 shadow-sm">
        @csrf
        @method('PUT')
        <input type="hidden" name="product_id" value="{{ $review->product->id }}">

        <!-- ⭐ Rating -->
        <div class="mb-4">
            <label class="form-label fw-semibold d-block mb-2">Your Rating</label>
            <div class="star-rating">
                @for ($i = 5; $i >= 1; $i--)
                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ $review->rating == $i ? 'checked' : '' }}>
                    <label for="star{{ $i }}" title="{{ $i }} stars"></label>
                @endfor
            </div>
            @error('rating')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <!-- 📸 Upload Section -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Add Photo or Video</label>
            <div class="border border-2 border-dashed rounded-3 text-center py-4 bg-light upload-box position-relative">
                <input type="file" name="image" id="mediaUpload" class="d-none" accept="image/*">
                <label for="mediaUpload" class="text-muted upload-label" style="cursor:pointer;">
                    <i class="ph ph-upload-simple fs-3 d-block mb-2"></i>
                    Click here to upload
                </label>

                <!-- Preview Area -->
                <div id="preview" class="position-relative">
                    @if ($review->image)
                        <div class="position-relative">
                            <img src="{{ asset('uploads/reviews/' . $review->image) }}" alt="Review Image" id="previewImage" style="cursor:pointer;"> 
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                document.querySelector('.upload-label').style.display = 'none';
                            });
                        </script>
                    @endif
                </div>
            </div>
            @error('image')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <!-- 🗒️ Review Text -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Write your Review</label>
            <textarea name="comment" id="reviewText" rows="4" maxlength="400"
                class="form-control rounded-3"
                placeholder="Would you like to write anything about this product?">{{ old('comment', $review->comment) }}</textarea>
            <div class="text-end small text-muted mt-1" id="charCount">
                {{ 400 - strlen(old('comment', $review->comment ?? '')) }} characters remaining
            </div>
            @error('comment')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <!-- 🚀 Submit -->
        <button type="submit" class="btn btn-main rounded-pill flex-align d-inline-flex gap-8 px-48">
            Update Review
        </button>
    </form>
</div>

<!-- ⭐ CSS Styling -->
<style>
.star-rating {
    direction: rtl;
    display: inline-flex;
    gap: 6px;
}
.star-rating input { display: none; }
.star-rating label {
    font-size: 2rem;
    color: #ccc;
    cursor: pointer;
    transition: color 0.2s;
}
.star-rating label::before { content: "★"; }
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #f5b301;
}

.upload-box {
    transition: all 0.2s ease;
}
.upload-box:hover {
    background-color: #f8f9fa;
    border-color: #0d6efd;
}
#preview img {
    max-width: 100%;
    max-height: 240px;
    border-radius: 8px;
    margin-top: 10px;
    object-fit: contain;
}
.remove-preview {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(0,0,0,0.6);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    font-size: 16px;
    line-height: 26px;
    cursor: pointer;
}
</style>

<!-- 💡 JS: Live Preview + Replace Image Anytime + Character Counter -->
<script>
const mediaUpload = document.getElementById('mediaUpload');
const preview = document.getElementById('preview');
const label = document.querySelector('.upload-label');

// Handle image upload + replace functionality
function renderPreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.innerHTML = '';
        const wrapper = document.createElement('div');
        wrapper.classList.add('position-relative');

        const img = document.createElement('img');
        img.src = e.target.result;
        img.id = 'previewImage';
        img.style.cursor = 'pointer';
        img.addEventListener('click', () => mediaUpload.click());

        const removeBtn = document.createElement('button');
        removeBtn.innerHTML = '×';
        removeBtn.classList.add('remove-preview');
        removeBtn.type = 'button';
        removeBtn.addEventListener('click', () => {
            preview.innerHTML = '';
            mediaUpload.value = '';
            label.style.display = 'block';
        });

        wrapper.appendChild(img);
        wrapper.appendChild(removeBtn);
        preview.appendChild(wrapper);
        label.style.display = 'none';
    };
    reader.readAsDataURL(file);
}

mediaUpload.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) renderPreview(file);
});

// Allow replacing an existing image by clicking it
document.addEventListener('click', function(e) {
    if (e.target.id === 'previewImage') {
        mediaUpload.click();
    }
});

// Character Counter
const reviewText = document.getElementById('reviewText');
const charCount = document.getElementById('charCount');
reviewText.addEventListener('input', () => {
    const remaining = 400 - reviewText.value.length;
    charCount.textContent = `${remaining} characters remaining`;
});
</script>
@endsection
