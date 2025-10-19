<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $fillable = [
        'name',
        'type',
        'desc',
    ];

    public const INCOME = 'income';

    public const EXPENSE = 'expense';
}
