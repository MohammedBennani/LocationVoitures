@extends('_layout')

@section('content')
<style>
    /* --- Styles Spécifiques pour l'Impression --- */
    @media print {
        /* 1. Cacher tout ce qui n'est pas le contrat */
        aside, header, footer, nav, .no-print, .back-button, .action-buttons {
            display: none !important;
        }

        /* 2. Réinitialiser les marges pour le papier A4 */
        body {
            background-color: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .main-container {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 20px !important;
            box-shadow: none !important;
            border: none !important;
        }

        /* 3. Forcer les couleurs à l'impression */
        .bg-blue-600 {
            -webkit-print-color-adjust: exact;
            background-color: #2563eb !important;
            color: white !important;
        }
        .bg-gray-100, .bg-blue-50, .bg-gray-50 {
            -webkit-print-color-adjust: exact;
            background-color: #f3f4f6 !important;
        }

        /* 4. Ajuster le texte */
        .text-white { color: white !important; }
        .rounded-3xl { border-radius: 12px !important; }
        
        /* Afficher l'en-tête de contrat */
        .print-only-header { display: flex !important; }
        .print-only-footer { display: grid !important; }
    }

    /* Styles cachés sur l'écran mais visibles à l'impression */
    .print-only-header { display: none; }
    .print-only-footer { display: none; }
</style>

<div class="max-w-4xl mx-auto main-container">
    
    <div class="print-only-header justify-between items-center border-b-2 border-gray-200 pb-6 mb-8">
        <div>
            <h1 class="text-3xl font-black text-blue-600 italic tracking-tighter">AUTOLOC</h1>
            <p class="text-sm text-gray-500 font-bold">Agence de Location de Véhicules</p>
            <p class="text-xs text-gray-400">Sefrou, Fez-Meknès, Maroc | +212 600 000 000</p>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-bold uppercase tracking-widest text-gray-800">Contrat de Location</h2>
            <p class="text-sm font-medium text-gray-600">Référence : #{{ strtoupper(substr($reservation->id, 0, 8)) }}</p>
            <p class="text-sm text-gray-500 font-medium">Date d'édition : {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <div class="flex items-center justify-between mb-8 no-print">
        <div class="flex items-center gap-4">
            <a href="{{ route('reservations.index') }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-100 transition-colors back-button">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Détails du Contrat</h2>
        </div>
        
        <div class="flex gap-3 action-buttons">
            <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium hover:bg-gray-50 transition-all shadow-sm">
                <i data-lucide="printer" class="w-4 h-4 text-gray-600"></i> Imprimer
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="md:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="text-blue-500 w-5 h-5"></i> Informations Client
                </h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Nom Complet</p>
                        <p class="text-gray-800 font-semibold">{{ $reservation->client->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">CIN / Passeport</p>
                        <p class="text-gray-800 font-semibold">{{ $reservation->client->national_id }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="car" class="text-blue-500 w-5 h-5"></i> Détails du Véhicule
                </h3>
                <div class="flex items-center gap-6">
                    <div class="p-4 bg-blue-50 rounded-2xl">
                        <i data-lucide="car" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <div class="grid grid-cols-2 flex-1 gap-4">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Modèle</p>
                            <p class="text-gray-800 font-semibold">{{ $reservation->car->brand }} {{ $reservation->car->model }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Immatriculation</p>
                            <span class="inline-block px-2 py-1 bg-gray-100 border border-gray-200 rounded text-sm font-mono tracking-wider font-bold text-gray-700">
                                {{ $reservation->car->registration }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-blue-600 p-6 rounded-3xl shadow-lg text-white">
                <h3 class="text-xs font-bold opacity-80 mb-6 uppercase tracking-widest">Résumé financier</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b border-blue-500/50 pb-2">
                        <span class="text-sm opacity-90 font-medium">Début</span>
                        <span class="font-bold tracking-tight text-sm">{{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-blue-500/50 pb-2">
                        <span class="text-sm opacity-90 font-medium">Fin</span>
                        <span class="font-bold tracking-tight text-sm">{{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-blue-500/50 pb-2">
                        <span class="text-sm opacity-90 font-medium">Durée</span>
                        <span class="font-bold text-sm">
                            {{ \Carbon\Carbon::parse($reservation->date_start)->diffInDays($reservation->date_end) }} jours
                        </span>
                    </div>
                    <div class="pt-4">
                        <p class="text-[10px] opacity-70 uppercase font-black mb-1">Total TTC à régler</p>
                        <p class="text-4xl font-black tracking-tighter italic">
                            {{ number_format($reservation->price, 2) }} 
                            <span class="text-base font-normal">DH</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 text-center">
                 @php
                    $now = now();
                    $start = \Carbon\Carbon::parse($reservation->date_start);
                    $end = \Carbon\Carbon::parse($reservation->date_end);
                 @endphp

                 @if($now->between($start, $end))
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-green-50 text-green-700 text-xs font-black uppercase tracking-widest border border-green-100">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> En cours
                    </span>
                 @elseif($now->lt($start))
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-black uppercase tracking-widest border border-blue-100">
                         Réservé
                    </span>
                 @else
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-gray-50 text-gray-600 text-xs font-black uppercase tracking-widest border border-gray-200">
                         Terminé
                    </span>
                 @endif
            </div>
        </div>
    </div>


    <div class="print-only-footer mt-10 text-[10px] text-gray-400 leading-tight">
        Ce document fait office de contrat de location officiel. En cas de litige, les tribunaux de la juridiction de l'agence sont seuls compétents. Le véhicule doit être rendu avec le même niveau de carburant qu'au départ.
    </div>

    <div class="print-only-footer grid grid-cols-2 gap-24 mt-24 px-10">
        <div class="text-center">
            <p class="text-sm font-bold text-gray-800 mb-20 uppercase tracking-widest">Le Locataire</p>
            <div class="border-t border-gray-300 pt-2 italic text-xs text-gray-400">
                Signature précédée de la mention "Lu et approuvé"
            </div>
        </div>
    <div class="text-center">
        <p class="text-sm font-bold text-gray-800 mb-20 uppercase tracking-widest">L'Agence AutoLoc</p>
        <div class="border-t border-gray-300 pt-2 italic text-xs text-gray-400">
            Cachet et signature du responsable
        </div>
    </div>
</div>

    
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endsection