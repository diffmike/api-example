<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Product
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $title
 * @property string $vendor_code
 * @property float $price
 * @property float $price_with_discount
 * @property integer $discount
 * @property string $trademark
 * @property float $weight
 * @property string $unit
 * @property string $structure
 * @property float $proteins
 * @property float $fats
 * @property float $carbohydrates
 * @property float $calories
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereShopId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereVendorCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product wherePriceWithDiscount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereDiscount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereTrademark($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereWeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereUnit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereStructure($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereProteins($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereFats($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereCarbohydrates($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereCalories($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Shop $shop
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Campaign[] $campaigns
 * @property string $description
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereDescription($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Check[] $checks
 * @property string $uid
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereUid($value)
 * @property \Carbon\Carbon $discount_start
 * @property \Carbon\Carbon $discount_finish
 * @property string $discount_type
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereDiscountStart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereDiscountFinish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Product whereDiscountType($value)
 */
class Product extends Model
{
    protected $fillable = ['uid', 'title', 'vendor_code', 'price', 'discount', 'price_with_discount', 'weight', 'unit', 'trademark', 'structure', 'proteins', 'fats', 'carbohydrates', 'calories', 'description', 'shop_id', 'discount_type', 'discount_finish', 'discount_start'];
    
    protected $casts = ['discount_start' => 'date', 'discount_finish' => 'date'];
    
    /**
     * The shop that this product is belong
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    
    /**
     * The campaigns which are contain product.
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }
    
    /**
     * The checks which are contain product.
     */
    public function checks()
    {
        return $this->belongsToMany(Check::class);
    }
}
