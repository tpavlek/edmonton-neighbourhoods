<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="/css/app.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

</head>
<body>
<div class="body-wrapper">
    @yield('content')
</div>

<footer>
    &copy; <a href="https://tpavlek.me">Troy Pavlek</a> {{ \Carbon\Carbon::now()->year }}. Data provided by
    <a href="https://data.edmonton.ca">Edmonton Open Data Catalogue</a>
</footer>

<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
@yield('scripts', '')
</body>
</html>
