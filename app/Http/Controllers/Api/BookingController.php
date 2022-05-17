<?php

namespace App\Http\Controllers\Api;

use App\Bookings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Api\BookingService;
use Auth;

class BookingController extends Controller
{
    /**
     * Метод для бронирования блоков, сохраняет бронь в таблицу bookings
     * Возвращает цену брони и количество блоков
     * @param Request $request
     * @param BookingService $bookingService
     * @return array
     */
    public function bookBlock (Request $request, BookingService $bookingService) {
        $requestBody = $request->json()->all();
        //validation
        $rules = [
            'location_id' => 'required|integer|gt:0',
            'volumeOfProducts' => 'required|integer|gt:0',
            'temperature' => 'required|integer|lt:0',
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date'
        ];

        $validator = Validator::make($requestBody, $rules);
        if (!$validator->passes()) {
            return($validator->errors()->all());
        }

        $booking = new Bookings; //model

        $booking->user_id = Auth::id();
        $booking->location_id = $requestBody['location_id'];
        $booking->quantity_blocks = $bookingService->getCountOfBlocks($requestBody['volumeOfProducts']);
        $booking->date_from = $requestBody['dateFrom'];
        $booking->date_to = $requestBody['dateTo'];
        $booking->status = 'active';
        $booking->tocken_to_edit = $bookingService->getHashForBooking();
        $booking->price = $bookingService->getBookingPrice(
            $requestBody['dateFrom'],
            $requestBody['dateTo'] ,
            $bookingService->getCountOfBlocks($requestBody['volumeOfProducts']),
            $requestBody['location_id']
        );

        $booking->save();

        $result = [
            'countBlocks' => $booking->quantity_blocks,
            'price' => $booking->price,
            'availableBlocks' => $booking->quantity_blocks
        ];

        return $result;
    }
}
