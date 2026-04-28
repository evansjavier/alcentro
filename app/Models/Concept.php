<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Concept extends Model
{
    protected $fillable = [
        'name',
        'is_billable',
        'billing_period_months',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_billable' => 'boolean',
        'billing_period_months' => 'integer',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function scopeBillable(Builder $query): void
    {
        $query->where('is_billable', true);
    }

    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class)
            ->withPivot('billing_period_months', 'amount')
            ->withTimestamps();
    }
}
