<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'amount_received',
        'payment_date',
        'method',
        'is_taxable',
        'reference_number',
        'notes',
        'is_approved',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'amount_received' => 'decimal:2',
            'payment_date' => 'date',
            'is_taxable' => 'boolean',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
