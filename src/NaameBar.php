<?php


namespace lhaamed\NaameBar;

use Illuminate\Support\Facades\Facade;

/**
 * @method static NaameBarService panel(string $panel)
 * @method static mixed getPanel()
 * @method static mixed getCredit()
 * @method static mixed send(string $phone_number, string $message)
 * @method static mixed bulkSend(array $phone_numbers, string $message)
 * @method static mixed verify(string $phone_number, array $parameters)
 * @method static mixed singleInquiry(string $message_id)
 */
class NaameBar extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return NaameBarService::class;
    }

}
