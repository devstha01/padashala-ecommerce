@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="page-content-wrapper">
                    <div class="page-head">
                        <div class="container">
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h1>{{__('dashboard.Edit Product')}}
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="portlet light">
                        <div class="portlet-body">
                            @include('fragments.message')
                            <input type="hidden" id="merchant-delivery-option" value="{{$merchant->purchase_option}}">
                            <ul class="nav nav-justified">
                                <li class="">
                                    <a href="{{route('edit-product-merchant',$product->slug)}}"> {{__('dashboard.General')}} </a>
                                </li>
                                <li class="">
                                    <a href="{{route('variant-edit-product-merchant',$product->slug)}}"> {{__('dashboard.Product Options')}} </a>
                                </li>
                                <li class="active" style="border-top: 1px solid red">
                                    <a href="{{route('specs-edit-product-merchant',$product->slug)}}">
                                        Specifications </a>
                                </li>
                                <li class="">
                                    <a href="{{route('image-edit-product-merchant',$product->slug)}}"> {{__('dashboard.Images')}} </a>
                                </li>
                            </ul>
                            <div>
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width: 20%">Name</th>
                                        <th style="width: 60%">Detail</th>
                                        <th colspan="2">Action</th>
                                    </tr>
                                    @forelse($product->specifications as $specification)
                                        <tr>
                                            <form action="{{route('update-specs-product',$specification)}}" method="post">
                                                {{csrf_field()}}
                                            <td>
                                                <input type="text" maxlength="191" name="name" value="{{$specification->name}}" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="detail" value="{{$specification->detail}}" class="form-control">
                                            </td>
                                            <td>
                                                <button class="btn blue">Update</button>
                                            </td>
                                            </form>
                                            <td>
                                                <form action="{{route('delete-spec-product',$specification->id)}}" method="post">
                                                    {{csrf_field()}}
                                                    <button class="btn red">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    <tr>
                                        <form action="{{route('add-specs-product',$product->id)}}" method="post">
                                            {{csrf_field()}}
                                            <td><input type="text" class="form-control" maxlength="191" name="name">
                                            </td>
                                            <td><input type="text" class="form-control" name="detail"></td>
                                            <td>
                                                <button type="submit" class="btn blue">Add</button>
                                            </td>
                                        </form>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{URL::asset('backend/js/merchant/create-product-merchant.js')}}"
            type="text/javascript"></script>
@stop
@section('stylesheets')
    <style>
        td {
            word-break: break-all;
        }
    </style>
@endsection