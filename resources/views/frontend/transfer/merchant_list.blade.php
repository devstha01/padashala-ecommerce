@extends('frontend.layouts.app')
@section('content')
    <main class="main" id="merchantListing">
        <div class="container">

            <hr>
            @if(isset($search))
                <h4>{{__('front.Search results for term')}} '{{$search}}'</h4>
            @endif

            <div class="row">

                <div class="col-md-3">
                    <form action="{{route('search-product')}}">
                        <input type="hidden" name="type" value="merchant">
                        <input type="hidden" name="slug" value="{{$search??''}}">

                        <div id="sortBy">
                            <b class="lead"> {{__('front.Sort By')}} </b>
                            <br>
                            <input class="trigger-submit-filter" type="radio" id="asc" name="sorting"
                                   value="asc" {{$old_sorting =='asc'?'checked':''}}>
                            <label for="asc">{{__('front.A - Z')}}</label>
                            <br>
                            <input class="trigger-submit-filter" type="radio" id="desc" name="sorting"
                                   value="desc" {{$old_sorting =='desc'?'checked':''}}>
                            <label for="desc">{{__('front.Z - A')}}</label>
                        </div>
                        <div id="sortBy">

                            <b class="lead"> {{__('front.Filter By')}} </b>
                            @foreach($country_filter as $country)
                                <br>
                                <input type="checkbox" class="trigger-submit-filter" name="country_select[]"
                                       value="{{$country->id}}"
                                       id="country-filter-{{$country->id}}" {{(in_array($country->id,$selected_country))?'checked':''}}>
                                <label for="country-filter-{{$country->id}}">{{$country->name}}</label>
                            @endforeach
                            <div class="filter-price-action fade">
                                <button type="submit" class="btn btn-primary"
                                        id="submit-filter">{{__('front.Filter')}}</button>
                            </div><!-- End .filter-price-action -->
                        </div>

                    </form>
                </div>

                <div class="col-md-9">
                    <!-- <div class="row"> -->
                    <!-- <div class="item" style="border: 1px solid red"> -->
                    {{--<div class="row testi_item">--}}
                    @forelse($merchants as $merchant)
                        <div class="store">
                            <a href="{{route('merchant-info',$merchant->slug)}}">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12">
                                            <div class="shopImgDiv">
                                                @if($merchant->getMerchant->logo !== null)
                                                    <img class="shopImg"
                                                         src="{{asset('image/merchantlogo/'.$merchant->getMerchant->logo)}}"
                                                         alt="">
                                                @else
                                                    <img class="shopImg"
                                                         src="{{asset('image/not-available.jpg')}}" alt="">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-5 col-sm-12">
                                            <div id="info">
                                                <h2>{{$merchant->name}}</h2>
                                                {{--<h3>Electronics, Fashion, Sports</h3>--}}
                                                <h3><i class="fa fa-phone"></i> {{$merchant->contact_number}}</h3>
                                                <p>
                                                    <i class="fa fa-map-marker"> {{$merchant->address}}
                                                        , {{$merchant->getCountry->name}}</i>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12" id="rightSide">
                                            {{--<div id="open">Open</div>--}}
                                            {{--<div id="buttons">--}}
                                                {{--<div class="cardButton">Pay by QrCode</div>--}}
                                                {{--<div class="cardButton">Cash on Delivery</div>--}}
                                                {{--<br>--}}
                                                {{--<br>--}}
                                            {{--</div>--}}

                                            {{--@if($merchant->getMerchant->qr_image !==null)--}}
                                                {{--<div class="cashInfoBox">{{__('front.Pay by Qr code')}}</div>--}}
                                                {{--<a title="Save Qr Code" href="{{$merchant->getMerchant->qr_image}}">--}}
                                                    {{--                                            <img src="{{asset('image/qr_image/merchant/'.$merchant->getMerchant->qr_image)}}"--}}
                                                    {{--<img src="{{$merchant->getMerchant->qr_image}}"--}}
                                                         {{--style="height: 150px">--}}
                                                {{--</a>--}}
                                            {{--@endif--}}

                                            <div>
                                                <i class="float-right">{{__('front.Merchant')}}
                                                    : {{$merchant->getMerchant->name}}</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <h3 style="color:lightgrey">{{__('front.No related merchants for the search term')}}.</h3>
                @endforelse
                {{--</div>--}}
                <!-- </div> -->
                    <nav class="toolbox toolbox-pagination">
                        <div class="toolbox-item toolbox-show">
                            <label>{{__('front.Showing')}}
                                @if($total >((($page - 1)*$perPage)+1))
                                    {{(($page - 1)*$perPage)+1}}
                                @else
                                    {{($total)}}
                                @endif
                                -
                                @if($total >($page *$perPage))
                                    {{($page *$perPage)}}
                                @else
                                    {{($total)}}
                                @endif
                                {{__('front.of')}} {{$total}}
                                {{__('front.results')}}</label>
                        </div><!-- End .toolbox-item -->

                        <ul class="pagination">
                            @if($page>1)
                                <li class="page-item">
                                    <a class="page-link page-link-btn" href="{{Request::fullUrl().'&page='.($page-1)}}"><i
                                                class="icon-angle-left"></i></a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                                         href="{{Request::fullUrl().'&page='.($page-1)}}">{{($page-1)}}</a>
                                </li>
                            @endif
                            <li class="page-item active"><a class="page-link ">{{($page)}}</a></li>

                            @if($total >($page *$perPage))
                                <li class="page-item"><a class="page-link"
                                                         href="{{Request::fullUrl().'&page='.($page+1)}}">{{($page+1)}}</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link page-link-btn" href="{{Request::fullUrl().'&page='.($page+1)}}"><i
                                                class="icon-angle-right"></i></a>
                                </li>
                            @endif

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('frontend/assets/css/merchant.css')}}">
@endsection