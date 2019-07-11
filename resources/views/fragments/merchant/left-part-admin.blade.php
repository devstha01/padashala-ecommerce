<div class="profile-sidebar">
    <!-- PORTLET MAIN -->
    <div class="portlet light profile-sidebar-portlet ">
        <!-- SIDEBAR USERPIC -->
        <div class="profile-userpic text-center">
            <div class="profile-usertitle-job"> {{$merchant->user_name}} </div>

            @if($merchant->logo !==null)
                <img src="{{asset('image/merchantlogo/'.$merchant->logo)}}" class="img-responsive"
                     alt="_">
            @else
                <img src="{{asset('image/not-available.jpg')}}" class="img-responsive"
                     alt="_">
            @endif
        </div>
        <!-- END SIDEBAR USERPIC -->
        <!-- SIDEBAR USER TITLE -->
        <div class="profile-usertitle">
            <div class="profile-usertitle-name"> {{$merchant->name}} {{$merchant->surname}} </div>
            <div class="fa fa-envelope text-info "> {{$merchant->email}} </div>
        </div>
        <!-- END SIDEBAR USER TITLE -->
        <!-- SIDEBAR BUTTONS -->
        <div class="profile-userbuttons" style="padding-right: 70px">
            <a class="btn btn-circle green btn-sm" href="{{route('edit-merchant-id',$merchant->id)}}"><i
                        class="fa fa-edit"></i>
                {{__('dashboard.Edit')}}
            </a>
            @if($merchant->status ===1)
                <a title="click to disable" class="btn btn-success btn-sm btn-circle"
                   href="{{route('change-status-merchant-admin',$merchant->id)}}"><i
                            class="fa fa-check"></i> {{__('dashboard.Status')}}</a>
            @else
                <a title="click to enable" class="btn btn-danger btn-sm btn-circle"
                   href="{{route('change-status-merchant-admin',$merchant->id)}}"><i
                            class="fa fa-times"></i> {{__('dashboard.Status')}}</a>
            @endif
        </div>
        <!-- END SIDEBAR BUTTONS -->
        <!-- SIDEBAR MENU -->
    </div>
    <!-- END PORTLET MAIN -->

    <div class="portlet light ">
        <!-- STAT -->
        <div class="row list-separated profile-stat">
            <div class="uppercase profile-stat-text">{{__('dashboard.Joined')}}</div>
            <div class="uppercase profile-stat-title"> {{\Carbon\Carbon::parse($merchant->joining_date)->diffForHumans()}} </div>
            <br>
            <div class="uppercase profile-stat-text">{{$merchant->identification_type}}</div>
            <div class="uppercase profile-stat-title"> {{$merchant->identification_number}} </div>
        </div>
        <!-- END STAT -->
        <div>
            <h4 class="profile-desc-title">{{__('dashboard.About')}} {{$merchant->name}} {{$merchant->surname}}</h4>
            <span class="profile-desc-text">
                {{$merchant->getCountry->name}}
                <br>{{$merchant->address}}
                <br>born on {{\Carbon\Carbon::parse($merchant->dob)->toFormattedDateString()}}
                <br>{{$merchant->gender}} | |
                @if($merchant->marital_status === 'no')
                    {{__('dashboard.Unmarried')}}
                @else
                    {{__('dashboard.Married')}}
                @endif
                <br>{{$merchant->contact_number}}
            </span>
        </div>
    </div>
    <!-- END BEGIN PROFILE SIDEBAR -->

</div>
<!-- END PROFILE CONTENT -->
