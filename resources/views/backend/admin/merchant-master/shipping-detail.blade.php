<hr>
<h4>{{__('dashboard.Shipping Detail')}}
    @if(!empty(session('shipp')))
        <i style="font-size: 16px" class="shipp-message alert alert-success alert-sm"><i
                    class="fa fa-check"></i>{{session('shipp')??''}}</i>
    @endif
</h4>
<div style="position: relative;min-height: 300px">
    <div class="fade" id="shipping-edit-form" style="position: absolute;top:0">
        <form action="{{route('admin-item-shipping',$order->id)}}" method="post">
            {{csrf_field()}}
            <input type="hidden" name="merchant_id"
                   value="{{$merchant->id}}">
            <label>{{__('dashboard.Tracking ID')}}:</label>
            <input type="text" class="form-control" name="tracking_id"
                   value="{{$shipping->tracking_id??''}}">
            <label>{{__('dashboard.Carrier')}}:</label>
            <input type="text" class="form-control" name="carrier"
                   value="{{$shipping->carrier??''}}">
            <label>{{__('dashboard.Weight')}}:</label>
            <input type="text" class="form-control" name="weight"
                   value="{{$shipping->weight??''}}">
            <label>{{__('dashboard.Url')}}:</label>
            <input type="text" class="form-control" name="url"
                   value="{{$shipping->url??''}}">
            <input type="checkbox" id="notify" name="notify" value="true"
            <?php echo (isset($shipping->notify)) ? (($shipping->notify == 1) ? 'checked' : '') : ''; ?>>
            <label for="notify">{{__('dashboard.send notification to buyer')}}</label>

            <input type="submit" class="btn blue" id="shipping-edit-form"
                   value="Change Shipping ">
        </form>
    </div>

    <div class="" id="shipping-view" style="position: absolute;top:0">
        <p>
            {{__('dashboard.Tracking ID')}}: <i class="text-right">{{$shipping->tracking_id??''}}</i>
            <br>{{__('dashboard.Carrier')}}: <i class="text-right">{{$shipping->carrier??''}}</i>
            <br>{{__('dashboard.Weight')}}: <i class="text-right">{{$shipping->weight??''}}</i>
            <br> {{__('dashboard.Url')}}: <i class="text-right">{{$shipping->url??''}}</i>
        </p>
        <button class="fa fa-cog btn blue" id="shipping-view-button">{{__('dashboard.Update Shipping')}}
        </button>
    </div>

</div>