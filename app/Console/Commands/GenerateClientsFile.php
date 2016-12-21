<?php

namespace App\Console\Commands;

use App\User;
use FTP;
use Illuminate\Console\Command;
use Storage;

class GenerateClientsFile extends Command
{
    protected $signature = 'bm:clients:file';

    protected $description = 'Generate clients file';
    
    public function handle()
    {
        $barcodes = User::whereRole(User::ROLE_USER)->latest()->pluck('barcode');
    
        $fileName = 'clients-' . date('d-m-Y') . '.csv';
        Storage::put("public/files/clients/{$fileName}", implode("\n", $barcodes->toArray()));
        FTP::connection()->uploadFile(storage_path("app/public/files/clients/{$fileName}"), "clients/{$fileName}");
    }
}
