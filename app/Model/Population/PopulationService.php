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
     * @return Population
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

    public function forNeighbourhoodYear($neighbourhood, $year = 2014)
    {
        $neighbourhood = strtolower($neighbourhood->name);

        $resources = [
            2009 => '/resource/ja72-scmz.json',
            2012 => '/resource/rz7e-z8gt.json',
            2014 => '/resource/44iw-gmaw.json',
        ];

        $neighbourhoodNameAttribute = [
            2009 => 'neighbourhood',
            2012 => 'neighbourhood_name',
            2014 => 'neighbourhood_name',
            2016 => 'neighbourhood_name'
        ];

        if ($year == 2016) {
            $query = [
                '$select' => "(_0_4 + _5_9 + _10_14 + _15_19 + _20_24 + _25_29 + _30_34 + _35_39 + _40_44 + _45_49 + _50_54 + _55_59 + _60_64 + _65_69 + _70_74 + _75_79 + _80_84 + _85 + no_response) as population",
                '$where' => "lower({$neighbourhoodNameAttribute[$year]}) like '$neighbourhood'",
            ];
            $results = $this->socrataClient->get('/resource/y8bi-vahs.json', $query);

            if (empty($results)) {
                return 0; // neighbourhood did not exist.
            }
            return $results[0]['population'];
        }

        $results = $this->socrataClient->get(
            $resources[$year],
            [
                '$limit' => 5000,
                '$where' => "lower({$neighbourhoodNameAttribute[$year]}) like '$neighbourhood'",
                '$select' => "(sum(male) + sum(female)) as population",
                '$group' => $neighbourhoodNameAttribute[$year]
            ]
        );

        if (empty($results)) {
            return 0; // neighbourhood did not exist
        }

        return $results[0]['population'];
    }


}
