<?php

namespace Inspector\CodeIgniter\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class PublishConfig extends BaseCommand
{
    protected $group = 'Inspector';

    protected $name = 'inspector:publish';

    protected $description = 'Installs Inspector configuration files.';

    public function run(array $params)
    {
        // Copy the config file
        $source = __DIR__ . '/../Config/Inspector.php';
        $destination = APPPATH . 'Config/Inspector.php';

        CLI::write("Publishing the configuration file to {$destination}");

        if (!file_exists($destination)) {
            copy($source, $destination);
        }

        CLI::write("Done!", "green");
    }
}
