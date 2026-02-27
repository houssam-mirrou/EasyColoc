@extends('layout')

@section('title', 'Balances - EasyColoc')

@section('content')
<div class="max-w-6xl mx-auto mt-8 px-4 pb-16">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-12">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <i class="ph ph-scales text-blue-600"></i> Balances
            </h1>
            <p class="text-gray-500 font-medium mt-1">État des comptes pour les membres actifs</p>
        </div>
        <a href="{{ route('expenses.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-sm">
            + Nouvelle Dépense
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <div class="lg:col-span-2 space-y-12">

            <section>
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    Solutions de remboursement
                </h2>

                @if(count($suggestedPayments) > 0)
                <div class="space-y-3">
                    @foreach($suggestedPayments as $debt)
                    <div
                        class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-gray-400 font-black uppercase">Débiteur</span>
                                <span
                                    class="font-bold text-gray-900 {{ $debt['from']->id === Auth::id() ? 'text-blue-600' : '' }}">
                                    {{ $debt['from']->id === Auth::id() ? 'Moi' : $debt['from']->name }}
                                </span>
                            </div>
                            <i class="ph ph-arrow-right text-gray-300"></i>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-gray-400 font-black uppercase">Créancier</span>
                                <span
                                    class="font-bold text-gray-900 {{ $debt['to']->id === Auth::id() ? 'text-blue-600' : '' }}">
                                    {{ $debt['to']->id === Auth::id() ? 'Moi' : $debt['to']->name }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-black text-gray-900">{{ number_format($debt['amount'], 2)
                                }}</span>
                            <span class="text-[10px] font-bold text-gray-400 ml-1">MAD</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div
                    class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center text-gray-400 font-medium">
                    Tous les comptes sont équilibrés. ✨
                </div>
                @endif
            </section>

            <section>
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
                    Dernières transactions
                </h2>
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($pastPayments as $payment)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-gray-400">{{
                                    $payment->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-700">
                                        {{ $payment->payer->name }} <span
                                            class="text-gray-300 font-normal px-1">rembourse</span> {{
                                        $payment->receiver->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-black text-green-600">{{ number_format($payment->amount,
                                        2) }} MAD</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="px-6 py-8 text-center text-gray-400 text-sm">Aucun historique.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                <div class="bg-white border-2 border-blue-600 rounded-3xl p-8 shadow-xl shadow-blue-500/5">
                    <h2 class="text-xl font-black text-gray-900 mb-6">Mes actions</h2>

                    @php
                    $myDebts = collect($suggestedPayments)->filter(fn($p) => $p['from']->id === Auth::id());
                    @endphp

                    @if($myDebts->count() > 0)
                    <div class="space-y-4">
                        @foreach($myDebts as $debt)
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-[10px] font-black text-gray-400 uppercase">À rembourser à {{
                                    $debt['to']->name }}</span>
                                <span class="text-lg font-black text-red-600">{{ number_format($debt['amount'], 2) }}
                                    MAD</span>
                            </div>
                            <form action="{{ route('balances.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $debt['to']->id }}">
                                <input type="hidden" name="amount" value="{{ $debt['amount'] }}">
                                <button
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                                    Régler la dette <i class="ph ph-check-circle"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6">
                        <div
                            class="w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ph-fill ph-smiley-wink text-3xl"></i>
                        </div>
                        <p class="font-bold text-gray-900">Vous êtes à jour !</p>
                        <p class="text-xs text-gray-400 mt-1">Aucune dette envers vos colocataires.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection