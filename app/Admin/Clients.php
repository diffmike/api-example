<?php

namespace App\Admin;

use AdminColumn;
use AdminDisplay;
use AdminDisplayFilter;
use AdminForm;
use AdminFormElement;
use App\Shop;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Contracts\DisplayInterface;
use SleepingOwl\Admin\Contracts\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;

class Clients extends Section implements Initializable
{
    /** @var bool Check access via Policy */
    protected $checkAccess = true;
    
    /**
     * @var string
     */
    protected $title = 'Клиенты';

    /**
     * @var string
     */
    protected $icon = 'fa fa-group';
    
    /**
     * @var \App\Client
     */
    protected $model;
    
    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation(400);
    }
    
    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        return AdminDisplay::datatablesAsync()
            ->addStyle('custom', 'css/admin.css')
            ->setFilters(
                AdminDisplayFilter::field('checks.shop_id', null)->setValue(request('shop_id'))
            )
            ->setTitle(request('shop_id') ? ('Магазин ' . Shop::findOrFail(request('shop_id'))->title) : null)
            ->setColumns(
                AdminColumn::text('name', 'Имя')->append(AdminColumn::custom(null, function(User $model) {
                    return sprintf("<a title='Чеки' href='%s'><i class='fa fa-arrow-right'></i></a>", url('/admin/checks?user_id=' . $model->id));
                })),
                AdminColumn::email('email', 'E-mail'),
                AdminColumn::text('barcode', 'Штрихкод')
            )
            ->setApply($this->getShopApply());
    }
    
    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id = null)
    {
        $form =  AdminForm::panel()
            ->addBody(
                AdminFormElement::text('name', 'Имя')->required(),
                AdminFormElement::text('email', 'E-mail')
                    ->required()
                    ->addValidationRule('email')
                    ->unique(),
                AdminFormElement::hidden('role')->setDefaultValue(User::ROLE_USER)
            );
        
        if (!$id) {
            $form->addItem(AdminFormElement::hidden('password')->setDefaultValue(str_random(8)));
        } else {
            $form->addItem(AdminFormElement::text('barcode', 'Штрихкод')->setReadonly(true));
            $form->addItem(AdminFormElement::file('source', 'Файл для импорта покупок')->addValidationRule('mimes:xml'));
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
    
    private function getShopApply()
    {
        return function (Builder $query) {
            $query->where('role', User::ROLE_USER);
            if (User::hasRole(User::ROLE_ADMIN)) return $query;
            
            return $query->whereHas('checks', function (Builder $query) {
                return $query->whereIn('shop_id', auth()->user()->shops->pluck('id')->toArray());
            });
        };
    }
}
