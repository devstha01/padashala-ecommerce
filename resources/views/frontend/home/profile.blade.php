@extends('frontend.layouts.app')

@section('content')
    <main class="main">
        <div class="container">
            <br>
            <h2>{{__('front.My Profile')}}</h2>
            <br>
            <div class="row">

                <div class="col-md-8">

                    <div class="card p-3" style="min-height:400px">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>{{$user->name}} {{ $user->surname }}</h3>
                            </div>
                            <div class="col-sm-6">
                                @if(Auth::user()->is_member ===1)
                                    <h3>
                                        <i class="fa mb-1 package-{{$user->getAsset->getPackage->name}}">{{__('front.Package')}}
                                            : {{$user->getAsset->getPackage->name}}</i>

                                    </h3>
                                @endif
                            </div>
                        </div>
                        <div style="min-width: 100%;border-top:1px solid grey"></div>
                        <div class="row">
                            <br>
                            <div class="col-md-6">
                                <br><i class="fa fa-user mb-1"><i class="fa fa-key ml-1"></i> {{$user->user_name}}</i>
                                <br><i class="fa fa-envelope mb-1"> {{$user->email}}</i>
                                <br><i class="fa fa-phone mb-1"> {{$user->contact_number??' -'}}</i>
                                <br><i class="fa fa-birthday-cake mb-1"> {{$user->dob??' -'}}</i>
                                <br><i class="fa fa-map-marker mb-1">{{$user->address ??' - '}}, {{$user->city??' - '}}
                                    , {{$user->getCountry->name}}</i>
                                @if(Auth::user()->is_member ===1)
                                <br><i class="fa fa-id-card mb-1"> {{$user->identification_type }}
                                    : {{$user->identification_number??' - '}}</i>
                                @endif
                                <br><i class="fa fa-id-card-o mb-1"> {{__('front.Gender')}} : {{$user->gender }}
                                    | {{__('front.Marital Status')}}
                                    :
                                    @if($user->marital_status === 'yes')
                                        {{__('dashboard.Married')}}
                                    @else
                                        {{__('dashboard.Single')}}
                                    @endif
                                </i>
                                {{--<br>@if($user->is_member)--}}
                                {{--<i class="fa fa-check text-success"> {{__('front.Member')}}</i>--}}
                                {{--@else--}}
                                {{--<i class="fa fa-times text-danger"> {{__('front.Member')}}</i>--}}
                                {{--@endif--}}

                                <br>
                                <br> <i class="fa fa-calendar mb-1"> {{__('front.Joining Date')}}
                                    : {{$user->joining_date}}</i>

                            </div>
                            <div class="col-md-6">

                                @if(Auth::user()->is_member ===1)
                                    <div>
                                        <br><h5>{{__('front.Nominee Detail')}}</h5>
                                        <i class="fa">{{__('front.Name')}}: {{$user->getNominee->nominee_name}}</i>
                                        <br> <i class="fa">{{$user->getNominee->identification_type}}
                                            : {{$user->getNominee->identification_number}}</i>
                                        <br> <i class="fa">{{__('front.Relation')}}
                                            : {{$user->getNominee->relationship}}</i>
                                        <br> <i class="fa">{{__('front.Contact')}}
                                            : {{$user->getNominee->contact_number}}</i>
                                    </div>
                                @endif
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3" style="min-height:400px">
                        <div class="qr-profile-block">
                            <a title="Save Qr Code" href="{{$user->qr_image}}">
                                <div class="row" style="padding: 0 30px">
                                    <div class="col-xs-4"><img src="{{$user->qr_image}}" alt=" "
                                                               class="qr-profile-image">
                                    </div>
                                    <div class="col-xs-8 mt-1"><h3>{{__('dashboard.My QR Code')}}</h3></div>
                                </div>
                            </a>
                        </div>

                        <a class="btn btn-info m-3" href="{{route('edit-profile',$user->id)}}"><i
                                    class="fa fa-edit"></i> {{__('front.Edit Profile')}}</a>
                        @if(Auth::user()->is_member ===1)
                            <a class="btn btn-info m-3" href="{{route('edit-bank',$user->id)}}"><i
                                        class="fa fa-university"></i> {{__('front.Bank Account Info')}}</a>
                        @endif
                        <a class="btn btn-info m-3" href="{{url('change/password')}}"><i
                                    class="fa fa-key"></i> {{__('front.Change Password')}}</a>

                        <a class="btn btn-info m-3" href="{{route('make-payment')}}"><i
                                    class="fa fa-money"></i> {{__('front.Make Payment')}}</a>

                        @if(!$user->is_member)
                            <a class="btn btn-info m-3" href="{{route('my-wallet')}}"> {{__('dashboard.My Wallet')}}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div><!-- End .container -->

        <div class="mb-6"></div><!-- margin -->
    </main><!-- End .main -->

@endsection

@section('scripts')
    <script>
        $(function () {
            $(".clickable-row").click(function () {
                window.location = $(this).data("href");
            });

            $('#table-confirm').DataTable();
            $('#table-complete').DataTable();
        });
    </script>
    <script src="{{asset('frontend/plugin/DataTables/datatables.js')}}"></script>
@stop

@section('stylesheets')
    <style>
        .clickable-row {
            cursor: pointer;
        }

        button:hover {
            opacity: 0.7;

        }

        .table-overflow {
            max-height: 700px;
            overflow-y: scroll;
            padding-right: 10px;
        }
    </style>
    <link rel="stylesheet" href="{{asset('frontend/plugin/DataTables/datatables.css')}}">
@endsection