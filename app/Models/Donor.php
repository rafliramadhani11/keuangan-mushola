<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'type',
        'is_anonymous',
    ];

    public const INDIVIDUAL = 'individual';

    public const ORGANIZATION = 'organization';
}
