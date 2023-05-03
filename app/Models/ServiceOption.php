<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'title',
        'accounts',
        'types',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'update_at' => 'datetime',
        'service_id' => 'int',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
