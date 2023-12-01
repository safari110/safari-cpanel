@extends('layouts.admin.app')

@section('title', translate('Food Preview'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="page-header-title text-break">
                    {{ $product['name'] }}
                </h1>
                <a href="{{ route('admin.food.edit', [$product['id']]) }}" class="btn btn--primary">
                    <i class="tio-edit"></i> {{ translate('Edit Info') }}
                </a>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row review--information-wrapper g-2 mb-3">
            <div class="col-lg-9">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="row align-items-md-center">
                            <div class="col-md-6  mb-3 mb-md-0">
                                <div class="d-flex flex-wrap align-items-center food--media">
                                   

                                        <div style="font-weight: 400;font-size: 16px;line-height: 30px;color: #242A30;">
                                            <span style="margin-inline-end:5px;">
                                                <i class="tio-user"></i> <a href="tel:{{ $product->phone }}"
                                                    style="text-decoration: none; color:  #4ef212;">{{ translate('messages.phone') }}:
                                                    {{ $product->phone }}</a>
                                            </span>
                                            <label class="input-label"><span class="out-of"></span></label>

                                            <span  style="margin-inline-end:5px;">
                                                <i class="tio-user"></i> <a href="mailto:{{ $product->email }}"
                                                    style="text-decoration: none; color:  #1282f2;">{{ translate('messages.email') }}:
                                                    {{ $product->email }}</a>
                                            </span>
                                        </div>
                                  
                                </div>

                                {{-- <div class="d-flex flex-wrap align-items-center food--media">
                                    <img class="avatar avatar-xxl avatar-4by3 mr-4 initial-53"
                                        src="{{ asset('storage/app/public/product') }}/{{ $product['image'] }}"
                                        onerror="this.src='{{ asset('/public/assets/admin/img/100x100/food-default-image.png') }}'"
                                        alt="Image Description">
                                    <div class="d-block">
                                         <div class="rating--review">
                                            {{-- {{ dd($product->restaurant) }}
                                            <h1 class="title">{{ number_format($product->avg_rating, 1) }}<span
                                                    class="out-of">/5</span></h1>
                                        {{--    @if ($product->avg_rating == 5)
                                                <div class="rating">
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                </div>
                                            @elseif ($product->avg_rating < 5 && $product->avg_rating >= 4.5)
                                                <div class="rating">
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star-half"></i></span>
                                                </div>
                                            @elseif ($product->avg_rating < 4.5 && $product->avg_rating >= 4)
                                                <div class="rating">
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                </div>
                                            @elseif ($product->avg_rating < 4 && $product->avg_rating >= 3.5)
                                                <div class="rating">
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star-half"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                </div>
                                            @elseif ($product->avg_rating < 3.5 && $product->avg_rating >= 3)
                                                <div class="rating">
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                </div>
                                            @elseif ($product->avg_rating < 3 && $product->avg_rating >= 2.5)
                                                <div class="rating">
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star-half"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                </div>
                                            @elseif ($product->avg_rating < 2.5 && $product->avg_rating > 2)
                                                <div class="rating">
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                </div>
                                            @elseif ($product->avg_rating < 2 && $product->avg_rating >= 1.5)
                                                <div class="rating">
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star-half"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                </div>
                                            @elseif ($product->avg_rating < 1.5 && $product->avg_rating > 1)
                                                <div class="rating">
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                </div>
                                            @elseif ($product->avg_rating < 1 && $product->avg_rating > 0)
                                                <div class="rating">
                                                    <span><i class="tio-star-half"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                </div>
                                            @elseif ($product->avg_rating == 1)
                                                <div class="rating">
                                                    <span><i class="tio-star"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                </div>
                                            @elseif ($product->avg_rating == 0)
                                                <div class="rating">
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                    <span><i class="tio-star-outlined"></i></span>
                                                </div>
                                            @endif
                                            <div class="info">
                                                {{-- <span class="mr-3">of {{ $product->rating ? count(json_decode($product->rating, true)): 0 }} Rating</span> 
                                                <span>{{ translate('messages.of') }} {{ $product->reviews->count() }}
                                                    {{ translate('messages.reviews') }}</span>
                                            </div>
                                        </div> 
                                    </div>
                                </div> --}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card h-100">

                    <div class="card-body d-flex flex-column justify-content-center">
                        @if ($product->restaurant)
                            <a class="resturant--information-single"
                                href="{{ route('admin.restaurant.view', $product->restaurant_id) }}"
                                title="{{ $product->restaurant['name'] }}">
                                <img class="avatar-img initial-54"
                                    onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                    src="{{ asset('storage/app/public/restaurant/' . $product->restaurant->logo) }}"
                                    alt="Image Description">
                                <div class="text-center">
                                    <h5 class="text-capitalize text--title font-semibold text-hover-primary d-block mb-1">
                                        {{ $product->restaurant['name'] }}
                                    </h5>
                                    {{-- <span class="text--title">
                                        <i class="tio-poi"></i> {{ $product->restaurant['address'] }}
                                    </span> --}}
                                </div>
                            </a>
                        @else
                            <div class="badge badge-soft-danger py-2">{{ translate('messages.restaurant') }}
                                {{ translate('messages.deleted') }}</div>
                        @endif
                    </div>


                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="px-4 w-80px">
                                    <h4 class="m-0">{{ translate('Short Description') }}</h4>
                                </th>
                                <th class="px-4 w-120px">
                                    <h4 class="m-0">{{ translate('messages.images') }}</h4>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4">
                                    <div>
                                        {!! $product['description'] !!}
                                    </div>
                                </td>
                                <td class="px-4">
                                    @foreach (json_decode($product->images, true) as $element)
                                        <img class="initial-52 object--cover border--dashed"
                                            style="width: 80px;
                                    height: 80px;"
                                            id="addImageButton" src="{{ asset('storage/app/public/product/' . $element) }}"
                                            alt="place image" />
                                    @endforeach


                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- End Body -->
        </div>
        <!-- End Card -->

        <!-- Card -->

    </div>
@endsection

@push('script_2')
    <script>
        function status_form_alert(id, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('messages.are_you_sure') }}',
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
                    $('#' + id).submit()
                }
            })
        }
    </script>
@endpush
