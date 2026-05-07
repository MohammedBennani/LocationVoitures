@extends('_layout')

@section('content')

<div class="flex justify-between items-start mb-6">

    <div>
        <h2 class="text-2xl font-bold text-gray-800">Gestion des Contrats</h2>
        <p class="text-gray-600 text-sm">
            Liste des réservations avec filtres avancés
        </p>
    </div>

    <div class="flex gap-3 flex-wrap">

        {{-- Tous --}}
        <a href="{{ route('reservations.index') }}"
           class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 shadow">
            Tous
        </a>

        {{-- Actives --}}
        <a href="{{ route('reservations.index', ['filter' => 'active']) }}"
           class="bg-yellow-500 text-white px-4 py-2 rounded-xl hover:bg-yellow-600 shadow flex items-center gap-2">
            Actives
        </a>

        {{-- Historique --}}
        <a href="{{ route('reservations.index', ['filter' => 'historique']) }}"
           class="bg-gray-700 text-white px-4 py-2 rounded-xl hover:bg-gray-800 shadow flex items-center gap-2">
            Historique
        </a>

        {{-- Nouveau --}}
        <a href="{{ route('reservations.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 shadow flex items-center gap-2">
            Nouveau
        </a>

    </div>

</div>

{{-- 🔎 SEARCH CIN --}}
<form method="GET" action="{{ route('reservations.index') }}" class="mb-5">

    <div class="flex gap-2 items-center">

        <input type="text"
               name="cin"
               value="{{ request('cin') }}"
               placeholder="Recherche par CIN..."
               class="border border-gray-300 rounded-xl px-4 py-2 w-72 focus:ring-2 focus:ring-blue-500">

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">
            Rechercher
        </button>

        @if(request('cin'))
            <a href="{{ route('reservations.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-xl hover:bg-gray-600">
                Reset
            </a>
        @endif

    </div>

</form>

{{-- TABLE --}}
<div class="bg-white shadow-xl rounded-2xl overflow-hidden border">

    <table class="w-full text-left border-collapse">

        <thead class="bg-gray-50">
            <tr>
                <th class="p-4">Réf</th>
                <th class="p-4">Client</th>
                <th class="p-4">Voiture</th>
                <th class="p-4">Période</th>
                <th class="p-4">Prix</th>
                <th class="p-4">Statut</th>
                <th class="p-4">Actions</th>
            </tr>
        </thead>

        <tbody>

        @forelse($reservations as $reservation)

            <tr class="border-b hover:bg-gray-50">

                {{-- REF --}}
                <td class="p-4 font-mono text-blue-600 font-bold">
                    #RES-{{ $reservation->id }}
                </td>

                {{-- CLIENT --}}
                <td class="p-4">
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-800">
                            {{ $reservation->client->name }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $reservation->client->national_id }}
                        </span>
                    </div>
                </td>

                {{-- CAR --}}
                <td class="p-4">
                    {{ $reservation->car->brand }} {{ $reservation->car->model }}
                    <div class="text-xs text-gray-500">
                        {{ $reservation->car->registration }}
                    </div>
                </td>

                {{-- PERIOD --}}
                <td class="p-4 text-sm">
                    <div class="text-green-600">
                        {{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y') }}
                    </div>
                    <div class="text-red-500">
                        {{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y') }}
                    </div>
                </td>

                {{-- PRICE --}}
                <td class="p-4 font-bold">
                    {{ number_format($reservation->price, 2) }} DH
                </td>

                {{-- STATUS --}}
                <td class="p-4">
                    @if($reservation->deleted_at)
                        <span class="px-3 py-1 text-xs bg-red-100 text-red-600 rounded-full">
                            Historique
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs bg-green-100 text-green-600 rounded-full">
                            Active
                        </span>
                    @endif
                </td>

                {{-- ACTIONS --}}
                <td class="p-4">

                    <div class="flex gap-2">

                        {{-- DETAIL --}}
                        <a href="{{ route('reservations.show', $reservation->id) }}"
                           class="bg-gray-500 text-white px-3 py-2 rounded-lg text-sm">
                            Detail
                        </a>

                        {{-- DELETED --}}
                        @if($reservation->deleted_at)

                            {{-- RESTORE --}}
                            <form action="{{ route('reservations.restore', $reservation->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <button class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm">
                                    Restore
                                </button>
                            </form>

                            {{-- FORCE DELETE --}}
                            <form action="{{ route('reservations.forceDelete', $reservation->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="bg-red-600 text-white px-3 py-2 rounded-lg text-sm">
                                    Delete
                                </button>
                            </form>

                        @else

                            {{-- SOFT DELETE --}}
                            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="bg-red-500 text-white px-3 py-2 rounded-lg text-sm">
                                    Supprimer
                                </button>
                            </form>

                        @endif

                    </div>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="7" class="text-center py-10 text-gray-500">
                    Aucune réservation trouvée
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection