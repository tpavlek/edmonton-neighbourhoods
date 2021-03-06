<?php

namespace Depotwarehouse\Neighbourhoods\Jobs;

use Carbon\Carbon;
use Depotwarehouse\Neighbourhoods\Model\Population\PopulationService;
use Depotwarehouse\Neighbourhoods\NeighbourhoodRecord;
use socrata\soda\Client;

class SyncNeighbourhoods
{

    /**
     * @var Client
     */
    private $socrataClient;
    /**
     * @var NeighbourhoodRecord
     */
    private $neighbourhoodRecord;
    /**
     * @var PopulationService
     */
    private $populationService;

    public function __construct(Client $socrataClient, NeighbourhoodRecord $neighbourhoodRecord, PopulationService $populationService)
    {
        $this->socrataClient = $socrataClient;
        $this->neighbourhoodRecord = $neighbourhoodRecord;
        $this->populationService = $populationService;
    }

    public function syncNeighbourhoods()
    {
        $results = $this->socrataClient->get("/resource/y8bi-vahs.json", [ '$select' => 'neighbourhood_name as name, neighbourhood_number', '$limit' => 5000 ]);

        foreach ($results as $result) {
            $neighbourhood = $this->neighbourhoodRecord->updateOrCreate($result['neighbourhood_number'], $result['name']);

            if (Carbon::now()->subDay()->lt($neighbourhood->updated_at)) {
                // We want to update the population data at maximum once every 24 hours.
                //continue;
            }

            foreach ([ 2009, 2012, 2016 ] as $year) {
                $population = $this->populationService->forNeighbourhoodYear($neighbourhood, $year);

                $neighbourhood->{"population_$year"} = $population;
            }
            $population = $this->populationService->dataForNeighbourhood($neighbourhood);

            $neighbourhood->population_2014 = $population->total();
            $neighbourhood->ward = $population->ward;
            $neighbourhood->save();
        }
    }

}
