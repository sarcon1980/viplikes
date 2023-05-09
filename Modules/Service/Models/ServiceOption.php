<?php

namespace Modules\Service\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

/**
 * @property int $id
 * @property int $service_id
 * @property string $title
 * @property string $accounts
 * @property string $types
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime|null $deleted_at
 *
 */
class ServiceOption extends Model
{
    use Userstamps, SoftDeletes, HasFactory;

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

    public function getAccountsAttribute()
    {
        return json_decode($this->attributes['accounts']);
    }

    public function getTypesAttribute()
    {
        return json_decode($this->attributes['types']);
    }

    public function getTitleAttribute()
    {
        return json_decode($this->attributes['title']);
    }
}
