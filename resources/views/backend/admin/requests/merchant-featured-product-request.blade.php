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
                                <h1> {{__('dashboard.Merchant Feature Product Request')}}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="page-content">
                        <div class="container">
                            <section>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="portlet light box">
                                            <div class="portlet-body box-body">
                                                <h3>{{__('dashboard.Featuring Requests')}}</h3>
                                                <div class="table-reponsive table-scrollable">
                                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                                           id="sample_2">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('dashboard.Product Id')}}</th>
                                                            <th>{{__('dashboard.Merchant')}}</th>
                                                            <th>{{__('dashboard.Admin Id')}}</th>
                                                            <th>{{__('dashboard.Feature From')}}</th>
                                                            {{--                                                            <th>{{__('dashboard.Feature Till')}}</th>--}}
                                                            <th>{{__('dashboard.Action')}}</th>
                                                            <th>{{__('dashboard.Status')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach ($featureproduct as $key=>$featpro)

                                                            @if($featpro->flag==0 && $featpro->getProduct->is_featured==0)
                                                                <tr>

                                                                    <td>{{$featpro->getProduct->name}}</td>
                                                                    <td>{{$featpro->getProduct->getBusiness->name}}
                                                                    <td>{{$featpro->admin_id}}</td>
                                                                    <td>{{$featpro->feature_from}}</td>
                                                                    {{--                                                                    <td>{{$featpro->feature_till}}</td>--}}
                                                                    <td>
                                                                        <a class="btn blue"
                                                                           href="{{route('admin-merchant-feature-product-delete',$featpro->id)}}">{{__('dashboard.Remove')}}</a>
                                                                        <a class="btn blue"
                                                                           href="{{route('admin-merchant-featured-product-accept',$featpro->id)}}">{{__('dashboard.Accept')}}</a>
                                                                    </td>
                                                                    <td>
                                                                        @if($featpro->getProduct->is_featured ===1)
                                                                            <i class="fa fa-check text-success">
                                                                                {{__('dashboard.Featured')}}</i>
                                                                        @else
                                                                            <i class="fa fa-times text-danger"> {{__('dashboard.Not Feature')}}</i>
                                                                        @endif
                                                                    </td>

                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12">
                                        <div class="portlet light box">
                                            <div class="portlet-body box-body">
                                                <h3>{{__('dashboard.Actively Featuring Products')}}</h3>
                                                <div class="table-reponsive table-scrollable">
                                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                                           id="sample_1">
                                                        <thead>

                                                        <tr>
                                                            <th>{{__('dashboard.Product Id')}}</th>
                                                            <th>{{__('dashboard.Merchant')}}</th>
                                                            <th>{{__('dashboard.Admin Id')}}</th>
                                                            <th>{{__('dashboard.Feature From')}}</th>
                                                            {{--                                                            <th>{{__('dashboard.Feature Till')}}</th>--}}
                                                            <th>{{__('dashboard.Action')}}</th>
                                                            <th>{{__('dashboard.Status')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($products as $key=>$pro)
                                                            @if($pro->is_featured==1)
                                                                <tr>
                                                                    <td>{{$pro->name}}</td>
                                                                    <td>{{$pro->getBusiness->name}}
                                                                    <td>{{$pro->getFeatureReq->first()->admin_id??' - '}}</td>
                                                                    <td>{{$pro->getFeatureReq->first()->feature_from??' - '}}</td>
                                                                    {{--                                                                    <td>{{$pro->getFeatureReq->first()->feature_till??' - '}}</td>--}}
                                                                    <td>
                                                                        <a class="btn blue"
                                                                           href="{{route('admin-merchant-feature-product-cancel', $pro->id)}}">{{__('dashboard.Stop')}}</a>
                                                                    </td>
                                                                    <td>
                                                                        @if($pro->is_featured ===1)
                                                                            <i class="fa fa-check text-success">
                                                                                {{__('dashboard.Featured')}}</i>
                                                                        @else
                                                                            <i class="fa fa-times text-danger"> {{__('dashboard.Not Feature')}}</i>
                                                                        @endif
                                                                    </td>

                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12">
                                        <div class="portlet light box">
                                            <div class="portlet-body box-body">
                                                <h3>{{__('dashboard.Cancelled List')}}</h3>
                                                <div class="table-reponsive table-scrollable">
                                                    <table class="table table-striped table-bordered table-hover dataTable dtr-inline"
                                                           id="sample_3">

                                                        <thead>

                                                        <tr>
                                                            <th>{{__('dashboard.Product Id')}}</th>
                                                            <th>{{__('dashboard.Merchant')}}</th>
                                                            <th>{{__('dashboard.Admin Id')}}</th>
                                                            <th>{{__('dashboard.Feature From')}}</th>
                                                            {{--                                                            <th>{{__('dashboard.Feature Till')}}</th>--}}
                                                            <th>{{__('dashboard.Status')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($featureproduct as $key=>$featpro)

                                                            @if($featpro->flag==1 && $featpro->getProduct->is_featured==0)
                                                                <tr>
                                                                    <td>{{$featpro->getProduct->name}}</td>
                                                                    <td>{{$featpro->getProduct->getBusiness->name}}
                                                                    <td>{{$featpro->admin_id}}</td>
                                                                    <td>{{$featpro->feature_from}}</td>
                                                                    {{--<td>{{$featpro->feature_till}}</td>--}}
                                                                    <td>
                                                                        @if($featpro->getProduct->is_featured ===1)
                                                                            <i class="fa fa-check text-success">
                                                                                {{__('dashboard.Featured')}}</i>
                                                                        @else
                                                                            <i class="fa fa-times text-danger"> {{__('dashboard.Not Feature')}}</i>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
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
        .dataTables_wrapper .dataTables_filter {
            display: block !important;
        }
    </style>
@stop
