@extends('backend.layouts.master')
@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    <div class="page-title">
                        <h3>{{__('dashboard.Order Detail')}} </h3>
                    </div>

                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="{{url('admin/dashboard')}}">{{__('dashboard.Home')}}</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <a href="{{route('merchant-list-admin')}}">{{__('dashboard.Merchants')}}</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li><a title="detail"
                               href="{{route('merchant-product-id',$merchant->id)}}">
                                <span>{{$merchant->name??''}} {{$merchant->surname??''}}</span>
                            </a>
                        </li>
                    </ul>

                    <div class="portlet light">
                        <div class="row">
                            <div class="col-md-9">
                                <h4> {{__('dashboard.Invoice')}} # {{$orderItem->first()->invoice}}
                                    <i class="pull-right"> {{__('dashboard.Order Date')}} :{{$order->order_date}}</i>
                                </h4>
                                <hr>
                                <h4>{{__('dashboard.Payment Method')}} <h4 class="badge badge-success">{{$method}}</h4>
                                </h4>
                                <table class="table table-hover">
                                    <tr>
                                        <th colspan="2">{{__('dashboard.Product')}}</th>
                                        <th>{{__('dashboard.Price')}}</th>
                                        <th>{{__('dashboard.Qty')}}</th>
                                        <th>Tax</th>
                                        <th>{{__('dashboard.Net Price')}}</th>
                                        <th>{{__('dashboard.Status')}}</th>
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
                                            <td>
                                                @if($item->getOrderStatus->key =='process')
                                                    <i class="badge badge-warning">{{$item->getOrderStatus->name}}</i>
                                                @elseif($item->getOrderStatus->key =='deliver')
                                                    <i class="badge badge-success">{{$item->getOrderStatus->name}}</i>
                                                @else
                                                    <i class="badge badge-danger">{{$item->getOrderStatus->name}}</i>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->getOrderStatus->key !='deliver')
                                                    <form action="{{route('admin-item-status-change',$item->id)}}"
                                                          method="post">
                                                        {{csrf_field()}}
                                                        <span style="color: red">{{$errors->first('action')??''}}</span>
                                                        <select name="action" class="select-action form-control">
                                                            <option value="">{{__('dashboard.-->Change Status<--')}}</option>
                                                            <option value="dispatch">{{__('dashboard.Dispatched')}}</option>
                                                            {{--                                                            <option value="hold">{{__('dashboard.On hold')}}</option>--}}
                                                            {{--                                                            <option value="stock">{{__('dashboard.Out of Stock')}}</option>--}}
                                                            <option value="deliver">{{__('dashboard.Delivered')}}</option>
                                                        </select>
                                                        <input type="submit" hidden>
                                                    </form>
                                                @else
                                                    <button class="btn blue" disabled><i class="fa fa-check-circle"></i>
                                                        {{__('dashboard.Delivered')}}
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="3"></th>
                                        <th colspan="2">{{__('dashboard.Total')}}</th>
                                        <th>Rs.{{$total}}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                    {{--<tr>--}}
                                    {{--<td colspan="2"></td>--}}
                                    {{--<td colspan="2">Tax</td>--}}
                                    {{--<td>Rs.{{$tax}}</td>--}}
                                    {{--<td colspan="2"></td>--}}
                                    {{--</tr>--}}
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="2">Net Tax</td>
                                        <td>Rs.{{$tax}}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th colspan="2">{{__('dashboard.Net Total')}}</th>
                                        <th>
                                            Rs.{{$net_total}}</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </table>

                            </div>
                            <div class="col-md-3">
                                <h4>{{__('dashboard.Customer')}}</h4>
                                <hr>
                                <img class="rounded-circle" src="{{asset('image/merchants/merchant.jpg')}}" alt="image"
                                     style="max-width:120px;max-height:120px">
                                <br>
                                <h4 class="text-right">{{$order->getUser->name}} {{ $order->getUser->surname }}</h4>
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

            $('.select-action').on('change', function () {
                var conf = confirm('Confirm ?');
                if (conf === true) {
                    $(this).closest('form').find("input[type='submit']").click();
                }
            });

            $('#shipping-view-button').on('click', function () {
                $('#shipping-view').addClass('fade');
                $('#shipping-edit-form').removeClass('fade');
            });


            $(window).load(function () {
                setTimeout(function () {
                    $('.shipp-message').fadeOut()
                }, 10000);
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
        lead {
            font-size: 18px;
        }

        .border {
            border: 1px solid lightgrey;
        }
    </style>
@stop
