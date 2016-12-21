<?php

namespace App\Policies;

use App\User;
use App\Shop;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;
    
    public function before(User $user, $ability)
    {
        if ($user->role == User::ROLE_ADMIN) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the shop.
     *
     * @param \App\User  $user
     * @param \App\Shop  $shop
     * @return mixed
     */
    public function view(User $user, Shop $shop)
    {
        return self::can($user, $shop);
    }

    /**
     * Determine whether the user can create shops.
     *
     * @param \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role == User::ROLE_ADMIN || $user->role == User::ROLE_MANAGER;
    }

    /**
     * Determine whether the user can display shops.
     *
     * @param \App\User  $user
     * @return mixed
     */
    public function display(User $user)
    {
        return $user->role == User::ROLE_ADMIN || $user->role == User::ROLE_MANAGER;
    }

    /**
     * Determine whether the user can update the shop.
     *
     * @param \App\User  $user
     * @param \App\Shop  $shop
     * @return mixed
     */
    public function update(User $user, Shop $shop)
    {
        return self::can($user, $shop);
    }

    /**
     * Determine whether the user can edit the shop.
     *
     * @param \App\User  $user
     * @param \App\Shop  $shop
     * @return mixed
     */
    public function edit(User $user, Shop $shop)
    {
        return self::can($user, $shop);
    }

    /**
     * Determine whether the user can delete the shop.
     *
     * @param \App\User  $user
     * @param \App\Shop  $shop
     * @return mixed
     */
    public function delete(User $user, Shop $shop)
    {
        return self::can($user, $shop);
    }
    
    public static function can(User $user, Shop $shop)
    {
        $shopIds = $user->shops->pluck('id');
        
        return $shopIds->contains($shop->id);
    }
}
