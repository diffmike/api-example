<?php
/**
 * User: diffmike
 * Date: 04.10.2016
 * Time: 1:11 PM
 */
namespace App\Contracts;

use App\User;

interface CheckImporterContract
{
    /**
     * CheckImporterContract constructor.
     * @param string $filePath
     * @param User $user
     */
    public function __construct($filePath, User $user);
    
    public function import();
}