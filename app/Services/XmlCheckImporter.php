<?php
/**
 * User: diffmike
 * Date: 04.10.2016
 * Time: 1:11 PM
 */

namespace App\Services;

use App\Check;
use App\Contracts\CheckImporterContract;
use App\Product;
use App\User;

class XmlCheckImporter implements CheckImporterContract
{
    private $filePath;
    private $user;
    
    /**
     * XmlCheckImporter constructor.
     * @param string $filePath
     * @param \App\User $user
     */
    public function __construct($filePath, User $user)
    {
        $this->filePath = $filePath;
        $this->user = $user;
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
        $checks = [];
        foreach (array_get($data, 'Checks_By_Period.Clients.Client', []) as $client) {
            if ( $product = Product::where(['title' => array_get($client, 'ShoppingList.GoodInfo.GOODNAME')])->first() ) {
                /** @var Check $check */
                $check = Check::updateOrCreate(['uid' => array_get($client, 'ShoppingList.CHECKNUMER')], [
                    'uid'               => array_get($client, 'ShoppingList.CHECKNUMER'),
                    'sum'               => array_get($client, 'ShoppingList.SUMCHECK', 0),
                    'sum_with_discount' => array_get($client, 'ShoppingList.SUMCHECKWITHDISCOUNT', 0),
                    'created_at'        => strtotime(array_get($client, 'ShoppingList.DATETIME')),
                    'user_id'           => $this->user->id,
                    'shop_id'           => $product->shop_id
                ]);
                $checks[$check->id][] = $product->id;
            }
        }
        collect($checks)->mapWithKeys(function ($productsIds, $checkId) {
            Check::find($checkId)->products()->sync($productsIds);
        });
    }
}