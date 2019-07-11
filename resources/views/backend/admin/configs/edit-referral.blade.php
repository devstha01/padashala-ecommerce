
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
                                    <h4> {{__('dashboard.REFERRAL BONUS')}}</h4>
                                    <a href="{{url('admin/config/refaral-bonus-lists')}}" class="btn btn-primary"> << {{__('dashboard.BACK')}}</a>
                                    <br>
                                    <br>
                                </div>
                                <div class="portlet-body form">
                                    <form action="{{route('edit-refaral-bonus')}}" method="post"
                                          enctype="multipart/form-data">
                                        <div class="form-body">
                                            {{csrf_field()}}
                                            <input type="hidden" name="refID" value="{{$referal->id}}">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Generation')}}</label>
                                                        <input type="number" name="generation_position"
                                                               class="form-control" value="{{$referal->generation_position}}">
                                                        <span style="color: red">{{$errors->first('generation_position')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">

                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Package')}}</label>
                                                        {{ Form::select('package_id', $packages ,$referal->package_id, ['class'=> 'form-control', 'placeholder' => 'Choose Package', 'id'=>"packageId"]) }}
                                                        <span style="color: red">{{$errors->first('package_id')??''}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">

                                                    <div class="form-group">
                                                        <label class="control-label">{{__('dashboard.Referal Percentage')}}</label>
                                                        <input type="text" name="refaral_percentage"
                                                               class="form-control" value="{{$referal->refaral_percentage}}">
                                                        <span style="color: red">{{$errors->first('refaral_percentage')??''}}</span>
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

