<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'phone',
        'email',
        'notes',
        'role',
    ];

    public const ROLES = [
        'Dueño',
        'Encargado',
        'Fiador',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
