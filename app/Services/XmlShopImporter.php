<?php
/**
 * User: diffmike
 * Date: 13.09.2016
 * Time: 4:51 PM
 */

namespace App\Services;

use App\Contracts\ShopImporterContract;
use App\Product;

class XmlShopImporter implements ShopImporterContract
{
    private $filePath;
    private $shopId;
    
    public function __construct($filePath, $shopId)
    {
        $this->filePath = $filePath;
        $this->shopId = $shopId;
    }

    public function __destruct()
    {
        if (!$this->filePath) return;
        unlink(public_path($this->filePath));
    }
    
    public function import()
    {
        if (!$this->filePath) return;
        $xml = simplexml_load_file(public_path($this->filePath));
        $data = json_decode(json_encode($xml), true);
        foreach (array_get($data, 'Goods_From_Price.Goods.Good', []) as $good) {
            $this->createOrUpdateProduct($good);
        }
        foreach (array_get($data, 'Checks_By_Period.Clients.Client', []) as $client) {
            $this->createOrUpdateProduct(array_get($client, 'ShoppingList.GoodInfo'));
        }
    }
    
    private function createOrUpdateProduct($good)
    {
        $good = array_filter($good, function ($el) {
            return $el && !is_array($el);
        });
        $product = Product::updateOrCreate(['title' => array_get($good, 'GOODNAME')], [
            'title'               => array_get($good, 'GOODNAME'),
            'vendor_code'         => array_get($good, 'ALIAS'),
            'price'               => array_get($good, 'COST', 0),
            'discount'            => array_get($good, 'DISCOUNTPERCENT', 0),
            'price_with_discount' => array_get($good, 'COSTWITHDISCOUNT', 0),
            'weight'              => array_get($good, 'WEIGHT', 0),
            'unit'                => array_get($good, 'VALUENAME'),
            'trademark'           => array_get($good, 'TM'),
            'structure'           => array_get($good, 'COMPOSITION'),
            'proteins'            => array_get($good, 'PROTEINS', 0),
            'fats'                => array_get($good, 'FATS', 0),
            'carbohydrates'       => array_get($good, 'CARBOHYDRATES', 0),
            'calories'            => array_get($good, 'ENERGYVALUEPER100G', 0),
            'description'         => array_get($good, 'DESCRIPTION'),
            'shop_id'             => $this->shopId
        ]);
        
        return $product;
    }
}