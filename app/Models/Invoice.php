<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_PAID = 'paid';

    protected $fillable = [
        'client_id',
        'period',
        'total_amount',
        'paid_amount',
        'due_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function recalculateStatus(): void
    {
        $paidAmount = $this->payments()->sum('amount_received');
        $this->paid_amount = $paidAmount;

        if ($this->paid_amount >= $this->total_amount) {
            $this->status = self::STATUS_PAID;
        } elseif ($this->paid_amount > 0) {
            $this->status = self::STATUS_PARTIAL;
        } else {
            $this->status = self::STATUS_PENDING;
        }

        $this->save();
    }

    public function generateItemsFromContract(Contract $contract, string $period): float
    {
        $premiseCode = $contract->premise?->code ?? 'Local';
        $totalAmount = 0;

        // 1. Renta
        if ($contract->rent_amount > 0) {
            $this->items()->create([
                'contract_id' => $contract->id,
                'type' => InvoiceItem::TYPE_RENT,
                'description' => "Renta {$premiseCode} - Periodo {$period}",
                'amount' => $contract->rent_amount
            ]);
            $totalAmount += $contract->rent_amount;
        }

        // 2. Mantenimiento
        if ($contract->maintenance_pct > 0) {
            $maintenanceAmount = ($contract->rent_amount * $contract->maintenance_pct) / 100;
            $this->items()->create([
                'contract_id' => $contract->id,
                'type' => InvoiceItem::TYPE_MAINTENANCE,
                'description' => "Mantenimiento {$premiseCode} ({$contract->maintenance_pct}%)",
                'amount' => $maintenanceAmount
            ]);
            $totalAmount += $maintenanceAmount;
        }

        // 3. Publicidad
        if ($contract->advertising_pct > 0) {
            $advertisingAmount = ($contract->rent_amount * $contract->advertising_pct) / 100;
            $this->items()->create([
                'contract_id' => $contract->id,
                'type' => InvoiceItem::TYPE_ADVERTISING,
                'description' => "Publicidad {$premiseCode} ({$contract->advertising_pct}%)",
                'amount' => $advertisingAmount
            ]);
            $totalAmount += $advertisingAmount;
        }

        return $totalAmount;
    }
}
