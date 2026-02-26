@extends('layout')

@section('title', 'Balances & Remboursements - EasyColoc')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('user.dashboard') }}" class="text-gray-500 hover:text-blue-600 transition font-medium">
                &larr; Retour
            </a>
            <h1 class="text-3xl font-black text-gray-900">Balances ⚖️</h1>
        </div>
        <a href="{{ route('expenses.create') }}"
            class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
            + Nouvelle Dépense
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md shadow-sm">
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm">
        <p class="text-red-700 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">À régler pour équilibrer</h2>

                @if(count($suggestedPayments) > 0)
                <div class="space-y-4">
                    @foreach($suggestedPayments as $debt)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center gap-3">
                            <span
                                class="font-bold {{ $debt['from']->id === Auth::id() ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $debt['from']->id === Auth::id() ? 'Vous' : $debt['from']->name }}
                            </span>
                            <span class="text-gray-400">doit à</span>
                            <span
                                class="font-bold {{ $debt['to']->id === Auth::id() ? 'text-green-600' : 'text-gray-800' }}">
                                {{ $debt['to']->id === Auth::id() ? 'Vous' : $debt['to']->name }}
                            </span>
                        </div>
                        <div class="font-black text-lg text-gray-900">
                            {{ number_format($debt['amount'], 2) }} MAD
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center p-8 text-gray-500">
                    <span class="text-4xl block mb-2">🎉</span>
                    Les comptes sont parfaits ! Personne ne doit rien à personne.
                </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Historique des remboursements</h2>
                @if($pastPayments->count() > 0)
                <table class="w-full text-left border-collapse">
                    <tbody>
                        @foreach($pastPayments as $payment)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-3 text-sm text-gray-500">{{ $payment->created_at->format('d/m/Y') }}</td>
                            <td class="py-3 font-medium">
                                {{ $payment->payer_id === Auth::id() ? 'Vous' : $payment->payer->name }}
                                <span class="text-gray-400 font-normal">a remboursé</span>
                                {{ $payment->receiver_id === Auth::id() ? 'Vous' : $payment->receiver->name }}
                            </td>
                            <td class="py-3 text-right font-bold text-green-600">+{{ number_format($payment->amount, 2)
                                }} MAD</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-gray-500 text-sm text-center py-4">Aucun remboursement n'a encore été enregistré.</p>
                @endif
            </div>
        </div>

        <div class="bg-gray-900 p-6 rounded-xl shadow-sm text-white h-fit">
            <h2 class="text-xl font-bold mb-4">Mes dettes à régler</h2>
            <p class="text-gray-400 text-sm mb-6">Remboursez vos colocataires en un clic pour équilibrer les comptes.
            </p>

            @php
            // We filter the calculated payments to only show the ones where YOU owe money
            $myDebts = collect($suggestedPayments)->filter(function($payment) {
            return $payment['from']->id === Auth::id();
            });
            @endphp

            @if($myDebts->count() > 0)
            <div class="space-y-4">
                @foreach($myDebts as $debt)
                <div
                    class="bg-gray-800 border border-gray-700 rounded-lg p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-center sm:text-left">
                        <span class="text-sm text-gray-400 block">Vous devez à</span>
                        <span class="font-bold text-lg text-white">{{ $debt['to']->name }}</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="font-black text-xl text-red-400">
                            {{ number_format($debt['amount'], 2) }} MAD
                        </span>

                        <form action="{{ route('balances.store') }}" method="POST" class="m-0">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $debt['to']->id }}">
                            <input type="hidden" name="amount" value="{{ $debt['amount'] }}">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded-lg transition whitespace-nowrap">
                                ✅ Rembourser
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 text-center">
                <span class="text-4xl block mb-3">😎</span>
                <p class="text-green-400 font-medium text-lg">Vous ne devez rien à personne !</p>
                <p class="text-gray-500 text-sm mt-1">Vos comptes sont parfaits.</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection