<?php

namespace Inspector\CodeIgniter\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\Commands;
use Exception;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * @internal
 */
final class Test extends BaseCommand
{
    protected $group       = 'Inspector';
    protected $name        = 'inspector:test';
    protected $description = 'Test the inspector integration.';

    public function __construct(LoggerInterface $logger, Commands $commands)
    {
        parent::__construct($logger, $commands);
        helper('inspector');
    }

    public function run(array $params)
    {
        $config = config('Inspector');

        inspector()->addSegment(static function () {
            usleep(10 * 1000);

            try {
                \proc_open('', [], $pipes);
            } catch (Throwable $exception) {
                CLI::error('❌ proc_open function disabled.');

                return;
            }
        }, 'check', 'Check system requirements');

        inspector()->addSegment(static function () use ($config) {
            $config->ingestionKey
                ? CLI::write('✅ Ingestion Key has been configured.', 'green')
                : CLI::error('❌ Ingestion key is empty.');
        }, 'check', 'Check Ingestion Key');

        inspector()->addSegment(static function () {
            \usleep(10 * 1000);

            function_exists('curl_version')
                ? CLI::write('✅ CURL extension is enabled.', 'green')
                : CLI::error('❌ CURL is actually disabled so your app could not be able to send data to Inspector.');
        }, 'test', 'Check CURL extension');

        inspector()->addSegment(static function () {
            sleep(1);
        }, 'query', 'SELECT name, (SELECT COUNT(*) FROM orders WHERE user_id = users.id) AS order_count FROM users');

        inspector()->reportException(new Exception('First Exception Detected'));
        CLI::write('Open the dashboard and check your first exception: https://app.inspector.dev', 'yellow');

        inspector()->transaction()->setResult('success');

        CLI::write('Done!');
    }
}
