<?php

namespace App\Policies;

use App\User;
use App\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;
    
    public function before(User $user, $ability)
    {
        if ($user->role == User::ROLE_ADMIN) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the client.
     *
     * @param \App\User  $user
     * @param \App\Client  $client
     * @return mixed
     */
    public function view(User $user, Client $client)
    {
        return self::can($user, $client);
    }

    /**
     * Determine whether the user can create clients.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can display clients.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function display(User $user)
    {
        return $user->role == User::ROLE_ADMIN || $user->role == User::ROLE_MANAGER;
    }

    /**
     * Determine whether the user can update the client.
     *
     * @param \App\User  $user
     * @param \App\Client  $client
     * @return mixed
     */
    public function update(User $user, Client $client)
    {
        return self::can($user, $client);
    }

    /**
     * Determine whether the user can edit the client.
     *
     * @param \App\User  $user
     * @param \App\Client  $client
     * @return mixed
     */
    public function edit(User $user, Client $client)
    {
        return self::can($user, $client);
    }

    /**
     * Determine whether the user can delete the client.
     *
     * @param \App\User  $user
     * @param \App\Client  $client
     * @return mixed
     */
    public function delete(User $user, Client $client)
    {
        return self::can($user, $client);
    }
    
    public static function can(User $user, Client $client)
    {
        $shopIds = $user->shops->pluck('id');
        
        return ! $shopIds->intersect($client->checks->pluck('shop_id'))->isEmpty();
    }
}
