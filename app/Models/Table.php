<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Table extends Model
{
    use HasFactory;


    protected $fillable = [
        'capacity',
    ];


    /**
     * Stůl může mít více rezervací
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function free(\DateTime $dateTime, int $capacity)
    {
        // Vrátí pouze stoly, které nemají rezervaci v daném časovém úseku
        return $this->where('capacity', '>=', $capacity)
            ->whereDoesntHave('reservations', function (Builder $query) use ($dateTime) {
                // Využití whereRaw pro správné zpracování SQL výrazu
                $query->whereRaw('(? BETWEEN reservation_time AND DATE_ADD(reservation_time, INTERVAL 3 HOUR))', [$dateTime]);
            })
            ->get();
    }
    
}
