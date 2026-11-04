<?php

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use lhaamed\NaameBar\NaameBar;
use lhaamed\NaameBar\NaameBarService;
use lhaamed\NaameBar\NaameBarServiceProvider;

$autoload = __DIR__.'/../vendor/autoload.php';
require is_file($autoload) ? $autoload : __DIR__.'/../../../vendor/autoload.php';

$app = new Application(sys_get_temp_dir());
$app->instance('config', new Repository());
Facade::setFacadeApplication($app);
$app->register(NaameBarServiceProvider::class);
$app->boot();

function check(bool $condition, string $message): void
{
    if (! $condition) {
        throw new RuntimeException($message);
    }
    echo "PASS: {$message}\n";
}

check($app->make('NaameBar') === $app->make(NaameBarService::class), 'Facade alias and service share one instance');
check(NaameBar::getFacadeRoot() === $app->make('NaameBar'), 'Facade resolves the registered singleton');
check($app['config']->has('NaameBar.panels.sms-ir.api_routes'), 'Provider loads package defaults');
$app['config']->set('NaameBar.panels.sms-ir.api_key', 'test-key');
$app['config']->set('NaameBar.panels.sms-ir.default_line', '500000');
$app['config']->set('NaameBar.panels.sms-ir.template_id', 123);
Http::preventStrayRequests();
Http::fake(['*' => Http::response(['status' => 1, 'data' => []], 200)]);
NaameBar::panel('sms-ir');
NaameBar::bulkSend(['09120000000'], 'test');
NaameBar::verify('09120000000', [['name' => 'OTP', 'value' => '1234']]);
Http::assertSent(fn ($request) => str_ends_with($request->url(), '/send/bulk')
    && $request->hasHeader('X-API-KEY', 'test-key')
    && $request['messageText'] === 'test');
Http::assertSent(fn ($request) => str_ends_with($request->url(), '/send/verify')
    && $request['templateId'] === 123);
Http::assertSentCount(2);
echo "PASS: bulk and verify requests work without application UI dependencies\n";
