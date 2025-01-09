<?php

namespace Inspector\CodeIgniter\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Install extends BaseCommand
{
    protected $group = 'Inspector';

    protected $name = 'inspector:publish';

    protected $description = 'Installs Inspector configuration files.';

    public function run(array $params)
    {
        // Copy the config file
        $source = __DIR__ . '/Inspector.php.stub';
        $destination = APPPATH . 'Config/Inspector.php';

        CLI::write("Publishing the configuration file to {$destination}");
        if (!file_exists($destination)) {
            copy($source, $destination);
        }
        CLI::write("Done!", "green");

        CLI::write("Configure the Ingestion Key in your environment file to enable data transfer.", "yellow");
        CLI::write("Check out the official documentation: https://docs.inspector.dev/guides/codeigniter", "yellow");
    }
}
