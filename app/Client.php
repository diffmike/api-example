<?php

namespace App;
use App\Contracts\CheckImporterContract;

/**
 * App\Client
 * 
 * Model-helper for correct using admin package (1 model - 1 section)
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Company[] $companies
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
 * @method static \Illuminate\Database\Query\Builder|\App\Client whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Client whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Client whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Client wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Client whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Client whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Client whereBarcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Client whereDiscount($value)
 * @mixin \Eloquent
 * @property integer $shop_id
 * @method static \Illuminate\Database\Query\Builder|\App\Client whereShopId($value)
 * @property-read \App\Shop $shop
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Check[] $checks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Shop[] $shops
 * @property-read mixed $last_month_discount
 * @property-read mixed $last_month_spent
 * @property mixed $source
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $readNotifications
 */
class Client extends User
{
    protected $table = 'users';
    
    /**
     * The companies that belong to the user.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user', 'user_id', 'company_id');
    }
    
    /**
     * The checks that belong to the users.
     */
    public function checks()
    {
        return $this->hasMany(Check::class, 'user_id');
    }
    
    public function setSourceAttribute($value)
    {
        resolve(CheckImporterContract::class, [$value, $this])->import();
    }
    
    public function getSourceAttribute() { }
}
