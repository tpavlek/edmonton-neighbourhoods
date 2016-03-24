<?php

namespace Depotwarehouse\Neighbourhoods\Model;

use Illuminate\Support\Collection;

class PetTypeCollection
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
        $collection = new Collection();
        foreach ($results as $result)
        {
            $collection->put($result['pet_type'], new PetType($result['pet_type'], $result['sum_count']));
        }

        return new self($collection);
    }

    public function has(string $animalName)
    {
        return $this->collection->has($animalName);
    }

    public function get(string $animalName)
    {
        return $this->collection->get($animalName);
    }

}
