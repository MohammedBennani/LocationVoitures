@extends('_layout')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Liste des Clients</h2>
    <a href="{{ route('clients.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700">
        <i data-lucide="user-plus" class="w-5 h-5"></i> Nouveau Client
    </a>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50/50 border-b border-gray-200">
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">CIN</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">N° Permis</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Ville</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($clients as $client)
            <tr class="hover:bg-blue-50/30 transition-colors duration-200">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs mr-3">
                            {{ substr($client->name, 0, 1) }}
                        </div>
                        <span class="font-semibold text-gray-900">{{ $client->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-md text-sm font-mono tracking-tighter">
                        {{ $client->national_id }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-600 text-sm italic">
                    {{ $client->license_number }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-1 text-gray-400"></i>
                        {{ $client->city }}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('clients.show', $client->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm text-xs font-medium">
                            <i data-lucide="folder-open" class="w-3.5 h-3.5 mr-1.5"></i>
                            Dossier
                        </a>
                        
                        <a href="{{ route('clients.edit', $client->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-100 rounded-lg hover:bg-amber-100 transition-all text-xs font-medium">
                            <i data-lucide="edit-2" class="w-3.5 h-3.5 mr-1.5"></i>
                            Modifier
                        </a>

                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce client ?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg hover:bg-rose-600 hover:text-white transition-all group" title="Supprimer">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $clients->links() }}
    </div>
</div>
@endsection