<?php

namespace Depotwarehouse\Neighbourhoods;

use Illuminate\Database\Eloquent\Model;

class NeighbourhoodRecord extends Model
{

    public $table = "neighbourhoods";
    public $incrementing = false;

    protected $fillable = [ 'id', 'name', 'population' ];

    public function setNameAttribute($value)
    {
        $this->slug = str_replace(" ", "-", strtolower($value));

        $this->attributes['name'] = $value;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @param array $attributes
     * @return self
     */
    public function updateOrCreate($id, $name)
    {
        $instance = $this->newQuery()->firstOrCreate([ 'id' => $id ]);

        $instance->name = $name;

        $instance->save();

        return $instance;
    }

}
