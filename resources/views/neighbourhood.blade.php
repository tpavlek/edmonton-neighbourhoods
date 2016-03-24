@extends('layout')

@section('title')
    All about {{ $neighbourhood->name }}
@stop

@section('content')
    <div class="banner bg-{{strtolower($neighbourhood->name)}}">
        <h1>{{ $neighbourhood->name }}</h1>
        <div class="break"></div>
        <h1>Pop. {{ $neighbourhood->population }}</h1>
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
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            var wrapper = $('.body-wrapper');
            wrapper.on("transitionend", targetScroll);
            wrapper.on("webkitTransitionEnd", targetScroll);

            $(window).scroll(function(e) {
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
                                formatter: function() {
                                    console.log(this);
                                    if (this.y > 0)
                                    {
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

            $.ajax({
                dataType: "html",
                "url": "{{ URL::route('pets', $neighbourhood->slug) }}",
                success: function(data) {
                    $('.pet-content').html(data);
                }
            });

            $.ajax({
                dataType: "html",
                url: "{{ URL::route('genders', $neighbourhood->slug) }}",
                success: function(data) {
                    $('.gender-content').html(data);
                }
            });

            $.ajax({
                dataType: "html",
                url: "{{ URL::route('trees', $neighbourhood->slug) }}",
                success: function(data) {
                    $('.tree-content').html(data);
                }
            });

            $.ajax({
                dataType: "html",
                url: "{{ URL::route('assessment', $neighbourhood->slug) }}",
                success: function(data) {
                    $('.assessment-content').html(data);
                }
            });
        });

        function targetScroll(event)
        {
            if ($(event.target).parents('.body-wrapper').hasClass('shrink')) {
                console.log("scrolling");
                window.scrollTo(0, 51);
            } else {
                console.log("no Scroll");
            }
        }

        function compensateForScroll()
        {
            if ($(document).scrollTop() > 50) {
                if (!$('.body-wrapper').hasClass('shrink')) {

                    if ( ($(document).height() - 500) < $(window).height()) {
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
