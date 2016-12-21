<?php

namespace App\Api\Controllers;

use App\Api\Transformers\CampaignTransformer;
use App\Api\Transformers\ShopTransformer;
use App\Repository\ShopRepository;
use App\Shop;

class ShopController extends Controller
{
    /**
     * Список магазинов
     *
     * @param ShopRepository $shops
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index(ShopRepository $shops)
    {
        $shops = $shops->findOrderedByDiscount()->paginate();
        
        return $this->response->paginator($shops, new ShopTransformer);
    }
    
    /**
     * Список активных акций магазина
     *
     * @param Shop $shop
     * @return \Dingo\Api\Http\Response
     */
    public function campaigns(Shop $shop)
    {
        $campaigns = $shop->campaigns()->orderBy('start', 'desc')->active()->get();
        
        return $this->response->collection($campaigns, new CampaignTransformer);
    }
    
    /**
     * Информация о магазине
     *
     * @param Shop $shop
     * @return \Dingo\Api\Http\Response
     */
    public function show(Shop $shop)
    {
        return $this->response->item($shop, new ShopTransformer);
    }
}
