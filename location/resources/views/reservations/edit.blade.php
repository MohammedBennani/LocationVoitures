@extends('_layout')

@section('content')

<div class="max-w-4xl mx-auto">

    <!-- Titre -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Modifier le Contrat</h2>
        <p class="text-gray-600">Mettez à jour les informations de la réservation.</p>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">

        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- CIN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Client (CIN / ID)
                    </label>

                    <input type="text" name="national_id"
                        value="{{ old('national_id', $reservation->client->national_id) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 bg-gray-50
                        @error('national_id') border-red-500 @enderror">

                    @error('national_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Registration -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Véhicule (Plaque)
                    </label>

                    <input type="text" name="registration"
                        value="{{ old('registration', $reservation->car->registration) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 bg-gray-50
                        @error('registration') border-red-500 @enderror">

                    @error('registration')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Start -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Date de début
                    </label>

                    <input type="date" name="date_start" id="date_start"
                        value="{{ old('date_start', $reservation->date_start) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 bg-gray-50
                        @error('date_start') border-red-500 @enderror">

                    @error('date_start')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date End -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Date de fin
                    </label>

                    <input type="date" name="date_end" id="date_end"
                        value="{{ old('date_end', $reservation->date_end) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 bg-gray-50
                        @error('date_end') border-red-500 @enderror">

                    @error('date_end')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix par jour -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Prix par jour (DH)
                    </label>

                    <input type="number" name="daily_price" id="daily_price"
                        value="{{ old('daily_price', $reservation->daily_price) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 bg-gray-50
                        @error('daily_price') border-red-500 @enderror">

                    @error('daily_price')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Price -->
                <div class="col-span-2 bg-blue-50 p-4 rounded-xl border border-blue-100 mt-2">

                    <label class="block text-sm font-semibold text-blue-800 mb-2">
                        Prix Total (DH)
                    </label>

                    <input type="number" name="price" id="total_price"
                        step="0.01" readonly
                        value="{{ old('price', $reservation->price) }}"
                        class="w-full bg-white border-blue-200 rounded-lg text-xl font-bold text-blue-700 p-3 shadow-inner cursor-not-allowed
                        @error('price') border-red-500 @enderror">

                    @error('price')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <p class="text-xs text-blue-600 mt-2 italic">
                        * Le prix est calculé automatiquement selon la durée.
                    </p>
                </div>

            </div>

            <!-- Buttons -->
            <div class="mt-8 flex items-center justify-end gap-4 border-t pt-6">

                <a href="{{ route('reservations.index') }}"
                   class="text-gray-600 hover:text-gray-800 font-medium">
                    Annuler
                </a>

                <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-all transform hover:scale-105">
                    Modifier le Contrat
                </button>

            </div>

        </form>

    </div>
</div>

<!-- SCRIPT CALCUL AUTOMATIQUE -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const dailyPrice = document.getElementById('daily_price');
    const dateStart = document.getElementById('date_start');
    const dateEnd = document.getElementById('date_end');
    const totalPrice = document.getElementById('total_price');

    function calculateTotal() {

        const price = parseFloat(dailyPrice.value) || 0;

        const start = new Date(dateStart.value);
        const end = new Date(dateEnd.value);

        if (start && end && end > start && price > 0) {

            const diffTime = end - start;
            const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            const total = days * price;

            totalPrice.value = total.toFixed(2);

        } else {
            totalPrice.value = 0;
        }
    }

    [dailyPrice, dateStart, dateEnd].forEach(el => {
        el.addEventListener('input', calculateTotal);
        el.addEventListener('change', calculateTotal);
    });

});
</script>

@endsection