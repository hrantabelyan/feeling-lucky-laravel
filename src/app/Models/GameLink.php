<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string $user_id
 * @property string $token
 * @property bool $is_active
 * @property Carbon $expired_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property User $user
 */
class GameLink extends Model
{
    use HasFactory, HasVersion4Uuids, SoftDeletes;
    //
    protected $fillable = [
        'user_id',
        'token',
        'expired_at',
        'is_active',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(GameResult::class);
    }

    public function scopeValid(Builder $query): void
    {
        $query->where('is_active', true)
              ->where('expired_at', '>', now());
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return $this->where('token', hash('sha256', $value))->where('is_active', true)->where('expired_at', '>', now())->firstOrFail();
    }
}
