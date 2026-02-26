@extends('layout')

@section('title', 'Ma Colocation - ' . $colocation->name)

@section('content')
<div class="max-w-7xl mx-auto mt-6 px-4 pb-12">

    <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100 overflow-hidden mb-8">
        <div
            class="bg-gray-900 p-6 sm:px-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-900/20">
                    <i class="ph-fill ph-buildings text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">{{ $colocation->name }}</h1>
                    <p class="text-blue-200/70 font-medium">Gérez vos dépenses communes et vos colocataires.</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <a href="{{ url('/balances') }}"
                    class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold py-2.5 px-5 rounded-xl transition-all border border-white/10">
                    <i class="ph-bold ph-chart-bar text-xl"></i>
                    <span>Balances</span>
                </a>

                @if(Auth::id() === $colocation->owner_id)
                <a href="{{ url('/colocations/' . $colocation->id . '/settings') }}"
                    class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-sm">
                    <i class="ph-bold ph-gear text-xl"></i>
                    <span>Paramètres</span>
                </a>
                @else
                <form action="{{ url('/colocations/leave') }}" method="POST"
                    onsubmit="return confirm('Voulez-vous vraiment quitter cette colocation ?');"
                    class="flex-1 md:flex-none">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 font-bold py-2.5 px-5 rounded-xl transition-all">
                        <i class="ph-bold ph-door-open text-xl"></i>
                        <span>Quitter</span>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                        <i class="ph-fill ph-users text-blue-500"></i> Membres
                    </h2>
                    <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2 py-1 rounded-md">{{
                        $colocation->activeMembers->count() }}</span>
                </div>

                <div class="space-y-4">
                    @foreach($colocation->activeMembers as $member)
                    <div
                        class="group flex items-center justify-between p-3 rounded-xl border border-transparent hover:border-gray-100 hover:bg-gray-50 transition-all">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold border border-blue-200">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 leading-tight">
                                    {{ $member->name }}
                                    @if($member->id === Auth::id()) <span
                                        class="text-blue-500 text-[10px] font-black uppercase ml-1">(Moi)</span> @endif
                                </p>
                                <p class="text-xs font-medium text-gray-400 mt-0.5">
                                    Réputation:
                                    <span
                                        class="{{ $member->reputation_score >= 0 ? 'text-green-500' : 'text-red-500' }} font-bold">
                                        {{ $member->reputation_score > 0 ? '+' : '' }}{{ $member->reputation_score }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <span
                            class="text-[10px] font-black px-2 py-1 rounded-lg uppercase tracking-wider {{ $member->id === $colocation->owner_id ? 'bg-purple-50 text-purple-600 border border-purple-100' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                            {{ $member->id === $colocation->owner_id ? 'Owner' : 'Membre' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div
                class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100 overflow-hidden">

                <div class="p-6 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                        <i class="ph-fill ph-receipt text-blue-500"></i> Historique
                    </h2>

                    <div class="flex items-center gap-2">
                        <form action="{{ url('/colocations/show') }}" method="GET" class="flex gap-2">
                            <div class="relative">
                                <select name="month"
                                    class="appearance-none border border-gray-200 rounded-xl pl-3 pr-8 py-2 text-xs font-bold text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 bg-gray-50 transition-all">
                                    <option value="">Tous les mois</option>
                                    @foreach(['01'=>'Janvier', '02'=>'Février', '03'=>'Mars', '04'=>'Avril',
                                    '05'=>'Mai', '06'=>'Juin', '07'=>'Juillet', '08'=>'Août', '09'=>'Septembre',
                                    '10'=>'Octobre', '11'=>'Novembre', '12'=>'Décembre'] as $val => $label)
                                    <option value="{{ $val }}" {{ request('month')==$val ? 'selected' : '' }}>{{ $label
                                        }}</option>
                                    @endforeach
                                </select>
                                <i
                                    class="ph-bold ph-caret-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                            </div>
                            <button type="submit"
                                class="bg-gray-900 text-white text-xs font-bold px-4 py-2 rounded-xl hover:bg-gray-800 transition-colors">
                                Filtrer
                            </button>
                        </form>

                        <a href="{{ url('/expenses/create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm hover:shadow-md transition-all text-xs flex items-center gap-1.5">
                            <i class="ph-bold ph-plus"></i> Ajouter
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-widest font-black">
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Titre</th>
                                <th class="px-6 py-4">Catégorie</th>
                                <th class="px-6 py-4">Paiement</th>
                                <th class="px-6 py-4 text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($expenses as $expense)
                            <tr class="group hover:bg-blue-50/30 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-gray-400">
                                    {{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900 text-sm">
                                    {{ $expense->title }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 text-[10px] font-black px-2 py-1 rounded-lg border border-blue-100 uppercase tracking-tighter">
                                        <i class="ph-fill ph-tag"></i> {{ $expense->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">
                                            {{ substr($expense->payer->name, 0, 1) }}
                                        </div>
                                        <span class="text-xs font-semibold text-gray-600">{{ $expense->payer->name
                                            }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-gray-900">
                                    {{ number_format($expense->amount, 2) }} <span
                                        class="text-[10px] text-gray-400 font-bold">MAD</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="ph ph-receipt-x text-5xl mb-3 opacity-20"></i>
                                        <p class="font-medium">Aucune dépense trouvée pour cette période.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection