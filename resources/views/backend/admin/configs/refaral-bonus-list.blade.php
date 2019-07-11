@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <!-- BEGIN PAGE HEAD-->
                    <div class="page-head">
                        <div class="container">
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h1>{{ __('dashboard.Referral Bonus') }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="container">
                            <section>
                                {{--<a href="{{url('admin/config/add-referral')}}" class="btn btn-primary"> + {{ __('dashboard.Add New')}}</a>--}}
                            </section><!-- /.content -->
                            <br>
                            <br>
                            <section>
                                <div class="row">
                                    @include('fragments.message')
                                    <div class="col-xs-12">
                                        <div class="portlet light box">
                                            <div class="portlet-body box-body">
                                                <div class="table table-reponsive">
                                                    <table class="table table-bordered text-center">
                                                        <tr>
                                                            <td colspan="5"
                                                                style="font-weight: bold">{{ __('dashboard.Bonus Distribution')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__('dashboard.Wallet')}}</td>
                                                            <td>{{__('dashboard.Cash Wallet')}}</td>
                                                            <td>{{__('dashboard.Voucher Wallet')}}</td>
                                                            <td>{{__('dashboard.Chip')}}</td>
                                                            <td>Rpoint </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__('dashboard.Percentage')}}</td>
                                                            <td>
                                                                <span class="edit-referal" data-type="distribution" data-key="ecash_percentage"
                                                                      data-package="1">{{$distribution->ecash_percentage??0}}</span>%
                                                            </td>
                                                            <td>
                                                                <span class="edit-referal" data-type="distribution" data-key="evoucher_percentage"
                                                                      data-package="1">{{$distribution->evoucher_percentage??0}}</span>%
                                                            </td>
                                                            <td>
                                                                <span class="edit-referal" data-type="distribution" data-key="chip_percentage"
                                                                      data-package="1">{{$distribution->chip_percentage??0}}</span>%
                                                            </td>
                                                            <td>
                                                                <span class="edit-referal" data-type="distribution" data-key="rpoint_percentage"
                                                                      data-package="1">{{$distribution->rpoint_percentage??0}}</span>%
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4"><br></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-weight: bold">{{ __('dashboard.Generation')}}</td>
                                                            <td style="font-weight: bold"
                                                                colspan="3">{{ __('dashboard.Referral Percentage')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td>{{__('dashboard.Gold')}}</td>
                                                            <td>{{__('dashboard.Platinum')}}</td>
                                                            <td>{{__('dashboard.Diamond')}}</td>
                                                        </tr>
                                                        @for($i=1;$i<13;$i++)
                                                            <tr>
                                                                <td>{{$i}}</td>
                                                                <td>
                                                                    <span class="edit-referal" data-generation="{{$i}}" data-type="referal"
                                                                          data-package="1">{{$bonus->where('generation_position',$i)->where('package_id',1)->first()->refaral_percentage??0}}</span>%
                                                                </td>
                                                                <td>
                                                                    <span class="edit-referal" data-generation="{{$i}}" data-type="referal"
                                                                          data-package="2">{{$bonus->where('generation_position',$i)->where('package_id',2)->first()->refaral_percentage??0}}</span>%
                                                                </td>
                                                                <td>
                                                                    <span class="edit-referal" data-generation="{{$i}}" data-type="referal"
                                                                          data-package="3">{{$bonus->where('generation_position',$i)->where('package_id',3)->first()->refaral_percentage??0}}</span>%
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                        <tr>
                                                            <td colspan="4">
                                                                <br>
                                                                <span class="alert fade"
                                                                      id="referal-rate-message"></span>

                                                                <button class="btn blue" id="referal-rate"><i
                                                                            class="fa fa-edit"> </i> Edit
                                                                </button>
                                                                <button class="btn blue fade"
                                                                        id="save-referal-rate"><i
                                                                            class="fa fa-save"> </i> Save
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>

                                            </div><!-- /.box-body -->
                                        </div><!-- /.box -->
                                    </div><!-- /.col -->

                                </div><!-- /.row -->
                            </section><!-- /.content -->
                        </div>
                    </div>


                </div>


            </div>
        </div>


    </div>
    <!-- END CONTAINER -->
@endsection

@section('stylesheets')
    <style>
        .edit-referal {
            padding: 2px 10px;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{asset('backend/js/admin/shopping-list.js')}}"></script>
@endsection