<?php

namespace App\Policies;

use App\User;
use App\Campaign;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;
    
    public function before(User $user, $ability)
    {
        if ($user->role == User::ROLE_ADMIN) {
            return true;
        }
    }
    
    /**
     * Determine whether the user can create campaign.
     *
     * @param \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role == User::ROLE_ADMIN || $user->role == User::ROLE_MANAGER;
    }
    
    /**
     * Determine whether the user can see the the campaign.
     *
     * @param \App\User  $user
     * @return mixed
     */
    public function display(User $user)
    {
        return $user->role == User::ROLE_ADMIN || $user->role == User::ROLE_MANAGER;
    }
    
    /**
     * Determine whether the user can edit the campaign.
     *
     * @param  \App\User  $user
     * @param  \App\Campaign  $campaign
     * @return mixed
     */
    public function edit(User $user, Campaign $campaign)
    {
        return self::can($user, $campaign);
    }
    
    /**
     * Determine whether the user can update the campaign.
     *
     * @param  \App\User  $user
     * @param  \App\Campaign  $campaign
     * @return mixed
     */
    public function update(User $user, Campaign $campaign)
    {
        return self::can($user, $campaign);
    }

    /**
     * Determine whether the user can delete the campaign.
     *
     * @param \App\User  $user
     * @param \App\Campaign  $campaign
     * @return mixed
     */
    public function delete(User $user, Campaign $campaign)
    {
        return self::can($user, $campaign);
    }
    
    public static function can(User $user, Campaign $campaign)
    {
        $shopIds = $user->shops->pluck('id');
        
        return $shopIds->contains($campaign->shop_id);
    }
}
