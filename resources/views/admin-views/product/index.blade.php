@extends('layouts.admin.app')

@section('title', translate('Add new food'))

@push('css_or_js')
    <!-- Existing CSS/JS ... -->

    <style>
        #image-viewer-section {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            /* Adjust the gap between images as needed */
        }

        .image-container {
            flex: 0 0 calc(33.33% - 10px);
            /* Adjust the width of each container (3 columns) */
            max-width: calc(33.33% - 10px);
            /* Adjust the max-width of each container */
            box-sizing: border-box;
            overflow: hidden;
        }

        .image-wrapper {
            position: relative;
            margin: 2px;
            width: 80px;
            height: 80px;
            overflow: hidden;
            display: inline-block;
        }

        .selected-image {
            width: 100%;
            height: auto;
        }

        .delete-image {
            position: absolute;
            top: 0;
            right: 0;
            padding: 2px;
            cursor: pointer;
        }

        /* Add any other custom styles here */
    </style>

    <!-- Remaining CSS/JS ... -->

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{ translate('messages.add') }}
                        {{ translate('messages.new') }} {{ translate('messages.food') }}</h1>
                </div>
            </div>
        </div>

        <!-- End Page Header -->
        <form action="javascript:" method="post" id="food_form" enctype="multipart/form-data">
            @csrf
            <div class="row g-2">
                <div class="col-lg-6">
                    <div class="card shadow--card-2 border-0">
                        <div class="card-body pb-0">
                            @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = str_replace('_', '-', app()->getLocale()))
                            @if ($language)
                                <ul class="nav nav-tabs mb-4">
                                    <li class="nav-item">
                                        <a class="nav-link lang_link active" href="#"
                                            id="default-link">{{ translate('Default') }}</a>
                                    </li>
                                    @foreach (json_decode($language) as $lang)
                                        <li class="nav-item">
                                            <a class="nav-link lang_link " href="#"
                                                id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        @if ($language)
                            <div class="card-body">

                                <div class="lang_form" id="default-form">


                                    <div class="form-group">
                                        <label class="input-label" for="default_name">{{ translate('messages.name') }}
                                            ({{ translate('Default') }})
                                        </label>
                                        <input type="text" name="name[]" id="default_name" class="form-control"
                                            placeholder="{{ translate('messages.new_food') }}"
                                            oninvalid="document.getElementById('en-link').click()">
                                    </div>
                                    <input type="hidden" name="lang[]" value="default">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.short') }}
                                            {{ translate('messages.description') }}
                                            ({{ translate('Default') }})</label>
                                        <textarea type="text" name="description[]" class="form-control ckeditor min-height-154px"></textarea>
                                    </div>
                                </div>

                                @foreach (json_decode($language) as $lang)
                                    <div class="d-none lang_form" id="{{ $lang }}-form">
                                        <div class="form-group">
                                            <label class="input-label"
                                                for="{{ $lang }}_name">{{ translate('messages.name') }}
                                                ({{ strtoupper($lang) }})
                                            </label>
                                            <input type="text" name="name[]" id="{{ $lang }}_name"
                                                class="form-control" placeholder="{{ translate('messages.new_food') }}"
                                                oninvalid="document.getElementById('en-link').click()">
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang }}">
                                        <div class="form-group mb-0">
                                            <label class="input-label"
                                                for="exampleFormControlInput1">{{ translate('messages.short') }}
                                                {{ translate('messages.description') }}
                                                ({{ strtoupper($lang) }})</label>
                                            <textarea type="text" name="description[]" class="form-control ckeditor min-height-154px"></textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="card-body">
                                <div id="default-form">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.name') }}
                                            ({{ translate('Default') }})</label>
                                        <input type="text" name="name[]" class="form-control"
                                            placeholder="{{ translate('messages.new_food') }}">
                                    </div>
                                    <input type="hidden" name="lang[]" value="default">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.short') }}
                                            {{ translate('messages.description') }}</label>
                                        <textarea type="text" name="description[]" class="form-control ckeditor min-height-154px"></textarea>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow--card-2 border-0 h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <span>{{ translate('Food Images') }} <small
                                        class="text-danger">({{ translate('messages.Ratio 200x200') }})</small></span>
                            </h5>
                            <div>
                                <div
                                    class="form-group mb-0 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <label>
                                        <div id="image-viewer-section" class="my-auto">
                                            <!-- Display selected images here -->
                                            <div id="image-grid" class="d-flex flex-wrap"></div>

                                        </div>

                                    </label>
                                    <!-- Add Image button -->
                                    <div class="image-wrapper">
                                        <img class="initial-52 object--cover border--dashed"
                                            style="width: 80px;
                                            height: 80px;"
                                            id="addImageButton" src="{{ asset('/public/assets/admin/img/upload.png') }}"
                                            alt="place image" />
                                        <input type="file" name="images" id="customFileEg1" class="d-none"
                                            accept="image/*" multiple>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {{-- 
                <div class="col-lg-12">
                    <div class="card shadow--card-2 border-0">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-header-icon mr-2">
                                    <i class="tio-dashboard-outlined"></i>
                                </span>
                                <span> {{ translate('Wilaya & Category Info ') }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlSelect1">{{ translate('messages.restaurant') }}<span
                                                class="input-label-secondary"></span></label>
                                        <select name="restaurant_id" id="restaurant_id"
                                            data-placeholder="{{ translate('messages.select') }} {{ translate('messages.restaurant') }}"
                                            class="js-data-example-ajax form-control"
                                            onchange="getRestaurantData('{{ url('/') }}/admin/restaurant/get-addons?data[]=0&restaurant_id=',this.value,'add_on')"
                                            oninvalid="this.setCustomValidity('{{ translate('messages.please_select_restaurant') }}')">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlSelect1">{{ translate('messages.category') }}<span
                                                class="input-label-secondary">*</span></label>
                                        <select name="category_id" id="category_id"
                                            class="form-control js-select2-custom"
                                            onchange="getRequest('{{ url('/') }}/admin/food/get-categories?parent_id='+this.value,'sub-categories')"
                                            oninvalid="this.setCustomValidity('Select Category')">
                                            <option value="" selected disabled>
                                                {{ translate('Select Category') }}</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category['id'] }}">{{ $category['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-12">
                    <div class="card shadow--card-2">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-header-icon mr-2">
                                    <i class="tio-dashboard-outlined"></i>
                                </span>
                                <span> {{ translate('Wilaya & Category Info ') }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">



                                    <div class="form-group">
                                        <label class="input-label"
                                            for="latitude">{{ translate('messages.latitude') }}<span
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.restaurant_lat_lng_warning') }}"
                                                class="input-label-secondary"><img
                                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span></label>
                                        <input type="text" id="latitude" name="latitude"
                                            class="form-control h--45px disabled"
                                            placeholder="{{ translate('messages.Ex :') }} -94.22213"
                                            value="{{ old('latitude') }}" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="longitude">{{ translate('messages.longitude') }}
                                            <span data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.restaurant_lat_lng_warning') }}"
                                                class="input-label-secondary"><img
                                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span>
                                        </label>
                                        <input type="text" name="longitude" class="form-control h--45px disabled"
                                            placeholder="{{ translate('messages.Ex :') }} 103.344322" id="longitude"
                                            value="{{ old('longitude') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlSelect1">{{ translate('messages.restaurant') }}<span
                                                class="input-label-secondary"></span></label>
                                        <select name="restaurant_id" id="restaurant_id"
                                            data-placeholder="{{ translate('messages.select') }} {{ translate('messages.restaurant') }}"
                                            class="js-data-example-ajax form-control"
                                            onchange="getRestaurantData('{{ url('/') }}/admin/restaurant/get-addons?data[]=0&restaurant_id=',this.value,'add_on')"
                                            oninvalid="this.setCustomValidity('{{ translate('messages.please_select_restaurant') }}')">

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="exampleFormControlSelect1">{{ translate('messages.category') }}<span
                                                class="input-label-secondary">*</span></label>
                                        <select name="category_id" id="category_id"
                                            class="form-control js-select2-custom"
                                            onchange="getRequest('{{ url('/') }}/admin/food/get-categories?parent_id='+this.value,'sub-categories')"
                                            oninvalid="this.setCustomValidity('Select Category')">
                                            <option value="" selected disabled>
                                                {{ translate('Select Category') }}</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category['id'] }}">{{ $category['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-8">
                                    <input id="pac-input" class="controls rounded initial-8"
                                        title="{{ translate('messages.search_your_location_here') }}" type="text"
                                        placeholder="{{ translate('messages.search_here') }}" />
                                    <div style="height: 370px !important" id="map"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card shadow--card-2 border-0">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span class="card-header-icon mr-2"><i class="tio-dollar-outlined"></i></span>
                                <span>{{ translate('Other Information') }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.phone') }}</label>
                                        <input type="phone" name="phone" class="form-control"
                                            placeholder="{{ translate('messages.Ex :') }} +213xxxxxxxxxx" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label"
                                            for="exampleFormControlInput1">{{ translate('messages.email') }}</label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="{{ translate('messages.Ex :') }} place@safari.com" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn"
                            class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection


@push('script_2')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=drawing,places&v=3.45.8">
    </script>
    <script>
        @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
        @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)

        function initAutocomplete() {
            var myLatLng = {
                lat: {{ $default_location['lat'] ? $default_location['lat'] : '35.33760884741549' }},
                lng: {{ $default_location['lng'] ? $default_location['lng'] : '7.76876073665926' }}
            };
            const map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: {{ $default_location['lat'] ? $default_location['lat'] : '35.33760884741549' }},
                lng: {{ $default_location['lng'] ? $default_location['lng'] : '7.76876073665926' }}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];


                geocoder.geocode({
                    'latLng': latlng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').innerHtml = results[1].formatted_address;
                        }
                    }
                });
            });
            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function(event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();
                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on('ready', function() {
            initAutocomplete()

        });

        document.addEventListener('keypress', function(e) {
            if (e.keyCode === 13 || e.which === 13) {
                e.preventDefault();
                return false;
            }
        });
    </script>
    <script>
        var count = 0;
        $(document).ready(function() {


            $("#add_new_option_button").click(function(e) {
                count++;
                var add_option_view = `
            <div class="__bg-F8F9FC-card view_new_option mb-2">
                <div class="d-flex align-items-center justify-content-between mb-3">
                <label class="form-check form--check">
                    <input id="options[` + count + `][required]" name="options[` + count + `][required]" class="form-check-input" type="checkbox">
                    <span class="form-check-label">{{ translate('Required') }}</span>
                </label>
                <div>
                    <button type="button" class="btn btn-danger btn-sm delete_input_button" onclick="removeOption(this)"
                        title="{{ translate('Delete') }}">
                        <i class="tio-add-to-trash"></i>
                    </button>
                </div>
                </div>
                <div class="row g-2">
                    <div class="col-xl-4 col-lg-6">
                        <label for="">{{ translate('Vatiation Title') }}</label>
                        <input required name=options[` + count +
                    `][name] class="form-control" type="text" onkeyup="new_option_name(this.value,` +
                    count + `)">
                    </div>

                    <div class="col-xl-4 col-lg-6">
                        <div>
                            <label class="input-label text-capitalize d-flex alig-items-center"><span class="line--limit-1">{{ translate('Variation Selection Type') }} </span>
                            </label>
                            <div class="resturant-type-group px-0">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" value="multi"
                                    name="options[` + count + `][type]" id="type` + count +
                    `" checked onchange="show_min_max(` + count + `)"
                                    >
                                    <span class="form-check-label">
                                        {{ translate('Multiple Selection') }}
                                    </span>
                                </label>

                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" value="single"
                                    name="options[` + count + `][type]" id="type` + count +
                    `" onchange="hide_min_max(` + count + `)"
                                    >
                                    <span class="form-check-label">
                                        {{ translate('Single Selection') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                        <div class="row g-2">
                            <div class="col-6">
                                <label for="">{{ translate('Min Option to Select') }}</label>
                                <input id="min_max1_` + count + `" required  name="options[` + count + `][min]" class="form-control" type="number" min="1">
                            </div>
                            <div class="col-6">
                                <label for="">{{ translate('Max Option to Select') }}</label>
                                <input id="min_max2_` + count + `"   required name="options[` + count + `][max]" class="form-control" type="number" min="1">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="option_price_` + count + `" >
                    <div class="border bg-white rounded p-3 pb-0 mt-3">
                        <div  id="option_price_view_` + count + `">
                            <div class="row g-3 add_new_view_row_class mb-2">
                                <div class="col-lg-4 col-sm-6">
                                    <label for="">{{ translate('Option_name') }}</label>
                                    <input class="form-control" required type="text" name="options[` + count +
                    `][values][0][label]" id="">
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    <label for="">{{ translate('Additional_price') }}</label>
                                    <input class="form-control" required type="number" min="0" step="0.01" name="options[` +
                    count + `][values][0][optionPrice]" id="">
                                </div>
                            </div>
                        </div>
                        <div class="row p-3 mr-1 d-flex "  id="add_new_button_` + count +
                    `">
                            <button type="button" class="btn btn--primary btn-outline-primary" onclick="add_new_row_button(` +
                    count + `)" >{{ translate('Add_New_Option') }}</button>
                        </div>
                    </div>
                </div>
            </div>`;

                $("#add_new_option").append(add_option_view);
            });
        });

        function show_min_max(data) {
            $('#min_max1_' + data).removeAttr("readonly");
            $('#min_max2_' + data).removeAttr("readonly");
            $('#min_max1_' + data).attr("required", "true");
            $('#min_max2_' + data).attr("required", "true");
        }

        function hide_min_max(data) {
            $('#min_max1_' + data).val(null).trigger('change');
            $('#min_max2_' + data).val(null).trigger('change');
            $('#min_max1_' + data).attr("readonly", "true");
            $('#min_max2_' + data).attr("readonly", "true");
            $('#min_max1_' + data).attr("required", "false");
            $('#min_max2_' + data).attr("required", "false");
        }




        function new_option_name(value, data) {
            $("#new_option_name_" + data).empty();
            $("#new_option_name_" + data).text(value)
            console.log(value);
        }

        function removeOption(e) {
            element = $(e);
            element.parents('.view_new_option').remove();
        }

        function deleteRow(e) {
            element = $(e);
            element.parents('.add_new_view_row_class').remove();
        }


        function add_new_row_button(data) {
            count = data;
            countRow = 1 + $('#option_price_view_' + data).children('.add_new_view_row_class').length;
            var add_new_row_view = `
                <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-sm-0">
                    <div class="col-md-4 col-sm-5">
                            <label for="">{{ translate('Option_name') }}</label>
                            <input class="form-control" required type="text" name="options[` + count + `][values][` +
                countRow + `][label]" id="">
                        </div>
                        <div class="col-md-4 col-sm-5">
                            <label for="">{{ translate('Additional_price') }}</label>
                            <input class="form-control"  required type="number" min="0" step="0.01" name="options[` +
                count +
                `][values][` + countRow + `][optionPrice]" id="">
                        </div>
                        <div class="col-sm-2 max-sm-absolute">
                            <label class="d-none d-sm-block">&nbsp;</label>
                            <div class="mt-1">
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)"
                                    title="{{ translate('Delete') }}">
                                    <i class="tio-add-to-trash"></i>
                                </button>
                            </div>
                    </div>
                </div>`;
            $('#option_price_view_' + data).append(add_new_row_view);

        }
    </script>

    <script>
        function getRestaurantData(route, restaurant_id, id) {
            $.get({
                url: route + restaurant_id,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }
        $(document).ready(function() {
            // Trigger file input click when Add Image button is clicked
            $('#addImageButton').click(function() {
                $('#customFileEg1').click();
            });

            $("#customFileEg1").change(function() {
                readImagesURL(this);
                $('#image-grid').show(1000);
            });

            // Delegated click event for dynamically added delete icons

            $('#image-grid').on('click', '.delete-image', function() {
                var imageWrapper = $(this).closest('.image-wrapper');
                var inputFile = $('#customFileEg1');

                // Remove the image container
                imageWrapper.remove();

                // If there are no more images, hide the image grid
                if ($('#image-grid .image-wrapper').length === 0) {
                    $('#image-grid').hide(1000);
                }

                // Reset the file input to allow re-selection of the same file
                inputFile.val('');
            });

            function readImagesURL(input) {
                if (input.files && input.files.length > 0) {
                    var imageGrid = $('#image-grid');
                    for (var i = 0; i < input.files.length; i++) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var img = $(
                                '<div class="image-wrapper"><img class="selected-image initial-52 object--cover border--dashed " src="' +
                                e.target.result +
                                '" alt="selected image"><span class="delete-image" style="color: red;" title="Delete Image"><i class="tio-delete"></i></span></div>'
                            );
                            imageGrid.append(img);
                        };

                        reader.readAsDataURL(input.files[i]);
                    }
                }
            }
        });
    </script>

    <script>
        $(document).on('ready', function() {
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{ url('/') }}/admin/restaurant/get-restaurants',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
    </script>


    <script src="{{ asset('public/assets/admin') }}/js/tags-input.min.js"></script>


    <script>
        function dataURLtoFile(dataurl, filename) {
            var arr = dataurl.split(','),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]),
                n = bstr.length,
                u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], filename, {
                type: mime
            });
        }
        $('#food_form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $('#image-grid .selected-image').each(function(index, element) {
                var fileInput = $(element).attr('src');
                formData.append('images[]', dataURLtoFile(fileInput, 'image' + index +
                    '.png')); // Convert data URL to file
            });
            console.log(formData);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.food.store') }}',
                //data: $('#food_form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    console.log(data)

                    $('#loading').hide();
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('messages.product_added_successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href =
                                '{{ route('admin.food.list') }}';
                        }, 200);
                    }
                }
            });
        });
    </script>
    <script>
        $(".lang_link").click(function(e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.substring(0, form_id.length - 5);
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{ $default_lang }}') {
                $("#from_part_2").removeClass('d-none');
            } else {
                $("#from_part_2").addClass('d-none');
            }
        })
    </script>
    <script>
        $('#reset_btn').click(function() {
            $('#restaurant_id').val(null).trigger('change');
            $('#category_id').val(null).trigger('change');
            $('#categories').val(null).trigger('change');
            $('#sub-veg').val(0).trigger('change');
            $('#add_on').val(null).trigger('change');
            $('#viewer').attr('src', "{{ asset('public/assets/admin/img/upload.png') }}");
        })
    </script>
@endpush
