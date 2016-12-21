<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Check
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $uid
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @method static \Illuminate\Database\Query\Builder|\App\Check whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Check whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Check whereUid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Check whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Check whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $shop_id
 * @method static \Illuminate\Database\Query\Builder|\App\Check whereShopId($value)
 * @property-read \App\Shop $shop
 * @property integer $sum
 * @property integer $sum_with_discount
 * @method static \Illuminate\Database\Query\Builder|\App\Check whereSum($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Check whereSumWithDiscount($value)
 */
class Check extends Model
{
    protected $fillable = ['uid', 'user_id', 'created_at', 'shop_id', 'sum', 'sum_with_discount'];
    
    /**
     * The user that check is belong
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * The shop that check is belong
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    
    /**
     * The products which are contain the check.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
