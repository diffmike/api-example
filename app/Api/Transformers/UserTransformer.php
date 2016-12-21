<?php
/**
 * User: diffmike
 * Date: 07.09.2016
 * Time: 3:38 PM
 */
namespace App\Api\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'barcode'    => $user->barcode,
            'created_at' => $user->created_at->toDateTimeString()
        ];
    }
}