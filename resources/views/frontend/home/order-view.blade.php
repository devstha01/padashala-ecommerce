@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
        {{--<div class="container">--}}
        {{--<ol class="breadcrumb">--}}
        {{--<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>--}}
        {{--<li class="breadcrumb-item"><a href="{{route('order-list')}}">Order</a></li>--}}
        {{--<li class="breadcrumb-item active" aria-current="page"># {{$orders->invoice_number}}</li>--}}
        {{--</ol>--}}
        {{--</div><!-- End .container -->--}}
        {{--</nav>--}}


        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <h3>{{__('front.Order Detail')}}</h3>
                    <hr>

                    <table class="table" style="border-top: 0">
                        {{--<tr style="border-top: 0">--}}
                        {{--<td>--}}
                        {{--<b>{{__('front.Order Status')}}</b>--}}
                        {{--</td>--}}
                        {{--<td>--}}
                        {{--@if($orders->order_status_id =='cancel')--}}
                        {{--<h2 class="text-danger">{{$orders->getOrderStatus->name}}</h2>--}}
                        {{--@else--}}
                        {{--<h2 class="text-success">{{$orders->getOrderStatus->name}}</h2>--}}
                        {{--@if($orders->order_status_id =='order')--}}
                        {{--(need confirmation)--}}
                        {{--@endif--}}
                        {{--@endif--}}
                        {{--</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td>
                                <b>{{__('front.Order date')}} </b>
                            </td>
                            <td>
                                {{$orders->order_date}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>{{__('front.Payment method')}} </b>
                            </td>
                            <td>
                                @foreach($orders->payment_array as $pay)
                                    @if($pay['status'] ==1)
                                        {{$pay['name']}} :  $ {{$pay['amount']}} |
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td><b>{{__('front.Shipping Address')}} </b>
                            </td>
                            <td>
                                {{$orders->address}}
                                , {{$orders->city}}
                                , {{$orders->getCountry->name}}
                            </td>
                        </tr>
                        <tr>
                            <td><b>{{__('front.Shipping Contact')}} </b>
                            </td>
                            <td>
                                <i class="fa fa-phone"></i> {{$orders->contact_number}}
                                <br><i class="fa fa-envelope"></i> {{$orders->email}}
                            </td>
                        </tr>
                    </table>
                    <br>
                    {{--<hr>--}}
                    <table class="table table-hover border">
                        <tr>
                            <th></th>
                            <th>{{__('front.Product')}}</th>
                            <th>{{__('front.Price')}}</th>
                            <th>{{__('front.Qty')}}</th>
                            <th>Tax</th>
                            <th>{{__('front.Subtotal')}}</th>
                            <th>{{__('front.Status')}}</th>
                        </tr>

                        @forelse($invoice_items as $invoice_item)
                            <tr>
                                <th colspan="8">
                                    {{__('front.Invoice')}} : {{$invoice_item['invoice']}}
                                    <a href="{{route('merchant-info',$invoice_item['merchant']->slug)}}"
                                       class="pull-right">{{__('front.Merchant')}}
                                        : {{$invoice_item['merchant']->name}}</a>
                                </th>
                            </tr>
                            @foreach($invoice_item['items'] as $item)
                                <tr>
                                    <td></td>
                                    <td>
                                        <a href="{{url('product/'.$item->getProduct->slug)}}">
                                            <img src="{{asset('image/products/'.$item->getProduct->featured_image)}}"
                                                 alt="image" style="height: 100px">
                                            {{$item->getProduct->name??''}}  {{ (isset($item->getProductVariant->name))? "[".$item->getProductVariant->name."]":''}}
                                        </a>
                                    </td>
                                    <td>${{$item->sell_price}}</td>
                                    <td>{{$item->quantity}}</td>
                                    <td>${{$item->net_tax+0}}</td>
                                    <td>${{$item->quantity * $item->sell_price}}</td>
                                    <td>{{$item->getOrderStatus->name}}
                                        <br>
                                        {{$item->deliver_date??''}}
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="8"> {{__('front.Empty order')}} !</td>
                            </tr>
                        @endforelse


                        <tr>
                            <td colspan="5">{{__('front.Sub Total (exclusive Tax)')}}</td>
                            <td colspan="2">${{$orders->sub_total}}</td>
                        </tr>
                        <tr>
                            <td colspan="5">Net Tax</td>
                            <td colspan="2">${{$orders->tax}}</td>
                        </tr>

                        <tr>
                            <td colspan="5">{{__('front.Net Total (inclusive Tax)')}}</td>
                            <td colspan="2">
                                ${{number_format($orders->total_price,2,'.','')}}</td>
                        </tr>
                    </table>

                    @if($orders->order_status_id == 'order')
                        <form action="{{route('confirm-order',$orders->id)}}" method="post"
                              style="display: inline-block">
                            {{csrf_field()}}
                            <button type="submit" class="btn btn-primary">{{__('front.Confirm Order')}}</button>
                        </form>
                    @endif

                    @if($orders->order_status_id == 'order')
                        <form action="{{route('cancel-order',$orders->id)}}" method="post"
                              style="display: inline-block">
                            {{csrf_field()}}
                            <button type="submit" class="btn btn-danger">{{__('front.Cancel Order')}}</button>
                        </form>
                    @endif

                </div>

                <div class="col-lg-4">
                </div>
            </div><!-- End .col-lg-8 -->
        </div><!-- End .container -->

        <div class="mb-6"></div><!-- margin -->
    </main><!-- End .main -->

@endsection