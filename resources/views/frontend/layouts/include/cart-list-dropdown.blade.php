<div class="dropdown cart-dropdown">
    <a href="{{route('cart-view')}}" id="my-cart-a" class="dropdown-toggle" role="button" data-toggle="dropdown"
       aria-haspopup="true"
       aria-expanded="false" data-display="static">
        <i class="fa fa-shopping-cart fa-2x text text-white"></i>
        <span class="cart-count">0</span>
        <span>{{__('front.My Cart')}}</span>
    </a>
    <div class="dropdown-menu">
        <div class="dropdownmenu-wrapper">
            <div class="dropdown-cart-header">
                <span class="cart-count">0 </span> &nbsp;{{__('front.Items')}}

                <a href="{{route('cart-view')}}">{{__('front.View Cart')}}</a>
            </div><!-- End .dropdown-cart-header -->
            <div class="dropdown-cart-products">

            </div>
            <!-- End .cart-product -->

            <div class="dropdown-cart-total">
                <span>{{__('front.Total')}}</span>

                <span class="cart-total-price"></span>
            </div><!-- End .dropdown-cart-total -->

            <div class="dropdown-cart-action">
                <a href="{{route('checkout-login')}}" class="btn btn-block">{{__('front.Checkout')}}</a>
            </div><!-- End .dropdown-cart-total -->
        </div><!-- End .dropdownmenu-wrapper -->
    </div><!-- End .dropdown-menu -->
</div><!-- End .dropdown -->
@section('scripts')
    <script>
        $('#my-cart-a').on('click', function () {
            var href = $(this).attr('href');
            window.location.replace(href);
        });
    </script>
@endsection
