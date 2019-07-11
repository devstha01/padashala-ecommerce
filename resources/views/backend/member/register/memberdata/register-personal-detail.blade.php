<h3 class="form-section">{{__('dashboard.Personnel Detail')}}</h3>



<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label">{{__('dashboard.Surname')}}</label>
            {{ Form::text('surname', null , ['class'=> 'form-control', 'placeholder' => 'surname', 'id'=>"surname",'required']) }}
            <span class="error-message"></span>
            @if ($errors->has('surname'))
                <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('surname') }}</strong>
						</span>
            @endif
        </div>
    </div>
    <!--/span-->
    <div class="col-md-6">
        <div class="form-group ">
            <label class="control-label">{{__('dashboard.Gender')}}</label>
            <div class="radio-list">
                <label class="radio-registration">
                    <input type="radio" name="gender" id="male"
                           value="male" > {{__('dashboard.Male')}} </label>
                <label class="radio-registration">
                    <input type="radio" name="gender" id="female"
                           value="female">{{__('dashboard.Female')}}</label>
            </div>
            <span class="error-message"></span>
            @if ($errors->has('gender'))
                <span class="has-error help-block"
                      style="color:red"><strong>{{ $errors->first('gender') }}</strong></span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.Name')}}</label>
            {{ Form::text('name', null , ['class'=> 'form-control', 'placeholder' => 'Name', 'id'=>"name",'required']) }}
            <span class="error-message"></span>
            @if ($errors->has('name'))
                <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('name') }}</strong>
						</span>
            @endif
        </div>
    </div>
    <!--/span-->
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label">{{__('dashboard.Contact Number')}}</label>
            {{ Form::number('contact_number',null,['class'=>'form-control','id'=>'contact_number','placeholder'=>'Contact Number']) }}
            <span class="error-message"></span>
            @if ($errors->has('contact_number'))
                <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('contact_number') }}</strong>
						</span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.Marital Status')}}</label>
            <select name="marital_status" class="form-control"
                    data-placeholder="Choose a Marital Status" tabindex="1">
                <option value="no">{{__('dashboard.Single')}}</option>
                <option value="yes">{{__('dashboard.Married')}}</option>
            </select>
            <span class="error-message"></span>
            @if ($errors->has('marital_status'))
                <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('marital_status') }}</strong>
						</span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.Country')}}</label>


                {{ Form::select('country', $countries ,null,['class'=>'form-control','id'=>'country']) }}


            <span class="error-message"></span>
        </div>
    </div>


</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.ID type')}}</label>
            {{ Form::select('identification_type', $identificationType , null,['class'=>'form-control']) }}
            <span class="error-message"></span>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label">{{__('dashboard.Identification Number')}}</label>
            {{ Form::text('identification_number',null,['class'=>'form-control','id'=>'identification_number','placeholder'=> 'Identification Number','required']) }}
            <span class="error-message"></span>
            @if ($errors->has('identification_number'))
                <span class="has-error help-block" style="color:red">
                    <strong>{{ $errors->first('identification_number') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label">{{__('dashboard.Email')}}</label>
            {{ Form::text('email',null,['class'=>'form-control','id'=>'email','placeholder'=>'Email','required']) }}
            <span class="error-message"></span>
            @if ($errors->has('email'))
                <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
            @endif
        </div>
    </div>
    <!--/span-->
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.Date of Birth')}}</label>
            {{ Form::text('dob',null,['class'=>'form-control datepicker','id'=>'dob','placeholder'=>'Date Of Birth','required']) }}
            <span class="error-message"></span>
            @if ($errors->has('dob'))
                <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('dob') }}</strong>
						</span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.Address')}}</label>
            {{ Form::text('address',null,['class'=>'form-control','id'=>'address','placeholder'=>'Address','required']) }}
            <span class="error-message"></span>
            @if ($errors->has('address'))
                <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('address') }}</strong>
						</span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.Joining Date')}}</label>
            {{ Form::text('joining_date',null,['class'=>'form-control datepicker','id'=>'joining_date','placeholder'=>'Joining Date','required']) }}
            <span class="error-message"></span>
            @if ($errors->has('joining_date'))
                <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('joining_date') }}</strong>
						</span>
            @endif
        </div>
    </div>
    <!--/span-->

</div>
