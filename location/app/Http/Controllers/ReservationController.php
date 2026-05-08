<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Car;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Liste des réservations
     */
public function index(Request $request)
{
    $query = Reservation::withTrashed()
        ->with(['client', 'car']);

    // 🔹 Filter statut
    if ($request->filter === 'historique') {
        $query->onlyTrashed();
    } elseif ($request->filter === 'active') {
        $query->whereNull('deleted_at');
    }

    // 🔹 Filter CIN (national_id)
    if ($request->cin) {
        $query->whereHas('client', function ($q) use ($request) {
            $q->where('national_id', 'like', '%' . $request->cin . '%');
        });
    }

    $reservations = $query->latest()->get();

    return view('reservations.index', compact('reservations'));
}
    /**
     * Formulaire création
     */
    public function create()
    {
        return view('reservations.create');
    }

    /**
     * Enregistrer réservation
     */
    public function store(Request $request)
    {
        $request->validate([
            'national_id' => 'required|exists:clients,national_id',
            'registration' => 'required|exists:cars,registration',
            'date_start' => 'required|date|after_or_equal:today',
            'date_end' => 'required|date|after:date_start',
            'price' => 'required|numeric',
        ], [
            'national_id.required' => 'Le numéro d’identification est obligatoire',
            'national_id.exists' => 'Client introuvable',

            'registration.required' => 'La plaque du véhicule est obligatoire',
            'registration.exists' => 'Voiture introuvable',

            'date_start.required' => 'La date de début est obligatoire',
            'date_start.date' => 'Format de date invalide',
            'date_start.after_or_equal' => 'La date de début doit être aujourd’hui ou après',

            'date_end.required' => 'La date de fin est obligatoire',
            'date_end.date' => 'Format de date invalide',
            'date_end.after' => 'La date de fin doit être après la date de début',

            'price.required' => 'Le prix est obligatoire',
            'price.numeric' => 'Le prix doit être un nombre valide',
        ]);

        $car = Car::where('registration', $request->registration)
            ->firstOrFail();

        $client = Client::where('national_id', $request->national_id)
            ->firstOrFail();

        if ($car->status === 'loué') {
            return back()
                ->withErrors([
                    'registration' => 'Cette voiture est déjà louée'
                ])
                ->withInput();
        }

        DB::transaction(function () use ($request, $car, $client) {

            Reservation::create([
                'user_id' => Auth::id(),
                'client_id' => $client->id,
                'car_id' => $car->id,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'price' => $request->price,
            ]);

            $car->update([
                'status' => 'loué'
            ]);
        });

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Contrat créé avec succès');
    }

    /**
     * Détails réservation
     */
    public function show(string $id)
    {
        $reservation = Reservation::withTrashed()
            ->with(['client', 'car'])
            ->findOrFail($id);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Soft delete
     */
    public function destroy(string $id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->delete();

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Réservation supprimée avec succès');
    }

    /**
     * Restaurer réservation
     */
    public function restore(string $id)
    {
        $reservation = Reservation::withTrashed()
            ->findOrFail($id);

        $reservation->restore();

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Réservation restaurée avec succès');
    }

    /**
     * Suppression définitive
     */
    public function forceDelete(string $id)
    {
        $reservation = Reservation::withTrashed()
            ->findOrFail($id);

        $reservation->forceDelete();

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Réservation supprimée définitivement');
    }
}