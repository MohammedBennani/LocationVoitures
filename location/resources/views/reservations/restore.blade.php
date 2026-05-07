@extends('_layout')
@section('content')
    <table class="min-w-full bg-white rounded-xl overflow-hidden shadow">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-3 text-left">Client</th>
            <th class="px-4 py-3 text-left">Voiture</th>
            <th class="px-4 py-3 text-left">Date début</th>
            <th class="px-4 py-3 text-left">Date fin</th>
            <th class="px-4 py-3 text-center">Statut</th>
            <th class="px-4 py-3 text-center">Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($reservations as $reservation)
            <tr class="border-b hover:bg-gray-50">

                <td class="px-4 py-3">
                    {{ $reservation->client->full_name }}
                </td>

                <td class="px-4 py-3">
                    {{ $reservation->car->brand }}
                    {{ $reservation->car->model }}
                </td>

                <td class="px-4 py-3">
                    {{ $reservation->date_start }}
                </td>

                <td class="px-4 py-3">
                    {{ $reservation->date_end }}
                </td>

                <td class="px-4 py-3 text-center">
                    @if($reservation->deleted_at)
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                            Supprimée
                        </span>
                    @else
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            Active
                        </span>
                    @endif
                </td>

                <td class="px-4 py-3 text-center">

                    @if($reservation->deleted_at)

                        {{-- Restore --}}
                        <form action="{{ route('reservations.restore', $reservation->id) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            @method('PATCH')

                            <button
                                onclick="return confirm('Restaurer cette réservation ?')"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg">
                                Restaurer
                            </button>
                        </form>

                        {{-- Force Delete --}}
                        <form action="{{ route('reservations.forceDelete', $reservation->id) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            @method('DELETE')

                            <button
                                onclick="return confirm('Supprimer définitivement ?')"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                Supprimer définitivement
                            </button>
                        </form>

                    @else

                        {{-- Soft Delete --}}
                        <form action="{{ route('reservations.destroy', $reservation->id) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            @method('DELETE')

                            <button
                                onclick="return confirm('Supprimer cette réservation ?')"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                                Supprimer
                            </button>
                        </form>

                    @endif

                </td>
            </tr>

        @empty
            <tr>
                <td colspan="6" class="text-center py-6 text-gray-500">
                    Aucune réservation trouvée
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection