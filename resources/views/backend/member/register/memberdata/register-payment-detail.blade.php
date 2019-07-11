<h3 class="form-section">{{__('dashboard.Payments Details')}}</h3>
<div class="row">
    <div class="col-md-6">
        <div class="form-group required">
            <label class="control-label">{{__('dashboard.Packages')}}</label>
            {{ Form::select('package_id', $packages ,null, ['class'=> 'form-control', 'placeholder' => 'Choose Package', 'id'=>"packageId",'required']) }}
            <span class="error-message"></span>
            @if ($errors->has('package_id'))
                <span class="has-error help-block" style="color:red">
                    <strong>{{ $errors->first('package_id') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>




