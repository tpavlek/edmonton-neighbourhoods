@extends('layout')

@section('title')
    All about {{ $neighbourhood->name }} in Edmonton
@stop

@section('social_meta')
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@troypavlek" />

    <meta name="twitter:title" content="@yield('title')" />
    <meta property="og:title" content="@yield('title')" />

    <meta name="twitter:description" content="Get quick information about a neighbourhood using Edmonton's Open Data. Built by Troy Pavlek" />
    <meta property="og:description" content="Get quick information about a neighbourhood using Edmonton's Open Data. Built by Troy Pavlek" />

    <meta name="twitter:image" content="{{URL::to('/') . $imgPath}}" />
    <meta property="og:image" content="{{URL::to('/') . $imgPath}}" />


    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:type" content="website" />
@stop

@section('content')
    <div class="banner" style="background: url('{{$imgPath}}')">
        <a href="{{ URL::route('list') }}"><h1>{{ $neighbourhood->name }}</h1></a>
        <div class="break"></div>
        <h1>Pop. {{ number_format($neighbourhood->population) }}</h1>
        <div class="break"></div>
        <h1>{{ $neighbourhood->ward }}</h1>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="col-md-5">
                <div class="gender-content data-panel">
                    <h2>Genders</h2>
                    <i class="fa fa-3x fa-refresh fa-spin"></i>
                </div>
            </div>
            <div class="col-md-7" style="text-align: center;">
                <h2><i class="fa fa-home"></i> Where people live</h2>
                <div id="structure_types">
                    <i class="fa fa-3x fa-refresh fa-spin"></i>
                </div>
            </div>

            <div class="col-md-4">
                <div class="tree-content data-panel">
                    <i class="fa fa-3x fa-refresh fa-spin"></i>
                </div>
            </div>

            <div class="col-md-4">
                <div class="pet-content data-panel">
                    <h2>Licensed Pets</h2>
                    <i class="fa fa-3x fa-refresh fa-spin"></i>
                </div>
            </div>

            <div class="col-md-4">
                <div class="assessment-content data-panel">
                    <h2>Assessed Property Value</h2>
                    <i class="fa fa-3x fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>
        <br/>
        <div class="container-fluid">

            <div class="col-md-6">
                <div class="transport-content data-panel">
                    <h2>Transportation Mode</h2>
                    <i class="fa fa-3x fa-refresh fa-spin"></i>
                </div>
            </div>

            <div class="col-md-6">
                <div class="criminal-content data-panel">
                    <h2>Criminal Incidents</h2>
                    <i class="fa fa-3x fa-refresh fa-spin"></i>
                </div>
            </div>

            <div class="col-md-12" style="text-align: center;">
                <h2><i class="fa fa-line-chart"></i> Population Change</h2>
                <div id="populations">
                    <i class="fa fa-3x fa-refresh fa-spin"></i>
                </div>
            </div>


        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            var wrapper = $('.body-wrapper');
            wrapper.on("transitionend", targetScroll);
            wrapper.on("webkitTransitionEnd", targetScroll);

            $(window).scroll(function (e) {
                compensateForScroll();
            });

            compensateForScroll();

            $.getJSON('{{ URL::route('structure_types', $neighbourhood->slug) }}', function (data) {
                var chart = $('#structure_types').highcharts({
                    chart: {
                        type: 'pie'
                    },
                    title: '',
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true,
                                formatter: function () {
                                    if (this.y > 0) {
                                        return this.key + ": " + this.y.toFixed(2) + "%";
                                    }
                                    return null;
                                }
                            }
                        }
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.num}</b> ({point.y:.2f}% of total)<br/>'
                    },

                    series: [{
                        name: 'Structure Type',
                        colorByPoint: true,
                        data: data.series_data
                    }],
                    drilldown: {
                        series: data.drilldown
                    }
                });
            });

            $.getJSON('{{ URL::route('populations', $neighbourhood->slug) }}', function (data) {

                console.log(data);
                $('#populations').highcharts({
                    title: {
                        text: ''
                    },
                    xAxis: {
                        categories: ['2009', '2012', '2014', '2016']
                    },
                    yAxis: {
                        title: {
                            text: 'Population'
                        }
                    },
                    tooltip: {
                        valueSuffix: ' People'
                    },
                    series: [
                        {
                            name: 'Neighbourhood',
                            data: data.neighbourhood_data
                        },
                        {
                            name: 'Average in Ward',
                            data: data.average_data
                        }
                    ]
                });

            });


            $.ajax({
                dataType: "html",
                "url": "{{ URL::route('pets', $neighbourhood->slug) }}",
                success: function (data) {
                    $('.pet-content').html(data);
                }
            });

            $.ajax({
                dataType: "html",
                url: "{{ URL::route('genders', $neighbourhood->slug) }}",
                success: function (data) {
                    $('.gender-content').html(data);
                }
            });

            $.ajax({
                dataType: "html",
                url: "{{ URL::route('trees', $neighbourhood->slug) }}",
                success: function (data) {
                    $('.tree-content').html(data);
                }
            });

            $.ajax({
                dataType: "html",
                url: "{{ URL::route('assessment', $neighbourhood->slug) }}",
                success: function (data) {
                    $('.assessment-content').html(data);
                }
            });

            $.ajax({
                dataType: "html",
                url: "{{ URL::route('criminal_incidents', $neighbourhood->slug) }}",
                success: function (data) {
                    $('.criminal-content').html(data);
                }
            });

            $.ajax({
                dataType: "html",
                url: "{{ URL::route('transport_mode', $neighbourhood->slug) }}",
                success: function (data) {
                    $('.transport-content').html(data);
                }
            });
        })
        ;

        function targetScroll(event) {
            if ($(event.target).parents('.body-wrapper').hasClass('shrink')) {
                window.scrollTo(0, 51);
            }
        }

        function compensateForScroll() {
            if ($(document).scrollTop() > 50) {
                if (!$('.body-wrapper').hasClass('shrink')) {

                    if (($(document).height() - 500) < $(window).height()) {
                        return; // We don't want to shrink if it's going to cause the page to be less than the client height.
                    }
                    $('.body-wrapper').addClass('shrink');

                }
            } else {
                $('.body-wrapper').removeClass('shrink');
            }
        }
    </script>
@stop
