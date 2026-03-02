@extends("layout.base")
@section("main")
    <main class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0">
            <h2 class="text-xl font-bold text-slate-800">
                <i class="fa-solid fa-shield-halved text-indigo-500 mr-2"></i>
                Administration
            </h2>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-slate-600">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
        </header>

        <div class="p-8 overflow-y-auto">

            @if (session('success'))
                <div class="mb-6 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl">
                    <i class="fa-solid fa-circle-check mt-0.5 text-emerald-500"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-500"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="text-sm font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Platform Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <i class="fa-solid fa-users text-xl"></i>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Utilisateurs</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalUsers) }}</h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600">
                            <i class="fa-solid fa-building-user text-xl"></i>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Total Groupes</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalGroups) }}</h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fa-solid fa-users-rectangle text-xl"></i>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Groupes Actifs</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ number_format($activeGroups) }}</h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                            <i class="fa-solid fa-wallet text-xl"></i>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Total Dépenses</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalExpenses, 0, ',', ' ') }} €</h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-cyan-50 rounded-xl flex items-center justify-center text-cyan-600">
                            <i class="fa-solid fa-handshake text-xl"></i>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Règlements</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalSettlements, 0, ',', ' ') }} €</h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fa-solid fa-user-slash text-xl"></i>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Bannis</p>
                    <h3 class="text-2xl font-bold text-slate-900">{{ number_format($bannedUsersCount) }}</h3>
                </div>
            </div>

            {{-- Users Management Table --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-lg">Gestion des Utilisateurs</h3>
                    <span class="text-sm text-slate-500">{{ $users->total() }} utilisateur{{ $users->total() > 1 ? 's' : '' }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Utilisateur</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Rôle</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Groupes</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Dépenses</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Réputation</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Statut</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Inscrit le</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $u)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <a href="/users/{{ $u->id }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                                            <img src="https://ui-avatars.com/api/?name={{ $u->first_name }}+{{ $u->last_name }}&background={{ $u->is_banned ? 'ef4444' : '6366f1' }}&color=fff&size=40" class="w-10 h-10 rounded-full" alt="">
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">{{ $u->first_name }} {{ $u->last_name }}</p>
                                                <p class="text-xs text-slate-500">{{ $u->email }}</p>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($u->is_admin)
                                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg">Admin</span>
                                        @else
                                            <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg">Membre</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-600 font-medium">{{ $u->active_groups_count }} actif{{ $u->active_groups_count > 1 ? 's' : '' }}</span>
                                        <span class="text-xs text-slate-400 block">{{ $u->groups_count }} total</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $u->expenses_count }}</td>
                                    <td class="px-6 py-4">
                                        @if($u->reputation > 0)
                                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-lg">+{{ $u->reputation }}</span>
                                        @elseif($u->reputation < 0)
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-lg">{{ $u->reputation }}</span>
                                        @else
                                            <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($u->is_banned)
                                            <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Banni
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Actif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ $u->created_at->translatedFormat('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if(!$u->is_admin)
                                            @if($u->is_banned)
                                                <form action="/admin/users/{{ $u->id }}/unban" method="POST" class="inline" onsubmit="return confirm('Débannir {{ $u->first_name }} {{ $u->last_name }} ?')">
                                                    @csrf
                                                    <button type="submit" class="text-slate-400 hover:text-indigo-600 transition-colors px-2 py-1 rounded-lg hover:bg-indigo-50" title="Débannir">
                                                        <i class="fa-solid fa-rotate-left"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="/admin/users/{{ $u->id }}/ban" method="POST" class="inline" onsubmit="return confirm('Bannir {{ $u->first_name }} {{ $u->last_name }} ?')">
                                                    @csrf
                                                    <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors px-2 py-1 rounded-lg hover:bg-red-50" title="Bannir">
                                                        <i class="fa-solid fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-xs text-slate-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($users->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>
@endsection
