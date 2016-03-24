<?php

namespace Depotwarehouse\Neighbourhoods\Model;

class PetType
{

    public $type;
    public $count;

    public function __construct(string $type, int $count)
    {
        $this->type = $type;
        $this->count = $count;
    }

}
