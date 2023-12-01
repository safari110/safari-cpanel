@extends('layouts.admin.app')

@section('title',$restaurant->name)

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h1 class="page-header-title text-break me-2">
                <i class="tio-shop"></i> <span>{{$restaurant->name}}</span>
            </h1>
            @if($restaurant->status)
            <a href="{{route('admin.restaurant.edit',[$restaurant->id])}}" class="btn btn--primary my-2">
                <i class="tio-edit mr-2"></i> {{translate('messages.edit')}} {{translate('messages.restaurant')}}
            </a>
            @else
                <div>
                    @if(!isset($restaurant->status))
                    <a class="btn btn--danger text-capitalize my-2"
                    onclick="request_alert('{{route('admin.restaurant.application',[$restaurant['id'],0])}}','{{translate('messages.you_want_to_deny_this_application')}}')"
                        href="javascript:">{{translate('messages.deny')}}</a>
                    @endif
                    <a class="btn btn--primary text-capitalize my-2"
                    onclick="request_alert('{{route('admin.restaurant.application',[$restaurant['id'],1])}}','{{translate('messages.you_want_to_approve_this_application')}}')"
                        href="javascript:">{{translate('messages.approve')}}</a>
                </div>
            @endif
        </div>
        @if($restaurant->status)
        <!-- Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <!-- Nav -->
            <ul class="nav nav-tabs page-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('admin.restaurant.view', $restaurant->id)}}">{{translate('messages.overview')}}</a>
                </li>
             
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'reviews'])}}"  aria-disabled="true">{{translate('messages.reviews')}}</a>
                </li>
               
            </ul>
            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
        @endif
    </div>
        <!-- End Page Header -->
    <!-- Page Heading -->
    <div class="row my-2 g-3">
        <!-- Earnings (Monthly) Card Example -->
        {{-- <div class="for-card col-md-4">
            <div class="card bg--1 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                    <div class="cash--subtitle">
                        {{translate('messages.collected_cash_by_restaurant')}}
                    </div>
                    <div class="d-flex align-items-center justify-content-center mt-3">
                        <img class="cash-icon mr-3" src="{{asset('/public/assets/admin/img/transactions/cash.png')}}" alt="transactions">
                        {{-- <h2

                            class="cash--title">{{\App\CentralLogics\Helpers::format_currency($wallet->collected_cash)}}
                        </h2> 
                    </div>
                </div>
                <div class="card-footer pt-0 bg-transparent">
                    <a class="btn btn-- bg--title h--45px w-100" href="{{route('admin.account-transaction.index')}}" title="{{translate('messages.goto')}} {{translate('messages.account_transaction')}}">{{translate('messages.collect_cash_from_restaurant')}}</a>
                </div>
            </div>
        </div> --}}

    
    </div>
    <div class="mt-4">
        <div id="restaurant_details" class="row g-3">
            <div class="col-lg-12">
                <div class="card mt-2">
                    <div class="card-header">
                        <h5 class="card-title m-0 d-flex align-items-center">
                            <span class="card-header-icon mr-2">
                                <i class="tio-shop-outlined"></i>
                            </span>
                            <span class="ml-1">{{translate('messages.restaurant')}} {{translate('messages.info')}}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center g-3">
                            <div class="col-lg-6">
                                <div class="resturant--info-address">
                                    <div class="logo">
                                        <img onerror="this.src='{{asset('public/assets/admin/img/100x100/restaurant-default-image.png')}}'"
                                            src="{{asset('storage/app/public/restaurant')}}/{{$restaurant->logo}}">
                                    </div>
                                    <ul class="address-info list-unstyled list-unstyled-py-3 text-dark">
                                        <li>
                                            <h5 class="name">
                                                {{$restaurant->name}}
                                            </h5>
                                        </li>
                                        <li>
                                            <i class="tio-city nav-icon"></i>
                                            <span class="pl-1">
                                                {{translate('messages.description')}} : {{$restaurant->description}}
                                            </span>
                                        </li>

                                        {{-- <li>
                                            <i class="tio-call-talking nav-icon"></i>
                                            <span class="pl-1">
                                                {{translate('messages.phone')}} : {{$restaurant->phone}}
                                            </span>
                                        </li>
                                        <li>
                                            <i class="tio-email nav-icon"></i>
                                            <span class="pl-1">
                                                {{translate('messages.email')}} : {{$restaurant->email}}
                                            </span>
                                        </li> --}}
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div id="map" class="single-page-map"></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card h-100"><div class="card-header">
                    <h5 class="card-title m-0 d-flex align-items-center">
                        <span class="card-header-icon mr-2">
                            <i class="tio-shop-outlined"></i>
                        </span>
                        <span class="ml-1">{{translate('messages.food')}} {{translate('messages.info')}}</span>
                    </h5>
                </div>
                    <div class="card-body p-0 verticle-align-middle-table">
                        <div class="table-responsive datatable-custom">
                            <table id="columnSearchDatatable"
                                    class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                    data-hs-datatables-options='{
                                        "order": [],
                                        "orderCellsTop": true,
                                        "paging":false
                                    }'>
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center max-90px">{{translate('messages.sl')}}</th>
                                        <th>{{translate('messages.food')}}</th>
                                       {{-- <th class="pl-4">{{translate('messages.reviewer_info')}}</th> 
                                        <th>{{translate('messages.review')}}</th>
                                        <th>{{translate('messages.date')}}</th>
                                        <th class="text-center w-100px">{{translate('messages.status')}}</th>
                                    </tr>
                                </thead>
            
                                <tbody id="set-rows">
                                @php($reviews = $restaurant->reviews()->with('food',function($query){
                                    $query->withoutGlobalScope(\App\Scopes\RestaurantScope::class);
                                })->latest()->paginate(25))
            
                                @foreach($reviews as $key=>$review)
                                    <tr>
                                        <td class="text-center">{{$key+$reviews->firstItem()}}</td>
                                        <td>
                                        @if ($review->food)
                                            <a class="media align-items-center" href="{{route('admin.food.view',[$review->food['id']])}}">
                                                <img class="avatar avatar-lg mr-3" src="{{asset('storage/app/public/product')}}/{{$review->food['image']}}"
                                                    onerror="this.src='{{asset('public/assets/admin/img/100x100/food-default-image.png')}}'" alt="{{$review->food->name}} image">
                                                <div class="media-body">
                                                    <h5 class="text-hover-primary mb-0">{{Str::limit($review->food['name'],10)}}</h5>
                                                    <!-- Static Order ID -->
                                                    {{-- <small class="text-body">Order ID: {{$review->order_id}}</small> 
                                                    <a class="text-body" href="{{route('admin.order.details',['id'=>$review->order_id])}}">Order ID: {{$review->order_id}}</a>
                                                    <!-- Static Order ID -->
                                                </div>
                                            </a>
                                        @else
                                            {{translate('messages.Food deleted!')}}
                                        @endif
                                        </td>
                                        <td>
                                        @if($review->customer)
                                            <a
                                            href="{{route('admin.customer.view',[$review['user_id']])}}">
                                                <div>
                                                <span class="d-block h5 text-hover-primary mb-0">{{Str::limit($review->customer['f_name']." ".$review->customer['l_name'], 15)}} <i
                                                        class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                                                        title="Verified Customer"></i></span>
                                                    <span class="d-block font-size-sm text-body">{{Str::limit($review->customer->phone)}}</span>
                                                </div>
                                            </a>
                                            @else
                                                {{translate('messages.customer_not_found')}}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-wrap w-18rem">
                                                <span class="d-block rating">
                                                    {{$review->rating}} <i class="tio-star"></i>
                                                </span>
                                                <small class="d-block">
                                                    {{Str::limit($review['comment'], 80)}}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            {{ Carbon\Carbon::parse($review['created_at'])->locale(app()->getLocale())->translatedFormat( 'd M Y ' .config('timeformat')) }}
                                        </td>
                                        <td>
                                            <label class="toggle-switch toggle-switch-sm" for="reviewCheckbox{{$review->id}}">
                                                <input type="checkbox" onclick="status_form_alert('status-{{$review['id']}}','{{$review->status?translate('messages.you_want_to_hide_this_review_for_customer'):translate('messages.you_want_to_show_this_review_for_customer')}}', event)" class="toggle-switch-input" id="reviewCheckbox{{$review->id}}" {{$review->status?'checked':''}}>
                                                <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <form action="{{route('admin.food.reviews.status',[$review['id'],$review->status?0:1])}}" method="get" id="status-{{$review['id']}}">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="page-area px-4 pb-3">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div>
                                    {!! $reviews->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  --}}
               </div>
            {{-- <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title m-0 d-flex align-items-center">
                            <span class="card-header-icon mr-2">
                                <i class="tio-user"></i>
                            </span>
                            <span class="ml-1">{{translate('messages.owner')}} {{translate('messages.info')}}</span>
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="resturant--info-address">
                            <div class="avatar avatar-xxl avatar-circle avatar-border-lg">
                                <img class="avatar-img" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                            src="{{asset('storage/app/public/vendor')}}/{{$restaurant->vendor->image}}" alt="Image Description">
                            </div>
                            <ul class="address-info address-info-2 list-unstyled list-unstyled-py-3 text-dark">
                                <li>
                                    <h5 class="name">
                                        {{$restaurant->vendor->f_name}} {{$restaurant->vendor->l_name}}
                                    </h5>
                                </li>
                                <li>
                                    <i class="tio-call-talking nav-icon"></i>
                                    <span class="pl-1">
                                        {{$restaurant->vendor->phone}}
                                    </span>
                                </li>
                                <li>
                                    <i class="tio-email nav-icon"></i>
                                    <span class="pl-1">
                                        {{$restaurant->vendor->email}}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title m-0 d-flex align-items-center">
                            <span class="card-header-icon mr-2">
                                <i class="tio-museum"></i>
                            </span>
                            <span class="ml-1">{{translate('messages.bank_info')}}</span>
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <ul class="list-unstyled list-unstyled-py-3 text-dark">
                            @if($restaurant->vendor->bank_name)
                            <li class="pb-1 pt-1">
                                <strong class="text--title">{{translate('messages.bank_name')}}:</strong> {{$restaurant->vendor->bank_name ? $restaurant->vendor->bank_name : 'No Data found'}}
                            </li>
                            <li class="pb-1 pt-1">
                                <strong class="text--title">{{translate('messages.branch')}}  :</strong> {{$restaurant->vendor->branch ? $restaurant->vendor->branch : 'No Data found'}}
                            </li>
                            <li class="pb-1 pt-1">
                                <strong class="text--title">{{translate('messages.holder_name')}} :</strong> {{$restaurant->vendor->holder_name ? $restaurant->vendor->holder_name : 'No Data found'}}
                            </li>
                            <li class="pb-1 pt-1">
                                <strong class="text--title">{{translate('messages.account_no')}}  :</strong> {{$restaurant->vendor->account_no ? $restaurant->vendor->account_no : 'No Data found'}}
                            </li>
                            @else
                            <li class="my-auto">
                                <center class="card-subtitle">{{translate('messages.no_data_found')}}</center>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title m-0 d-flex align-items-center">
                            <span class="card-header-icon mr-2">
                                <i class="tio-crown"></i>
                            </span>
                            <span class="ml-1">{{translate('messages.Restaurant')}} {{translate('messages.Model')}} : {{ translate($restaurant->restaurant_model ?? 'None') }}</span>
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="resturant--info-address">

                            <ul class="address-info address-info-2 list-unstyled list-unstyled-py-3 text-dark">

                                    @if (isset($restaurant->restaurant_sub) )
                                    <li>
                                        <span class="pl-1">
                                           {{ translate('messages.Package Name') }} : {{$restaurant->restaurant_sub->package->package_name}}
                                        </span>
                                    </li>
                                    <li>
                                    <li>
                                        <span class="pl-1">
                                        {{ translate('messages.Package_price') }} : {{\App\CentralLogics\Helpers::format_currency($restaurant->restaurant_sub->package->price)}}
                                        </span>
                                    </li>
                                    <li>
                                        <span class="pl-1">
                                            {{ translate('messages.Expire_Date') }} :   {{$restaurant->restaurant_sub->expiry_date->format('d M Y')}}
                                        </span>
                                    </li>
                                    <li>
                                        @if ($restaurant->restaurant_sub->status == 1)
                                            <span class="badge badge-soft-success">
                                                {{ translate('messages.Status') }} : {{ translate('messages.active') }}</span>
                                            @else
                                            <span class="badge badge-soft-danger">
                                                {{ translate('messages.Status') }} : {{ translate('messages.inactive') }}</span>
                                        @endif
                                    </li>
                                    @elseif(!isset($restaurant->restaurant_sub) && $restaurant->restaurant_model == 'unsubscribed'  )
                                    <li>
                                        <span class="pl-1">
                                            {{ translate('messages.Not_subscribed_to_any_package') }}
                                        </span>
                                    </li>
                                    @else


                                    @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <!-- Page level plugins -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value}}&callback=initMap&v=3.45.8" ></script>
    <script>
        const myLatLng = { lat: {{$restaurant->latitude}}, lng: {{$restaurant->longitude}} };
        let map;
        initMap();
        function initMap() {
                 map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: myLatLng,
            });
            new google.maps.Marker({
                position: myLatLng,
                map,
                title: "{{$restaurant->name}}",
            });
        }
    </script>
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        function request_alert(url, message) {
            Swal.fire({
                title: "{{translate('messages.are_you_sure')}}",
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: "{{translate('messages.no')}}",
                confirmButtonText: "{{translate('messages.yes')}}",
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
    </script>
@endpush
