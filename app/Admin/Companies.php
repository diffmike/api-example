<?php

namespace App\Admin;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Company;
use App\User;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\DisplayInterface;
use SleepingOwl\Admin\Contracts\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;

class Companies extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Компании';
    
    /**
     * @var string
     */
    protected $icon = 'fa fa-building';
    
    /**
     * @var \App\Company
     */
    protected $model;
    
    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation(200);
    }

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        return AdminDisplay::datatablesAsync()
            ->addStyle('custom', 'css/admin.css')
            ->setColumns(
                AdminColumn::text('title', 'Название сети')->append(AdminColumn::custom(null, function(Company $model) {
                    return sprintf("<a title='Магазины' href='%s'><i class='fa fa-arrow-right'></i></a>", url('/admin/shops?company_id=' . $model->id));
                })),
                AdminColumn::text('official_title', 'Юр. имя'),
                AdminColumn::lists('shops.title', 'Магазины')
            )
            ->setNewEntryButtonText('Добавить');
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        return AdminForm::panel()
            ->addBody(
                AdminFormElement::text('official_title', 'Юр. имя')->unique()->required(),
                AdminFormElement::html('Например: ООО "Евросеть-Ритейл"<br><br>'),
                AdminFormElement::text('title', 'Название сети')->required()
            );
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }
    
    public function can($action, Model $model)
    {
        return User::hasRole(User::ROLE_ADMIN);
    }
}
