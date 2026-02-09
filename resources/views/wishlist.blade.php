@extends('layouts.apps')
@section('content')
<div class="breadcrumb py-26 bg-main-50">
    <div class="container container-lg">
        <div class="breadcrumb-wrapper flex-between flex-wrap gap-16">
            <h6 class="mb-0">Cart</h6>
            <ul class="flex-align gap-8 flex-wrap">
                <li class="text-sm">
                    <a href="{{ route('home.index') }}" class="text-gray-900 flex-align gap-8 hover-text-main-600">
                        <i class="ph ph-house"></i>
                        Home
                    </a>
                </li>
                <li class="flex-align">
                    <i class="ph ph-caret-right"></i>
                </li>
                <li class="text-sm text-main-600"> Wishlist </li>
            </ul>
        </div>
    </div>
</div>
<!-- ========================= Breadcrumb End =============================== -->

    <!-- ================================ Cart Section Start ================================ -->
 <section class="cart py-80">
    <div class="container container-lg">
        <div class="row gy-4">
            <div class="col-xl-9 col-lg-8 w-100">
                <div class="cart-table border border-gray-100 rounded-8 px-40 py-48">
                    <div class="overflow-x-auto scroll-sm scroll-sm-horizontal">
                      @if (Cart::instance('wishlist')->content()->count()>0)
                        <table class="table style-three">
                            <thead>
                                <tr>
                                    <th class="h6 mb-0 text-lg fw-bold">Delete</th>
                                    <th class="h6 mb-0 text-lg fw-bold">Product Image</th>
                                    <th class="h6 mb-0 text-lg fw-bold">Product Name</th>
                                    <th class="h6 mb-0 text-lg fw-bold">Product Rating</th>
                                    <th class="h6 mb-0 text-lg fw-bold">Price</th> 
                                    <th class="h6 mb-0 text-lg fw-bold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($items as $item)
                              <?php
                                $ratings = $item->model->reviews->pluck('rating')->toArray();

                                $medianRating = 0;

                                if (count($ratings) > 0) {
                                    sort($ratings); // sort ascending
                                    $count = count($ratings);
                                    $middle = floor(($count - 1) / 2);

                                    if ($count % 2) {
                                        // Odd count
                                        $medianRating = $ratings[$middle];
                                    } else {
                                        // Even count
                                        $lowMiddle = $ratings[$middle];
                                        $highMiddle = $ratings[$middle + 1];
                                        $medianRating = ($lowMiddle + $highMiddle) / 2;
                                    }
                                }
                                ?>
                                <tr>
                                    <td>
                                      <form method="POST" action="{{route('wishlist.item.remove',['rowId'=>$item->rowId])}}" id="remove-item-{{$item->id}}">
                                      @csrf
                                      @method('DELETE')
                                        <button type="submit" class="remove-tr-btn flex-align gap-12 hover-text-danger-600">
                                            <i class="ph ph-x-circle text-2xl d-flex"></i>
                                            Remove
                                        </button>
                                      </form> 
                                    </td>
                                    <td>
                                        <div class="table-product d-flex align-items-center gap-24">
                                            <a href="{{ route('shop.product.details', ['product_slug' => $item->model->slug]) }}" class="table-product__thumb border border-gray-100 rounded-8 flex-center ">
                                                <img src="{{asset('uploads/products/thumbnails')}}/{{$item->model->image}}" alt="">
                                            </a> 
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-product d-flex align-items-center gap-24">  
                                        <h6 class="title text-lg fw-semibold mb-8">
                                            <a href="{{ route('shop.product.details', ['product_slug' => $item->model->slug]) }}" class="link text-line-2" tabindex="0">{{$item->name}}</a>
                                        </h6>
                                        </div>
                                    </td>
                                    <td> 
                                    <div class="flex-align gap-16 mb-16">
                                        <div class="flex-align gap-6">
                                            <span class="text-md fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                            <span class="text-md fw-semibold text-gray-900">{{ $medianRating }}</span>
                                        </div>
                                        <span class="text-sm fw-medium text-gray-200">|</span>
                                        <span class="text-neutral-600 text-sm">{{ $item->model->reviews->count() }} Reviews</span>
                                    </div>  
                                    </td>
                                    <td>
                                        <span class="text-lg h6 mb-0 fw-semibold">원{{$item->price}}</span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{route('wishlist.move.to.cart',['rowId'=>$item->rowId])}}">
                                        @csrf 
                                        <button type="submit" class="btn btn-main py-18 rounded-8" @if($item->model->quantity == 0) disabled @endif>
                                        @if($item->model->quantity > 0)
                                            Move To Cart
                                        @else
                                            Out of Stock
                                        @endif
                                        </button>
                                        </form>
                                    </td>
                                </tr>
                              @endforeach 
                              @else
                              <h4 class="text-center">No items in wishlist</h4>
                              @endif 
                            </tbody>
                        </table>
                    </div>
                    @if (Cart::instance('wishlist')->content()->count()>0)
                    <div class="flex-between flex-wrap gap-16 mt-16">
                        <div class="flex-align gap-16">
                          <form method="POST" action="{{route('wishlist.items.clear')}}">
                          @csrf
                          @method('DELETE')
                            <button type="submit" class="btn btn-danger py-18 w-100 rounded-8">Clear Wishlist</button>
                          </form>
                        </div>
                    </div>
                    @else
                    <div class="flex-between flex-wrap gap-16 mt-16">
                        <div class="flex-align gap-16">
                          
                            <a href="{{route('shop.index')}}" class="btn btn-main py-18 w-100 rounded-8">Add Wishlist</a>
                          
                        </div>
                        <a href="{{ route('wishlist.index') }}" class="text-lg text-gray-500 hover-text-main-600">Update Wishlist</a>
                    </div>
                    @endif
                </div>
            </div> 
        </div>
    </div>
 </section>
@endsection 
@push('scripts')
<script>
    $(function(){
        $(".qty-control__increase").on("click",function(){
            $(this).closest('form').submit();
        });
        $(".qty-control__reduce").on("click",function(){
            $(this).closest('form').submit();
        });
        $(".remove-cart").on("click",function(){
            $(this).closest('form').submit();
        });
    })
</script>
    
@endpush