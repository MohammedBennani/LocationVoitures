@extends('_layout')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-6">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Historique des Maintenances
            </h2>

            <p class="text-gray-600 text-sm">
                {{ $car->brand }} {{ $car->model }}
                - {{ $car->registration }}
            </p>
        </div>

        <a href="{{ route('maintenance.create', $car->id) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 shadow">
            Nouvelle Maintenance
        </a>

    </div>

    {{-- FILTER DATE --}}
    <form method="GET"
          action="{{ route('maintenance.index', $car->id) }}"
          class="mb-5">

        <div class="flex gap-3 items-end flex-wrap">

            {{-- DATE START --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">
                    Date début
                </label>

                <input type="date"
                       name="date_start"
                       value="{{ request('date_start') }}"
                       class="border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- DATE END --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">
                    Date fin
                </label>

                <input type="date"
                       name="date_end"
                       value="{{ request('date_end') }}"
                       class="border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- BUTTON --}}
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">
                Filtrer
            </button>

            {{-- RESET --}}
            @if(request('date_start') || request('date_end'))
                <a href="{{ route('maintenance.index', $car->id) }}"
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
                    <th class="p-4">Date</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Description</th>
                    <th class="p-4">Kilométrage</th>
                    <th class="p-4">Coût</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>

            <tbody>

            @forelse($maintenances as $maintenance)

                <tr class="border-b hover:bg-gray-50">

                    {{-- DATE --}}
                    <td class="p-4">
                        {{ \Carbon\Carbon::parse($maintenance->maintenance_date)->format('d/m/Y') }}
                    </td>

                    {{-- TYPE --}}
                    <td class="p-4">
                        {{ $maintenance->type }}
                    </td>

                    {{-- DESCRIPTION --}}
                    <td class="p-4">
                        {{ $maintenance->description }}
                    </td>

                    {{-- KM --}}
                    <td class="p-4">
                        {{ number_format($maintenance->mileage) }} KM
                    </td>

                    {{-- COST --}}
                    <td class="p-4 font-bold">
                        {{ number_format($maintenance->cost, 2) }} DH
                    </td>

                    {{-- ACTIONS --}}
                    <td class="p-4">

                        <div class="flex gap-2">

                            {{-- EDIT --}}
                            <a href="{{ route('maintenance.edit', $maintenance->id) }}"
                               class="bg-yellow-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-yellow-600">
                                Modifier
                            </a>

                            {{-- DELETE --}}
                            <form action="{{ route('maintenance.destroy', $maintenance->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Supprimer cette maintenance ?')"
                                    class="bg-red-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-red-600">
                                    Supprimer
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center py-10 text-gray-500">
                        Aucune maintenance trouvée
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection