<?php
/**
 * AUTHOR : p.sheybani
 * CREATED AT : 2020-07-02 10:39 AM
 **/

namespace greenweb\addon\migrations;


use greenweb\addon\Addon;
use Illuminate\Database\Migrations;

class Migration
{
    const DATABASE = 'database.php';

    public $app;

    private $migrations;
    private $migrationDir;

    public function __construct(Addon $app)
    {
        $this->app = $app;
        $this->migrationDir = $this->app->BaseDir.DIRECTORY_SEPARATOR.rtrim($app->MigrationPath, '/'). '/';
        $this->migrations =  scandir($this->migrationDir);
    }

    public function run()
    {
        collect($this->migrations)->each(function ($value, $key){
            if (!in_array($value, ['..', '.'])) {
                $fileInfo = pathinfo($this->migrationDir . DIRECTORY_SEPARATOR . $value);
                $migrationClass = $this->app->MigrationNameSpace."\\".$fileInfo['filename'];
                $class = new $migrationClass();

                if ($class instanceof Migrations\Migration) {
                    $class->run();
                }
            }
        });

        collect($this->app->database['migrations'])->each(function ($value){
            $class = new $value($this->app);
            $class->run();
        });

        return true;
    }

}