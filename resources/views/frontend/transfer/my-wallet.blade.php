@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card" style="min-height:300px">
                        <table class="table">
                            <tr>
                                <th colspan="2">{{__('front.My Balance')}} :</th>
                            </tr>
                            <tr>
                                <td><b> <i class="fa fa-money"></i> {{__('front.E cash Wallet')}} :</b></td>
                                <td>${{$user->getWallet->ecash_wallet??'0.00'}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3" style="min-height:300px">
                        <div class="qr-profile-block">
                            <a title="Save Qr Code" href="{{$user->qr_image}}">
                                <div class="row" style="padding: 0 30px">
                                    <div class="col-xs-4"><img src="{{$user->qr_image}}" alt=" "
                                                               class="qr-profile-image">
                                    </div>
                                    <div class="col-xs-8 mt-1"><h3>{{__('dashboard.My Qr Code')}}</h3></div>
                                </div>
                            </a>
                        </div>

                        <a class="btn btn-info m-3" href="{{$user->qr_image}}"> {{__('front.Top Up')}}</a>
{{--                        <a class="btn btn-info m-3" href="{{route('make-transfer')}}"><i--}}
{{--                                    class="fa fa-money"></i> {{__('front.Wallet Transfer')}}</a>--}}
                        <a class="btn btn-info m-3" href="{{route('my-reports')}}"> {{__('dashboard.Reports')}}</a>

                    </div>
                </div>
            </div>
        </div>
        <div class="mb-5"></div><!-- margin -->
    </main>
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('frontend/plugin/DataTables/datatables.css')}}">
    {{--<link href="http://mlm.local/assets/custom/css/global.css" rel="stylesheet" type="text/css"/>--}}
@endsection

@section('scripts')
    <script src="{{asset('frontend/assets/js/payment-form.js')}}"></script>
    <script src="{{asset('frontend/plugin/DataTables/datatables.js')}}"></script>
    <script>
        $(function () {
            $('#sample_2').DataTable();
        })
    </script>

    <script src="{{asset('frontend/custom/ajax-post.js')}}" type="text/javascript"></script>
    <script src="{{asset('frontend/custom/repo.js')}}" type="text/javascript"></script>

@endsection