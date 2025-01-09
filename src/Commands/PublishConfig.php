<?php

namespace Inspector\CodeIgniter\Commands;

use CodeIgniter\CLI\BaseCommand;

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

        if (!file_exists($destination)) {
            copy($source, $destination);
        }
    }
}
