<div>
    {{ $neighbourhood->name }} is <br />
    <span class="strong female">{{ $demographic->percentFemale() }} <i class="fa fa-female"></i> Female</span>
    <br />
    and
    <br />
    <span class="strong male">{{ $demographic->percentMale() }} <i class="fa fa-male"></i> Male</span>
</div>
