<?php

namespace Depotwarehouse\Neighbourhoods\Http\Controllers;

use Carbon\Carbon;
use Depotwarehouse\Neighbourhoods\Model\AverageAssessment;
use Depotwarehouse\Neighbourhoods\Model\Population;
use Depotwarehouse\Neighbourhoods\Model\PetTypeCollection;
use Depotwarehouse\Neighbourhoods\Model\Population\PopulationService;
use Depotwarehouse\Neighbourhoods\Model\StructureType;
use Depotwarehouse\Neighbourhoods\Model\TreeTypeCollection;
use Depotwarehouse\Neighbourhoods\NeighbourhoodRecord;
use socrata\soda\Client;

class Neighbourhoods extends Controller
{

    /**
     * @var Client
     */
    private $socrataClient;

    public function __construct(Client $socrataClient)
    {

        $this->socrataClient = $socrataClient;
    }

    public function index(NeighbourhoodRecord $neighbourhoodRecord)
    {
        $neighbourhoods = $neighbourhoodRecord->newQuery()->where('population', '>', 0)->orderBy('name')->get();

        return view('list')->with('neighbourhoods', $neighbourhoods);
    }

    public function show(NeighbourhoodRecord $neighbourhood)
    {
        return view('neighbourhood')
            ->with('neighbourhood', $neighbourhood);
    }

    public function structure_types(NeighbourhoodRecord $neighbourhood)
    {
        $results = $this->socrataClient->get('/resource/6x7z-icju.json', [ '$limit' => 5000, '$where' => "neighbourhood_name = '{$neighbourhood->name}'" ]);
        $structures = StructureType::fromSocrataResult($results);

        return response()->json($structures->toHighChartPie());
    }

    public function genders(NeighbourhoodRecord $neighbourhood, PopulationService $populationService)
    {
        $demographic = $populationService->dataForNeighbourhood($neighbourhood);

        return view('partials.genderDemographics')
            ->with('demographic', $demographic)
            ->with('neighbourhood', $neighbourhood);
    }

    public function trees(NeighbourhoodRecord $neighbourhood)
    {

        $query = [
            '$limit' => 5000,
            '$where' => "neighbourhood_name = '{$neighbourhood->name}'",
            '$select' => "sum(count) as sum_count, species",
            '$group' => "species"
        ];

        $results = $this->socrataClient->get('/resource/93cp-z7sw.json', $query);

        $treeTypes = TreeTypeCollection::fromSocrataResults($results);

        return view('partials.treeTypes')->with('treeTypes', $treeTypes)->with('neighbourhood', $neighbourhood);
    }

    public function pets(NeighbourhoodRecord $neighbourhood)
    {

        $curMonth = Carbon::now()->month;
        $curYear = Carbon::now()->year;
        $lastYear = $curYear - 1;

        $query = [
            '$limit' => 5000,
            '$where' => "neighbourhood = '{$neighbourhood->name}' and ((year = $curYear and month_number <= $curMonth) or (year = $lastYear and month_number > $curMonth))",
            '$select' => "sum(count) as sum_count, pet_type",
            '$group' => "pet_type"
        ];

        $results = $this->socrataClient->get('/resource/5squ-mg4w.json', $query);

        $pets = PetTypeCollection::fromSocrataResults($results);

        return view('partials.petTypes')->with('pets', $pets);
    }

    public function assessment(NeighbourhoodRecord $neighbourhood)
    {
        $name = strtolower($neighbourhood->name);

        $query = [
            '$where' => "lower(neighbourhood) like '$name'",
            '$select' => 'avg(total_asmt) as avg_assessment'
        ];

        $results = $this->socrataClient->get('/resource/3pdp-qp95.json', $query);

        $assessment = new AverageAssessment($results[0]['avg_assessment']);

        return view('partials.propertyAssessment')->with('assessment', $assessment);
    }

}
