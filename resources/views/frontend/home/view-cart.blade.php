@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{__('front.Shopping Cart')}}</li>
                </ol>
            </div><!-- End .container -->
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="cart-table-container">
                        <table class="table table-cart">
                            <thead>
                            <tr s>
                                <th style="width: 55%" class="product-col">{{__('front.Product')}}</th>
                                <th style="width:15%" class="price-col">{{__('front.Price')}}</th>
                                <th style="width:10%" class="qty-col">{{__('front.Qty')}}</th>
                                <th style="width:10%">{{__('front.Subtotal')}}</th>
                                <th style="width:10%"></th>
                            </tr>
                            </thead>
                        </table>
                        <div class="scroll-cart-list" id="scroll-cart-list">
                            <table class="table table-cart">
                                <tbody id="checkout-table-list">

                                </tbody>
                            </table>
                        </div>
                        <table class="table table-cart">
                            <tfoot>
                            <tr>
                                <td colspan="4" class="clearfix">
                                    <div class="float-right">
                                        <a href="{{url('/')}}"
                                           class="btn btn-outline-secondary">{{__('front.Continue Shopping')}}</a>
                                    </div><!-- End .float-left -->

                                    {{--<div class="float-right">--}}
                                    {{--<a href="{{route('clear-cart-checkout')}}"--}}
                                    {{--onclick="return confirm('This will remove all your cart items. \n Are you sure?')"--}}
                                    {{--class="btn btn-outline-secondary btn-clear-cart">Clear Shopping Cart</a>--}}
                                    {{--</div><!-- End .float-right -->--}}
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- End .cart-table-container -->

                    {{--<div class="cart-discount">--}}
                    {{--<h4>Apply Discount Code</h4>--}}
                    {{--<form action="#">--}}
                    {{--<div class="input-group">--}}
                    {{--<input type="text" class="form-control form-control-sm"--}}
                    {{--placeholder="Enter discount code" required="">--}}
                    {{--<div class="input-group-append">--}}
                    {{--<button class="btn btn-sm btn-primary" type="submit">Apply Discount</button>--}}
                    {{--</div>--}}
                    {{--</div><!-- End .input-group -->--}}
                    {{--</form>--}}
                    {{--</div><!-- End .cart-discount -->--}}
                </div><!-- End .col-lg-8 -->

                <div class="col-md-4">
                    <div class="cart-summary">
                        <h3>{{__('front.Summary')}}</h3>

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
                            <tbody id="cart-checkout-summary">
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4">{{__('front.Total')}}</td>
                                <td id="checkout_net_total">Rs. 0.00</td>
                            </tr>
                            </tfoot>
                        </table>

                        <div class="checkout-methods">
                            @if(count(\Gloudemans\Shoppingcart\Facades\Cart::content())!==0)
                                <a href="{{route('order-address')}}"
                                   class="btn btn-block btn-sm btn-primary">{{__('front.Checkout')}}</a>
                            @endif
                        </div><!-- End .checkout-methods -->
                    </div><!-- End .cart-summary -->
                </div><!-- End .col-lg-4 -->
            </div><!-- End .row -->
        </div>

    </main><!-- End .main -->
@endsection
@section('stylesheets')
    <style>
        .scroll-cart-list {
            max-height: 500px;
            overflow-y: scroll;
            padding: 10px
        }

        .product-image img {
            max-height: 140px;
        }

        .break-word{
            word-break: break-all;
        }

    </style>
@endsection
@section('scripts')
    <script src="{{asset('frontend/assets/js/cart-checkout.js')}}" type="text/javascript"></script>
@stop