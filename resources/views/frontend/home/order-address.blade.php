@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
        {{--<div class="container">--}}
        {{--<ol class="breadcrumb">--}}
        {{--<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>--}}
        {{--<li class="breadcrumb-item active" aria-current="page">{{__('front.Checkout')}}</li>--}}
        {{--</ol>--}}
        {{--</div><!-- End .container -->--}}
        {{--</nav>--}}


        <div class="container">
            @include('fragments.message')
            <span style="display:none" id="ecash_payment">{{$user->getWallet->ecash_wallet??0}}</span>
            <span style="display:none" id="evoucher_payment">{{$user->getWallet->evoucher_wallet??0}}</span>
            <span id="are-you-sure-message" style="display: none"></span>
            <span id="user-type" style="display: none">{{$user->is_member?'member':'customer'}}</span>

            <ul class="checkout-progress-bar">
                <li>
                    <span>{{__('front.Shipping')}} &amp; {{__('front.Payments')}}</span>
                </li>
            </ul>
            <div class="row">
                <div class="col-md-8">
                    {{ Form::open([
                          'url' => 'order/address',
                          'class' => 'horizontal-form ajax-post-order-address',
                          'method'=> 'POST'
                          ])   }}
                    {{--<form action="{{route('post-order-address')}}" method="post">--}}

                    <ul class="checkout-steps">
                        <li>
                            <h2 class="step-title">{{__('front.Shipping Address')}}</h2>

                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="change-bill-address"
                                       value="false" name="old_address">
                                <label class="custom-control-label"
                                       for="change-bill-address">{{__('front.Use your default address')}}
                                    <br><b>{{$user->address}}, {{$user->city}}
                                        , {{$user->getCountry->name}}</b></label>
                                <br>
                                <span class="error-message"></span>
                            </div>

                            <label class="new_address">{{__('front.Ship to this Address instead')}}:</label>

                            <div class="row">
                                <div class="col-sm-10">

                                    <label class="new_address">{{__('front.Address')}}</label>
                                    <input type="text" class="new_address form-control" name="address"
                                           value="{{session('input_address')}}">
                                    <span class="error-message new_address"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="new_address">{{__('front.City')}}</label>
                                    <input type="text" class="new_address form-control" name="city"
                                           value="{{session('input_city')}}">
                                    <span class="error-message new_address"></span>
                                </div>
                                <div class="col-sm-4">
                                    <label class="new_address">{{__('front.Country')}}</label>
                                    <select name="country_id" class="new_address form-control">
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}" {{($country->id == session('input_country_id'))?'selected':''}}>{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="error-message new_address"></span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <h2 class="step-title">{{__('front.Contact Info')}}</h2>


                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="change-bill-contact"
                                       value="false" name="old_contact">
                                <label class="custom-control-label"
                                       for="change-bill-contact">{{__('front.Use your default contact')}}
                                    <br><b>{{$user->email}}
                                        <br>{{$user->contact_number}}
                                    </b></label>
                                <br>
                                <span class="error-message"></span>
                            </div>

                            <div class="row">
                                <div class="col-sm-10">

                                    <label class="new_contact">{{__('front.Email')}}</label>
                                    <input type="email" class="new_contact form-control" name="email"
                                           value="{{session('input_email')}}">
                                    <span class="error-message new_contact"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">
                                    <label class="new_contact">{{__('front.Contact Number')}}</label>
                                    <input type="text" class="new_contact form-control" name="contact_number"
                                           value="{{session('input_contact_number')}}">
                                    <span class="error-message new_contact"></span>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="checkout-step-shipping">
                                <h2 class="step-title">{{__('front.Payment Methods')}}</h2>
                                <span class="error-message" id="payment_method_error"></span>
                                <table class="table table-step-shipping">
                                    <tbody>
                                    @if(Auth::user())
                                        @if(Auth::user()->is_member === 1)
                                            <tr class="">
                                                <td colspan="2" class="text-right">{{__('front.E cash Wallet')}} :
                                                    $
                                                </td>
                                                <td><input type="text" name="ecash_wallet" id="ecash_wallet"
                                                           class="form-control"
                                                           value="{{session('input_ecash_wallet')}}">
                                                    <span class="error-message" id="ecash_method_error"></span>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td colspan="2" class="text-right">{{__('front.E voucher Wallet')}}
                                                    : $
                                                </td>
                                                <td><input type="text" id="evoucher_wallet" name="evoucher_wallet"
                                                           class="form-control"
                                                           value="{{session('input_evoucher_wallet')}}">
                                                    <span class="error-message" id="evoucher_method_error"></span>
                                                </td>
                                            </tr>
                                        @else
                                            <tr class="clickable-row">
                                                <td><input type="radio" name="payment_method"
                                                           value="cash" id="cash_payment_method" checked>
                                                </td>
                                                <td colspan="2">{{__('front.Cash on Delivery')}}</td>
                                            </tr>
                                            <tr class="clickable-row">
                                                <td><input type="radio" name="payment_method"
                                                           value="ecash_wallet" id="ecash_payment_method">
                                                </td>
                                                <td colspan="2">{{__('front.E cash Wallet')}}</td>
                                            </tr>

                                        @endif
                                    @endif

                                    </tbody>
                                </table>
                            </div><!-- End .checkout-step-shipping -->
                        </li>
                    </ul>
                    <div class="checkout-steps-action">
                        <a class="btn btn-warning" href="{{route('cart-view')}}">{{__('front.BACK')}}</a>
                        <button type="submit" class="btn btn-primary float-right">{{__('front.PLACE ORDER')}}</button>
                    </div><!-- End .checkout-steps-action -->
                    {{Form::close()}}
                </div>            <!-- End .col-lg-8 -->

                <div class="col-md-4">
                    @if(Auth::user())
                        <h3>{{__('front.Wallet Info')}}</h3>
                        <hr>
                        <table class="table border">
                            <tr>
                                <th colspan="2">{{__('front.Available wallet')}} :</th>
                            </tr>
                            <tr>
                                <td><b> <i class="fa fa-money"></i> {{__('front.E cash Wallet')}} :</b></td>
                                <td>${{$user->getWallet->ecash_wallet??'0.00'}}</td>
                            </tr>
                            @if(Auth::user()->is_member === 1)
                                <tr>
                                    <td><b> <i class="fa fa-money"></i> {{__('front.E voucher Wallet')}} :</b></td>
                                    <td>${{$user->getWallet->evoucher_wallet??'0.00'}}</td>
                                </tr>
                            @endif
                        </table>
                    @endif
                    <br>
                    <div class="cart-summary">
                        <h3>{{__('front.Billing info')}}</h3>

                        <table class="table table-totals">
                            <thead>
                            <tr>
                                <th style="width: 10%">{{__('front.s/n')}}</th>
                                <th style="width: 45%">{{__('front.Product')}}</th>
                                <th style="width: 15%">{{__('front.Price')}}</th>
                                <th style="width: 10%">{{__('front.Qty')}}</th>
                                <th style="width: 15%">{{__('front.Subtotal')}}</th>
                            </tr>
                            </thead>
                            <tbody id="cart-checkout-summary-address">
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4">{{__('front.Total')}}</td>
                                <td id="checkout-net_total">$0.00</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" id="total_checkout" value="{{$total}}">
                </div>
            </div>
        </div><!-- End .container -->

        <div class="mb-6"></div><!-- margin -->
    </main><!-- End .main -->

@endsection

@section('scripts')
    <script src="{{asset('frontend/assets/js/address-checkout.js')}}" type="text/javascript"></script>
    <script>
        $(function () {
            $(".clickable-row").click(function () {
                // console.log('ok');
                $(this).children('td').children('input[name="payment_method"]').prop("checked", true);
            });
        });
    </script>

    <script src="{{asset('frontend/custom/repo.js')}}" type="text/javascript"></script>
    <script src="{{asset('frontend/custom/ajax-post.js')}}" type="text/javascript"></script>
    <script src="{{asset('frontend/assets/js/cart-checkout.js')}}" type="text/javascript"></script>
@stop
