<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Premise extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'square_meters',
        'suggested_rent',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'square_meters' => 'decimal:2',
            'suggested_rent' => 'decimal:2',
        ];
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'contracts');
    }

    public function activeContract(): HasOne
    {
        return $this->hasOne(Contract::class)
            ->where('status', Contract::STATUS_ACTIVO)
            ->latestOfMany();
    }
}
