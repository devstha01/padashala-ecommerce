
@extends('backend.layouts.master')
@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">

                    <div class="row">
                        @include('fragments.message')
                        <div class="col-md-12">
                            <br>
                            <div class="portlet light">
                                <div class="portlet-title">
                                    <h4> {{__('dashboard.Holidays')}}</h4>
                                    <a href="{{url('admin/config/holiday-dates')}}" class="btn btn-primary"> << {{__('dashboard.BACK')}}</a>
                                    <br>
                                    <br>
                                </div>
                                <div class="portlet-body form">
                                    <form action="{{route('edit-holiday')}}" method="post"
                                          enctype="multipart/form-data">
                                        <div class="form-body">
                                            {{csrf_field()}}
                                            <input type="hidden" name="holiday_id" value="{{$holiday->id}}">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Holiday Date')}}</label>
                                                        {{ Form::text('holiday_date',$holiday->holiday_date,['class'=>'form-control datepicker','id'=>'holiday_date','placeholder'=>'Holiday Date']) }}
                                                        <span class="error-message"></span>
                                                        @if ($errors->has('holiday_date'))
                                                            <span class="has-error help-block" style="color:red">
							<strong>{{ $errors->first('holiday_date') }}</strong>
						</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>


                                            <button type="submit" class="btn btn-success">{{__('dashboard.UPDATE')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        $('input[name="holiday_date"].datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD',
            },
        });
    </script>
@stop

