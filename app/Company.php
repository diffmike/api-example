<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Company
 *
 * @property integer $id
 * @property string $title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Shop[] $shops
 * @property string $official_title
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereOfficialTitle($value)
 */
class Company extends Model
{
    protected $fillable = ['title'];
    
    /**
     * The shops that belong to the company.
     */
    public function shops()
    {
        return $this->hasMany(Shop::class);
    }
}
