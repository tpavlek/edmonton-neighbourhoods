<?php

namespace Depotwarehouse\Neighbourhoods\Model;

use Illuminate\Support\Collection;

class StructureTypeCollection
{

    /**
     * @var Collection
     */
    private $collection;

    public function __construct(Collection $collection)
    {

        $this->collection = $collection;
    }

    public function toHighChartSeries()
    {
        $result = [
            'series' => [
                [ 'name' => 'Owned', 'data' => $this->collection->pluck('num_owned') ],
                [ 'name' => 'Rented', 'data' => $this->collection->pluck('num_rented') ],
                [ 'name' => 'No Response', 'data' => $this->collection->pluck('num_no_response') ],
            ],
            'categories' => $this->collection->pluck('type')
        ];

        return $result;
    }

    public function toHighChartPie()
    {
        $total = $this->collection->sum(function(StructureType $structureType) {
            return $structureType->total();
        });

        return [
            'series_data' => $this->collection->map(function(StructureType $structureType) use ($total) {
                return [
                    'name' => $structureType->type,
                    'num' => $structureType->total(),
                    'y' => ($structureType->total() / $total) * 100,
                    'drilldown' => $structureType->type
                ];
            })->toArray(),

            'drilldown' => $this->collection->map(function(StructureType $structureType) {
                return [
                    'name' => $structureType->type,
                    'id' => $structureType->type,
                    'data' => [
                        [ 'Rented', ($structureType->num_rented / $structureType->total()) * 100 ],
                        [ 'Owned', ($structureType->num_owned / $structureType->total()) * 100 ],
                        [ 'No Response', ($structureType->num_no_response / $structureType->total()) * 100 ]
                    ]
                ];
            })
        ];
    }

}
