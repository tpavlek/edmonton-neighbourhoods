<?php

namespace Depotwarehouse\Neighbourhoods\Model;

class AverageAssessment
{

    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function toString()
    {
        return "$" . number_format($this->value, 2);
    }
}
