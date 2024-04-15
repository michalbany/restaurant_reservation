<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_id',
        'reservation_time',
        'guest_number',
        'note',
    ];

    /**
     * Rezervace patří k jednomu uživateli
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Rezervace patří k jednomu stolu
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
