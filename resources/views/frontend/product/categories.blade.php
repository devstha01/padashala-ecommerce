@extends('frontend.layouts.app')
@section('content')
    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
        {{--<div class="container">--}}
        {{--<ol class="breadcrumb">--}}
        {{--<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home"></i></a></li>--}}
        {{--<li class="breadcrumb-item">--}}
        {{--{{__('front.Categories')}}--}}
        {{--</li>--}}
        {{--</ol>--}}
        {{--</div><!-- End .container -->--}}
        {{--</nav>--}}


        <div id="categoryPage">
            {{--<div class="container"><h2 class="carousel-title">{{__('front.Categories')}}</h2></div>--}}
            <div id="stickyBoxWrap">
                <div id="stikyBoxes">
                    <div class="row">
                        @forelse($all_categories as $coun => $category)

                            <div class="col-lg-2 col-md-3 col-sm-4 col-4 categoryBox topCategory text-center"
                                 data-id="{{$category->id}}">
                                <div class="imageDiv">
                                    @if(!empty($category->image))
                                        <img src="{{asset('image/admin/category/'.$category->image)}}" alt="_">
                                    @endif
                                </div>
                                <span>{{$category->name}}</span>
                            </div>
                        @empty
                        @endforelse

                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    @foreach($all_categories as $category)
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <div class="categoryLists">
                                <h4 class="listHeader listCategory-{{$category->id}}">
                                    <a href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">
                                        {{$category->name}}
                                    </a>
                                </h4>
                                <div class="row">
                                    @foreach($category->getSubCategory->where('status',1) as $key=>$subCategory)
                                        @if(count($subCategory->getSubChildCategory->where('status',1)) !=0)
                                            <div class="col-lg-3 col-md-6 col-sm-12">
                                                <h5 class="subHeader">
                                                    <a href="{{route('product-by-category',['type'=>'sub-category','slug'=>$subCategory->slug])}}">
                                                        {{$subCategory->name}}
                                                    </a></h5>
                                                <ul>
                                                    @foreach($subCategory->getSubChildCategory->where('status',1) as $k1=>$subCat)
                                                        @if($k1%2 == 0)
                                                            <li>
                                                                <a href="{{route('product-by-category',['type'=>'sub-child-category','slug'=>$subCat->slug])}}">
                                                                    {{$subCat->name}}</a></li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                                {{--<h5 class="viewMore"><a>View More<i class="fas fa-chevron-down"></i></a></h5>--}}
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-2 secondCol">
                                                <ul>
                                                    @foreach($subCategory->getSubChildCategory->where('status',1) as $k2=>$subCat)
                                                        @if($k2%2 != 0)
                                                            <li>
                                                                <a href="{{route('product-by-category',['type'=>'sub-child-category','slug'=>$subCat->slug])}}">
                                                                    {{$subCat->name}}</a></li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
                <div class="col-md-3 col-sm-0"></div>
            </div>
        </div>

    </main><!-- End .main -->
@endsection

@section('stylesheets')
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
@endsection