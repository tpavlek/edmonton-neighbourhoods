<?php

namespace Depotwarehouse\Neighbourhoods\Model;

use Illuminate\Support\Collection;

class StructureType
{

    const REQUIRED_PARAMS = [ 'type', 'ward', 'neighbourhood_name', 'num_rented', 'num_owned', 'num_no_response' ];

    public $type;
    public $ward;
    public $neighbourhood_name;
    public $num_rented;
    public $num_owned;
    public $num_no_response;

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

        foreach ($socrataResult as $result)
        {
            $structure = new self([
                'type' => $result['structure_type'],
                'ward' => $result['ward'],
                'neighbourhood_name' => $result['neighbourhood_name'],
                'num_rented' => $result['rented'],
                'num_owned' => $result['owned'],
                'num_no_response' => $result['no_response']
            ]);

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
        return $this->num_rented + $this->num_owned + $this->num_no_response;
    }

}
