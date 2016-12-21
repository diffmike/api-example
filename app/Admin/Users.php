<?php
/**
 * User: diffmike
 * Date: 01.09.2016
 * Time: 5:48 PM
 */

namespace App\Admin;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Company;
use App\Notifications\UserCreationConfirmation;
use App\Shop;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\DisplayInterface;
use SleepingOwl\Admin\Contracts\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;

/**
 * Class Users
 * @package App\Http\Admin
 *
 * @property User $model
 */
class Users extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;
    
    /**
     * @var string
     */
    protected $title = 'Суб-администраторы';
    
    /**
     * @var string
     */
    protected $icon = 'fa fa-user-secret';
    
    /**
     * @var string
     */
    protected $alias = 'users';
    
    /**
     * @var \App\User
     */
    protected $model;
    
    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation(100);
        
        $this->created(function($config, User $user) {
            $user->notify(new UserCreationConfirmation(request('password')));
        });
    }
    
    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        return AdminDisplay::datatablesAsync()
            ->addStyle('custom', 'css/admin.css')
            ->setColumns(
                AdminColumn::text('name', 'Имя')->append(AdminColumn::custom(null, function(User $model) {
                    return sprintf("<a title='Магазины' href='%s'><i class='fa fa-arrow-right'></i></a>", url('/admin/shops?user_id=' . $model->id));
                })),
                AdminColumn::email('email', 'E-mail'),
                AdminColumn::text('barcode', 'Штрихкод'),
                AdminColumn::lists('shops.title')->setLabel('Магазины')
            )
            ->setApply(function (Builder $query) { $query->where('role', User::ROLE_MANAGER); })
            ->setNewEntryButtonText('Добавить');
    }
    
    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id = null)
    {
        $form = AdminForm::panel()
            ->addHeader(
                AdminFormElement::text('name', 'Имя')->required(),
                AdminFormElement::text('email', 'E-mail')
                    ->required()
                    ->addValidationRule('email')
                    ->unique(),
                AdminFormElement::html('Пароль генерируется сервисом и будет отправлен на указанный email при создании<br><br>'),
                AdminFormElement::text('barcode', 'Штрихкод')->setDefaultValue(str_random())->setReadonly(true),
                AdminFormElement::multiselect('shops', 'Магазины', Shop::class)->setOptions($this->getGroupedShops())->setDisplay(null),
                AdminFormElement::hidden('role')->setDefaultValue(User::ROLE_MANAGER)
            );
        if (!$id) {
            $form->addItem(AdminFormElement::hidden('password')->setDefaultValue(str_random(8)));
        }
        
        return $form;
    }
    
    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit();
    }
    
    public function can($action, Model $model)
    {
        return User::hasRole(User::ROLE_ADMIN);
    }
    
    private function getGroupedShops()
    {
        return Company::all()->mapWithKeys(function (Company $item) {
            return [$item['title'] => $item->shops->pluck('title', 'id')];
        })->toArray();
    }
}