<?php
/**
 * AUTHOR = p.sheybani
 * CREATED AT = 6/29/2020 4:12 PM
 **/

namespace greenweb\addon\models;


use greenweb\addon\Addon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class Setting
 * @package greenweb\addon\models
 *
 * @method static Builder code()
 *
 * @property  string $value
 * @property  string $code
 * @property  string $key
 */
class Setting extends Model
{
    protected $table = 'setting';
    public $timestamps = false;
    protected $fillable = [
        'code', 'key', 'value'
    ];

    public function scopeCode(Builder $query)
    {
        return $query->where('code', Addon::$instance->config['settingConfig']['code']);
    }
}