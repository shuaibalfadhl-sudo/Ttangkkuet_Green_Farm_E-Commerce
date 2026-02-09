@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>All Products</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">All Products</div>
                </li>
            </ul>
        </div>
        <div class="wg-box">
            

           
            <div class="flex items-center justify-between gap10 flex-wrap">
                {{-- Live Search Container --}}
                <div class="wg-filter flex-grow" style="position: relative;">
                    <form class="form-search" action="{{ url()->current() }}" method="GET" id="search-form">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="search"
                                id="product-search-input"
                                tabindex="2" value="{{ request('search') }}" aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                        {{-- Dropdown Placeholder for AJAX Results --}}
                        <div id="search-results-dropdown" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 100; background: white; border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            {{-- Live results will be injected here by JavaScript --}}
                        </div>
                    </form>
                </div>
                {{-- End Live Search Container --}}

                <div class="flex items-center gap10 flex-wrap">
                    <a class="tf-button style-1 w208" href="{{route('admin.product.add')}}">
                        <i class="icon-plus"></i>Add new
                    </a>

                    <form action="{{ route('admin.products.sendAllUpdates') }}" method="POST">
                        @csrf
                        <button type="submit" class="tf-button style-1 w208 send-email-button">
                            <i class="icon-mail"></i> Send Email to Subscribers
                        </button>
                    </form>
                </div>
            </div>
            <div class="table-responsive"> 
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>SalePrice</th>
                            <th>SKU</th>
                            <th>Category</th> 
                            <th>Featured</th>
                            <th>Stock</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Product loop goes here --}}
                        @foreach ($products as $product)
                        <tr>
                            <td>{{$product->id}}</td>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{asset('uploads/products/thumbnails')}}/{{$product->image}}" alt="{{$product->name}}" class="image">
                                </div>
                                <div class="name">
                                    <a href="#" class="body-title-2">{{$product->name}}</a>
                                    <div class="text-tiny mt-3">{{$product->slug}}</div>
                                </div>
                            </td>
                            <td>원{{ number_format($product->regular_price, 2)}}</td>
                            <td>원{{ number_format($product->sale_price, 2)}}</td>
                            <td>{{$product->SKU}}</td>
                            <td>{{$product->category->name}}</td> 
                            <td>{{$product->featured == 0 ? "No":"Yes"}}</td>
                            <td>{{$product->stock_status}}</td>
                            <td>{{$product->quantity}}</td>
                            <td>
                                <div class="list-icon-function">
                                    <a href="#" target="_blank">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </a>
                                    <a href="{{route('admin.product.edit', ['id'=>$product->id])}}">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>
                                    <form action="{{route('admin.product.delete', ['id'=>$product->id])}}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="item text-danger delete-btn" style="border: none; background: none; cursor: pointer; padding: 0;">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        {{-- End Product loop --}}
                    </tbody>
                </table>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{-- Add appends(['search' => request('search')]) to maintain search filter during pagination --}}
                {{$products->appends(['search' => request('search')])->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Include SweetAlert library if you haven't already --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script> --}} 
    <script>
        $(function(){
            @if (Session::has('success')) 
                swal( "Success", "{{ Session::get('success') }}", 'success', { 
                    button: true, 
                    timer: 5000,
                    dangerMode: false,
                });
            @elseif (Session::has('error')) 
                swal( "Failed", "{{ Session::get('error') }}", 'error', { 
                    button: true, 
                    timer: 5000,
                    dangerMode: true,
                });
            @elseif(Session::has('info'))
                swal( "Warning", "{{ Session::get('info') }}", 'info', { 
                    button: true, 
                    timer: 5000,
                    dangerMode: false,
                });
            @elseif(Session::has('status')) 
                swal( "Success", "{{ Session::get('status') }}", 'success', { 
                    button: true, 
                    timer: 5000,
                    dangerMode: false,
                });
            @endif
            // --- SweetAlert Delete Confirmation (Updated for button element) ---
            $('.delete-btn').on('click', function(e){
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title:"Are you sure?",
                    text:"Once deleted, you will not be able to recover this data",
                    icon:"warning",
                    buttons: true,
                    dangerMode: true,
                }).then(function(willDelete){
                    if(willDelete){
                        form.submit();
                    }
                });
            });
            $('.send-email-button').on('click', function(e){
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title:"Are you sure?",
                    text:"Send email to all users who subscribed to updates?",
                    icon:"info",
                    buttons: true,
                    dangerMode: true,
                }).then(function(willDelete){
                    if(willDelete){
                        form.submit();
                    }
                });
            });
            
            // --- Live Search Logic ---
            const searchInput = $('#product-search-input');
            const resultsDropdown = $('#search-results-dropdown');
            const searchForm = $('#search-form');

            searchInput.on('keyup', function() {
                const query = $(this).val();

                // Start showing preview at the first letter
                if (query.length === 0) {
                    resultsDropdown.hide().empty();
                    return;
                }

                // Make AJAX request to get live search results
                $.ajax({
                    // *** IMPORTANT: Replace this route with your actual Laravel AJAX search route ***
                    url: '{{ route('home.live.search') }}', 
                    method: 'GET',
                    data: { query: query },
                    success: function(data) {
                        resultsDropdown.empty();

                        if (data.length > 0) {
                            let list = $('<ul style="list-style: none; padding: 0; margin: 0;"></ul>');
                            $.each(data, function(index, product) {
                                // 🚨 MODIFICATION HERE: Added 'font-size: 16px;' (or another size you prefer)
                                let listItem = $('<li class="live-search-item" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee; font-size: 16px;">' + product.name + '</li>');
                                
                                // On clicking a preview item, populate the search bar and submit the form
                                listItem.on('click', function() {
                                    searchInput.val(product.name);
                                    resultsDropdown.hide().empty();
                                    searchForm.submit(); // Submit the form to filter the main table
                                });

                                // Add hover effect (optional, depends on your CSS framework)
                                listItem.hover(
                                    function() { $(this).css('background-color', '#f0f0f0'); },
                                    function() { $(this).css('background-color', 'white'); }
                                );

                                list.append(listItem);
                            });
                            resultsDropdown.append(list).show();
                        } else {
                            resultsDropdown.html('<div style="padding: 10px; color: #666;">No products found matching "' + query + '".</div>').show();
                        }
                    },
                    error: function(error) {
                        console.log('Error during live search:', error);
                    }
                });
            });

            // Hide dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!searchForm.is(e.target) && searchForm.has(e.target).length === 0) {
                    resultsDropdown.hide().empty();
                }
            });

        });
    </script>
@endpush