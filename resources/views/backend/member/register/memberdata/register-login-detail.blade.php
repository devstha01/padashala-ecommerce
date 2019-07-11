<h3 class="form-section">{{__('dashboard.Login Detail')}}</h3>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label">{{__('dashboard.Login Name')}}</label>
            {{ Form::text('user_name', null , ['class'=> 'form-control', 'placeholder' => 'Login ID'  , 'id'=>"user_name"]) }}
            <span class="error-message"></span>
            <button id="chaeckUsername" class="btn btn-success" type="button">
                <i class="fa fa-arrow-left fa-fw"></i> {{__('dashboard.Check')}}
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label">{{__('dashboard.Login Password')}}</label>
            <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>

            <span class="error-message"></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label">{{__('dashboard.Confirm Login Password')}}</label>
            <input type="password" class="form-control" id="confirmPsd" placeholder= "Confirm Password"
                   name="password_confirmation" required >
            <span id="psdConfirm"></span>
            <span class="error-message"></span>
        </div>
    </div>
</div>
<!--/span-->
<div class="row">
    <div class="col-md-6">

        <div class="form-group required">
            <label class="control-label">{{__('dashboard.Transaction Password')}}</label>
            <input type="password" class="form-control" placeholder="Transaction Password" id="tranPasswd"
                   name="transaction_password" required>
            <span class="error-message"></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label">{{__('dashboard.Transaction Password Confirm')}}</label>
            <input type="password" class="form-control" placeholder = "Transactin Password Confirm" id="tranConfirmPsd" name="transaction_password_confirmation" required>
            <span id="trnPsdConfirm"></span>
            <span class="error-message"></span>
        </div>
    </div>
</div>