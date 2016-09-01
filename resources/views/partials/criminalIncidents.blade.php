<div>
    In the past year there have been:
    <table style="margin: 0 auto;">
        @foreach($incidents as $type => $count)
            <tr>
                <td class="criminal-incident count">
                    {{ $count }}
                </td>
                <td style="padding-left:1em; text-align:left;">
                    <span class="criminal-incident">
                        {{ $type }}
                    </span>
                </td>
            </tr>
        @endforeach
    </table>
</div>
