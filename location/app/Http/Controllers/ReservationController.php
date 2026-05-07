<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Les imports indispensables que j'avais oubliés :
use App\Models\Reservation;
use App\Models\Car;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // On récupère les réservations avec les infos du client et de la voiture
    // pour éviter le problème du "N+1 query"N
    $reservations = Reservation::all();
    // dd($reservations);
    return view('reservations.index', compact('reservations'));
}

    public function create()
    {
        // On ne récupère que les voitures 'disponible'
        return view('reservations.create');
    }

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

    $car = Car::where('registration', $request->registration)->firstOrFail();
    $client = Client::where('national_id', $request->national_id)->firstOrFail();

    if ($car->status === 'loué') {
        return back()->with('error', 'Cette voiture est déjà louée');
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

        $car->update(['status' => 'loué']);
    });

    return redirect()
        ->route('reservations.index')
        ->with('success', 'Contrat créé !');
}



// Affiche le formulaire d'édition
    public function edit(Reservation $reservation)
    {
        // On a besoin de renvoyer la liste des clients et voitures pour les menus déroulants
        $clients = Client::all();
        $cars = Car::all();
        
        return view('reservations.edit', compact('reservation', 'clients', 'cars'));
    }

// Enregistre les modifications
    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'car_id' => 'required|exists:cars,id',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after:date_start',
            'price' => 'required|numeric|min:0',
        ]);

        // On met à jour les données
        $reservation->update($request->all());

        return redirect()->route('reservations.index')
                        ->with('success', 'La réservation a été modifiée avec succès.');
    }

    public function show($id)
{
    // On récupère la réservation avec ses relations
    $reservation = Reservation::with(['client', 'car'])->findOrFail($id);

    return view('reservations.show', compact('reservation'));
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}