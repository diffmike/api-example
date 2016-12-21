<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/** @var \Dingo\Api\Routing\Router $api */
$api->post('login', ['as' => 'auth.login', 'uses' => 'AuthController@login']);

$api->group(['middleware' => 'api.auth'], function($api) {
    /** @var \Dingo\Api\Routing\Router $api */
    $api->get('shops', ['as' => 'shops.list', 'uses' => 'ShopController@index']);
    $api->get('shops/{shop}', ['as' => 'shops.show', 'uses' => 'ShopController@show']);
    $api->get('shops/{shop}/campaigns', ['as' => 'shops.campaigns', 'uses' => 'ShopController@campaigns']);
    
    $api->get('campaigns/active-ids', ['as' => 'campaigns.active-ids', 'uses' => 'CampaignController@activeIds']);
    $api->get('campaigns/{campaign}', ['as' => 'campaigns.show', 'uses' => 'CampaignController@show']);
    
    $api->get('users/details', ['as' => 'users.details', 'uses' => 'UserController@details']);
    $api->get('checks', ['as' => 'checks.list', 'uses' => 'CheckController@index']);
});
