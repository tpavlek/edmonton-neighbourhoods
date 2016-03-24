<?php

namespace Depotwarehouse\Neighbourhoods\Model;

class Population
{

    public $num_male;
    public $num_female;
    public $ward;

    public function __construct(int $male, int $female, string $ward)
    {
        $this->num_male = $male;
        $this->num_female = $female;
        $this->ward = $ward;
    }

    public static function fromSocrataResult(array $results)
    {

        if (!count($results)) {
            return new self(0, 0, "Unknown");
        }

        $result = $results[0];
        return new self($result['num_male'], $result['num_female'], $result['ward']);
    }

    public function total()
    {
        return $this->num_female + $this->num_male;
    }

    public function percentMale()
    {
        return number_format(($this->num_male / $this->total()) * 100) . "%";
    }

    public function percentFemale()
    {
        return number_format(($this->num_female / $this->total()) * 100) . "%";
    }
}
