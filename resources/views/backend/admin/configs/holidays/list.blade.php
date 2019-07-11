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
                                <h1>{{__('dashboard.Holidays')}}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="container">
                            <section>
                                <a href="{{url('admin/config/add-holiday')}}" class="btn btn-primary"> + {{ __('dashboard.Add New')}}</a>
                            </section><!-- /.content -->
                            <br>
                            <br>
                            <section>
                                <div class="row">
                                    @include('fragments.message')
                                    <div class="col-xs-12">
                                        <div class="portlet light box">
                                            <div class="portlet-body box-body">
                                                <div class="table-scrollable table-reponsive">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>S.N</th>
                                                            <th>{{ __('dashboard.Holiday Date')}}</th>
                                                            <th>{{ __('dashboard.Action')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if ($holidays->total() == 0)
                                                            <tr>
                                                                <td colspan="8">{{__('dashboard.NO DATA')}}</td>
                                                            </tr>
                                                        @endif
                                                        @php $i=1 ; @endphp
                                                        @foreach($holidays as $item)
                                                            <tr>
                                                                <td>{{ $i++ }}</td>
                                                                <td>
                                                                    {{ $item->holiday_date }}
                                                                </td>
                                                                <td>
                                                                    <a type="button" class="btn btn-primary pull-left" href="{{ url('admin/config/edit-holiday/'.$item->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                    <a type="button" class="btn btn-danger pull-left" href="{{ url('admin/config/delete-holiday/'.$item->id) }}"
                                                                       onclick="return confirm('Are you sure you want to Delete this Holiday?');"><i class="fa fa-close" aria-hidden="true"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </table>
                                                </div>

                                                <div class="card text-center">
                                                    {{ $holidays->links() }}
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

@stop