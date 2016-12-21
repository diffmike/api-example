<?php

namespace App\Console\Commands;

use App\Product;
use App\Shop;
use FTP;
use Illuminate\Console\Command;
use Log;

class SyncProductsCommand extends Command
{
    protected $signature = 'sync:products {--D|date=}';

    protected $description = 'Synchronize products from file in FTP bucket';
    
    private static $shopsId = [];
    
    /** @var \Anchu\Ftp\Ftp $ftp */
    private $ftp;
    
    public function __construct(FTP $ftp)
    {
        parent::__construct();
        $this->ftp = $ftp::connection();
    }

    public function handle()
    {
        $date = $this->option('date') ?: date('d.m.Y');
        $directories = $this->getShopDirectories();
        foreach ($directories as $directory) {
            $file = $this->ftp->readFile("./{$directory}/Price_{$date}.xml");
            if (!$file) { continue; }
            
            $xml = simplexml_load_string($file);
            $data = json_decode(json_encode($xml), true);
            foreach ((array)array_get($data, 'Goods.Good', []) as $good) {
                $good = array_filter($good, function ($el) { return $el && !is_array($el); });
                if (!array_get($good, 'StoreID') || !array_get($good, 'GoodKey')) continue;
                $this->saveProduct($good);
            }
            Log::info("Import/update products from directory {$directory}");
        }
    }
    
    private function getShopDirectories()
    {
        $dirContent = collect($this->ftp->getDirListing());
        
        return $dirContent->filter(function($name) {
            return $this->ftp->size($name) == -1;
        });
    }
    
    private function getShopId($uid)
    {
        if ($shopId = array_get(self::$shopsId, $uid)) {
            return $shopId;
        }
        
        $shopId = Shop::firstOrCreate(['uid' => $uid], ['uid' => $uid])->id;
        self::$shopsId[$uid] = $shopId;
        
        return $shopId;
    }
    
    protected function saveProduct($good)
    {
        /** @var Product $product */
        $product = Product::updateOrCreate(['uid' => array_get($good, 'GoodKey')], [
            'uid'                 => array_get($good, 'GoodKey'),
            'vendor_code'         => array_get($good, 'BarCode'),
            'title'               => array_get($good, 'GoodName'),
            'price'               => array_get($good, 'Cost', 0),
            'price_with_discount' => array_get($good, 'CostWithDiscount', 0),
            'trademark'           => array_get($good, 'TM'),
            'weight'              => array_get($good, 'Weight', 0),
            'unit'                => array_get($good, 'ValueName'),
            'structure'           => array_get($good, 'Composition'),
            'description'         => array_get($good, 'Description'),
            'proteins'            => array_get($good, 'Proteins', 0),
            'fats'                => array_get($good, 'Fats', 0),
            'carbohydrates'       => array_get($good, 'Carbohydrates', 0),
            'calories'            => array_get($good, 'EnergyValuePer100G', 0),
            'discount_start'      => array_get($good, 'DateBeginDiscount') ? date('Y-m-d', strtotime($good['DateBeginDiscount'])) : null,
            'discount_finish'     => array_get($good, 'DateEndDiscount') ? date('Y-m-d', strtotime($good['DateEndDiscount'])) : null,
            'discount_type'       => array_get($good, 'DiscountType'),
            'shop_id'             => $this->getShopId(array_get($good, 'StoreID'))
        ]);
        
        return $product;
    }
}
