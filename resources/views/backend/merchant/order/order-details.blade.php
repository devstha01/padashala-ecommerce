@extends('backend.layouts.master')
@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    <div class="page-title">
                        <h3>{{__('dashboard.Order Detail')}} </h3>
                    </div>


                    <div class="portlet light">
                        <div class="row">
                            <div class="col-sm-4 col-md-2">
                                <img class="rounded-circle" src="{{asset('image/merchants/merchant.jpg')}}" alt="image"
                                     style="max-width:120px;max-height:120px">
                            </div>
                            <div class="col-sm-8 col-md-4">
                                <br>
                                <h4 class="">{{$order->getUser->name}} {{ $order->getUser->surname }}</h4>
                                <i class="fa fa-envelope mb-1"> {{$order->email}}</i>
                                <br>
                                <i class="fa fa-phone mb-1"> {{$order->contact_number??' -'}}</i>
                                <i class="fa fa-map-marker mb-1">{{$order->address ??' - '}}
                                    , {{$order->city??' - '}}
                                    , {{$order->getCountry->name}}</i>
                                <i class="fa fa-id-card-o mb-1"> {{__('dashboard.Gender')}}
                                    : {{$order->getUser->gender }} | {{__('dashboard.Marital Status')}}
                                    : {{$order->getUser->marital_status}}</i>
                                <br>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <h4 class="pull-right">
                                    {{__('dashboard.Order Date')}}
                                    |{{\Carbon\Carbon::parse($order->order_date)->format('d M, Y H:i')}}
                                </h4>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <h4>{{__('dashboard.Payment Method')}} <h4 class="badge badge-success">{{$method}}</h4>
                                </h4>
                            </div>
                            <div class="col-sm-6">
                                <h4 class="pull-right">
                                    {{__('dashboard.Invoice')}} # {{$orderItem->first()->invoice}}
                                </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-hover">
                                    <tr>
                                        <th colspan="2">{{__('dashboard.Product')}}</th>
                                        <th>{{__('dashboard.Price')}}</th>
                                        <th>{{__('dashboard.Qty')}}</th>
                                        <th>Tax</th>
                                        <th>{{__('dashboard.Net Price')}}</th>
                                        <th>Payment</th>
                                        <th>Merchant</th>
                                        <th>Order Status</th>
                                        <th>{{__('dashboard.Action')}}</th>
                                    </tr>
                                    @foreach($orderItem as $item)
                                        <tr>
                                            <td>
                                                <img src="{{asset('image/products/'.$item->getProduct->featured_image)}}"
                                                     alt="image" height="70px">
                                            </td>
                                            <td> {{$item->getProduct->name}}
                                                <br>{{$item->getProductVariant?("[".$item->getProductVariant->name."]"):''}}
                                            </td>
                                            <td>
                                                Rs.{{$item->sell_price}}
                                            </td>
                                            <td>X {{$item->quantity}}</td>
                                            <td>Rs.{{$item->net_tax+0}}</td>
                                            <td>Rs.{{$item->net_price+$item->net_tax}}</td>
                                            <td class="td-status">
                                                <i class="badge badge-info">{{$item->payment_status}}</i>
                                            </td>
                                            <td class="td-status">
                                                <i class="badge badge-info">{{$item->merchant_status}}</i>
                                            </td>
                                            <td class="td-status">
                                                <i class="badge badge-info">{{$item->getOrderStatus->name}}</i>
                                            </td>
                                            <form action="{{route('item-status-change',$item->id)}}"
                                                  method="post">
                                                {{csrf_field()}}
                                                <td class="select-status">
                                                    <i class="badge badge-info">{{$item->payment_status}}</i>
                                                </td>
                                                <td class="select-status">
                                                    <select name="merchant_status"
                                                            class="form-control ">
                                                        <option value="pending" {{$item->merchant_status =='pending'?'selected':''}}>
                                                            pending
                                                        </option>
                                                        <option value="packaging" {{$item->merchant_status =='packaging'?'selected':''}}>
                                                            packaging
                                                        </option>
                                                        <option value="ready to dispatch" {{$item->merchant_status =='ready to dispatch'?'selected':''}}>
                                                            ready to dispatch
                                                        </option>
                                                        <option value="delivered" {{$item->merchant_status =='delivered'?'selected':''}}>
                                                            delivered
                                                        </option>
                                                    </select>
                                                </td>
                                                <td class="select-status">
                                                    <i class="badge badge-info">{{$item->getOrderStatus->name}}</i>
                                                </td>
                                                <td class="select-status">
                                                    <button class="update-status btn green">Update
                                                    </button>
                                                </td>
                                            </form>
                                            <td class="td-status">
                                                <button class="change-status btn blue">Change</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="3"></th>
                                        <th colspan="2">{{__('dashboard.Total')}}</th>
                                        <th>Rs.{{$total}}</th>
                                        <th colspan="4"></th>
                                    </tr>
                                    {{--<tr>--}}
                                    {{--<td colspan="2"></td>--}}
                                    {{--<td colspan="2">Tax</td>--}}
                                    {{--<td>Rs.{{$tax}}</td>--}}
                                    {{--<td colspan="2"></td>--}}
                                    {{--</tr>--}}
                                    <tr>
                                        <th colspan="3"></th>
                                        <th colspan="2">Net Tax</th>
                                        <th>Rs.{{$tax}}</th>
                                        <th colspan="4"></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th colspan="2">{{__('dashboard.Net Total')}}</th>
                                        <th>
                                            Rs.{{$net_total}}</th>
                                        <th colspan="4"></th>
                                    </tr>
                                </table>

                            </div>
                            <div class="col-md-3">
                                {{--@if($order->getUser->is_member)--}}
                                {{--<i class="fa fa-check text-success"> {{__('dashboard.Member')}}</i>--}}
                                {{--@else--}}
                                {{--<i class="fa fa-times text-danger"> {{__('dashboard.Member')}}</i>--}}
                                {{--@endif--}}

                                {{--@include('backend.admin.merchant-master.shipping-detail')--}}

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                {{--<a href="{{route('admin-order-invoice',['id'=>$order->id,'m_id'=>$merchant->id])}}"--}}
                                {{--<a href="#inProcess"--}}
                                {{--style="background:dodgerblue;padding:10px 70px;color:white;text-decoration:none;">--}}
                                {{--Generate Invoice</a>--}}

                                <button type="button" data-toggle="modal"
                                        data-target="#myModal"
                                        style="background:dodgerblue;padding:10px 70px;color:white;text-decoration:none;">
                                    Generate Invoice
                                </button>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="myModal" role="dialog">
                                <div class="modal-dialog" style="width: 80%">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            {{--<h4 class="modal-title"></h4>--}}
                                        </div>
                                        <div class="modal-body" id="print-invoice">
                                            <div class="page-margin">
                                                @include('pdf.invoice')
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                            <button type="button" class="btn btn-default pull-left"
                                                    id="print-modal-content">Print
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--@endforeach--}}
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(function () {

            $('.change-status').on('click', function (e) {
                e.preventDefault();

                $('.select-status').hide();
                $('.td-status').show();

                var form_status = $(this).parent().parent();
                form_status.find('.select-status').each(function () {
                    $(this).show();
                });
                form_status.find('.td-status').each(function () {
                    $(this).hide();
                });
            });


            $('#print-modal-content').on('click', function (e) {
                e.preventDefault();
                printDiv();
            });

            function printDiv() {
                var divToPrint = $('#print-invoice');

                var bootstrap_link = "{!! asset('frontend/assets/css/bootstrap.min.css') !!}";
                var print_css_link = "{!! asset('frontend/assets/css/print.css') !!}";
                var printable = "<!doctype html>" +
                    "<html lang='en'>" +
                    "<head>" +
                    "    <meta charset='UTF-8'>" +
                    "    <title>Invoice</title>" +
                    "    <link rel='stylesheet' href='" + bootstrap_link + "' type='text/css'>" +
                    "    <link rel='stylesheet' href='" + print_css_link + "' type='text/css'>" +
                    "</head>" +
                    "<body onload='window.print()'>" + divToPrint.html() + "</body></html>";

                var newWin = window.open('', 'Print-Window');
                newWin.document.open();
                newWin.document.write(printable);
                newWin.document.close();
                setTimeout(function () {
                    newWin.close();
                }, 10);
            }
        });
    </script>
@endsection
@section('stylesheets')
    <link rel="stylesheet" href="{{asset('frontend/assets/css/print.css')}}" type="text/css">
    <style>
        .border {
            border: 1px solid lightgrey;
        }

        .select-status {
            display: none;
        }
    </style>
@stop
