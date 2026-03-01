@extends('layout')

@section('title', 'Mes Colocations - EasyColoc')

@section('content')
<div class="max-w-6xl mx-auto mt-6 px-4 pb-12">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
        <div class="flex items-center gap-3">
            <div
                class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shadow-sm border border-blue-100">
                <i class="ph-fill ph-buildings text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Mes Colocations</h1>
                <p class="text-gray-500 font-medium mt-1">Historique complet de vos groupes.</p>
            </div>
        </div>
        <a href="{{ url('/colocations/create') }}"
            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
            <i class="ph-bold ph-plus"></i> Nouvelle Colocation
        </a>
    </div>

    @if($allColocations->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($allColocations as $coloc)
        @php
        $isOwner = isset($coloc->pivot) && $coloc->pivot->role === 'owner';
        $hasLeft = isset($coloc->pivot) && $coloc->pivot->left_at !== null;
        $isCancelled = $coloc->status === 'desactive';
        $isActive = !$isCancelled && !$hasLeft;
        @endphp

        <div
            class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100 flex flex-col hover:border-blue-200 hover:shadow-md transition-all relative overflow-hidden">

            <div class="absolute top-0 right-0">
                <span
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-bl-xl text-xs font-bold uppercase tracking-wide {{ $isOwner ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-600 border-b border-l border-gray-200' }}">
                    <i class="{{ $isOwner ? 'ph-bold ph-crown' : 'ph-bold ph-user' }}"></i>
                    {{ $isOwner ? 'Admin' : 'Membre' }}
                </span>
            </div>

            <div class="flex justify-between items-start mb-4 mt-2">
                <div
                    class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-600 border border-gray-200">
                    <i class="ph-fill ph-house text-xl"></i>
                </div>

                <div class="pr-16">
                    @if($isCancelled)
                    <span
                        class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide flex items-center gap-1.5">
                        <i class="ph-bold ph-x"></i> Annulée
                    </span>
                    @elseif($hasLeft)
                    <span
                        class="bg-gray-100 text-gray-600 border border-gray-200 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide flex items-center gap-1.5">
                        <i class="ph-bold ph-clock-counter-clockwise"></i> Quittée
                    </span>
                    @else
                    <span
                        class="bg-green-50 text-green-600 border border-green-200 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                    </span>
                    @endif
                </div>
            </div>

            <h3 class="text-lg font-black text-gray-900 mb-1 truncate">{{ $coloc->name }}</h3>

            <div class="mb-6 space-y-1">
                <p class="text-sm text-gray-500 font-medium flex justify-between">
                    <span>{{ $isOwner ? 'Créée le :' : 'Rejointe le :' }}</span>
                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($isOwner ? $coloc->created_at :
                        $coloc->pivot->created_at)->format('d/m/Y') }}</span>
                </p>
                @if($hasLeft)
                <p class="text-sm text-red-500 font-medium flex justify-between">
                    <span>Quittée le :</span>
                    <span>{{ \Carbon\Carbon::parse($coloc->pivot->left_at)->format('d/m/Y') }}</span>
                </p>
                @endif
            </div>

            <div class="mt-auto pt-4 border-t border-gray-100 flex gap-2">
                @if($isOwner && $isActive)
                <a href="{{ url('/colocations/' . $coloc->id . '/settings') }}"
                    class="flex-1 flex items-center justify-center gap-1.5 text-center bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold py-2.5 px-3 rounded-xl transition-colors text-sm border border-gray-200">
                    <i class="ph-bold ph-gear"></i> Paramètres
                </a>
                @endif

                <a href="{{ route('colocations.show', $coloc->id) }}"
                    class="flex-1 flex items-center justify-center gap-1.5 text-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold py-2.5 px-3 rounded-xl transition-colors text-sm border border-blue-200">
                    <i class="ph-bold ph-arrow-right"></i> Accéder
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div
        class="bg-white border border-gray-200 border-dashed rounded-2xl p-12 text-center flex flex-col items-center justify-center shadow-sm">
        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 mb-4">
            <i class="ph-fill ph-magnifying-glass text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-1">Aucune colocation</h3>
        <p class="text-gray-500 font-medium">Vous n'avez pas encore créé ou rejoint de colocation.</p>
    </div>
    @endif

</div>
@endsection