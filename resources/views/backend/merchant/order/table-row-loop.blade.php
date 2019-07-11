<tr style="cursor: pointer;">
    <td>
        {{$k}}
    </td>
    <td>{{$order->first()->getOrder->order_date}}</td>
    <td>{{$order->first()->getOrder->getUser->name}} {{$order->first()->getOrder->getUser->surname}}</td>
    <td>
        @foreach($order as $item)
            {{$item->getProduct->name}}
            @if($item->getProductVariant !==null)
                [ {{$item->getProductVariant->name}} ]
            @endif


            @if($item->getOrderStatus->key =='process')
                <i class="badge badge-warning" style="float:right;">{{$item->getOrderStatus->name}}</i>
            @elseif($item->getOrderStatus->key =='deliver')
                <i class="badge badge-success" style="float:right;">{{$item->getOrderStatus->name}}</i>
            @else
                <i class="badge badge-danger" style="float:right;">{{$item->getOrderStatus->name}}</i>
            @endif

            <br>
        @endforeach
    </td>
    <td>
        <a href="{{route('order-details',$order->first()->getOrder->id)}}"><i class="fa fa-location-arrow"></i></a>
    </td>
</tr>