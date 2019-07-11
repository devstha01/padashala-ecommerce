@extends('backend.layouts.master')

@section('content')
    <main class="main">
        <div class="container">
            <br>
            <h2>{{__('front.Profile')}}</h2>
            <br>
            <div class="row">
                @include('backend.includes.flash')

                <div class="col-md-8">

                    <div class="card-back p-3" style="min-height:400px">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>{{$user->name}} {{ $user->surname }}</h3>
                            </div>
                            <div class="col-sm-6">
                                <h3>
                                    <i class="fa mb-1 package-{{$user->getAsset->getPackage->name}}">{{__('front.Package')}}
                                        : {{$user->getAsset->getPackage->name}}</i>

                                </h3>
                            </div>
                        </div>
                        <div style="min-width: 100%;border-top:1px solid grey"></div>
                        <div class="row">
                            <br>
                            <div class="col-md-6">
                                <br><i class="fa fa-user mb-1"><i class="fa fa-key ml-1"></i>
                                </i><span>{{$user->user_name}}</span>
                                <br><i class="fa fa-envelope mb-1"> </i><span>{{$user->email}}</span>
                                <br><i class="fa fa-phone mb-1"> </i><span>{{$user->contact_number??' -'}}</span>
                                <br><i class="fa fa-birthday-cake mb-1"> </i><span>{{$user->dob??' -'}}</span>
                                <br><i class="fa fa-map-marker mb-1"></i><span>{{$user->address ??' - '}}
                                    , {{$user->city??' - '}}
                                    , {{$user->getCountry->name}}</span>
                                <br><i class="fa fa-id-card mb-1"> </i><span>{{$user->identification_type }}
                                    : {{$user->identification_number??' - '}}</span>
                                <br><i class="fa fa-id-card-alt mb-1"></i><span> {{__('front.Gender')}}
                                    : {{$user->gender }}
                                    | {{__('front.Marital Status')}}
                                    :
                                    @if($user->marital_status === 'yes')
                                        {{__('dashboard.Married')}}
                                    @else
                                        {{__('dashboard.Single')}}
                                    @endif
                                </span>
                                {{--<br>@if($user->is_member)--}}
                                {{--<i class="fa fa-check text-success"> {{__('front.Member')}}</i>--}}
                                {{--@else--}}
                                {{--<i class="fa fa-times text-danger"> {{__('front.Member')}}</i>--}}
                                {{--@endif--}}
                                <br>
                                <br> <i class="fa fa-calendar mb-1"></i><span class="mb-1"> {{__('front.Joining Date')}}
                                    : {{$user->joining_date}}</span>

                            </div>
                            <div class="col-md-6">
                                <div>
                                    <br><h5>{{__('front.Nominee Detail')}}</h5>
                                    <span>{{__('front.Name')}}: {{$user->getNominee->nominee_name}}</span>
                                    <br> <span class="mb-1">{{$user->getNominee->identification_type}}
                                        : {{$user->getNominee->identification_number}}</span>
                                    <br> <span class="mb-1">{{__('front.Relation')}}
                                        : {{$user->getNominee->relationship}}</span>
                                    <br> <span class="mb-1">{{__('front.Contact')}}
                                        : {{$user->getNominee->contact_number}}</span>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-back p-3 custom-btn-box" style="min-height:400px">
                        <div class="qr-profile-block">
                            <a title="Save Qr Code" href="{{$user->qr_image}}">
                                <div class="row" style="padding: 0 30px">
                                    <div class="col-xs-4"><img src="{{$user->qr_image}}" alt=" "
                                                               style="    max-height: 60px;max-width: 60px;">
                                    </div>
                                    <div class="col-xs-8 mt-3"><h3 style="color: black">{{__('dashboard.My Qr Code')}}</h3></div>
                                </div>
                            </a>
                        </div>
                        <a class="custom-btn btn btn-info m-3" href="{{url('admin/edit-member',$user->id)}}"
                           style="width:90%"><i
                                    class="fa fa-edit"></i> Update Profile</a>

                        <a class="custom-btn btn btn-info m-3" href="{{url('admin/grant-member-wallet',$user->id)}}"
                           style="width:90%"><i
                                    class="fa fa-money"></i> Grant Wallet</a>
                        <a class="custom-btn btn btn-info m-3" href="{{url('admin/retain-member-wallet',$user->id)}}"
                           style="width:90%"><i
                                    class="fa fa-money"></i> Retain Wallet</a>
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
