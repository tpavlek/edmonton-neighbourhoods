<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    @yield('social_meta', '')
    <link rel="stylesheet" href="/css/app.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

</head>
<body>
<div class="body-wrapper">
    @yield('content')
</div>

<footer>
    &copy; <a href="https://tpavlek.me">Troy Pavlek</a>. Data provided by
    <a href="https://data.edmonton.ca">Edmonton Open Data Catalogue</a>
    | Submit a picture of your neighbourhood to <a href="mailto:troy@tpavlek.me">troy@tpavlek.me</a>
</footer>

<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@yield('scripts', '')
</body>
</html>
