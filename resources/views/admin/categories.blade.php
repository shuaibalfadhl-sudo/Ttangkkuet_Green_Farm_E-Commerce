@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Categories</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Categories</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                {{-- Live Search Container --}}
                <div class="wg-filter flex-grow" style="position: relative;">
                    <form class="form-search" action="{{ url()->current() }}" method="GET" id="search-category-form">
                        <fieldset class="name">
                            <input type="text" placeholder="Search categories..." class="" name="search"
                                id="category-search-input"
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
                <a class="tf-button style-1 w208" href="{{route('admin.category.add')}}"><i
                        class="icon-plus"></i>Add new</a>
            </div>
            <div class="wg-table table-all-user">
                <div class="table-responsive"> 
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Products</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr>
                                <td>{{$category->id}}</td>
                                <td class="pname">
                                    <div class="image">
                                        <img src="{{asset('uploads/categories')}}/{{$category->image}}" alt="{{$category->name}}" class="image">
                                    </div>
                                    <div class="name">
                                        <a href="#" class="body-title-2">{{$category->name}}</a>
                                    </div>
                                </td>
                                <td>{{$category->slug}}</td>
                                <td>{{$category->products->count()}}</td>
                                <td>
                                    <div class="list-icon-function">
                                        <a href="{{route('admin.category.edit', ['id'=>$category->id])}}">
                                            <div class="item edit">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>
                                        <form action="{{route('admin.category.delete', ['id'=>$category->id])}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="item text-danger delete">
                                                <i class="icon-trash-2"></i>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @if($categories->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">No categories found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{-- Appends the search query to pagination links --}}
                    {{$categories->appends(['search' => request('search')])->links('pagination::bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function(){
            // --- SweetAlert Delete Confirmation ---
            $('.delete').on('click', function(e){
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title:"Are you sure?",
                    text:"Once deleted, you will not be able to recover this data",
                    icon:"warning",
                    buttons: true, // Use boolean true for modern swal
                    dangerMode: true,
                }).then(function(willDelete){
                    if(willDelete){
                        form.submit();
                    }
                });
            });
              
            @if(Session::has('status')) 
                swal( "Success", "{{ Session::get('status') }}", 'success', { 
                    button: true, 
                    timer: 5000,
                    dangerMode: false,
                });
            @endif 
            // --- Live Search / Autocomplete Logic for Categories ---
            const searchInput = $('#category-search-input');
            const resultsDropdown = $('#search-results-dropdown');
            const searchForm = $('#search-category-form');

            searchInput.on('keyup', function() {
                const query = $(this).val();

                // Start showing preview at the first letter (or based on your preference)
                if (query.length === 0) {
                    resultsDropdown.hide().empty();
                    return;
                }

                $.ajax({
                    // *** IMPORTANT: You need to define this route: admin.category.livesearch ***
                    url: '{{ route('admin.category.livesearch') }}', 
                    method: 'GET',
                    data: { query: query },
                    success: function(data) {
                        resultsDropdown.empty();

                        if (data.length > 0) {
                            let list = $('<ul style="list-style: none; padding: 0; margin: 0;"></ul>');
                            $.each(data, function(index, category) {
                                // Added font-size: 16px to enlarge the recommended text
                                let listItem = $('<li class="live-search-item" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee; font-size: 16px;">' + category.name + '</li>');
                                
                                // On clicking a preview item, populate the search bar and submit the form
                                listItem.on('click', function() {
                                    searchInput.val(category.name);
                                    resultsDropdown.hide().empty();
                                    searchForm.submit(); // Submits form to filter main table
                                });

                                listItem.hover(
                                    function() { $(this).css('background-color', '#f0f0f0'); },
                                    function() { $(this).css('background-color', 'white'); }
                                );
                                list.append(listItem);
                            });
                            resultsDropdown.append(list).show();
                        } else {
                            resultsDropdown.html('<div style="padding: 10px; color: #666;">No categories found matching "' + query + '".</div>').show();
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