# NaameBar

Laravel SMS integration. Currently only SMS.ir is supported. PHP 8.3+ and Laravel 11, 12, or 13 are required. Additional provider adapters and automatic failover are outside the current release.

## Installation

Install from the GitHub repository:

```sh
composer config repositories.naame-bar vcs https://github.com/lhaamed/naamebar.git
composer require lhaamed/naame-bar:^0.1
```

For a tagged stable release, require the corresponding version instead. Laravel discovers the provider automatically; no application PSR-4 mapping or manual provider registration is needed.

For an unpublished local checkout, use a Composer `path` repository pointing to that checkout, with `options.versions` set to `{"lhaamed/naame-bar":"dev-main"}`.

## Configuration

```dotenv
SMSIR_API_KEY=
SMSIR_USERNAME=
SMSIR_LINE_NUMBER=
SMSIR_TEMPLATE_ID=
```

Optional configuration publishing:

```sh
php artisan vendor:publish --tag=nb:config
```

Configuration uses the existing `NaameBar` key and `config/NaameBar.php` filename. Never commit API credentials. After changing environment settings, rebuild any existing Laravel configuration cache.

## Usage

```php
use lhaamed\NaameBar\NaameBar;

NaameBar::panel('sms-ir');
$response = NaameBar::send('09120000000', 'Message');
$response = NaameBar::bulkSend(['09120000000'], 'Message');
$response = NaameBar::singleInquiry('message-id');
$response = NaameBar::getCredit();
$response = NaameBar::verify('09120000000', [
    ['name' => 'OTP', 'value' => '1234'],
]);
```

`verify()` requires an approved SMS.ir template and `SMSIR_TEMPLATE_ID`. Applications without that approval can continue sending OTP messages through `send()`. NaameBar does not automatically switch between these methods or display UI notifications.

## Current limitations

This extraction preserves the existing request formats and response arrays. It does not redesign the transport: `send()` still uses the legacy URL parameters, TLS verification is currently disabled, and the selected panel is static mutable state. Review these before broader production adoption. No automatic retries or failover are performed, avoiding implicit duplicate sends.

## Tests

With the package dependencies installed, run `php tests/run.php`. Inside the host project, run `php lhaamed/NaameBar/tests/run.php`; requests are faked and no SMS is sent.

For v0.1.1, all five SMS operations, provider registration, configuration loading, and facade binding were checked against Laravel 11.57.0 and the Laravel 13.40.0 framework source fetched directly from GitHub. The Laravel 13 source check reused the host's installed third-party dependencies; it was not a clean Laravel 13 dependency installation.
