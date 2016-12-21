<?php

namespace App\Api\Controllers;

use App\Api\Transformers\UserTransformer;

class UserController extends Controller
{
    /**
     * Получение информации о авторизованном пользователе
     *
     * @return \Dingo\Api\Http\Response
     */
    public function details()
    {
        $meta = [
            'last_month_discount' => auth()->user()->lastMonthDiscount,
            'last_month_spent'    => auth()->user()->lastMonthSpent
        ];
        return $this->response()->item(auth()->user(), new UserTransformer)->setMeta($meta);
    }
}
