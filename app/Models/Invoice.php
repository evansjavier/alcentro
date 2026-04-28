<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public const DOC_STATUS_DRAFT = 'draft';
    public const DOC_STATUS_ISSUED = 'issued';
    public const DOC_STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'client_id',
        'contract_id',
        'period',
        'total_amount',
        'paid_amount',
        'due_date',
        'status',
        'document_status',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    public function printDocumentStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->document_status) {
                self::DOC_STATUS_DRAFT => 'Borrador',
                self::DOC_STATUS_ISSUED => 'Emitida',
                self::DOC_STATUS_CANCELLED => 'Cancelada',
                default => $this->document_status,
            }
        );
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function approvedPayments(): HasMany
    {
        return $this->hasMany(Payment::class)->approved();
    }

    public function recalculateStatus(): void
    {
        $paidAmount = $this->approvedPayments()->sum('amount_received');
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
