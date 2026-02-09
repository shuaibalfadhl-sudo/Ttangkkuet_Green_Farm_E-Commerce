@extends('layouts.admin')

@section('content')
@push('styles')
<style>
    /* 🌟 NEW FIX: Truncate long emails with ellipsis (...) */
    .wg-table .email-cell {
        /* 1. Prevent the text from wrapping to force overflow */
        white-space: nowrap;
        /* 2. Hide any content that overflows the cell */
        overflow: hidden;
        /* 3. Display an ellipsis (...) for the clipped text */
        text-overflow: ellipsis;
        /* 4. Ensure the column has some space to shrink to */
        max-width: 150px; 
    }

    /* OPTIONAL: To help the whole table adjust better */
    .wg-table .table {
        /* Forces the table to stretch and columns to distribute space */
        width: 100%;
        table-layout: auto; 
    }
    
    .wg-table .table td:first-child, /* # */
    .wg-table .table td:last-child { /* Action */
        white-space: nowrap;
        width: 1%;
    }
    
    /* Ensure last login column date is readable */
    .wg-table .table td:nth-child(7) { 
        min-width: 120px;
        white-space: normal;
    }
</style>
@endpush
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Users</h3>
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
                    <div class="text-tiny">All User</div>
                </li>
            </ul>
        </div>  
        <div class="wg-box">
            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    {{-- Main Filter and Search Wrapper (Left Side) --}}
                    <div class="flex items-center gap10 flex-wrap">
                        {{-- Search Form Container (Matches Image Style: Left-aligned search box) --}}
                        <div class="wg-filter" style="position: relative;">
                            <form class="form-search" action="{{ url()->current() }}" method="GET" id="search-user-form">
                                <fieldset class="name" style="min-width: 250px;">
                                    <input type="text" placeholder="Search by name or email..." class="" name="search"
                                        id="user-search-input"
                                        tabindex="2" value="{{ request('search') }}" aria-required="true">
                                </fieldset>
                                <div class="button-submit">
                                    <button class="" type="submit"><i class="icon-search"></i></button>
                                </div>
                                
                                {{-- Live Search Dropdown --}}
                                <div id="search-results-dropdown" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 100; background: white; border: 1px solid #ccc; max-height: 200px; overflow-y: auto; display: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                    {{-- Live results will be injected here --}}
                                </div>
                            </form>
                        </div>

                        {{-- Filters Form (Separate form for role/sort filters) --}}
                        <form id="filter-form" action="{{ url()->current() }}" method="GET" class="flex wg-filter items-center gap10">
                            {{-- Pass the current search term to maintain state after filtering --}}
                            <input type="hidden" name="search" value="{{ request('search') }}">

                            {{-- User Type (utype) Filter --}}
                            <select name="utype" class="wg-filter tf-button style-3" onchange="this.form.submit();">
                                <option value="">Filter by Role</option>
                                <option value="ADM" {{ request('utype') == 'ADM' ? 'selected' : '' }}>Admin</option>
                                <option value="USR" {{ request('utype') == 'USR' ? 'selected' : '' }}>User</option>
                                <option value="RDR" {{ request('utype') == 'RDR' ? 'selected' : '' }}>Rider</option>
                            </select>

                            {{-- Sort By Filter (New Addition) --}}
                            <select name="sort" class="wg-filter tf-button style-3" onchange="this.form.submit();">
                                <option value="id_desc" {{ request('sort', 'id_desc') == 'id_desc' ? 'selected' : '' }}>Sort by ID (Newest)</option>
                                <option value="login_desc" {{ request('sort') == 'login_desc' ? 'selected' : '' }}>Most Recent Login</option>
                                <option value="login_asc" {{ request('sort') == 'login_asc' ? 'selected' : '' }}>Least Recent Login</option>
                                <option value="orders_desc" {{ request('sort') == 'orders_desc' ? 'selected' : '' }}>Total Orders (High to Low)</option>
                            </select>
                        </form>
                    </div>  
                </div>
            </div>
            <div class="wg-table table-all-user">  
                <div class="table-responsive"> 
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th class="text-center">Total Orders</th>
                                <th>Role</th>
                                <th class="text-center">Verified</th>
                                {{-- NEW COLUMN HEADER --}}
                                <th>Last Login</th> 
                                <th class="text-center">Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @if($users->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center">No users found matching the criteria.</td>
                                </tr>
                            @endif
                            @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td class="pname">
                                    <div class="image">
                                        <img src="{{ $user->profile_image 
                                        ? asset('uploads/profile_images/' . $user->profile_image) 
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=6c757d&color=fff' }}" alt="" class="image">
                                    </div>
                                    <div class="name">
                                        <a href="#" class="body-title-2">{{$user->name}}</a>
                                        <div class="text-tiny mt-3">ID: {{$user->utype}}</div>
                                    </div>
                                </td>
                                <td>{{$user->mobile}}</td>
                                <td>{{$user->email}}</td>
                                <td class="text-center"><a href="#" target="_blank">{{$user->orders_count}}</a></td>
                                <td>
                                    <span class="badge {{ $user->utype == 'ADM' ? 'bg-danger' : ($user->utype == 'RDR' ? 'bg-info' : 'bg-primary') }}">
                                        {{$user->utype == 'ADM' ? 'Admin' : ($user->utype == 'RDR' ? 'Rider' : 'Customer')}}
                                    </span>
                                </td>
                                {{-- NEW COLUMN DATA CELL --}}
                                <td class="text-center">
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">Verified</span>
                                    @else
                                        <span class="badge bg-danger">Unverified</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->diffForHumans() }}
                                        <div class="text-tiny text-muted mt-1">{{ $user->last_login_at->format('Y-m-d H:i') }}</div>
                                    @else
                                        Never Logged In
                                    @endif
                                </td>
                                <td>
                                    <div class="list-icon-function justify-content-center">
                                        {{-- Link to open a modal or dedicated page for role update --}}
                                        <a href="{{ route('admin.user.edit_role', ['id' => $user->id]) }}">
                                            <div class="item edit" title="Update Role">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>
                                        {{-- Add delete button if required, similar to other tables --}}
                                        <form action="{{ route('admin.user.delete', ['id' => $user->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <div class="item text-danger delete-user-btn" title="Delete User">
                                                <i class="icon-trash-2"></i>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>  
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">  
                {{$users->appends(request()->query())->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function(){
            // --- Delete User Confirmation ---
            $('.delete-user-btn').on('click', function(e){
                e.preventDefault();
                // Find the closest form element associated with this button
                var form = $(this).closest('form'); 
                
                // Show SweetAlert confirmation
                swal({
                    title:"Are you sure?",
                    text:"Once deleted, the user will be permanently removed.",
                    icon:"warning", // Use 'icon' for SweetAlert 2
                    buttons: true,
                    dangerMode: true,
                }).then(function(willDelete){
                    if(willDelete){
                        form.submit(); // Submit the form if confirmed
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

            // --- Live Search / Autocomplete Logic for Users (by Name/Email) ---
            const searchInput = $('#user-search-input');
            const resultsDropdown = $('#search-results-dropdown');
            const searchForm = $('#search-user-form');
            let debounceTimer; // Initialize debounce timer variable

            searchInput.on('keyup', function() {
                const query = $(this).val();

                // 1. Clear previous timer
                clearTimeout(debounceTimer);

                if (query.length < 1) { 
                    resultsDropdown.hide().empty();
                    return;
                }

                // 2. Debounce the AJAX call
                debounceTimer = setTimeout(function() {
                    $.ajax({
                        // *** IMPORTANT: Define this route: admin.user.livesearch ***
                        url: '{{ route('admin.user.livesearch') }}', 
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            resultsDropdown.empty();

                            if (data.length > 0) {
                                let list = $('<ul style="list-style: none; padding: 0; margin: 0;"></ul>');
                                $.each(data, function(index, user) {
                                    let display_text = user.name + ' (' + user.email + ')';
                                    
                                    let listItem = $('<li class="live-search-item" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee; font-size: 16px;">' + display_text + '</li>');
                                    
                                    // On click, populate the search bar and submit the form
                                    listItem.on('click', function() {
                                        searchInput.val(user.name); // You might prefer user.email or the exact query used
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
                                resultsDropdown.html('<div style="padding: 10px; color: #666;">No users found matching "' + query + '".</div>').show();
                            }
                        },
                        error: function(error) {
                            console.log('Error during user live search:', error);
                        }
                    });
                }, 300); // Wait 300ms after the last key stroke before searching
            });

            // 3. Hide dropdown when clicking outside (Crucial fix: attached only once)
            $(document).on('click', function(e) {
                // Check if the click target is NOT the search input/form container or the dropdown itself
                if (!searchForm.is(e.target) && searchForm.has(e.target).length === 0 &&
                    !resultsDropdown.is(e.target) && resultsDropdown.has(e.target).length === 0) {
                    
                    resultsDropdown.hide().empty();
                }
            });
        });
    </script>
@endpush