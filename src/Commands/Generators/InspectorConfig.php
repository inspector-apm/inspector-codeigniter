<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Inspector\CodeIgniter\Commands\Generators;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\GeneratorTrait;

/**
 * Generates a skeleton config file.
 */
class InspectorConfig extends BaseCommand
{
    use GeneratorTrait;

    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Generators';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'make:inspector-config';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Generates a new inspector library config file.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'make:inspector-config <name> [options]';

    /**
     * The Command's Arguments
     *
     * @var array<string, string>
     */
    protected $arguments = [
        'name' => 'The config class name.',
    ];

    /**
     * The Command's Options
     *
     * @var array<string, string>
     */
    protected $options = [
        '--namespace' => 'Set root namespace. Default: "APP_NAMESPACE".',
        '--suffix'    => 'Append the component title to the class name (e.g. User => UserConfig).',
        '--force'     => 'Force overwrite existing file.',
        '--key'       => 'The inspector dashboard ingestion key.',
    ];

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        $this->component = 'Config';
        $this->directory = 'Config';
        $this->template = 'inspector.tpl.php';
        $this->classNameLang = 'CLI.generator.className.config';

        $this->generateClass($params);
    }
    
    /**
     * Prepare options and do the necessary replacements.
     */
    protected function prepare(string $class): string
    {
        $namespace = $this->getOption('namespace') ?? APP_NAMESPACE;

        if ($namespace === APP_NAMESPACE) {
            $class = substr($class, strlen($namespace . '\\'));
        }

        return $this->parseTemplate($class);
    }

    /**
     * Gets the generator view as defined in the `Config\Generators::$views`,
     * with fallback to `$template` when the defined view does not exist.
     *
     * @param array<string, mixed> $data
     */
    protected function renderTemplate(array $data = []): string
    {
        $parser = service('parser');
        $template = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $this->template);
        $data = array_merge([
          'key' => $this->getOption('key') ?? 'YOUR_INGESTION_KEY'
        ], $data);
        return $parser->setData($data)->renderString($template);
    }
}
