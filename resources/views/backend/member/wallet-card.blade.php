<div class="portlet adminBoxes">
    <div class="row" style="margin: 0">
        <div class="col-md-4 col-sm-6 box-custom-md-invert  " >
            <div class="box">
                <h3>{{__('dashboard.Cash Wallet')}}</h3>
                <i class="fa fa-money-bill-wave"></i>
                <h5>${{ $wallet->ecash_wallet }}</h5>
            </div>
        </div>
        <div class="col-md-4 col-sm-6  box-custom-md-invert" >
            <div class="box">
                <h3>{{__('dashboard.Voucher Wallet')}}</h3>
                <i class="fa fa-money-check-alt"></i>
                <h5>${{ $wallet->evoucher_wallet }}</h5>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 box-custom-md  " >
            <div class="box">
                <h3>{{__('dashboard.R point')}}</h3>
                <i class="fa fa-file-invoice-dollar"></i>
                <h5>{{ $wallet->r_point }}</h5>
            </div>
        </div>
        <div class="col-md-4 col-sm-6  box-custom-md-invert" >
            <div class="box">
                <h3>{{__('dashboard.Chips')}}</h3>
                <i class="fa fa-coins"></i>
                <h5>{{ $wallet->chip }}</h5>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 box-custom-md-invert " >
            <div class="box">
                <h3>{{__('dashboard.Shopping Point')}}</h3>
                <i class="fa fa-tags"></i>
                <h5>{{ $wallet->shop_point }}</h5>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 box-custom-md " >
            <div class="box">
                <h3>{{__('dashboard.Assets')}} / {{__('dashboard.Dividend')}}</h3>
                <i class="fa fa-gift"></i>
                <h5>{{ $wallet->capital_amount }} / {{ $wallet->capital }}</h5>
            </div>
        </div>
    </div>
</div>
