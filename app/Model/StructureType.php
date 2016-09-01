<?php

namespace Depotwarehouse\Neighbourhoods\Model;

use Illuminate\Support\Collection;

class StructureType
{

    const REQUIRED_PARAMS = [ 'type', 'ward', 'neighbourhood_name', 'num_residents' ];

    public $type;
    public $ward;
    public $neighbourhood_name;
    public $num_residents;

    public function __construct($params)
    {
        \Depotwarehouse\Toolbox\Verification\require_set(
            $params,
            self::REQUIRED_PARAMS
        );

        foreach (array_only($params, self::REQUIRED_PARAMS) as $key => $param) {
            $this->{$key} = (is_numeric($param)) ? (int)$param : $param;
        }
    }

    public static function fromSocrataResult(array $socrataResult)
    {

        $structures = new Collection();

        $constant = [
            'ward' => $socrataResult[0]['ward'],
            'neighbourhood_name' => $socrataResult[0]['neighbourhood_name'],
            'num_no_response' => $socrataResult[0]['no_response'],
        ];

        foreach ([ 'apartment_1_4_stories', 'apartment_5_stories', 'duplex_fourplex', 'hotel_motel', 'institution_collective_residence', 'manufactured_mobile_home', 'row_house', 'rv_tent_other', 'single_detached_house' ] as $key)
        {

            $map = [
                'apartment_1_4_stories' => "1-4 Storey Apartment",
                'apartment_5_stories' => "5+ Storey Apartment",
                'duplex_fourplex' => "Duplex/Fourplex",
                'hotel_motel' => "Hotel/Motel",
                'institution_collective_residence' => "Institution/Collective Residence",
                'manufactured_mobile_home' => "Mobile Home",
                'row_house' => "Row House",
                'rv_tent_other' => "RV/Tent/Other",
                'single_detached_house' => "Single Detached House",
                'no_response' => "No Response"
            ];

            $structure = new self(array_merge([
                'type' => $map[$key],
                'num_residents' => $socrataResult[0][$key]
            ], $constant));

            if (!$structure->any()) {
                continue;
            }

            $structures->push($structure);
        }

        return new StructureTypeCollection($structures);
    }

    /**
     * Are there any of this structure type in the neighbourhood?
     *
     * @return bool
     */
    public function any()
    {
        return $this->total() > 0;
    }

    /**
     * How many total structures are there in the neighbourhood?
     *
     * @return int
     */
    public function total()
    {
        return $this->num_residents;
    }

}
