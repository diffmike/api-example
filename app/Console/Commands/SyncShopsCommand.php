<?php

namespace App\Console\Commands;

use App\Company;
use App\Shop;
use FTP;
use Illuminate\Console\Command;
use Log;

class SyncShopsCommand extends Command
{
    protected $signature = 'sync:shops {--D|date=}';

    protected $description = 'Synchronize shops from file in FTP bucket';
    
    /** @var \Anchu\Ftp\Ftp $ftp */
    private $ftp;
    
    public function __construct(FTP $ftp)
    {
        parent::__construct();
        $this->ftp = $ftp::connection();
    }
    
    public function handle()
    {
        $date = $this->option('date') ?: date('d.m.y');
        $file = $this->ftp->readFile("Shops_{$date}.xml");
        if (!$file) {
            Log::info("File Shops_{$date}.xml not found for import/update shops");
            return;
        }
        
        $xml = simplexml_load_string($file);
        $data = json_decode(json_encode($xml), true);
        foreach ((array)array_get($data, 'Stores.Store', []) as $store) {
            $store = array_filter($store, function ($el) { return $el && !is_array($el); });
            if (!array_get($store, 'TMName')) continue;
            
            /** @var Shop $shop */
            $shop = Shop::firstOrCreate(['uid' => array_get($store, 'StoreID')], [
                'uid'       => array_get($store, 'StoreID'),
                'title'     => array_get($store, 'StroreName'),
                'address'   => array_get($store, 'Address'),
                'city'      => array_get($store, 'City'),
                'company_id'=> Company::firstOrCreate(['title' => $store['TMName']], ['title' => $store['TMName']])->id
            ]);
            Log::info("Import/update shop {$shop->title} ({$shop->id})");
        }
    }
}
