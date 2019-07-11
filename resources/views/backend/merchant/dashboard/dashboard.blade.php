@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <div class="container">
                        <br>
                        <br>
                        @include('backend.merchant.merchant-wallet-card')
                    </div>
                </div>
            </div>


        </div>
        <!-- END CONTAINER -->
    </div>
@endsection
