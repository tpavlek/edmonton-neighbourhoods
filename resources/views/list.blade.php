@extends('layout')

@section('title')
    Neighbourhoods in Edmonton
@stop

@section('content')
    <div class="banner main-banner" style="background: url('/img/neighbourhood-banner/edmonton.jpg')">
        <div class="content">
            <h1>{{ $neighbourhood->name }}</h1>
            <div class="break"></div>
            <h1>Pop. {{ number_format($neighbourhood->population) }}</h1>
        </div>

        <div class="fade">hi friend</div>
    </div>
    <div class="mainpage">

        <div style="width:100%; text-align:center;" class="whitecard">
            <style>
                select { width: 30em; height: 4em; }
            </style>
            <h1>Search for a Neighbourhood (or browse the list below)</h1>
            <form method="get" action="/neighbourhood">
                <select>
                    @foreach($wards->flatten(1) as $neighbourhood)
                        <option value="{{$neighbourhood->slug}}">{{$neighbourhood->name}}</option>
                    @endforeach
                </select>
            </form>
        </div>


        @foreach($wards as $ward => $neighbourhoods)
            <div class="whitecard" style="text-align: center;">

                <h2>{{$ward}}</h2>

                @foreach ($neighbourhoods as $neighbourhood)


                    <a href="{{ URL::route('neighbourhood.show', $neighbourhood->slug) }}">

                        <div class="neighbourhood-overview">
                            <h4>{{ $neighbourhood->name }}</h4>
                        </div>
                    </a>

                @endforeach

            </div>
        @endforeach
    </div>

@stop

@section('scripts')
    <script type="text/javascript">
        $('select').select2({
            placeholder: "Select a neighbourhood"
        });

        $('select').change(function() {
            window.location =  '/' + $('select').val();
        })
    </script>
@stop
