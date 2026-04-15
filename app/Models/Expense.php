<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_concept_id',
        'amount',
        'expense_date',
        'payment_method',
        'reference_number',
        'notes',
        'attachment_path',
        'user_id',
        'is_approved',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public static array $paymentMethods = [
        'transferencia' => 'Transferencia',
        'efectivo' => 'Efectivo',
        'cheque' => 'Cheque',
        'tarjeta' => 'Tarjeta',
        'otro' => 'Otro',
    ];

    public function concept()
    {
        return $this->belongsTo(ExpenseConcept::class, 'expense_concept_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
