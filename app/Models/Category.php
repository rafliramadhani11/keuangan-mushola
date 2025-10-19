<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type',
        'desc',
        'is_active',
    ];

    public const INCOME = 'income';

    public const EXPENSE = 'expense';
}
