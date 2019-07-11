@extends('frontend.layouts.app')

<style>
    #aboutPage {
    min-height:320px
    }
    #aboutPage #right {
        background: #f1f1f1;
    }

    #aboutPage .customContainer {
        width: 85%;
        text-align: justify;
        margin: auto;
        padding: 8% 0;
    }

    #aboutPage .subtitle {
        color: #08c;
    }
</style>
@section('content')
    <main class="main">
        <div class="container text-right">
            <h3> {{__('front.About Us')}}</h3>
        </div>
        <div class="container">
            <div id="aboutPage">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="customContainer">
                            <h2 class="subtitle">THE COMPANY</h2>
                            <img src="{{asset('image/gghl-logo.png')}}" alt=" " style="height: 70px">
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12" id="right">
                        <div class="customContainer">
                            <h2 class="subtitle">THE MISSION</h2>
                            <p class="lead">
                                “ Our mission is to empower the people by leveraging the power of shopping.
                                "</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection