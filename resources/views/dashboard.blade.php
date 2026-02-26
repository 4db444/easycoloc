@extends("layout.base")
@section("main")
    <main class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0">
            <h2 class="text-xl font-bold text-slate-800">Statistiques Globales</h2>
            <div class="flex items-center gap-4">
                <button class="p-2 text-slate-400 hover:text-indigo-600 relative">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <div class="h-8 w-px bg-slate-200"></div>
                <span class="text-sm font-medium text-slate-600">26 Février 2026</span>
            </div>
        </header>

        <div class="p-8 overflow-y-auto">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <i class="fa-solid fa-users text-xl"></i>
                        </div>
                        <span class="text-emerald-500 text-xs font-bold bg-emerald-50 px-2 py-1 rounded-lg">+12%</span>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Total Utilisateurs</p>
                    <h3 class="text-2xl font-bold text-slate-900">1,284</h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                            <i class="fa-solid fa-building-user text-xl"></i>
                        </div>
                        <span class="text-emerald-500 text-xs font-bold bg-emerald-50 px-2 py-1 rounded-lg">+5%</span>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Colocations Actives</p>
                    <h3 class="text-2xl font-bold text-slate-900">312</h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                            <i class="fa-solid fa-wallet text-xl"></i>
                        </div>
                        <span class="text-slate-400 text-xs font-bold bg-slate-50 px-2 py-1 rounded-lg">Stable</span>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Dépenses ce mois</p>
                    <h3 class="text-2xl font-bold text-slate-900">14,250 €</h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                            <i class="fa-solid fa-user-slash text-xl"></i>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Utilisateurs Bannis</p>
                    <h3 class="text-2xl font-bold text-slate-900">14</h3>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-lg">Derniers Utilisateurs Inscrits</h3>
                    <button class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Voir tout</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Utilisateur</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Colocation</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Réputation</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Statut</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="https://i.pravatar.cc/150?u=1" class="w-10 h-10 rounded-full" alt="">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">Thomas Martin</p>
                                            <p class="text-xs text-slate-500">thomas@example.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 font-medium">Appart Paris 11</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-lg">+4</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Actif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-slate-400 hover:text-red-600 transition-colors px-2 py-1 rounded-lg hover:bg-red-50" title="Bannir">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="https://i.pravatar.cc/150?u=4" class="w-10 h-10 rounded-full" alt="">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">Julie Durand</p>
                                            <p class="text-xs text-slate-500">julie.d@gmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 font-medium">Coloc Lyon Bellecour</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-lg">-2</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Banni
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-slate-400 hover:text-indigo-600 transition-colors px-2 py-1 rounded-lg hover:bg-indigo-50" title="Débannir">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
@endsection