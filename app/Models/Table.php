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

    /**
     * Hledáme stoly, kde čas plánované rezervace se nepřekrývá s již existující rezervací nebo na stole nezačíná rezervace do 3 hodin po začátku plánované rezervace
     * @param \DateTime $dateTime Datum a čas plánované rezervace
     * @param int $capacity Počet osob, kteří chtějí stůl rezervovat
     * @return Seznam stolů, které jsou k dispozici pro rezervaci
     */
    public function free(\DateTime $dateTime, int $capacity)
    {
        // Vypočítáme časové okno, kdy stůl nesmí mít žádnou rezervaci
        $endReservationTime = (clone $dateTime)->modify('+3 hours');

        return $this->where('capacity', '>=', $capacity)
            ->whereDoesntHave('reservations', function (Builder $query) use ($dateTime, $endReservationTime) {
                $query->where(function (Builder $subQuery) use ($dateTime, $endReservationTime) {
                    // Kontrola, že zadaný čas rezervace nespadá do již existující rezervace až do 3 hodin po jejím začátku
                    $subQuery->whereRaw('(? BETWEEN reservation_time AND DATE_ADD(reservation_time, INTERVAL 3 HOUR))', [$dateTime]);
                })
                ->orWhere(function (Builder $subQuery) use ($dateTime, $endReservationTime) {
                    // Kontrola, že žádná existující rezervace nezačne do 3 hodin po začátku požadované rezervace
                    $subQuery->where('reservation_time', '>', $dateTime)
                            ->where('reservation_time', '<', $endReservationTime);
                });
            })
            ->get();


    }

    
}
