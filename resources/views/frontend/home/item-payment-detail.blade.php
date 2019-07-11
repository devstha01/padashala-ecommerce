@extends('frontend.layouts.app')

@section('content')
    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
        {{--<div class="container">--}}
        {{--<ol class="breadcrumb">--}}
        {{--<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>--}}
        {{--<li class="breadcrumb-item active">{{__('front.Profile')}}</li>--}}
        {{--</ol>--}}
        {{--</div><!-- End .container -->--}}
        {{--</nav>--}}


        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card p-3">
                        <img class="rounded-circle" src="{{asset('image/merchants/no-image.png')}}" alt="image"
                             style="max-width:220px;max-height:220px">
                        <br>
                        <h3 class="text-right">{{$user->name}} {{ $user->surname }}</h3>
                        <i class="fa fa-user mb-1"><i class="fa fa-key ml-1"></i> {{$user->user_name}}</i>
                        <i class="fa fa-envelope mb-1"> {{$user->email}}</i>
                        <i class="fa fa-phone mb-1"> {{$user->contact_number??' -'}}</i>
                        <i class="fa fa-birthday-cake mb-1"> {{$user->dob??' -'}}</i>
                        <i class="fa fa-map-marker mb-1">{{$user->address ??' - '}}, {{$user->city??' - '}}
                            , {{$user->getCountry->name}}</i>
                        <i class="fa fa-id-card mb-1"> {{$user->identification_type }}
                            : {{$user->identification_number??' - '}}</i>
                        <i class="fa fa-id-card-o mb-1"> {{__('front.Gender')}} : {{$user->gender }}
                            | {{__('front.Marital Status')}}
                            : {{$user->marital_status}}</i>
                        @if($user->is_member)
                            <i class="fa fa-check text-success"> {{__('front.Member')}}</i>
                        @else
                            <i class="fa fa-times text-danger"> {{__('front.Member')}}</i>
                        @endif

                        <a class="btn btn-info m-3"
                           href="{{route('edit-profile',$user->id)}}">{{__('front.Edit Profile')}}</a>
                        @if(Auth::user()->is_member ===1)
                            <a class="btn btn-info m-3"
                               href="{{route('edit-bank',$user->id)}}">{{__('front.Bank Account Info')}}</a>
                        @endif
                        <a class="btn btn-info m-3"
                           href="{{url('change/password')}}">{{__('front.Change Password')}}</a>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        @include('fragments.message')
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <img src="{{asset('image/products/'.$item->getProduct->featured_image)}}"
                                 alt="image"
                                 style="height: 200px">
                        </div>
                        <div class="col-sm-7">
                            <b>{{__('front.Product')}}: {{$item->getProduct->name}}</b>
                            <br>
                            <b>{{__('front.Variant')}}: {{$item->getProductVariant->name??'--'}}</b>
                            <br>
                            <b>{{__('front.Status')}}: {{$item->getOrderStatus->name}}</b>
                            <br>
                            <b>{{__('front.Quantity')}}: {{$item->quantity}}</b>
                            <br>
                            <b>{{__('front.Sell Price')}}: ${{$item->sell_price}}</b>
                            <br>
                            <b>{{__('front.Delivery')}}: $0.00</b>
                            <br>
                            <br>
                            <b>{{__('front.Total')}}:
                                ${{number_format((($item->sell_price * $item->quantity) *1.00),2,'.',0)}}</b>
                        </div>
                    </div>
                    <br>
                    <div class="row">

                        <form action="{{route('payment-submit')}}" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$item->id}}">

                            @if(Auth::user())
                                <input type="radio" id="val_1" name="payment_method" value="ecash_wallet" checked>
                                <label for="val_1"> <i class="fa fa-money"> </i> {{__('front.E cash Wallet')}}</label>
                                <br>
                                @if(Auth::user()->is_member === 1)
                                    <input type="radio" id="val_2" name="payment_method" value="evoucher_wallet">
                                    <label for="val_2"><i class="fa fa-money"> </i> {{__('front.E voucher Wallet')}}</label>
                                    <br>
                                @else
                                    <input type="radio" id="val_3" name="payment_method" value="cash">
                                    <label for="val_3"><i class="fa fa-money"> </i> {{__('front.Cash on Delivery')}}</label>
                                    <br>
                                @endif
                            @endif
                            <br>
                            <input type="checkbox" id="confirm" name="confirm" value="true">
                            <label for="confirm">{{__('front.Confirm Payment')}}</label>
                            <br>
                            <button type="submit" id="pay-submit" class="btn btn-info" style="display:none">{{__('front.Submit Payment')}}
                            </button>
                        </form>

                    </div>

                </div>
            </div><!-- End .col-lg-8 -->
        </div><!-- End .container -->

        <div class="mb-6"></div><!-- margin -->
    </main><!-- End .main -->

@endsection
@section('scripts')
    <script>
        $(function () {
            $('#confirm').on('change', function () {
                $('#pay-submit').toggle();
            });
        })
    </script>
@stop