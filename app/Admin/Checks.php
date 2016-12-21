<?php

namespace App\Admin;

use AdminColumn;
use AdminDisplay;
use AdminDisplayFilter;
use App\Check;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Contracts\DisplayInterface;
use SleepingOwl\Admin\Section;

class Checks extends Section
{

    /**
     * @var string
     */
    protected $title = 'Список чеков';

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        return AdminDisplay::datatablesAsync()
            ->addStyle('custom', 'css/admin.css')
            ->setTitle('Клиент ' . User::findOrFail(request('user_id'))->name)
            ->setColumns(
                AdminColumn::custom('№ Чека', function(Check $model) {
                    return sprintf("<a href='%s'>{$model->uid}</a>", url('/admin/products?check_id=' . $model->id));
                }),
                AdminColumn::datetime('created_at', 'Дата')->setFormat('H:i d.m.Y'),
                AdminColumn::text('sum', 'Сумма'),
                AdminColumn::text('sum_with_discount', 'Сумма со скидкой'),
                AdminColumn::text('shop.title', 'Магазин')
            )
            ->setApply(function(Builder $query) {
                return $query->where(['user_id' => request('user_id')]);
            });
    }
}
