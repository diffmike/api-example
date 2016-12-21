<?php

namespace App\Admin;

use AdminColumn;
use AdminDisplay;
use AdminDisplayFilter;
use AdminForm;
use AdminFormElement;
use App\Campaign;
use App\Product;
use App\Shop;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Contracts\DisplayInterface;
use SleepingOwl\Admin\Contracts\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;

class Campaigns extends Section implements Initializable
{
    /** @var bool Check access via Policy */
    protected $checkAccess = true;
    
    /**
     * @var string
     */
    protected $title = 'Акции';
    
    /**
     * @var string
     */
    protected $icon = 'fa fa-trophy';
    
    /**
     * @var \App\Campaign
     */
    protected $model;
    
    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation(600);
    }
    
    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        return AdminDisplay::datatablesAsync()
            ->setHtmlAttribute('style', 'table-layout: fixed; width: 100%')
            ->addStyle('custom', 'css/admin.css')
            ->setFilters(AdminDisplayFilter::field('shop_id', null))
            ->setTitle(request('shop_id') ? ('Магазин ' . Shop::findOrFail(request('shop_id'))->title) : null)
            ->setColumns(
                AdminColumn::text('title', 'Название'),
                AdminColumn::custom('Активна', function(Campaign $model) { return $model->is_active ? 'Да' : 'Нет'; }),
                AdminColumn::datetime('start', 'Начало')->setFormat('d.m.Y')->setOrderable(true),
                AdminColumn::datetime('finish', 'Окончание')->setFormat('d.m.Y'),
                AdminColumn::custom('Ссылка', function(Campaign $model) {
                    return "<a style='word-wrap: break-word;' target='_blank' href='{$model->link}'>{$model->link}</a>";
                }),
                AdminColumn::custom('Товары', function(Campaign $model) {
                    return sprintf("<a href='%s'>Товары</a>", url('/admin/products?campaign_id=' . $model->id));
                }),
                AdminColumn::image('image', 'Картинка')
            )
            ->setApply($this->getShopApply())
            ->setOrder([[2, 'desc']])
            ->setNewEntryButtonText('Добавить');
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        $form = AdminForm::panel()
            ->setHtmlAttribute('enctype', 'multipart/form-data')
            ->addBody(
                AdminFormElement::text('title', 'Название')->addValidationRule('between:2,22')->required(),
                AdminFormElement::checkbox('is_active', 'Активна'),
                AdminFormElement::date('start', 'Начало')->setFormat('Y-m-d')->required(),
                AdminFormElement::date('finish', 'Окончание')
                    ->setFormat('Y-m-d')
                    ->required()
                    ->addValidationRule('after:' . date('Y-m-d', strtotime(request('start') . ' - 1 day'))),
                AdminFormElement::text('link', 'Ссылка')->required(),
                AdminFormElement::image('image', 'Картинка')->required()
            )
            ->addFooter(
                AdminFormElement::multiselect('products', 'Товары', Product::class)
                    ->setOptions($this->getAvailableProducts())
                    ->setDisplay(null)
                    ->required()
            );
    
        $shops = $this->getAvailableShops();
        if (is_array($shops) && count($shops) == 1) {
            $form->addItem(AdminFormElement::hidden('shop_id')->setDefaultValue(key($shops)));
        } else {
            $form->addFooter(AdminFormElement::select('shop_id', 'Магазин', $shops)->required());
        }
        
        return $form;
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }
    
    private function getAvailableShops()
    {
        if (User::hasRole(User::ROLE_ADMIN)) {
            return Shop::class;
        } else {
            return auth()->user()->shops->pluck('title', 'id')->toArray();
        }
    }
    
    private function getAvailableProducts()
    {
        if (User::hasRole(User::ROLE_ADMIN)) {
            return Product::all()->pluck('title', 'id')->toArray();
        } else {
            $products = auth()->user()->shops->map(function(Shop $s) {
                return $s->products->pluck('title', 'id');
            })->toArray();
            
            $result = [];
            foreach ($products as $product) {
                $result += $product;
            }
        
            return $result;
        }
    }
    
    private function getShopApply()
    {
        return function (Builder $query) {
            if (User::hasRole(User::ROLE_ADMIN)) return $query;
            return $query->whereIn(
                'shop_id', auth()->user()->shops->pluck('id')->toArray()
            );
        };
    }
}
