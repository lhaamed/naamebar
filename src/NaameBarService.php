<?php

namespace lhaamed\NaameBar;


use App\Facades\Alert;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\NoReturn;

class NaameBarService
{
    public static string|null $active_panel = null;

    public function panel(string $panel): NaameBarService
    {
        if (in_array($panel,$this->getValidPanels())){
            self::$active_panel = $panel;
        }

        return $this;
    }

    public function getPanel(): string|null
    {
        return self::$active_panel;
    }

    private function getValidPanels(): array
    {
        return array_keys(config('NaameBar.panels'));
    }

    private function getConfig()
    {
        return config('NaameBar.panels.'.self::$active_panel);
    }

    private function getApiKey(): string
    {
        return $this->getConfig()['api_key'];
    }

    private function getUsername(): string
    {
        return $this->getConfig()['username'];
    }

    private function getApiRoute(string $name)
    {
        return $this->getConfig()['api_routes'][$name]['route'];
    }

    private function getApiRouteMethod(string $name)
    {
        return $this->getConfig()['api_routes'][$name]['methods'][0];
    }


    /**
     * @throws ConnectionException
     */
    public function getCredit()
    {
        return $this->callAPI('GET', $this->getApiRoute('credit'))->json();
    }


    /**
     * @throws ConnectionException
     */
    public function send(string $phone_number, string $message): array
    {
        $line = $this->getConfig()['default_line'];
        $response = $this->callAPI($this->getApiRouteMethod('normal-dispatcher'), $this->getApiRoute('normal-dispatcher') . "?username={$this->getUsername()}&password={$this->getApiKey()}&mobile={$phone_number}&line={$line}&text={$message}");
        if (!$response->successful()) {
            Alert::toast('رمز یکبار مصرف','خطایی رخ داده است. لطفا بعدا مجدد تلاش کنید.','error');
            throw new ConnectionException($response->json()['message']);
        }else{
            Alert::toast('رمز یکبار مصرف','با موفقیت ارسال شد.');
            return $response->json();
        }
    }

    /**
     * @throws ConnectionException
     */
    public function bulkSend(array $phone_numbers, string $message)
    {
        $line = $this->getConfig()['default_line'];
        $response = $this->callAPI($this->getApiRouteMethod('bulk-dispatcher'), $this->getApiRoute('bulk-dispatcher'),
        [
            'lineNumber' => $line,
            'messageText' => $message,
            'mobiles' => $phone_numbers
        ]);
        if (!$response->successful()) {
            Alert::toast('خطا در ارسال پیامک گروهی','پیامک گروهی ارسال نشد.','error');
            throw new ConnectionException($response->json()['message']);
        }else{
            Alert::toast('ارسال موفق پیامک گروهی','پیامک گروهی با موفقیت ارسال شد.');
            return $response->json();
        }
    }

    /**
     * @throws ConnectionException
     */
    #[NoReturn] public function verify(string $phone_number, array $parameters): array
    {


        $template_id = 584333;
        $data = [
            'templateId' => $template_id,
            'mobile' => $phone_number,
            'parameters' => $parameters
        ];

        $response = $this->callAPI($this->getApiRouteMethod('verify-dispatcher'), $this->getApiRoute('verify-dispatcher'),$data);

        if (!$response->successful()) {
            throw new ConnectionException($response->json()['message']);
        }else{
            Alert::toast('رمز یکبار مصرف','با موفقیت ارسال شد.');
            return $response->json();
        }

    }

    /**
     * @throws ConnectionException
     */
    private function callAPI(string $method, $url, array $data = []): PromiseInterface|Response
    {
        $request = Http::withHeaders([
            'Accept' => 'application/json',
            'X-API-KEY' => $this->getApiKey(),
        ])->withoutVerifying();

        if ($method == 'POST') {
            return $request->post($url, $data);
        }else {
            return $request->get($url, $data);
        }
    }
}
