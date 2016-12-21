<?php

namespace App\Console\Commands;

use App\Check;
use App\Product;
use App\Shop;
use App\User;
use FTP;
use Illuminate\Console\Command;
use Log;

class SyncChecksCommand extends Command
{
    protected $signature = 'sync:checks';

    protected $description = 'Synchronize checks from file in FTP bucket';
    
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
        $directories = $this->getShopDirectories();
        foreach ($directories as $directory) {
            $this->ftp->makeDir("./{$directory}/archive");
            foreach ($this->ftp->getDirListing("./{$directory}") as $fileName) {
                if (!str_contains($fileName, 'Bill_')) { continue; }
                $file = $this->ftp->readFile($fileName);
                if (!$file) { continue; }
    
                $xml = simplexml_load_string($file);
                $data = json_decode(json_encode($xml), true);
                foreach ((array)array_get($data, 'ChecksByPeriod.Client', []) as $clientInfo) {
                    /** @var User $user */
                    $user = User::where(['barcode' => array_get($clientInfo, 'CardCode')])->first();
                    if (!$user) continue;
                    
                    $this->saveCheck($clientInfo, $user);
                }
                $this->ftp->rename($fileName, "./{$directory}/archive/" . explode('/', $fileName)[2]);
            }
            Log::info("Import/update checks from directory {$directory}");
        }
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
    
    private function getShopDirectories()
    {
        $dirContent = collect($this->ftp->getDirListing());
        
        return $dirContent->filter(function($name) {
            return $this->ftp->size($name) == -1;
        });
    }
    
    protected function saveCheck($clientInfo, $user)
    {
        foreach ((array)array_get($clientInfo, 'ShoppingList.Checks.Check', []) as $bill) {
            if (!array_get($bill, 'GoodsList')) continue;
            
            /** @var Check $check */
            $check = Check::firstOrCreate(['uid' => array_get($bill, 'CheckNumber')], [
                'uid'               => array_get($bill, 'CheckNumber'),
                'created_at'        => date_create_from_format('d.m.y H:i:s', array_get($bill, 'DateTime')),
                'sum'               => array_get($bill, 'SumCheck', 0),
                'sum_with_discount' => array_get($bill, 'SumCheckWithDiscount', 0),
                'shop_id'           => $this->getShopId(array_get($clientInfo, 'ShoppingList.StoreID')),
                'user_id'           => $user->id
            ]);
            
            $check->products()->detach();
            $check->products()->saveMany(Product::whereIn('uid', explode(';', $bill['GoodsList']))->get());
        }
        
        $this->info("Checks for {$user->id} were saved");
    }
}
