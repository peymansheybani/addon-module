<?php
/**
 * AUTHOR = p.sheybani
 * CREATED AT = 6/29/2020 3:29 PM
 **/

namespace greenweb\addon\setting;


use greenweb\addon\Addon;
use greenweb\addon\models\Setting as SettingModel;

class Setting
{
    /**
     * @var Addon 
     */
    private $app;
    /**
     * @var SettingModel
     */
    public $config;

    public function __construct(Addon $app)
    {
        $this->app = $app;
        $this->config = SettingModel::where('code', $app->config['settingConfig']['code'])
            ->get()->pluck('value', 'key');
    }
}