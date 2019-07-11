@extends('frontend.layouts.app')

@section('content')
    <main class="main">

        <div class="container">

            @include('fragments.message')
            @include('frontend.home.include.list-section')
        </div><!-- End .container -->

        <div class="mb-6"></div><!-- margin -->
    </main><!-- End .main -->

@endsection

@section('scripts')
    <script>
        $(function () {
            $(".clickable-row").click(function () {
                window.location = $(this).data("href");
            });

            $('#table-confirm').DataTable();
            $('#table-complete').DataTable();
        });
    </script>
    <script src="{{asset('frontend/plugin/DataTables/datatables.js')}}"></script>
@stop

@section('stylesheets')
    <style>
        .clickable-row {
            cursor: pointer;
        }

        button:hover {
            opacity: 0.7;

        }

        .table-overflow {
            max-height: 700px;
            overflow-y: scroll;
            padding-right: 10px;
        }
    </style>
    <link rel="stylesheet" href="{{asset('frontend/plugin/DataTables/datatables.css')}}">
@endsection