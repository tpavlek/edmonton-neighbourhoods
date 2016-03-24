<?php

namespace Depotwarehouse\Neighbourhoods\Model\Population;

use Depotwarehouse\Neighbourhoods\Model\Population;
use Depotwarehouse\Neighbourhoods\NeighbourhoodRecord;
use socrata\soda\Client;

class PopulationService
{

    /**
     * @var Client
     */
    private $socrataClient;


    public function __construct(Client $socrataClient)
    {
        $this->socrataClient = $socrataClient;
    }

    /**
     * @param NeighbourhoodRecord|int $neighbourhood
     */
    public function dataForNeighbourhood($neighbourhood)
    {
        $number = $neighbourhood;

        if ($neighbourhood instanceof NeighbourhoodRecord) {
            $number = $neighbourhood->id;
        }

        $results = $this->socrataClient->get(
            '/resource/44iw-gmaw.json',
            [
                '$limit' => 5000,
                '$where' => "neighbourhood_number = '$number'",
                '$select' => "sum(male) as num_male, sum(female) as num_female, ward",
                '$group' => 'ward'
            ]
        );

        return Population::fromSocrataResult($results);
    }


}
