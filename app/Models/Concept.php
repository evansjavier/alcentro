<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
