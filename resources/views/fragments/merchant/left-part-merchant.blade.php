<div class="profile-sidebar">
    <!-- PORTLET MAIN -->
    <div class="portlet light profile-sidebar-portlet ">
        <!-- SIDEBAR USERPIC -->
        <div class="profile-userpic text-center">
            <div class="profile-usertitle-job"> {{$merchant->user_name}} </div>
            @if($merchant->logo !==null)
                <img src="{{asset('image/merchantlogo/'.$merchant->logo)}}" class="img-responsive" alt="_">
            @else
                <img src="{{asset('image/not-available.jpg')}}" class="img-responsive" alt="_">
            @endif
        </div>
        <!-- END SIDEBAR USERPIC -->
        <!-- SIDEBAR USER TITLE -->
        <div class="profile-usertitle">
            <div class="profile-usertitle-name"> {{$merchant->name}} {{$merchant->surname}} </div>
            <div class="fa fa-envelope text-info "> {{$merchant->email}} </div>
            <hr>
            <div class="text-center">

                <a class="btn btn-circle green btn-sm" href="{{route('merchant-edit-merchant-id')}}"
                   style="width: 90px;margin: 0 20px 20px 0"><i
                            class="fa fa-edit"></i>
                    {{__('dashboard.Edit')}}
                </a>

            </div>
        </div>
        <div class="profile-usermenu">
        </div>
        <!-- END MENU -->
    </div>
    <!-- END PORTLET MAIN -->
</div>
<!-- END PROFILE CONTENT -->
