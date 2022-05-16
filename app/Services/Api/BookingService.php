<?php

namespace App\Services\Api;

use Illuminate\Support\Str;

class BookingService
{

    /**
     * Метод для получения цены хранения
     * @param $dateFrom
     * @param $dateTo
     * @param $countBlocks
     * @param $locationId
     */
    public function getBookingPrice($dateFrom, $dateTo, $countBlocks, $locationId)
    {
        $countDays = (strtotime ($dateTo)-strtotime ($dateFrom))/(60*60*24); // Колическо дней скоко будет храниться товар
        $countDays = $countDays != 0 ? $countDays : 1;

        $locationCoefficient = self::getPriceByLocation($locationId);

        $price = $countDays * $locationCoefficient * $countBlocks;

        return $price;
    }

    /**
     * Метод для расчета коефициента цены в зависимости от локации
     * @param $id
     * @return int
     */
    public static function getPriceByLocation($id) {
        return rand(1,5);
    }

    public function getHashForBooking () {
        return Str::random(12);
    }

    /**
     * * Метод для расчета количества занятых блоков, один блок 2 куб. метра
     * @param $volume
     * @return float
     */

    public function getCountOfBlocks ($volume) {
        return ceil($volume / 2); // Возвращаем количество занятых блоков в зависимости от обьема товара
    }
}
