<?php

namespace APIBasedPluginTests\Unit\Repository;

use APIBasedPlugin\Repository\APIDataRepository;
use Mockery;
use PHPUnit\Framework\TestCase;
use WP_Error;
use WP_Mock;
use WP_Mock\Functions;

class APIDataRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        WP_Mock::setUp();
        Mockery::mock('alias:APIBasedPlugin\Helper\Config')
         ->shouldReceive('get')
         ->with('settingsPrefix')
         ->andReturn('my_prefix_');

        Mockery::mock('alias:APIBasedPlugin\Helper\Utils')
         ->shouldReceive('getOption')
         ->with('data_source_url', '')
         ->andReturn('https://example.com/api');

        if (!defined('ABSPATH')) {
            define('ABSPATH', '/var/www/html/');
        }

        if (!defined('HOUR_IN_SECONDS')) {
            define('HOUR_IN_SECONDS', 3600);
        }
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
        Mockery::close();
    }

    public function testFetchAPIDataReturnsObjectOnSuccessfulFetch()
    {
        $apiData = json_encode(['key' => 'value']);
        $response = [
            'body' => $apiData,
            'response' => ['code' => 200]
        ];

        WP_Mock::userFunction('wp_remote_get', [
            'times' => 1,
            'args' => ['https://example.com/api'],
            'return' => $response
        ]);

        WP_Mock::userFunction('is_wp_error', [
            'times' => 1,
            'return' => false
        ]);

        WP_Mock::userFunction('wp_remote_retrieve_body', [
            'times' => 1,
            'return' => $apiData
        ]);

        WP_Mock::userFunction('set_transient', [
            'times' => 1,
            'args' => ['my_prefix_api_data', Functions::type('object'), HOUR_IN_SECONDS]
        ]);

        $result = APIDataRepository::fetchAPIData();
        $this->assertIsObject($result);
        $this->assertEquals(json_decode($apiData), $result);
    }

    public function testFetchAPIDataReturnsEmptyObjectOnError()
    {
        // Check if WP_Error is not already defined
        if (!class_exists('WP_Error')) {
            eval('class WP_Error { function is_wp_error() { return true; } }');
        }

        // Mock wp_remote_get to simulate a response that triggers an error
        WP_Mock::userFunction('wp_remote_get', [
            'times' => 1,
            'args' => ['https://example.com/api'],
            'return' => new WP_Error('http_request_failed', 'A connection error occurred')
        ]);

        // Ensure is_wp_error is expected to be called once with any arguments
        WP_Mock::userFunction('is_wp_error', [
            'times' => 1,
            'return' => true
        ]);

        $result = APIDataRepository::fetchAPIData();
        $this->assertEquals(new \stdClass(), $result);
    }
}
