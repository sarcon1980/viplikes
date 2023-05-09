<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alias',
    ];

}
