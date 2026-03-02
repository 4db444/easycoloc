@extends("layout.base")
@section("main")
    <main class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0">
            <h2 class="text-xl font-bold text-slate-800">Vue d'ensemble</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-slate-600">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
        </header>

        <div class="p-8 overflow-y-auto">

            {{-- Welcome Banner --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl p-8 mb-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-1/2 w-32 h-32 bg-white/5 rounded-full translate-y-1/2"></div>
                <div class="relative">
                    <h1 class="text-2xl font-bold mb-2">Bonjour, {{ $user->first_name }} 👋</h1>
                    <p class="text-indigo-100 text-sm">Bienvenue sur votre tableau de bord ColocManager. Voici un résumé de vos activités.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl">
                    <i class="fa-solid fa-circle-check mt-0.5 text-emerald-500"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <i class="fa-solid fa-users text-xl"></i>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Groupes Actifs</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ $activeGroups->count() }}</h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fa-solid fa-wallet text-xl"></i>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Total Dépensé</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalExpensesPaid, 2, ',', ' ') }} €</h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $totalDebt > 0 ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }}">
                            <i class="fa-solid fa-arrow-trend-{{ $totalDebt > 0 ? 'down' : 'up' }} text-xl"></i>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">{{ $totalDebt > 0 ? 'Vous devez' : 'On vous doit' }}</p>
                    <h3 class="text-2xl font-bold {{ $totalDebt > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                        {{ number_format($totalDebt > 0 ? $totalDebt : $totalCredit, 2, ',', ' ') }} €
                    </h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
                            <i class="fa-solid fa-star text-xl"></i>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Réputation</p>
                    <h3 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                        {{ $user->reputation }}
                        @if($user->reputation > 0)
                            <span class="text-xs font-bold px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg">Fiable</span>
                        @elseif($user->reputation < 0)
                            <span class="text-xs font-bold px-2 py-1 bg-red-100 text-red-700 rounded-lg">À surveiller</span>
                        @else
                            <span class="text-xs font-bold px-2 py-1 bg-slate-100 text-slate-600 rounded-lg">Neutre</span>
                        @endif
                    </h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- Active Groups --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 text-lg">Mes Groupes Actifs</h3>
                        <a href="/groups" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Voir tout</a>
                    </div>
                    <div class="p-6">
                        @forelse($activeGroups as $group)
                            <a href="/groups/{{ $group->id }}" class="flex items-center justify-between p-4 rounded-2xl hover:bg-slate-50 transition-colors mb-2 last:mb-0">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                                        <i class="fa-solid fa-building-user"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $group->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $group->active_members_count }} membre{{ $group->active_members_count > 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-right text-slate-400 text-xs"></i>
                            </a>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-users-slash text-slate-400 text-xl"></i>
                                </div>
                                <p class="text-sm text-slate-500 font-medium">Aucun groupe actif</p>
                                <a href="/groups" class="mt-3 inline-block text-sm font-bold text-indigo-600 hover:text-indigo-800">Rejoindre un groupe</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Outstanding Debts --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-lg">Solde par Groupe</h3>
                    </div>
                    <div class="p-6">
                        @php $hasDebtsOrCredits = count($debts) > 0 || count($credits) > 0; @endphp
                        @if($hasDebtsOrCredits)
                            @foreach($credits as $credit)
                                <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-50/50 mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-arrow-up text-emerald-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">{{ $credit['group']->name }}</span>
                                    </div>
                                    <span class="text-sm font-bold text-emerald-600">+{{ number_format($credit['amount'], 2, ',', ' ') }} €</span>
                                </div>
                            @endforeach
                            @foreach($debts as $debt)
                                <div class="flex items-center justify-between p-4 rounded-2xl bg-red-50/50 mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-arrow-down text-red-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">{{ $debt['group']->name }}</span>
                                    </div>
                                    <span class="text-sm font-bold text-red-600">-{{ number_format($debt['amount'], 2, ',', ' ') }} €</span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-check-circle text-emerald-400 text-xl"></i>
                                </div>
                                <p class="text-sm text-slate-500 font-medium">Tout est en ordre !</p>
                                <p class="text-xs text-slate-400 mt-1">Aucune dette en cours</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Expenses --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-lg">Dernières Dépenses</h3>
                </div>
                <div class="overflow-x-auto">
                    @if($recentExpenses->count() > 0)
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Dépense</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Groupe</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Payé par</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Catégorie</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Montant</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentExpenses as $expense)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-slate-900">{{ $expense->title }}</p>
                                            <p class="text-xs text-slate-500">{{ $expense->created_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $expense->group->name }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <img src="https://ui-avatars.com/api/?name={{ $expense->user->first_name }}+{{ $expense->user->last_name }}&background=6366f1&color=fff&size=32" class="w-6 h-6 rounded-lg" alt="">
                                                <span class="text-sm text-slate-700">{{ $expense->user->first_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($expense->category)
                                                <span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg">{{ $expense->category->name }}</span>
                                            @else
                                                <span class="text-xs text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm font-bold text-slate-900">{{ number_format($expense->amount, 2, ',', ' ') }} €</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-receipt text-slate-400 text-xl"></i>
                            </div>
                            <p class="text-sm text-slate-500 font-medium">Aucune dépense récente</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>
@endsection