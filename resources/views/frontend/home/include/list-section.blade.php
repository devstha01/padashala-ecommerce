<h2>{{__('front.My Orders')}}</h2>

<ul class="nav nav-tabs nav-justified" id="myTab1" role="tablist">
    <li class="nav-item">
        <a class="nav-link active"
           data-toggle="tab" href="#tab-1" role="tab"
           aria-controls="tab-1" aria-selected="true">{{__('front.Pending Orders')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
           data-toggle="tab" href="#tab-2" role="tab"
           aria-controls="tab-2" aria-selected="false">{{__('front.Completed Orders')}}</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent1">
    <div class="tab-pane active" id="tab-1" role="tabpanel" aria-labelledby="tab-1">
        <br>
        <table class="table border table-hover dataTable dtr-inline" id="table-confirm">
            <thead>
            <tr>
                <th style="width: 5%">{{__('front.SN')}}</th>
                <th style="width: 20%">{{__('front.Order Date')}}</th>
                <th style="width: 10%">{{__('front.Products')}}</th>
                <th style="width: 15%">{{__('front.Net Amount')}}</th>
                <th style="width: 35%">{{__('front.Address')}}</th>
                <th style="width: 15%">{{__('front.Action')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($p_orders as $key=>$p_order)
                <tr class=" border">
                    <td>{{++$key}}</td>
                    <td><i class="fa fa-calendar"></i> {{$p_order->order_date}}</td>
                    <td>{{count($p_order->getOrderItem)}}</td>
                    <td>${{$p_order->total_price}}</td>
                    <td>{{$p_order->address}} | {{$p_order->city}} | {{$p_order->getCountry->name}}</td>
                    <td>
                        <a href="{{route('order-detail',$p_order->id)}}"
                           class="btn btn-primary">{{__('front.Detail')}}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
    <div class="tab-pane" id="tab-2" role="tabpanel" aria-labelledby="tab-2">
        <br>
        <table class="table border table-hover dataTable dtr-inline" id="table-complete">
            <thead>
            <tr>
                <th style="width: 5%">{{__('front.SN')}}</th>
                <th style="width: 20%">{{__('front.Order Date')}}</th>
                <th style="width: 10%">{{__('front.Products')}}</th>
                <th style="width: 15%">{{__('front.Net Amount')}}</th>
                <th style="width: 35%">{{__('front.Address')}}</th>
                <th style="width: 15%">{{__('front.Action')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($c_orders as $key=>$c_order)
                <tr class=" border">
                    <td>{{++$key}}</td>
                    <td><i class="fa fa-calendar"></i> {{$c_order->order_date}}</td>
                    <td>{{count($c_order->getOrderItem)}}</td>
                    <td>${{$c_order->total_price}}</td>
                    <td>{{$c_order->address}} | {{$c_order->city}} | {{$c_order->getCountry->name}}</td>
                    <td>
                        <a href="{{route('order-detail',$c_order->id)}}"
                           class="btn btn-primary">{{__('front.Detail')}}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
