<?php
/**
 * AUTHOR : p.sheybani
 * CREATED AT : 2020-07-01 9:17 AM
 **/

namespace greenweb\addon\migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class PermissionMigration implements Migration
{
    public function run()
    {
        if(!Capsule::schema()->hasTable('green_permission')) {
            Capsule::schema()->create('green_permission', function (Blueprint $table) {
                $table->increments('id');
                $table->bigInteger('role_id');
                $table->json('permissions');
                $table->timestamps();
                $table->engine = 'InnoDB';
            });
        }
    }
}