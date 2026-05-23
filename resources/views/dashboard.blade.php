<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold tracking-tight text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-600 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 shadow-2xl shadow-black/50">
                <div class="p-8 text-slate-100 sm:p-10">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/40 bg-cyan-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-cyan-200">
                                <span class="h-2 w-2 rounded-full bg-cyan-300"></span>
                                System Status
                            </div>

                            <h3 class="mt-4 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                                {{ __('Welcome back, :name', ['name' => auth()->user()->name]) }}
                            </h3>


                        </div>

                        <div class="rounded-2xl border border-fuchsia-400/40 bg-fuchsia-500/10 px-4 py-3 text-sm text-fuchsia-100 backdrop-blur">
                            <p class="font-semibold text-fuchsia-100">{{ __('Session Active') }}</p>
                            <p class="text-fuchsia-200/90">{{ now()->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-2xl border border-violet-300/50 bg-violet-600/25 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-violet-200">{{ __('Total Capital Out') }}</p>
                                <p class="mt-2 text-2xl font-bold text-white">{{ __('₱ :amount', ['amount' => number_format((float) ($totalCapitalOut ?? 0), 2)]) }}</p>
                                <p class="mt-1 text-xs text-violet-100/80">{{ __('Monitor disbursed loan principal') }}</p>
                            </div>

                            <div class="rounded-2xl border border-cyan-300/50 bg-cyan-600/25 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-cyan-200">{{ __('Total Interest') }}</p>
                                <p class="mt-2 text-2xl font-bold text-white">{{ __('₱ :amount', ['amount' => number_format((float) ($totalInterest ?? 0), 2)]) }}</p>
                                <p class="mt-1 text-xs text-cyan-100/80">{{ __('Accumulated interest earnings') }}</p>
                            </div>

                            <div class="rounded-2xl border border-emerald-300/50 bg-emerald-600/25 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-emerald-200">{{ __('Active Loans') }}</p>
                                <p class="mt-2 text-2xl font-bold text-white">{{ number_format((int) ($activeLoans ?? 0)) }}</p>
                                <p class="mt-1 text-xs text-emerald-100/80">{{ __('Currently running accounts') }}</p>
                            </div>

                            <div class="rounded-2xl border border-rose-300/50 bg-rose-600/25 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-rose-200">{{ __('Overdue Watchlist') }}</p>
                                <p class="mt-2 text-2xl font-bold text-white">{{ number_format((int) ($overdueLoans ?? 0)) }}</p>
                                <p class="mt-1 text-xs text-rose-100/80">{{ __('Priority follow-up accounts') }}</p>
                            </div>
                        </div>

                        <div class="mt-8 border-t border-slate-700 pt-6">
                            <p class="mb-3 text-xs font-semibold uppercase tracking-[0.14em] text-slate-300">
                                {{ __('Quick Actions (Admin)') }}
                            </p>

                            <div class="rounded-2xl border border-slate-500/80 bg-slate-800/70 p-4 sm:p-5">
                                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                <a href="{{ route('borrowers.index') }}"
                                   class="inline-flex w-full min-h-12 items-center justify-center rounded-xl border border-violet-200/70 bg-gradient-to-r from-violet-500 to-fuchsia-500 px-4 py-3 text-sm font-bold text-white shadow-xl shadow-violet-950/60 transition hover:from-violet-400 hover:to-fuchsia-400 focus:outline-none focus:ring-2 focus:ring-violet-200 focus:ring-offset-2 focus:ring-offset-slate-900">
                                    {{ __('Add Borrower') }}
                                </a>

                                <a href="{{ route('loans.create') }}"
                                   class="inline-flex w-full min-h-12 items-center justify-center rounded-xl border border-cyan-100/70 bg-gradient-to-r from-blue-500 to-cyan-400 px-4 py-3 text-sm font-bold text-white shadow-xl shadow-cyan-950/60 transition hover:from-blue-400 hover:to-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-100 focus:ring-offset-2 focus:ring-offset-slate-900">
                                    {{ __('Disburse Loan') }}
                                </a>

                                <a href="{{ route('payments.create') }}"
                                   class="inline-flex w-full min-h-12 items-center justify-center rounded-xl border border-emerald-100/70 bg-gradient-to-r from-emerald-500 to-lime-500 px-4 py-3 text-sm font-bold text-white shadow-xl shadow-emerald-950/60 transition hover:from-emerald-400 hover:to-lime-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:ring-offset-2 focus:ring-offset-slate-900">
                                    {{ __('Record Payment') }}
                                </a>

                                <a href="{{ route('ledger') }}"
                                   class="inline-flex w-full min-h-12 items-center justify-center rounded-xl border border-amber-100/80 bg-gradient-to-r from-amber-400 to-orange-500 px-4 py-3 text-sm font-bold text-white shadow-xl shadow-orange-950/60 transition hover:from-amber-300 hover:to-orange-400 focus:outline-none focus:ring-2 focus:ring-amber-100 focus:ring-offset-2 focus:ring-offset-slate-900">
                                    {{ __('Open Digital Ledger') }}
                                </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
