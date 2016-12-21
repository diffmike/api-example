<?php
/**
 * User: diffmike
 * Date: 07.09.2016
 * Time: 3:38 PM
 */
namespace App\Api\Transformers;

use App\Shop;
use League\Fractal\TransformerAbstract;

class ShopTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['campaigns'];
    
    /**
     * @param Shop $shop
     *
     * @return array
     */
    public function transform(Shop $shop)
    {
        return [
            'id'                    => $shop->id,
            'title'                 => $shop->title,
            'city'                  => $shop->city,
            'address'               => $shop->address,
            'image'                 => $shop->image ? url($shop->image) : null,
            'created_at'            => $shop->created_at->toDateTimeString(),
            'campaigns_count'       => $shop->campaigns_count,
            'max_discount'          => $shop->maxDiscount()
        ];
    }
    
    /**
     * @param Shop $shop
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCampaigns(Shop $shop)
    {
        return $this->collection($shop->campaigns()->active()->get(), new CampaignTransformer);
    }
}