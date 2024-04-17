<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Validation\ValidationException;


class ReservationController extends Controller
{
    /**
     * Uloží novou rezervaci
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {   
        try {
            $request->validate([
                'date' => 'required|date_format:Y-m-d',
                'time' => 'required|date_format:H:i',
                'capacity' => 'required|integer|min:1',
                'tableId' => 'required|integer|exists:tables,id',
                'note' => 'nullable|string|max:255'
            ]);
        } catch (ValidationException $e) {
            // Mohli bychom vrátit error pro každé pole, ale při VeeValidate to není nutně potřeba
            return redirect()->back()->with('error', 'Rezervaci se nepodařila vytvořit.');
        }
            
        Reservation::create([
            'user_id' => auth()->id(),
            'table_id' => $request->input('tableId'),
            'reservation_time' => $request->input('date') . ' ' . $request->input('time'),
            'guest_number' => $request->input('capacity'),
            'note' => $request->input('note')
        ]);

        return redirect()->back()->with('success', 'Rezervace byla úspěšně vytvořena.');
    }

    /**
     * Vrátí seznam rezervací pro uživatele
     * @param User $user
     * @return Inertia\Response
     */
    public function index()
    {
        $reservations = Reservation::where('user_id', auth()->id())->get();

        return Inertia::render('Reservations/Index', [
            'reservations' => $reservations
        ]);
    }
}
