<?php

namespace App\Admin;

use AdminColumn;
use AdminDisplay;
use AdminDisplayFilter;
use AdminForm;
use AdminFormElement;
use App\Company;
use App\Shop;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Contracts\DisplayInterface;
use SleepingOwl\Admin\Contracts\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Columns\Column;
use SleepingOwl\Admin\Section;

class Shops extends Section implements Initializable
{
    /** @var bool Check access via Policy */
    protected $checkAccess = true;
    
    /**
     * @var string
     */
    protected $title = 'Магазины';
    
    /**
     * @var string
     */
    protected $icon = 'fa fa-home';

    /**
     * @var \App\Shop
     */
    protected $model;
    
    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation(300);
        $this->created(function($config, Shop $shop) {
            if (User::hasRole(User::ROLE_MANAGER)) {
                $shop->users()->save(auth()->user());
            }
        });
    }
    
    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        return AdminDisplay::datatablesAsync()
            ->addStyle('custom', 'css/admin.css')
            ->setFilters(
                AdminDisplayFilter::field('company_id', null),
                $this->getUserFilter()
            )
            ->setTitle(request('user_id') ? ('Cуб-администратор ' . User::findOrFail(request('user_id'))->name) : null)
            ->setColumns(
                AdminColumn::text('title', 'Имя')->append(AdminColumn::custom(null, function(Shop $model) {
                    return sprintf("<a title='Клиенты' href='%s'><i class='fa fa-arrow-right'></i></a>", url('/admin/clients?shop_id=' . $model->id));
                })),
                AdminColumn::text('city', 'Город'),
                AdminColumn::text('address', 'Адрес'),
                AdminColumn::custom('Товары', function(Shop $model) {
                    return sprintf("<a href='%s'>Товары</a>", url('/admin/products?shop_id=' . $model->id));
                }),
                AdminColumn::custom('Акции', function(Shop $model) {
                    return sprintf("<a href='%s'>Акции</a>", url('/admin/campaigns?shop_id=' . $model->id));
                }),
                AdminColumn::text('company.title', 'Компания')
            )
            ->setApply($this->getShopApply())
            ->setNewEntryButtonText('Добавить');
    }
    
    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        $uploader = AdminFormElement::file('source', 'Файл для импорта')->addValidationRule('mimes:xml');
        if (!$id) {
            $uploader->setReadonly(true)->setLabel('Сохраните магазин для импорта товаров');
        }
        $form = AdminForm::panel()
            ->setHtmlAttribute('enctype', 'multipart/form-data')
            ->addBody(
                AdminFormElement::text('title', 'Название')->addValidationRule('between:2,17')->required(),
                AdminFormElement::text('city', 'Город')->addValidationRule('between:2,20')->required(),
                AdminFormElement::textarea('address', 'Адрес')->addValidationRule('between:2,20')->setRows(5)->required(),
                AdminFormElement::columns([new Column([
                    AdminFormElement::image('image', 'Картинка')->required()
                ]), new Column([$uploader])])
            );
    
        $companies = $this->getAvailableCompanies();
        if (is_array($companies) && count($companies) == 1) {
            $form->addItem(AdminFormElement::hidden('company_id')->setDefaultValue(key($companies)));
        } else {
            $form->addFooter(AdminFormElement::select('company_id', 'Компания', $companies)->required());
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
    
    private function getAvailableCompanies()
    {
        if (User::hasRole(User::ROLE_ADMIN)) {
            return Company::class;
        } else {
            $result = [];
            foreach (auth()->user()->shops as $shop) {
                $result[$shop->company_id] = $shop->company->title;
            }
            return $result;
        }
    }
    
    private function getUserFilter()
    {
        $filter = AdminDisplayFilter::field('id', null);
        
        if (request('user_id')) {
            $filter->setOperator('in')->setValue(
                User::find(request('user_id'))->shops->pluck('id')->toArray()
            );
        }
        return $filter;
    }
    
    private function getShopApply()
    {
        return function (Builder $query) {
            if (User::hasRole(User::ROLE_ADMIN)) return $query;
            return $query->whereIn('id', auth()->user()->shops->pluck('id')->toArray());
        };
    }
}
