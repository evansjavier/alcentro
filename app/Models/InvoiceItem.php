<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    public const TYPE_RENT = 'rent';
    public const TYPE_MAINTENANCE = 'maintenance';
    public const TYPE_ADVERTISING = 'advertising';
    public const TYPE_UTILITIES = 'utilities';
    public const TYPE_LATE_FEE = 'late_fee';

    protected $fillable = [
        'invoice_id',
        'contract_id',
        'concept_id',
        'type',
        'description',
        'amount',
    ];

    public static function getTypeLabels(): array
    {
        return [
            self::TYPE_RENT => 'Renta',
            self::TYPE_MAINTENANCE => 'Mantenimiento',
            self::TYPE_ADVERTISING => 'Publicidad',
            self::TYPE_UTILITIES => 'Servicios',
            self::TYPE_LATE_FEE => 'Mora',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return static::getTypeLabels()[$this->type] ?? $this->type;
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }
}
