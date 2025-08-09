<?php


namespace lhaamed\NaameBar;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Facade;

/**
 * @method static NaameBarService panel(string $panel)
 * @method static string|null getPanel()
 * @method static array send(string $phone_number, string $message)
 * @method static array bulkSend(array $phone_numbers, string $message)
 * @method static array verify(string $phone_number, array $parameters)
 */
class NaameBar extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return NaameBarService::class;
    }

}
