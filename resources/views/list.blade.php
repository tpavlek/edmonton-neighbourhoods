@extends('layout')

@section('title')
    Neighbourhoods in Edmonton
@stop

@section('content')
    <div class="banner bg-edmonton">
        <h1>{{ $neighbourhood->name }}</h1>
        <div class="break"></div>
        <h1>Pop. {{ number_format($neighbourhood->population) }}</h1>
    </div>
    <div class="mainpage">

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
