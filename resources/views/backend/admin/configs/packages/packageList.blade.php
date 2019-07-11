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
                                <h1>Package List</h1>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="container">
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
                                                            <td colspan="11"><h4
                                                                        class="text-warning">Packages ,Chips ,Capital Amount ,Dividend Config</h4>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="11">{{__('dashboard.Total')}} 100%</td>
                                                        </tr>


                                                        <tr>
                                                            <td colspan="12">Packages</td>
                                                        </tr>
                                                        @foreach($packages as $item)
                                                            <tr>
                                                                <td colspan="2">{{ $item->name }}</td>
                                                                <td colspan="2">

                                                                    <span class="shopping-content"
                                                                          data-key="{{ $item->name }}"> {{ $item->amount }}</span>
                                                                </td>
                                                                <td colspan="2">Capital Amount</td>
                                                                <td colspan="2">

                                                                    <span class="shopping-content"
                                                                          data-key="capital_value_{{ $item->name }}"> {{ $item->capital_value }}</span>
                                                                </td>
                                                                <td colspan="2">Dividend</td>
                                                                <td colspan="2">

                                                                    <span class="shopping-content"
                                                                          data-key="dividend_{{ $item->name }}"> {{ $item->dividend }}</span>
                                                                </td>

                                                            </tr>


                                                        @endforeach
                                                        <br>


                                                        <tr>
                                                            <td colspan="12">Chips and Capital</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6">Price Per Chips</td>
                                                            <td colspan="6"> <span class="shopping-content" data-key="price_per_chips"> {{ $chipConfig->price_per_chips }}</span></td>
                                                        </tr>

                                                    </table>
                                                    <span class="alert fade" id="message-shopping"></span>
                                                    <button class="btn blue" id="edit-package"><i class="fa fa-edit"></i>
                                                        {{__('dashboard.Edit')}}
                                                    </button>
                                                    <button class="shopping-btn btn blue fade" id="save-package"><i
                                                                class="fa fa-save"></i> {{__('dashboard.Save')}}
                                                    </button>
                                                    <br>
                                                    <br>
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
    </div>

@stop

@section('stylesheets')
    <style>
        .shopping-row {
            cursor: pointer;
        }

        .shopping-content {
            padding: 2px 10px;
        }

    </style>
    <script src="{{asset('backend/js/admin/shopping-list.js')}}"></script>
@stop