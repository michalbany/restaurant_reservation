<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Table;
use Dotenv\Exception\ValidationException;

class TableController extends Controller
{

    /**
     * Vrací raw seznam stolů, které jsou k dispozici v restauraci
     * Při filtraci vrací seznam volných stolů
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $table = new Table();

        if ($request->has('date', 'time', 'capacity')) {
            $validated = $request->validate([
                'date' => 'required|date_format:Y-m-d|after_or_equal:today',
                'time' => 'required|date_format:H:i',
                'capacity' => 'required|integer|min:1|max:10',
            
            ]);
            $dateTime = \DateTime::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time']);
            $freeTables = $table->free($dateTime, $validated['capacity']);
        }

        return Inertia::render('Dashboard', [
            'tables' => fn () => Table::all(),
            'freeTables' => Inertia::lazy(fn () => $freeTables ?? []),
        ]);
    }
}
