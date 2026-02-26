@extends('layout')

@section('title', 'Balances & Remboursements - EasyColoc')

@section('content')
<div class="max-w-6xl mx-auto mt-8 px-4 pb-16">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 flex items-center gap-3">
                <i class="ph ph-scales text-blue-600"></i> Balances
            </h1>
            <p class="text-gray-500 font-medium mt-1">État des comptes de la colocation</p>
        </div>
        <a href="{{ route('expenses.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-sm">
            <i class="ph-bold ph-plus"></i> Nouvelle Dépense
        </a>
    </div>

    @if(session('success'))
    <div class="bg-blue-50 border border-blue-100 text-blue-700 px-4 py-3 rounded-xl mb-8 flex items-center gap-3">
        <i class="ph-fill ph-check-circle text-xl"></i>
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <div class="lg:col-span-2 space-y-10">

            <section>
                <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2 uppercase tracking-wider">
                    <i class="ph ph-arrows-left-right"></i> À régler pour équilibrer
                </h2>

                @if(count($suggestedPayments) > 0)
                <div class="grid gap-3">
                    @foreach($suggestedPayments as $debt)
                    <div
                        class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-tight">De</span>
                                <span
                                    class="font-bold text-gray-900 {{ $debt['from']->id === Auth::id() ? 'text-blue-600' : '' }}">
                                    {{ $debt['from']->id === Auth::id() ? 'Moi' : $debt['from']->name }}
                                </span>
                            </div>
                            <i class="ph ph-caret-right text-gray-300"></i>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-tight">À</span>
                                <span
                                    class="font-bold text-gray-900 {{ $debt['to']->id === Auth::id() ? 'text-blue-600' : '' }}">
                                    {{ $debt['to']->id === Auth::id() ? 'Moi' : $debt['to']->name }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-black text-gray-900">{{ number_format($debt['amount'], 2)
                                }}</span>
                            <span class="text-xs font-bold text-gray-400 ml-1">MAD</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-white border-2 border-dashed border-gray-100 rounded-3xl p-12 text-center">
                    <p class="text-gray-400 font-medium">🎉 Les comptes sont parfaitement équilibrés !</p>
                </div>
                @endif
            </section>

            <section>
                <h2 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2 uppercase tracking-wider">
                    <i class="ph ph-clock-counter-clockwise"></i> Historique
                </h2>

                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                    @if($pastPayments->count() > 0)
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 border-b border-gray-100">
                            <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Détails</th>
                                <th class="px-6 py-4 text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($pastPayments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-500">{{
                                    $payment->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-700">
                                        {{ $payment->payer->name }} <i class="ph ph-arrow-right text-[10px] mx-1"></i>
                                        {{ $payment->receiver->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-green-600">{{ number_format($payment->amount, 2) }}
                                        MAD</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="p-8 text-center text-gray-400 text-sm">Aucun remboursement pour le moment.</div>
                    @endif
                </div>
            </section>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-24">
                <div class="bg-white border-2 border-blue-600 rounded-3xl p-8 shadow-xl shadow-blue-500/5">
                    <h2 class="text-xl font-black text-gray-900 mb-2">Mes dettes</h2>
                    <p class="text-sm text-gray-500 mb-8">Soldez vos comptes en un clic dès que vous remboursez.</p>

                    @php
                    $myDebts = collect($suggestedPayments)->filter(fn($p) => $p['from']->id === Auth::id());
                    @endphp

                    @if($myDebts->count() > 0)
                    <div class="space-y-6">
                        @foreach($myDebts as $debt)
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <span
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Rembourser
                                        à</span>
                                    <span class="font-bold text-gray-900 text-lg">{{ $debt['to']->name }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xl font-black text-red-600">{{ number_format($debt['amount'], 2)
                                        }}</span>
                                    <span class="text-[10px] font-bold text-red-400 ml-0.5">MAD</span>
                                </div>
                            </div>

                            <form action="{{ route('balances.store') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $debt['to']->id }}">
                                <input type="hidden" name="amount" value="{{ $debt['amount'] }}">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-2 group">
                                    <span>Paiement effectué</span>
                                    <i
                                        class="ph ph-check-circle text-lg transition-transform group-hover:scale-110"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6">
                        <div class="text-4xl mb-4">✨</div>
                        <p class="font-bold text-gray-900">Tout est payé !</p>
                        <p class="text-sm text-gray-400 mt-1">Vous ne devez d'argent à personne.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection