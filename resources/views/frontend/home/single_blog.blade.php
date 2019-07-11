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
                            <img src="{{asset('image/blog/'. $blog->image)}}" alt="Post" style="width:870px;height:340px">
                        </a>
                    </div><!-- End .entry-media -->

                    <div class="entry-body">
                        <div class="entry-date">
                        <span class="day">{{Carbon\Carbon::parse($blog->date_published)->format('d')}}</span>
                        <span class="month">{{Carbon\Carbon::parse($blog->date_published)->format('M')}}</span>
                        </div><!-- End .entry-date -->

                        <h2 class="entry-title">
                            <a href="#">{{__('front.Post Format - Image')}}</a>
                        </h2>

                        <div class="entry-content">
                        <p>{!!$blog->description!!}</p>

                        <div class="entry-meta">
                        <span><i class="icon-calendar"></i>{{$blog->date_published}}</span>
                        <span><i class="icon-user"></i>By <a href="#">{{$blog->author}}</a></span>
                            <span><i class="icon-folder-open"></i>
                                <a href="#">Haircuts & hairstyles</a>,
                                <a href="#">Fashion trends</a>,
                                <a href="#">Accessories</a>
                            </span>
                        </div><!-- End .entry-meta -->
                    </div><!-- End .entry-body -->
                </article><!-- End .entry -->
                @endforeach
            </div>
        </div>
    </div>

    <div class="mb-6"></div><!-- margin -->
</main>

@endsection