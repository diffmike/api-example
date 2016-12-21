<?php
/**
 * User: diffmike
 * Date: 12.09.2016
 * Time: 10:25 AM
 */
namespace App\Api\Transformers;

use App\Campaign;
use League\Fractal\TransformerAbstract;

class CampaignTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['products'];
    
    /**
     * @param Campaign $campaign
     *
     * @return array
     */
    public function transform(Campaign $campaign)
    {
        return [
            'id'           => $campaign->id,
            'title'        => $campaign->title,
            'image'        => $campaign->image ? url($campaign->image) : null,
            'link'         => $campaign->link,
            'start'        => $campaign->start->toDateString(),
            'finish'       => $campaign->finish->toDateString()
        ];
    }
    
    /**
     * @param Campaign $campaign
     * @return \League\Fractal\Resource\Collection
     */
    public function includeProducts(Campaign $campaign)
    {
        return $this->collection($campaign->products, new ProductTransformer);
    }
}