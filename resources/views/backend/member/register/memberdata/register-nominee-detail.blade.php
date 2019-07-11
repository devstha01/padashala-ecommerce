<h3 class="form-section">{{__('dashboard.Nominee Detail')}}</h3>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.Name')}}</label>
            {{ Form::text('nominee_name', null , ['class'=> 'form-control', 'placeholder' => 'Nominee Detail', 'id'=>"nominee_name"]) }}
            <span class="error-message"></span>
            @if ($errors->has('nominee_name'))
                <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('nominee_name') }}</strong>
						</span>
            @endif
        </div>
    </div>
    <!--/span-->
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.Contact Number')}}</label>
            {{ Form::text('nominee_contact_number',null,['class'=>'form-control','id'=>'nominee_contact_number','placeholder'=>'Contact Number']) }}
            <span class="error-message"></span>
            @if ($errors->has('nominee_contact_number'))
                <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('nominee_contact_number') }}</strong>
						</span>
            @endif
        </div>
    </div>
</div>




<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.ID Type')}}</label>
            {{ Form::select('nominee_identification_type_id', $identificationType ,null,['class'=>'form-control','id'=>'nominee_identification_type_id']) }}
            <span class="error-message"></span>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.ID/Passport Number')}}</label>
            {{ Form::text('nominee_identification_number',null,['class'=>'form-control','id'=>'nominee_identification_number','placeholder'=> 'ID/Passport Number']) }}
            <span class="error-message"></span>
            @if ($errors->has('nominee_identification_number'))
                <span class="has-error help-block" style="color:red">
                    <strong>{{ $errors->first('nominee_identification_number') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">{{__('dashboard.Relationship')}}</label>
            {{ Form::text('relationship',null,['class'=>'form-control','id'=>'relationship','placeholder'=>'Relationship']) }}
            <span class="error-message"></span>
            @if ($errors->has('relationship'))
                <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('relationship') }}</strong>
						</span>
            @endif
        </div>
    </div>
</div>