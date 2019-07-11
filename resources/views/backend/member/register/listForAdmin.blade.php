
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
                                <h1>{{__('dashboard.Member Lists')}}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="container">
                            <section>
                                @include('backend.member.register.memberdata.member-filter-list')
                            </section><!-- /.content -->

                            <section>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="portlet light box">
                                            <div class="portlet-body box-body">
                                                @include('backend.includes.flash')
                                                <div class="table-scrollable table-reponsive">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>{{__('dashboard.SN')}}</th>
                                                            <th>{{__('dashboard.Login Id')}}</th>
                                                            <th>{{__('dashboard.Id/Passport')}}</th>
                                                            <th>{{__('dashboard.Address')}}</th>
                                                            <th>{{__('dashboard.Email')}}</th>
                                                            <th>{{__('dashboard.Created At')}}</th>
                                                            <th>{{__('dashboard.Action')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if ($members->total() == 0)
                                                            <tr>
                                                                <td colspan="8">{{__('dashboard.NO DATA')}}</td>
                                                            </tr>
                                                        @endif
                                                        @foreach($members as $item)
                                                            <tr>
                                                                <td>{{ $item->id }}</td>
                                                                <td>{{ $item->user_name }}</td>
                                                                <td>
                                                                    {{ $item->identification_number }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->address }}
                                                                </td>
                                                                <td>{{ $item->email }}</td>
                                                                <td>{{ $item->created_at }}</td>
                                                                <td>
                                                                    {{--@auth("admin")--}}
                                                                        {{--<a type="button"--}}
                                                                           {{--class="btn btn-primary ml-action"--}}
                                                                           {{--href="{{ url('member/view-member/'.$item->id) }}"><i--}}
                                                                                    {{--class="fa fa-eye"--}}
                                                                                    {{--aria-hidden="true"></i> View</a>--}}
                                                                    {{--@endauth--}}
                                                                        {{--@auth("admin")--}}
                                                                        {{--<a type="button"--}}
                                                                           {{--class="btn btn-primary ml-action"--}}
                                                                           {{--href="{{ url('admin/edit-member/'.$item->id) }}"><i--}}
                                                                                    {{--class="fa fa-pencil"--}}
                                                                                    {{--aria-hidden="true"></i>Profile</a>--}}
                                                                        {{--@endauth--}}
                                                                        @auth("admin")
                                                                            <a type="button"
                                                                               class="btn btn-primary ml-action"
                                                                               href="{{ url('admin/member-profile/'.$item->id) }}"><i
                                                                                        class="fa fa-pencil"
                                                                                        aria-hidden="true"></i>Profile</a>
                                                                        @endauth
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </table>
                                                </div>

                                                <div class="card text-center">
                                                    {{ $members->links() }}
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
