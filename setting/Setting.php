<?php
/**
 * AUTHOR = p.sheybani
 * CREATED AT = 6/29/2020 3:29 PM
 **/

namespace greenweb\addon\setting;


use greenweb\addon\Addon;
use greenweb\addon\component\Component;
use greenweb\addon\models\Setting as SettingModel;

/**
 * Class Setting
 * @package greenweb\addon\setting
 *
 * @property SettingModel $data
 */
class Setting extends Component
{
    public $data;

    public function __construct(Addon $app)
    {
        parent::__construct($app);
        $this->data = SettingModel::code()->get()->pluck('value', 'key');
    }
}