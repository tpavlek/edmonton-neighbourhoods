<?php

namespace Depotwarehouse\Neighbourhoods\Model;

class TreeType
{

    /**
     * @var string
     */
    public $species;
    /**
     * @var int
     */
    public $count;

    public function __construct(string $type, int $count)
    {
        $this->species = $type;
        $this->count = $count;
    }

}
