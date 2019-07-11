<h3 class="form-section">{{__('dashboard.Placement Detail')}}</h3>


<div class="row">
    <div class="col-md-6">


        @auth("admin")
            @php  $username='';@endphp
            @else
            @php $username=\Auth::user()->user_name; @endphp
        @endauth



        <div class="form-group required">
            <label class="control-label">{{__('dashboard.sponsor')}}</label>
            {{ Form::text('sponser_id' ,$username, ['class'=> 'form-control', 'placeholder' => 'sponsor','required']) }}
            <span class="error-message"></span>
        </div>


    </div>
</div>
<div class="row">
    <div class="col-md-6">


        <div class="form-group required">
            <label class="control-label">{{__('dashboard.SPILL')}}</label>
            {{ Form::text('parent_id' ,request()->input('spill'), ['class'=> 'form-control', 'placeholder' => 'SPILL','id'=>'parentId','required']) }}
            <span class="error-message"></span>
            @if ($errors->has('parent_id'))
                <span class="has-error help-block" style="color:red">
                    <strong>{{ $errors->first('parent_id') }}</strong>
                </span>
            @endif
        </div>


    </div>
</div>


{{--<div class="row">--}}
{{--<div class="col-md-6">--}}
{{--<div class="form-group required">--}}
{{--<label class="control-label">Positions</label>--}}
{{--{{ Form::select('position_id', [] , null,['class'=>'form-control','id'=>'position_id']) }}--}}
{{--<select name="position_id" class="form-control " id="position_id">--}}
{{--<span class="error-message"></span>--}}
{{--@if ($errors->has('position_id'))--}}
{{--<span class="has-error help-block" style="color:red">--}}
{{--<strong>{{ $errors->first('position_id') }}</strong>--}}
{{--</span>--}}
{{--@endif--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
<div class="row">
    <div class="col-md-8">
        <div class="form-group required">
            <label class="control-label">{{__('dashboard.Positions')}}</label>

            <input type="radio" name="position_id" value="1" id="position_id_1" disabled> {{__('dashboard.One')}}

            <input type="radio" name="position_id" value="2" id="position_id_2" disabled> {{__('dashboard.Two')}}

            <input type="radio" name="position_id" value="3" id="position_id_3" disabled> {{__('dashboard.Three')}}

            <input type="radio" name="position_id" value="4" id="position_id_4" disabled> {{__('dashboard.Four')}}

            <input type="radio" name="position_id" value="5" id="position_id_5" disabled> {{__('dashboard.Five')}}

            <input type="radio" name="position_id" value="" id="position_id_6" disabled style="display: none">
            <br>
            <span class="error-message"></span>
            @if ($errors->has('position_id'))
                <span class="has-error help-block" style="color:red">
                    <strong>{{ $errors->first('position_id') }}</strong>
                </span>
            @endif

        </div>

    </div>

</div>

@if(request()->input('spill'))
    <script>
        var value = '<?php echo $_GET['placement']?>';
        $("input[name=position_id][value=" + value + "]").removeAttr('disabled');
        $("input[name=position_id][value=" + value + "]").prop("checked", true);
    </script>
@endif