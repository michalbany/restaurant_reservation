<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Table;

class TableController extends Controller
{
    public function index(Request $request)
    {
        $table = new Table();
        $date = $request->input('date');
        $time = $request->input('time');
        $capacity = $request->input('capacity');
        
        $freeTables = [];

        /**
         * Přidat validaci, jako že je datum v minulosti a podobně
         */
        
        if ($date && $time && $capacity) {
            
            // $request->validate([
            //     'date' => 'required|date_format:Y-m-d',
            //     'time' => 'required|date_format:H:i',
            //     'capacity' => 'required|integer|min:1'
            // ]);


            $dateTimeStrig = $date . ' ' . $time;
            $dateTime = \DateTime::createFromFormat('Y-m-d H:i', $dateTimeStrig);

            $freeTables = $table->free($dateTime, $capacity);
            
        }

        return Inertia::render('Dashboard', [
            'tables' => fn () => Table::all(),
            'freeTables' => Inertia::lazy(fn () => $freeTables ?? []),
        ]);
    }
}
