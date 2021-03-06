<?php
/**
 * AUTHOR : p.sheybani
 * CREATED AT : 2020-07-02 11:40 AM
 **/

namespace greenweb\addon\migrations;


use greenweb\addon\Addon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class PermissionMigration extends Migration
{
    /**
     * @var Addon
     */
    private $app;

    public function __construct(Addon $app)
    {
        $this->app = $app;
    }

    public function run()
    {
        if (!isset($this->app->config['loader']['permission'])) {
            return false;
        }

        if(!Capsule::schema()->hasTable('green_permission')) {
            Capsule::schema()->create('green_permission', function (Blueprint $table) {
                $table->increments('id');
                $table->bigInteger('role_id');
                $table->json('permissions')->nullable();
                $table->timestamps();
                $table->engine = 'InnoDB';
            });
        }
    }

}