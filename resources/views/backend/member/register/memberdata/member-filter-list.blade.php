<div class="row">
    <div class="col-xs-12">

        <form action="{{ url()->current() }}" method="get">
            <div class="portlet light box">
                <div class="portlet-body box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.First Name')}}</label>
                                <input class="form-control"
                                       placeholder="First Name"
                                       value="{{ request()->input('firstName') }}"
                                       id="firstName" name="firstName" type="text">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Last Name')}}</label>
                                <input class="form-control"
                                       placeholder="Surname"
                                       value="{{ request()->input('surname') }}"
                                       id="surname" name="surname" type="text">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Login Id')}}</label>
                                <input class="form-control"
                                       placeholder="Login ID"
                                       value="{{ request()->input('loginid') }}"
                                       id="loginid" name="loginid" type="text">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Id/Passport')}}</label>
                                <input class="form-control"
                                       placeholder="ID/Passport"
                                       value="{{ request()->input('IDPassport') }}"
                                       id="IDPassport" name="IDPassport" type="text">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.Start Date')}}</label>
                                <input class="form-control"
                                       placeholder="Start Date"
                                       value="{{ request()->input('startdate') }}"
                                       id="startdate" autocomplete="off" name="startdate" type="text">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{__('dashboard.End Date')}}</label>
                                <input class="form-control"
                                       placeholder="End Date"
                                       value="{{ request()->input('enddate') }}"
                                       id="enddate" autocomplete="off" name="enddate" type="text">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button class="btn btn-success" type="submit">{{__('dashboard.Search')}}</button>
                                <a href="{{ url()->current() }}" class="btn btn-success">{{__('dashboard.RESET')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div><!-- /.box -->
        </form>

    </div><!-- /.col -->

</div><!-- /.row -->