<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string $user_id
 * @property string $game_link_id
 * @property int $random_number
 * @property bool $result
 * @property float $win_amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class GameResult extends Model
{
    use HasFactory, HasVersion4Uuids;
    //
    protected $fillable = [
        'user_id',
        'game_link_id',
        'random_number',
        'result',
        'win_amount',
    ];

    protected $casts = [
        'result' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(GameLink::class, 'game_link_id');
    }
}
