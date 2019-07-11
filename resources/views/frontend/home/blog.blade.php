@extends('frontend.layouts.app')

@section('content')

    <main class="main">
        {{--<nav aria-label="breadcrumb" class="breadcrumb-nav">--}}
            {{--<div class="container">--}}
                {{--<ol class="breadcrumb">--}}
                    {{--<li class="breadcrumb-item"><a href="{{url('/')}}"><i class="icon-home"></i></a></li>--}}
                    {{--<li class="breadcrumb-item active" aria-current="page">{{__('front.Blog')}}</li>--}}
                {{--</ol>--}}
            {{--</div><!-- End .container -->--}}
        {{--</nav>--}}

        <div class="container">
            <div class="row">

                <div class="col-lg-9">
                    @foreach($blogs as $blog)
                        <article class="entry">
                            <div class="entry-media">
                                <a href="#">
                                    <img src="{{asset('image/blog/'. $blog->image)}}" alt="Post"
                                         style="width:870px;height:340px">
                                </a>
                            </div><!-- End .entry-media -->

                            <div class="entry-body">
                                <div class="entry-date">
                                    <span class="day">{{Carbon\Carbon::parse($blog->date_published)->format('d')}}</span>
                                    <span class="month">{{Carbon\Carbon::parse($blog->date_published)->format('M')}}</span>
                                </div><!-- End .entry-date -->

                                <h2 class="entry-title">
                                    <a href="#">Post Format - Image</a>
                                </h2>

                                <div class="entry-content">
                                    <p>{!!$blog->description!!}</p>

                                    <a href="{{route('single-blog')}}" class="read-more">{{__('front.Read More')}} <i
                                                class="icon-angle-double-right"></i></a>
                                </div><!-- End .entry-content -->

                                <div class="entry-meta">
                                    <span><i class="icon-calendar"></i>{{$blog->date_published}}</span>
                                    <span><i class="icon-user"></i>{{__('front.By')}} <a href="#">{{$blog->author}}</a></span>
                                    {{--<span><i class="icon-folder-open"></i>--}}
                                    {{--<a href="#">Haircuts & hairstyles</a>,--}}
                                    {{--<a href="#">Fashion trends</a>,--}}
                                    {{--<a href="#">Accessories</a>--}}
                                    {{--</span>--}}
                                </div><!-- End .entry-meta -->
                            </div><!-- End .entry-body -->
                        </article><!-- End .entry -->
                    @endforeach

                </div><!-- End .col-lg-9 -->

                <aside class="sidebar col-lg-3">
                    <div class="sidebar-wrapper">
                        <div class="widget widget-search">
                            <form role="search" method="get" class="search-form" action="#">
                                <input type="search" class="form-control" placeholder="Search posts here..." name="s"
                                       required>
                                <button type="submit" class="search-submit" title="Search">
                                    <i class="icon-search"></i>
                                    <span class="sr-only">{{__('front.Search')}}</span>
                                </button>
                            </form>
                        </div><!-- End .widget -->

                        <div class="widget widget-categories">
                            <h4 class="widget-title">{{__('front.Blog Categories')}}</h4>

                            <ul class="list">
                                <li><a href="#">All about clothing</a></li>
                                <li><a href="#">Make-up &amp; beauty</a></li>
                                <li><a href="#">Accessories</a></li>
                                <li><a href="#">Fashion trends</a></li>
                                <li><a href="#">Haircuts &amp; hairstyles</a></li>
                            </ul>
                        </div><!-- End .widget -->

                        <div class="widget">
                            <h4 class="widget-title">{{__('front.Recent Posts')}}</h4>

                            <ul class="simple-entry-list">
                                <li>
                                    <div class="entry-media">
                                        <a href="#">
                                            <img src="{{asset('frontend/assets/images/blog/widget/post-1.jpg')}}"
                                                 alt="Post">
                                        </a>
                                    </div><!-- End .entry-media -->
                                    <div class="entry-info">
                                        <a href="#">Post Format - Video</a>
                                        <div class="entry-meta">
                                            April 08, 2018
                                        </div><!-- End .entry-meta -->
                                    </div><!-- End .entry-info -->
                                </li>

                                <li>
                                    <div class="entry-media">
                                        <a href="#">
                                            <img src="{{asset('frontend/assets/images/blog/widget/post-2.jpg')}}"
                                                 alt="Post">
                                        </a>
                                    </div><!-- End .entry-media -->
                                    <div class="entry-info">
                                        <a href="#">Post Format - Image</a>
                                        <div class="entry-meta">
                                            March 23, 2016
                                        </div><!-- End .entry-meta -->
                                    </div><!-- End .entry-info -->
                                </li>
                            </ul>
                        </div><!-- End .widget -->

                        <div class="widget">
                            <h4 class="widget-title">Tagcloud</h4>

                            <div class="tagcloud">
                                <a href="#">Fashion</a>
                                <a href="#">Shoes</a>
                                <a href="#">Skirts</a>
                                <a href="#">Dresses</a>
                                <a href="#">Bags</a>
                            </div><!-- End .tagcloud -->
                        </div><!-- End .widget -->

                        <div class="widget">
                            <h4 class="widget-title">Archive</h4>

                            <ul class="list">
                                <li><a href="#">April 2018</a></li>
                                <li><a href="#">March 2018</a></li>
                                <li><a href="#">February 2018</a></li>
                            </ul>
                        </div><!-- End .widget -->


                        <div class="widget widget_compare">
                            <h4 class="widget-title">Compare Products</h4>

                            <p>You have no items to compare.</p>
                        </div><!-- End .widget -->
                    </div><!-- End .sidebar-wrapper -->
                </aside><!-- End .col-lg-3 -->
            </div><!-- End .row -->
        </div><!-- End .container -->

        <div class="mb-6"></div><!-- margin -->
    </main>

@endsection