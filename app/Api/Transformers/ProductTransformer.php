<?php
/**
 * User: diffmike
 * Date: 12.09.2016
 * Time: 4:37 PM
 */

namespace App\Api\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * @param Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'title'               => $product->title,
            'price'               => $product->price,
            'price_with_discount' => $product->price_with_discount,
            'discount'            => $product->discount,
            'discount_start'      => $product->discount_start->toDateString(),
            'discount_finish'     => $product->discount_finish->toDateString(),
            'discount_type'       => $product->discount_type,
        ];
    }
}