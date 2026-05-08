@extends('_layout')

@section('content')
<form class="max-w-md mx-auto mb-6" method="GET" action="{{ route('cars') }}">

    <label class="sr-only">Search</label>

    <div class="relative">

        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
            <i data-lucide="search" class="w-4 h-4 text-gray-500"></i>
        </div>

        <input type="search"
            name="mat"
            value="{{ $mat }}"
            placeholder="Immatriculation..."
            class="block w-full p-3 pl-10 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

        <div class="absolute right-1.5 bottom-1.5 flex gap-1">

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-2.5 rounded-md">
                Chercher
            </button>

            <a href="{{ route('cars') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white text-xs px-3 py-2.5 rounded-md">
                Annuler
            </a>

        </div>

    </div>

</form>

    <a href="{{ route('cars.add') }}" style="margin-left: 25px" class="w-fit bg-blue-600 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 hover:bg-blue-700 shadow-lg transition-all">
    <i data-lucide="plus-circle" class="w-5 h-5"></i> Nouveau Voiture
</a>

<div class="p-4 sm:p-6">
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach ($cars as $car)
            <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

                <div class="h-48 w-full overflow-hidden">
                  <img src="{{ asset('storage/' . $car->image) }}"
                      class="w-full h-full object-cover hover:scale-105 transition duration-300">
              </div>

                <div class="p-4 space-y-2">

                    <h2 class="text-lg font-bold text-gray-800">
                        {{ $car->brand }} - {{ $car->model }}
                    </h2>

                    <p class="text-sm text-gray-600">
                        Année : {{ $car->year }}
                    </p>

                    <p class="text-sm text-gray-600">
                        Carburant : {{ $car->fuel_type }}
                    </p>

                    <p class="text-sm text-gray-600">
                        Kilométrage : {{ $car->mileage }} km
                    </p>

                    <p class="text-sm text-gray-600">
                        Statut : 
                        <span class="font-semibold 
                            {{ $car->status == 'disponible' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $car->status }}
                        </span>
                    </p>

                    <p class="text-blue-600 font-bold">
                        {{ $car->price_per_day }} MAD / jour
                    </p>

                    <p class="text-xs text-gray-400">
                        Immatriculation : {{ $car->registration }}
                    </p>
                    <a href="{{route('cars.show',$car->id)}}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm text-xs font-medium">
                    <i data-lucide="folder-open" class="w-3.5 h-3.5 mr-1.5"></i>
                        Détail
                    </a>
                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection