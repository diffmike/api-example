<?php

namespace App\Repository;

use App\Shop;

class ShopRepository extends AbstractRepository
{
    public function __construct(Shop $model)
    {
        parent::__construct($model);
    }
    
    public function findOrderedByDiscount()
    {
        return $this->model
            ->select('shops.*')
            ->leftJoin('campaigns as c', function ($join) {
                $join->on('c.shop_id', '=', 'shops.id')
                    ->where('c.is_active', 1)
                    ->where('c.start', '<=', date('Y-m-d'))
                    ->where('c.finish', '>=', date('Y-m-d'));
            })
            ->leftJoin('campaign_product as cp', 'cp.campaign_id', '=', 'c.id')
            ->leftJoin('products as p', 'p.id', '=', 'cp.product_id')
            ->orderBy('p.discount', 'desc')
            ->groupBy('shops.id')
            ->withCount(['campaigns' => function ($query) { $query->active(); }]);
    }
}
