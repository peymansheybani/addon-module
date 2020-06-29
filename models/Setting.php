<?php
/**
 * AUTHOR = p.sheybani
 * CREATED AT = 6/29/2020 4:12 PM
 **/

namespace greenweb\addon\models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'setting';

    protected $fillable = [
        'code', 'key', 'value'
    ];

    public function scopeCode(Builder $query)
    {
        return $query->where('code', $this->app);
    }

    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }

    public function getQueueableConnection()
    {
        // TODO: Implement getQueueableConnection() method.
    }

    public function resolveRouteBinding($value, $field = null)
    {
        // TODO: Implement resolveRouteBinding() method.
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}