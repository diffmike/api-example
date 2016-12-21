<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use KodiComponents\Support\Upload;

/**
 * App\Campaign
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $title
 * @property \Carbon\Carbon $start
 * @property \Carbon\Carbon $finish
 * @property string $link
 * @property string $image
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $max_discount
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign whereShopId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign whereStart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign whereFinish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign whereLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Shop $shop
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @property boolean $is_active
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Campaign active()
 */
class Campaign extends Model
{
    use Upload;
    
    protected $appends = ['max_discount'];
    
    protected $casts = ['image' => 'image', 'start' => 'date', 'finish' => 'date'];
    
    /**
     * The shop that this campaign is belong
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    
    /**
     * The products that belong to the campaign.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    
    /**
     * Scope a query to only include active Campaigns
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where([['start', '<=', date('Y-m-d')], ['finish', '>=', date('Y-m-d')], ['is_active', '=', 1]]);
    }
    
    public function getMaxDiscountAttribute()
    {
        return $this->products->max('discount');
    }
}
