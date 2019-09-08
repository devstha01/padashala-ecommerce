@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <div class="page-head">
                        <div class="container">
                            <!-- BEGIN PAGE TITLE -->
                            <div class="page-title">
                                <h1>Dashboard
                                </h1>
                            </div>

                        </div>
                    </div>
                    <div class="container">
                        @include('fragments.message')

                        <div class="portlet light">
                            <div class="row">
                                @foreach($cards as $card)
                                    <div class="col-sm-6 col-lg-3">
                                        <a href="{{$card['url']??'#'}}" style="text-decoration: none">
                                            <div class="card text-center">
                                                <h4>{{$card['name']??''}}</h4>
                                                <b class="lead">{{$card['count']??0}}</b>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            {{--                            @include('backend.merchant.merchant-wallet-card')--}}

                            <div class="row">
                                <div class="col-md-7">
                                    <canvas id="category_chart" class="chart"></canvas>
                                    <canvas id="date_chart" class="chart"></canvas>
                                </div>
                                <div class="col-md-5">
                                    <canvas id="product_chart" class="chart"
                                            height="{{($chart_count['products'] *35)+90}}px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTAINER -->
    </div>
@endsection
@section('stylesheets')
    <style>
        .card {
            margin: 0 5px;
            padding: 0 5px;
            color: #840405;
            border: 2px solid #1f4e79;
            height: 75px;
            white-space: nowrap;
            background: whitesmoke;
        }

        .chart {
            padding: 10px;
            margin: 20px 0;
            background: whitesmoke;
        }
    </style>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js" type="text/javascript"></script>
    <script>
        var category_chart = document.getElementById('category_chart').getContext('2d');
        var category_data = {!! json_encode($byCategory) !!};
        var myChart = new Chart(category_chart, {
            type: 'doughnut',
            data: {
                labels: category_data.name,
                datasets: [
                    {
                        label: 'Price',
                        data: category_data.net_value,
                        borderWidth: 1,
                        backgroundColor: category_data.color
                    },
                    {
                        label: 'Quantity',
                        data: category_data.quantity,
                        borderWidth: 1,
                        backgroundColor: category_data.color
                    }
                ]
            },
            options: {
                legend: {
                    display: true,
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Product Purchase by Category'
                }
            }
        });


        var product_chart = document.getElementById('product_chart').getContext('2d');
        var product_data = {!! json_encode($byProducts) !!};
        var myChart1 = new Chart(product_chart, {
            type: 'horizontalBar',
            data: {
                labels: product_data.name,
                datasets: [
                    {
                        label: 'Quantity',
                        data: product_data.quantity,
                        borderWidth: 1,
                        backgroundColor: product_data.color
                    },
                    // {
                    //     label: 'Price',
                    //     data: product_data.net_value,
                    //     borderWidth: 1,
                    //     backgroundColor: product_data.color
                    // }
                ]
            },
            options: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: 'Product Purchase'
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Products'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Quantity'
                        }
                    }]
                }

            }
        });


        var date_chart = document.getElementById('date_chart').getContext('2d');
        var date_data = {!! json_encode($byDate) !!};
        var myChart2 = new Chart(date_chart, {
            type: 'bar',
            data: {
                labels: date_data.name,
                datasets: [
                    // {
                    //     label: 'Quantity',
                    //     data: date_data.quantity,
                    //     borderWidth: 1,
                    //     backgroundColor: date_data.color
                    // },
                    {
                        label: 'Price',
                        fill: false,
                        data: date_data.net_value,
                        borderWidth: 1,
                        backgroundColor: date_data.color,
                    }
                ]
            },
            options: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: 'Product Purchase by Date'
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Price in Rs.'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }]
                }

            }
        });
    </script>
@endsection