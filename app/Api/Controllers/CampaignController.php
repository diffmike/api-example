<?php
/**
 * User: diffmike
 * Date: 12.09.2016
 * Time: 4:22 PM
 */

namespace App\Api\Controllers;

use App\Api\Transformers\CampaignTransformer;
use App\Campaign;

class CampaignController extends Controller
{
    /**
     * Детали акции со списком продуктов акции
     *
     * @param Campaign $campaign
     * @return \Dingo\Api\Http\Response
     */
    public function show(Campaign $campaign)
    {
        request()->merge(['include' => 'products']);
        return $this->response->item($campaign, new CampaignTransformer);
    }
    
    /**
     * Список ID всех активных акций
     *
     * @return array
     */
    public function activeIds()
    {
        return ['data' => Campaign::active()->pluck('id')];
    }
}