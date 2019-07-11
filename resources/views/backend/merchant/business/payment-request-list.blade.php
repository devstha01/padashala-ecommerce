@extends('backend.layouts.master')

@section('content')

    <style>
        .table.overview tr {
            border: 0;
        }

        .table.overview tr td {
            vertical-align: middle;
            border: 0;
        }

        .table.overview {
            margin-bottom: 0;
        }

        .table.overview > tbody > tr > td, .table.overview > tbody > tr > th, .table.overview > tfoot > tr > td, .table.overview > tfoot > tr > th, .table.overview > thead > tr > td, .table.overview > thead > tr > th {
            padding: 0;
        }
    </style>

    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <!-- BEGIN PAGE HEAD-->
                    <div class="page-head">
                        <div class="container">
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h1>{{ __('dashboard.Wallet Transfer Request') }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        @include('backend.merchant.merchant-wallet-card')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light">
                                    <h3>{{__('dashboard.Sent Payment Requests')}}</h3>
                                    <table class="table table-hover">
                                        <tr>
                                            <th>{{__('dashboard.Username')}}</th>
                                            <th>{{__('dashboard.Wallet')}}</th>
                                            <th>{{__('dashboard.Amount')}}</th>
                                            <th>{{__('dashboard.Action')}}</th>
                                        </tr>
                                        @forelse($request as $key=>$req)
                                            <tr>
                                                <td>{{$req->getFromMember->user_name}}</td>
                                                <td>{{$req->getWallet->detail}}</td>
                                                <td>{{$req->amount}}</td>
                                                <td>
                                                    {{ Form::open([
                                                'url' => 'merchant/payment/cancel-request/'.$req->id,
                                                'class' => 'horizontal-form ajax-post-merchant',
                                                'method'=> 'POST'
                                                ])   }}
                                                    <button type="submit" class="btn blue"><i class="fa fa-times"></i>
                                                        {{__('dashboard.Cancel')}}
                                                    </button>
                                                    {{Form::close()}}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">{{__('dashboard.No request available')}}.</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>
        <!-- END CONTAINER -->
    </div>

    <script src="{{ URL::asset('assets/custom/js/wallet-transfer.js') }}" type="text/javascript"></script>

@stop

@section('stylesheets')
    <style>
        .qr-scan {
            position: relative;
        }
    </style>
@endsection
