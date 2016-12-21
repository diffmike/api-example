<?php

namespace App;

use App\Contracts\ShopImporterContract;
use Illuminate\Database\Eloquent\Model;
use KodiComponents\Support\Upload;

/**
 * App\Shop
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $title
 * @property string $address
 * @property string $image
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Shop whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Shop whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Shop whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Shop whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Shop whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Shop whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $campaigns_count
 * @property-read \App\Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Campaign[] $campaigns
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Check[] $checks
 * @property string $city
 * @property mixed $source
 * @method static \Illuminate\Database\Query\Builder|\App\Shop whereCity($value)
 * @property string $uid
 * @method static \Illuminate\Database\Query\Builder|\App\Shop whereUid($value)
 */
class Shop extends Model
{
    use Upload;
    
    protected $fillable = ['title', 'address', 'company_id', 'uid', 'city'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['image' => 'image'];
    
    protected $appends = ['source'];
    
    /**
     * The company that this shop is belong
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    /**
     * The products that belong to the shop.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    /**
     * The checks that belong to the shop.
     */
    public function checks()
    {
        return $this->hasMany(Check::class);
    }
    
    /**
     * The users that belong to the shops.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
    /**
     * The campaigns that belong to the shop.
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
    
    public function maxDiscount()
    {
        $discounts = $this->campaigns()->active()->get()->pluck('max_discount')->toArray();
        return $discounts ? max($discounts) : null;
    }
    
    public function setSourceAttribute($value)
    {
        app(ShopImporterContract::class, [$value, $this->id])->import();
    }
    
    public function getSourceAttribute() { }
}
