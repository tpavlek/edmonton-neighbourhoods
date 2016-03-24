<?php

namespace Depotwarehouse\Neighbourhoods\Model;

use Illuminate\Support\Collection;

class TreeTypeCollection
{

    /**
     * @var Collection
     */
    private $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public static function fromSocrataResults(array $results)
    {
        return new self(collect($results)->map(function($result) {
            return new TreeType($result['species'], $result['sum_count']);
        }));
    }

    public function mostCommon()
    {
        return $this->collection->sortByDesc(function (TreeType $treeType) {
            return $treeType->count;
        })
            ->values()
            ->slice(0, 3);
    }

}
