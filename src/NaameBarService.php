<?php

namespace lhaamed\NaameBar;


use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

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
     * @return array|mixed
     * @throws ConnectionException
     */
    public function getCredit(): mixed
    {
        return $this->callAPI('GET', $this->getApiRoute('credit'))->json();
    }


    /**
     * @param string $phone_number
     * @param string $message
     * @return array|mixed
     * @throws ConnectionException
     */
    public function send(string $phone_number, string $message): mixed
    {
        $line = $this->getConfig()['default_line'];
        $response = $this->callAPI($this->getApiRouteMethod('normal-dispatcher'), $this->getApiRoute('normal-dispatcher') . "?username={$this->getUsername()}&password={$this->getApiKey()}&mobile={$phone_number}&line={$line}&text={$message}");
        if (!$response->successful()) {
            throw new ConnectionException($response->json()['message']);
        }else{
            return $response->json();
        }
    }

    /**
     * @param array $phone_numbers
     * @param string $message
     * @return array|mixed
     * @throws ConnectionException
     */
    public function bulkSend(array $phone_numbers, string $message): mixed
    {
        $line = $this->getConfig()['default_line'];
        $response = $this->callAPI($this->getApiRouteMethod('bulk-dispatcher'), $this->getApiRoute('bulk-dispatcher'),
        [
            'lineNumber' => $line,
            'messageText' => $message,
            'mobiles' => $phone_numbers
        ]);
        if (!$response->successful()) {
            throw new ConnectionException($response->json()['message']);
        }else{
            return $response->json();
        }
    }

    /**
     * @param string $message_id
     * @return array|mixed
     * @throws ConnectionException
     */
    public function singleInquiry(string $message_id): mixed
    {
        $line = $this->getConfig()['default_line'];
        $response = $this->callAPI($this->getApiRouteMethod('single-inquiry'), $this->getApiRoute('single-inquiry') . "/$message_id");
        if (!$response->successful()) {
            throw new ConnectionException($response->json()['message']);
        }else{
            return $response->json();
        }
    }

    /**
     * @throws ConnectionException
     */
    public function verify(string $phone_number, array $parameters): array
    {

        $template_id = $this->getConfig()['template_id'];
        $data = [
            'templateId' => $template_id,
            'mobile' => $phone_number,
            'parameters' => $parameters
        ];

        $response = $this->callAPI($this->getApiRouteMethod('verify-dispatcher'), $this->getApiRoute('verify-dispatcher'),$data);

        if (!$response->successful()) {
            throw new ConnectionException($response->json()['message']);
        }else{
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
