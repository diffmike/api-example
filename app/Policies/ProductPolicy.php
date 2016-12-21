<?php

namespace App\Policies;

use App\User;
use App\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;
    
    public function before(User $user, $ability)
    {
        if ($user->role == User::ROLE_ADMIN) {
            return true;
        }
    }
    
    /**
     * Determine whether the user can create product.
     *
     * @param \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role == User::ROLE_ADMIN || $user->role == User::ROLE_MANAGER;
    }
    
    /**
     * Determine whether the user can see the product.
     *
     * @param \App\User  $user
     * @return mixed
     */
    public function display(User $user)
    {
        return $user->role == User::ROLE_ADMIN || $user->role == User::ROLE_MANAGER;
    }
    
    /**
     * Determine whether the user can edit the product.
     *
     * @param  \App\User  $user
     * @param \App\Product  $product
     * @return mixed
     */
    public function edit(User $user, Product $product)
    {
        return self::can($user, $product);
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param \App\User  $user
     * @param \App\Product  $product
     * @return mixed
     */
    public function update(User $user, Product $product)
    {
        return self::can($user, $product);
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param \App\User  $user
     * @param \App\Product  $product
     * @return mixed
     */
    public function delete(User $user, Product $product)
    {
        return self::can($user, $product);
    }
    
    public static function can(User $user, Product $product)
    {
        $shopIds = $user->shops->pluck('id');
        
        return $shopIds->contains($product->shop_id);
    }
}
