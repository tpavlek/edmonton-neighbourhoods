<div>
    Pets licensed since last {{ \Carbon\Carbon::now()->subYear()->addMonth()->format("F") }}
    <br />
    @if ($pets->has('Cat'))
        <span class="cat strong"><i class="icon-cat"></i> {{ $pets->get('Cat')->count }} cat(s)</span><br/>
    @endif
    @if ($pets->has('Dog'))
        <span class="dog strong"><i class="icon-dog"></i> &nbsp; {{ $pets->get('Dog')->count }} dog(s)</span><br />
    @endif
    @if ($pets->has('Pigeon'))
        <span class="pigeon strong"><i class="icon-pigeon"></i> {{ $pets->get('Pigeon')->count }} pigeons(s)</span><br />
    @endif
</div>
