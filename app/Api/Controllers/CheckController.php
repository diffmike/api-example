<?php
/**
 * User: diffmike
 * Date: 12.09.2016
 * Time: 5:14 PM
 */

namespace App\Api\Controllers;

use App\Api\Transformers\CheckTransformer;

class CheckController extends Controller
{
    /**
     * Список чеков пользователя с товарами
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        return $this->response->collection(auth()->user()->checks, new CheckTransformer);
    }
}