<?php
return [
    /*
	|--------------------------------------------------------------------------
	| Default FTP Connection Name
	|--------------------------------------------------------------------------
	|
	| Here you may specify which of the FTP connections below you wish
	| to use as your default connection for all ftp work.
	|
	*/
    'default' => 'files',
    
    /*
    |--------------------------------------------------------------------------
    | FTP Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the FTP connections setup for your application.
    |
    */
    'connections' => [
        'files' => [
            'host'     => env('FTP_HOST'),
            'port'     => 21,
            'username' => env('FTP_USER'),
            'password' => env('FTP_PASS'),
            'passive'  => true,
        ],
    ],
];