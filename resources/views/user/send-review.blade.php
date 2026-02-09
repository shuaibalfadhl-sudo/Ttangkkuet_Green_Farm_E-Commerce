@extends('layouts.apps')

@section('content')
<div class="container my-5 w-100 p-10">
    <h4 class="fw-bold mb-4 text-center">Write a Review</h4>

    <!-- 🛒 Product Info -->
    <div class="d-flex align-items-center p-3 bg-light rounded-3 shadow-sm mb-4 mt-10 p-10">
        <img src="{{ asset('uploads/products/' . $product->image) }}" alt="Product" class="rounded me-3" width="60" height="60">
        <div>
            <h6 class="mb-0 fw-semibold">{{ $product->name }}</h6>
            <small class="text-muted">{{ $product->short_description }}</small>
        </div>
    </div>

    <!-- 📝 Review Form -->
    <form action="{{ route('review.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-10 rounded-3 shadow-sm">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <!-- ⭐ Rating -->
        <div class="mb-4">
            <label class="form-label fw-semibold d-block mb-2">Your Rating</label>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars"></label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars"></label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars"></label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars"></label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star"></label>
            </div>
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

                <!-- Live Preview -->
                <div id="preview" class="position-relative"></div>
            </div>
        </div>

        <!-- 🗒️ Review Text -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Write your Review</label>
            <textarea name="comment" id="reviewText" rows="4" maxlength="400"
                class="form-control rounded-3"
                placeholder="Would you like to write anything about this product?"></textarea>
            <div class="text-end small text-muted mt-1" id="charCount">400 characters remaining</div>
        </div>

        <!-- 🚀 Submit -->
        <button type="submit" class="btn btn-main rounded-pill flex-align d-inline-flex gap-8 px-48">
            Submit Review
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
.star-rating input {
    display: none;
}
.star-rating label {
    font-size: 2rem;
    color: #ccc;
    cursor: pointer;
    transition: color 0.2s;
}
.star-rating label::before {
    content: "★";
}
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
    cursor: pointer;
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

<!-- 💡 JS: Live Preview + Character Counter + Click to Change Anytime -->
<script>
const mediaUpload = document.getElementById('mediaUpload');
const preview = document.getElementById('preview');
const label = document.querySelector('.upload-label');

// Live Preview and Re-upload by Clicking the Image
mediaUpload.addEventListener('change', function(event) {
    preview.innerHTML = ''; // clear previous preview
    const file = event.target.files[0];
    if (!file) {
        label.style.display = 'block';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('position-relative');

        const img = document.createElement('img');
        img.src = e.target.result;

        // Allow clicking the image itself to re-upload
        img.addEventListener('click', () => {
            mediaUpload.click();
        });

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
