<div>
    How {{ $neighbourhood->name }} commutes:
    <table style="margin: 0 auto;">
        <tr>
            <td class="count transport car">
                <i class="fa fa-car"></i> {{ $cars['driver'] }}
            </td>
            <td style="padding-left:1em; text-align:left;" class="transport car">
                Drive ({{ $cars['passenger'] }} passengers)
            </td>
        </tr>
        @foreach($transport_mode as $mode => $count)
            <tr>
                <td class="transport" style="text-align: right;">
                    @if ($mode == 'Bicycle')
                        <i class="fa fa-bicycle"></i>
                    @endif
                    @if ($mode == 'Public_Transit')
                        <i class="fa fa-bus"></i>
                    @endif
                    @if ($mode == 'Walk')
                        <i class="fa fa-male"></i>
                    @endif
                    {{ $count }}
                </td>
                <td style="padding-left:1em; text-align:left;">
                    <span class="transport">
                        {{ str_replace('_', ' ', $mode) }}
                    </span>
                </td>
            </tr>
        @endforeach
    </table>
</div>
