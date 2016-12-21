<?php
/**
 * User: diffmike
 * Date: 22.11.2016
 * Time: 4:01 PM
 */
namespace App\Observers;

use App\Client;
use App\Services\Barcode;
use FTP;
use Storage;

class ClientObserver
{
    public function created(Client $client)
    {
        if (!$client->barcode) {
            $client->barcode = Barcode::generate($client->id);
            $client->saveOrFail();
        }
    
        $fileName = 'clients-' . date('d-m-Y') . '.csv';
        Storage::append("public/files/clients/{$fileName}", $client->barcode);
        FTP::connection()->uploadFile(storage_path("app/public/files/clients/{$fileName}"), "clients/{$fileName}");
    }
}