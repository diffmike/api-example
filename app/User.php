<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property boolean $role
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $barcode
 * @property boolean $discount
 * @property integer $shop_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Company[] $companies
 * @property-read \App\Shop $shop
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Check[] $checks
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereBarcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDiscount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereShopId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Shop[] $shops
 * @property-read mixed $last_month_discount
 * @property-read mixed $last_month_spent
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $readNotifications
 */
class User extends Authenticatable
{
    use Notifiable;
    
    const ROLE_USER    = 1;
    const ROLE_MANAGER = 2;
    const ROLE_ADMIN   = 3;

    protected $appends  = ['last_month_discount', 'last_month_spent'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'barcode'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'role'
    ];
    
    /**
     * Set the user's password crypted
     *
     * @param  string  $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    
    /**
     * Check if logged user has passed role
     *
     * @param string $role
     * @return bool
     */
    public static function hasRole($role)
    {
        return auth()->check() && auth()->user()->role == $role;
    }
    
    /**
     * The shops that belong to the users.
     */
    public function shops()
    {
        return $this->belongsToMany(Shop::class)->withPivot('discount');
    }
    
    /**
     * The checks that belong to the users.
     */
    public function checks()
    {
        return $this->hasMany(Check::class);
    }
    
    /**
     * The companies that belong to the users via shops.
     */
    public function companies()
    {
        return $this->shops->map(function(Shop $shop) {
            return $shop->company;
        });
    }
    
    public function getLastMonthDiscountAttribute()
    {
        return floatval($this->last_month_spent - Check::where([['created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 month'))], ['user_id', '=', $this->id]])->sum('sum_with_discount'));
    }
    
    public function getLastMonthSpentAttribute()
    {
        return floatval(Check::where([['created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 month'))], ['user_id', '=', $this->id]])->sum('sum'));
    }
}
