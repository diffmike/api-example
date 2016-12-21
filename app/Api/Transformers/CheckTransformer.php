<?php
/**
 * User: diffmike
 * Date: 12.09.2016
 * Time: 5:16 PM
 */
namespace App\Api\Transformers;

use App\Check;
use League\Fractal\TransformerAbstract;

class CheckTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['products'];
    
    /**
     * @param Check $check
     * @return array
     */
    public function transform(Check $check)
    {
        return [
            'id'                => $check->id,
            'uid'               => $check->uid,
            'sum'               => $check->sum,
            'sum_with_discount' => $check->sum_with_discount,
            'shop_id'           => $check->shop_id,
            'created_at'        => $check->created_at->toDateTimeString()
        ];
    }
    
    /**
     * @param Check $check
     * @return \League\Fractal\Resource\Collection
     */
    public function includeProducts(Check $check)
    {
        return $this->collection($check->products, new ProductTransformer);
    }
}