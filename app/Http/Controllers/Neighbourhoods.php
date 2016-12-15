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
        $neighbourhoods = $neighbourhoodRecord->newQuery()->where('population_2016', '>', 0)->orderBy('name')->get();

        $wards = $neighbourhoods->groupBy('ward')->sortBy(function ($value, $key) {
            return substr($key, 5);
        });
        $edmonton = (object)[
            'name' => "Edmonton",
            'population' => $neighbourhoods->sum('population_2016'),
        ];

        $imgPath = '/img/neighbourhood-banner/edmonton.jpg';

        return view('list')
            ->with('wards', $wards)
            ->with('neighbourhood', $edmonton)
            ->with('imgPath', $imgPath);
    }

    public function show(NeighbourhoodRecord $neighbourhood)
    {
        $imgPath = '/img/neighbourhood-banner/'.strtolower($neighbourhood->name).'.jpg';

        if (!file_exists(public_path() . $imgPath)) {
            $imgPath = '/img/neighbourhood-banner/default.jpg';
        }

        return view('neighbourhood')
            ->with('neighbourhood', $neighbourhood)
            ->with('imgPath', $imgPath);
    }

    public function structure_types(NeighbourhoodRecord $neighbourhood)
    {
        $name = strtolower($neighbourhood->name);
        $results = $this->socrataClient->get('/resource/tp3b-2w9z.json', [ '$limit' => 5000, '$where' => "lower(neighbourhood_name) = '$name'" ]);
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

    public function criminal_incidents(NeighbourhoodRecord $neighbourhood)
    {
        $name = strtolower($neighbourhood->name);

        $lastYear = Carbon::now()->subYear()->year;
        $thisYear = Carbon::now()->year;
        $month = Carbon::now()->month;

        $query = [
            '$where' => "lower(neighbourhood_description_occurrence) like '$name' and ((incident_reported_year=$lastYear and incident_reported_month > $month) or (incident_reported_year=$thisYear and incident_reported_month <= $month))",
            '$select' => 'sum(incidents) as num_incidents, ucr_violation_type_group_incident as incident_type',
            '$group' => 'ucr_violation_type_group_incident'
        ];

        $results = (new Client('https://dashboard.edmonton.ca/'))->get('/resource/ms89-7khp.json', $query);

        $incidents = [ ];
        foreach ($results as $result) {
            $incidents[$result['incident_type']] = $result['num_incidents'];
        }

        arsort($incidents);

        return view('partials.criminalIncidents')->with('incidents', $incidents);
    }

    public function transport_mode(NeighbourhoodRecord $neighbourhood)
    {
        $name = strtolower($neighbourhood->name);
        $query = [
            '$where' => "lower(neighbourhood_name) like '$name'",
            '$select' => "car_truck_van_as_driver as driver, car_truck_van_as_passenger as passenger, public_transit as Public_Transit, walk as Walk, bicycle as Bicycle, other as Other"
        ];

        $results = $this->socrataClient->get('/resource/rmdf-pu8i.json', $query);

        $transport_mode = $results[0];
        arsort($transport_mode);

        $cars = [ 'driver' => $transport_mode['driver'], 'passenger' => $transport_mode['passenger'] ];
        unset($transport_mode['driver']);
        unset($transport_mode['passenger']);

        return view('partials.transportMode')->with('transport_mode', $transport_mode)->with('cars', $cars)->with('neighbourhood', $neighbourhood);
    }

    public function populations(NeighbourhoodRecord $neighbourhood)
    {
        $avg = \DB::table('neighbourhoods')
            ->where('population_2016', '>', 0)
            ->where('ward', '=', $neighbourhood->ward)
            ->select([ \DB::raw('round(avg(population_2009)) as pop_2009, round(avg(population_2012)) as pop_2012, round(avg(population_2014)) as pop_2014, round(avg(population_2016)) as pop_2016') ])
            ->first();

        return response()->json([
            'neighbourhood_data' => [ $neighbourhood->population_2009, $neighbourhood->population_2012, $neighbourhood->population_2014, $neighbourhood->population_2016 ],
            'average_data' => [ (int)$avg->pop_2009, (int)$avg->pop_2012, (int)$avg->pop_2014, (int)$avg->pop_2016 ]
        ]);
    }

    public function test()
    {

        $query = [
            '$select' => 'lower(neighbourhood_name) as name'
        ];

        $results = $this->socrataClient->get('/resource/93i6-agam.json', $query);

        $newNames = array_column($results, 'name');

        $results = $this->socrataClient->get('/resource/ykfz-2ebi.json', [ '$select' => 'lower(name) as name' ]);

        $oldNames = array_column($results, 'name');


        dd(collect($oldNames)->diff($newNames));
        if (2016 == 2016) {
            $query = [
                '$select' =>'neighbourhood_name, ( sum(_0_4) + sum(_5_9) + sum(_10_14) + sum(_15_19) + sum(_20_24) + sum(_30_34) + sum(_35_39) + sum(_40_44) + sum(_45_49) + sum(_50_54) + sum(_55_59) + sum(_60_64) + sum(_65_69) + sum(_70_74) + sum(_75_79) + sum(_80_84) + sum(_85) + sum(no_response) ) as population',
                '$where' => "lower(neighbourhood_name) like 'oliver'",
                '$group' => 'neighbourhood_name'
            ];
            $results = $this->socrataClient->get('/resource/y8bi-vahs.json', $query);

            dd($results);
            if (empty($results)) {
                return 0; // neighbourhood did not exist.
            }
            return $results[0]['population'];
        }

        dd($results);
    }

}
