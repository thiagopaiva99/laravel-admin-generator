@extends('layouts.app')

@section('content')
    <br>
    <div class="container-fluid">

        <div class="col-md-12">

            {{--<div class="col-md-3">--}}
                {{--<div class="info-box" style="background: #616161; color: #fff;"">--}}
                    {{--<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>--}}

                    {{--<div class="info-box-content">--}}
                        {{--<span class="info-box-text">Inventory</span>--}}
                        {{--<span class="info-box-number">5,200</span>--}}

                        {{--<div class="progress">--}}
                            {{--<div class="progress-bar" style="width: 50%"></div>--}}
                        {{--</div>--}}
                        {{--<span class="progress-description">--}}
                            {{--50% Increase in 30 Days--}}
                        {{--</span>--}}
                    {{--</div>--}}
                    {{--<!-- /.info-box-content -->--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="col-md-3">--}}
                {{--<div class="info-box bg-green">--}}
                    {{--<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>--}}

                    {{--<div class="info-box-content">--}}
                        {{--<span class="info-box-text">Inventory</span>--}}
                        {{--<span class="info-box-number">5,200</span>--}}

                        {{--<div class="progress">--}}
                            {{--<div class="progress-bar" style="width: 50%"></div>--}}
                        {{--</div>--}}
                        {{--<span class="progress-description">--}}
                            {{--50% Increase in 30 Days--}}
                        {{--</span>--}}
                    {{--</div>--}}
                    {{--<!-- /.info-box-content -->--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="col-md-3">--}}
                {{--<div class="info-box bg-green">--}}
                    {{--<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>--}}

                    {{--<div class="info-box-content">--}}
                        {{--<span class="info-box-text">Inventory</span>--}}
                        {{--<span class="info-box-number">5,200</span>--}}

                        {{--<div class="progress">--}}
                            {{--<div class="progress-bar" style="width: 50%"></div>--}}
                        {{--</div>--}}
                        {{--<span class="progress-description">--}}
                            {{--50% Increase in 30 Days--}}
                        {{--</span>--}}
                    {{--</div>--}}
                    {{--<!-- /.info-box-content -->--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="col-md-3">--}}
                {{--<div class="info-box" style="background: #616161; color: #fff;">--}}
                    {{--<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>--}}

                    {{--<div class="info-box-content">--}}
                        {{--<span class="info-box-text">Inventory</span>--}}
                        {{--<span class="info-box-number">5,200</span>--}}

                        {{--<div class="progress">--}}
                            {{--<div class="progress-bar" style="width: 50%"></div>--}}
                        {{--</div>--}}
                        {{--<span class="progress-description">--}}
                            {{--50% Increase in 30 Days--}}
                        {{--</span>--}}
                    {{--</div>--}}
                    {{--<!-- /.info-box-content -->--}}
                {{--</div>--}}
            {{--</div>--}}



        </div>

        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body">
                    <canvas id="canvas" style="width: 98%;"></canvas>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
//        /*=================== Line Chart ===================*/
//        var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
//        var lineChartData = {
//            labels : ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
//            datasets : [
//                {
//                    label: "My First dataset",
//                    fillColor : "rgba(119,202,161,0.6)",
//                    strokeColor : "rgba(220,220,220,1)",
//                    pointColor : "rgba(220,220,220,1)",
//                    pointStrokeColor : "#fff",
//                    pointHighlightFill : "#fff",
//                    pointHighlightStroke : "rgba(220,220,220,1)",
//                    data : [60,80,70,90,40,30,50,60,80,70,90,40,30,50]
//                },
//                {
//                    label: "My Second dataset",
//                    fillColor : "rgba(60,136,180,0.6)",
//                    strokeColor : "rgba(151,187,205,1)",
//                    pointColor : "rgba(151,187,205,1)",
//                    pointStrokeColor : "#fff",
//                    pointHighlightFill : "#fff",
//                    pointHighlightStroke : "rgba(151,187,205,1)",
//                    data : [30,10,50,80,20,0,90,30,10,50,80,20,0,90]
//                }
//            ]
//
//        }
//
//        window.onload = function(){
//            var ctx = document.getElementById("canvas").getContext("2d");
//            window.myLine = new Chart(ctx).Line(lineChartData, {
//                responsive: true
//            });
//        }
    </script>
@endsection
