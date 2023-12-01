@extends('layouts.admin.app')

@section('title', translate('Restaurant List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title"><i class="tio-filter-list"></i> {{ translate('messages.restaurants') }} <span
                    class="badge badge-soft-dark ml-2" id="itemCount">{{ $restaurants->total() }}</span></h1>
        </div>
        <!-- End Page Header -->

        <!-- Resturent Card Wrapper -->
        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card bg--1">
                    <h4 class="title" id="itemCount">{{ $restaurants->total() }}</h4>
                    <span class="subtitle">{{ translate('messages.total_restaurants') }}</span>
                    <img class="resturant-icon" src="{{ asset('/public/assets/admin/img/resturant/map-pin.png') }}"
                        alt="resturant">
                </div>
            </div>
            <!-- Add other cards if needed -->

        </div>
        <!-- Resturent Card Wrapper -->

        <!-- Resturent List -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Card Header -->
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h3 class="card-title">{{ translate('messages.restaurants') }} {{ translate('messages.list') }}
                            </h3>
                        </div>
                    </div>
                    <!-- Card Header -->

                    <!-- Table -->
                    <div
                        class="table-responsive datatable-custom resturant-list-table">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-uppercase w-90px">{{ translate('messages.sl') }}</th>
                                    <th class="initial-58">{{ translate('messages.restaurant') }}
                                        {{ translate('messages.info') }}</th>
                                    {{-- <th class="w-230px text-center">{{ translate('messages.owner') }} 
                                        {{ translate('messages.info') }} </th>
                                    <th class="w-130px">{{ translate('messages.zone') }}</th>
                                    <th class="w-130px">{{ translate('messages.cuisine') }}</th>--}}
                                    <th class="w-100px">{{ translate('messages.status') }}</th>
                                    <th class="text-center w-60px">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($restaurants as $key => $dm)
                                    <tr>
                                        <td>{{ $key + $restaurants->firstItem() }}</td>
                                        <td>
                                            <a href="{{ route('admin.restaurant.view', $dm->id) }}" alt="view restaurant"
                                                class="table-rest-info">
                                                <img
                                                    onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                                                    src="{{ asset('storage/app/public/restaurant') }}/{{ $dm['logo'] }}">
                                                <div class="info">
                                                    <span class="d-block text-body">
                                                        {{ Str::limit($dm->name, 20, '...') }}<br>
                                                        <!-- Rating -->
                                                        <span class="rating">
                                                            @if ($dm->reviews_count)
                                                                @php($reviews_count = $dm->reviews_count)
                                                                @php($reviews = 1)
                                                            @else
                                                                @php($reviews = 0)
                                                                @php($reviews_count = 1)
                                                            @endif
                                                            <i class="tio-star"></i>
                                                            {{
                                                                round($dm->reviews_sum_rating / $reviews_count, 1)
                                                            }}
                                                        </span>
                                                        <!-- Rating -->
                                                    </span>
                                                </div>
                                            </a>
                                        </td>
                                        {{-- <td>
                                            <span class="d-block owner--name text-center">
                                                {{ $dm->vendor->f_name . ' ' . $dm->vendor->l_name }}
                                            </span>
                                            <span class="d-block font-size-sm text-center">
                                                {{ $dm['phone'] }}
                                            </span>
                                        </td> --}}
                                        {{-- <td>
                                            {{ $dm->zone ? $dm->zone->name : translate('messages.zone') . ' ' . translate('messages.deleted') }}
                                        </td> 
                                        <td>
                                            <div class="white-space-initial">
                                                @if ($dm->cuisine)
                                                    @forelse ($dm->cuisine as $c)
                                                        {{ $c->name . ',' }}
                                                    @empty
                                                        {{ translate('Cuisine_not_found') }}
                                                    @endforelse
                                                @endif
                                            </div>
                                        </td>--}}
                                        <td>
                                           
                                                
                                                    <label
                                                        class="toggle-switch toggle-switch-sm"
                                                        for="stocksCheckbox{{ $dm->id }}">
                                                        <input
                                                            type="checkbox"
                                                            onclick="status_change_alert('{{ route('admin.restaurant.status', [$dm->id, $dm->status ? 0 : 1]) }}', '{{ translate('messages.you_want_to_change_this_restaurant_status') }}', event)"
                                                            class="toggle-switch-input"
                                                            id="stocksCheckbox{{ $dm->id }}"
                                                            {{ $dm->status ? 'checked' : '' }}>
                                                        <span class="toggle-switch-label">
                                                            <span class="toggle-switch-indicator"></span>
                                                        </span>
                                                    </label>
                                               
                                        </td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                <a
                                                    class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                    href="{{ route('admin.restaurant.edit', [$dm['id']]) }}"
                                                    title="{{ translate('messages.edit') }} {{ translate('messages.restaurant') }}"><i
                                                        class="tio-edit"></i>
                                                </a>
                                                <a
                                                    class="btn btn-sm btn--warning btn-outline-warning action-btn"
                                                    href="{{ route('admin.restaurant.view', [$dm['id']]) }}"
                                                    title="{{ translate('messages.view') }} {{ translate('messages.restaurant') }}"><i
                                                        class="tio-invisible"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if (count($restaurants) === 0)
                            <div class="empty--data">
                                <img src="{{ asset('/public/assets/admin/img/empty.png') }}" alt="public">
                                <h5>
                                    {{ translate('no_data_found') }}
                                </h5>
                            </div>
                        @endif
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {!! $restaurants->appends(request()->all())->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- Resturent List -->
    </div>

@endsection

@push('script_2')
    <script>
        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
    </script>
@endpush
