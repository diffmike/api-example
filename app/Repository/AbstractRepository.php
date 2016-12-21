<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    protected $model;
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}