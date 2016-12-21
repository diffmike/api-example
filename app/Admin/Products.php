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
use SleepingOwl\Admin\Form\Columns\Column;
use SleepingOwl\Admin\Section;

class Products extends Section implements Initializable
{
    /** @var bool Check access via Policy */
    protected $checkAccess = true;
    
    /**
     * @var string
     */
    protected $title = 'Товары';
    
    /**
     * @var string
     */
    protected $icon = 'fa fa-shopping-bag';

    /**
     * @var \App\Product
     */
    protected $model;
    
    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation(500);
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        $display = AdminDisplay::datatablesAsync()
            ->addStyle('custom', 'css/admin.css')
            ->setFilters(
                AdminDisplayFilter::field('shop_id', null),
                AdminDisplayFilter::field('campaigns.id', null)->setValue(request('campaign_id')),
                AdminDisplayFilter::related('checks.id', null)->setValue(request('check_id'))
            )
            ->with('shop')
            ->setTitle($this->getSubTitle())
            ->setColumns(
                AdminColumn::link('title', 'Название'),
                AdminColumn::text('vendor_code', 'Артикул'),
                AdminColumn::text('price', 'Цена'),
                AdminColumn::text('discount', 'Скидка, %'),
                AdminColumn::text('shop.title', 'Магазин')->append(AdminColumn::filter('shop_id')),
                AdminColumn::text('trademark', 'Торговая марка')
            )
            ->setApply($this->getShopApply())
            ->setNewEntryButtonText('Добавить');
        
        return $display;
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        $display = AdminDisplay::tabbed()->setTabs(function() use ($id) {
            $product = Product::findOrFail($id);
            $tabs[] = AdminDisplay::tab(
                AdminForm::elements([
                    AdminFormElement::view('admin.product', compact('product'))
                ])
            )->setLabel('Просмотр')->setActive(true);
            
            $tabs[] = AdminDisplay::tab($this->getEditForm())->setLabel('Редактирование');
            
            return $tabs;
        });
        
        return $display;
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->getEditForm();
    }
    
    private function getAvailableShops()
    {
        if (User::hasRole(User::ROLE_ADMIN)) {
            return Shop::all()->pluck('title', 'id')->toArray();
        } else {
            $shops = auth()->user()->shops->pluck('title', 'id')->toArray();
            
            return $shops;
        }
    }
    
    private function getEditForm()
    {
        $form = AdminForm::panel()
            ->addStyle('custom', 'css/admin.css')
            ->addBody(
                AdminFormElement::text('title', 'Название')->required(),
                AdminFormElement::columns([new Column([
                    AdminFormElement::text('price', 'Цена')
                        ->required()
                        ->setDefaultValue(0)
                        ->addValidationRule('numeric'),
                    AdminFormElement::text('discount', 'Скидка, %')
                        ->required()
                        ->setDefaultValue(0)
                        ->addValidationRule('numeric')
                        ->addValidationRule('between:0,100'),
                    AdminFormElement::date('discount_start', 'Начало скидки'),
                    AdminFormElement::date('discount_finish', 'Конец скидки'),
                    AdminFormElement::text('weight', 'Масса')
                        ->required()
                        ->setDefaultValue(0)
                        ->addValidationRule('numeric'),
                    AdminFormElement::text('structure', 'Состав'),
                    AdminFormElement::text('proteins', 'Белки')
                        ->required()
                        ->setDefaultValue(0)
                        ->addValidationRule('numeric'),
                    AdminFormElement::text('carbohydrates', 'Углеводы')
                        ->required()
                        ->setDefaultValue(0)
                        ->addValidationRule('numeric')
                ]), new Column([
                    AdminFormElement::text('vendor_code', 'Артикул'),
                    AdminFormElement::text('price_with_discount', 'Цена со скидкой')
                        ->required()
                        ->setDefaultValue(0)
                        ->addValidationRule('numeric'),
                    AdminFormElement::text('discount_type', 'Описание скидки'),
                    AdminFormElement::text('trademark', 'Торговая марка'),
                    AdminFormElement::text('unit', 'Единица измерения'),
                    AdminFormElement::text('fats', 'Жиры')
                        ->required()
                        ->setDefaultValue(0)
                        ->addValidationRule('numeric'),
                    AdminFormElement::text('calories', 'Энергетическая ценность на 100 грамм')
                        ->required()
                        ->setDefaultValue(0)
                        ->addValidationRule('numeric')
                ])]),
                AdminFormElement::textarea('description', 'Описание')->setRows(2)
            );
    
        $shops = $this->getAvailableShops();
        if (is_array($shops) && count($shops) == 1) {
            $form->addItem(AdminFormElement::hidden('shop_id')->setDefaultValue(key($shops)));
        } else {
            $form->addFooter(AdminFormElement::select('shop_id', 'Магазин', $shops)->required());
        }
        
        return $form;
    }
    
    private function getShopApply()
    {
        return function (Builder $query) {
            if (User::hasRole(User::ROLE_ADMIN)) return $query;
            return $query->whereIn('shop_id', auth()->user()->shops->pluck('id')->toArray());
        };
    }
    
    private function getSubTitle()
    {
        return request('campaign_id')
            ? ('Акция ' . Campaign::findOrFail(request('campaign_id'))->title)
            : (request('shop_id')
                ? ('Магазин ' . Shop::findOrFail(request('shop_id'))->title)
                : null);
    }
}
