<?php
/**
 * User: diffmike
 * Date: 14.09.2016
 * Time: 3:14 PM
 */
namespace App\Contracts;

interface ShopImporterContract
{
    /**
     * ShopImporterContract constructor.
     * @param string $filePath
     * @param $shopId
     */
    public function __construct($filePath, $shopId);
    
    public function import();
}