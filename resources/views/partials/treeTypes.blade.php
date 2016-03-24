<div>
    The most common trees in {{ $neighbourhood->name }}
    <table style="margin: 0 auto;">
        @foreach($treeTypes->mostCommon() as $index => $treeType)
            <tr>
                <td style="text-align:left;">
                    <span class="trees">
                        @foreach (range(0, 2 - $index) as $i)
                            <i class="fa fa-tree"></i>
                        @endforeach
                    </span>
                </td>
                <td style="padding-left:1em; text-align:left;">
                    <span class="trees">
                        {{ $treeType->species }} ({{ $treeType->count }})
                    </span>
                </td>
            </tr>
        @endforeach
    </table>
</div>
