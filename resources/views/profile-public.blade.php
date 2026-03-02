@extends("layout.base")
@section("main")
    <main class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0">
            <div class="flex items-center gap-3">
                <a href="javascript:history.back()" class="text-slate-400 hover:text-indigo-600 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h2 class="text-xl font-bold text-slate-800">Profil de {{ $user->first_name }}</h2>
            </div>
        </header>

        <div class="p-8 overflow-y-auto">
            <div class="max-w-2xl mx-auto">

                {{-- Profile Card --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 h-32 relative">
                        <div class="absolute -bottom-12 left-1/2 -translate-x-1/2">
                            <img src="https://ui-avatars.com/api/?name={{ $user->first_name }}+{{ $user->last_name }}&background=6366f1&color=fff&size=96&bold=true&font-size=0.4" class="w-24 h-24 rounded-2xl border-4 border-white shadow-lg" alt="Avatar">
                        </div>
                    </div>
                    <div class="pt-16 p-8 text-center">
                        <h3 class="text-2xl font-bold text-slate-900">{{ $user->first_name }} {{ $user->last_name }}</h3>
                        <p class="text-sm text-slate-500 mt-1">Membre depuis {{ $user->created_at->translatedFormat('d F Y') }}</p>

                        {{-- Status Badge --}}
                        @if($user->is_banned)
                            <span class="inline-flex items-center gap-1.5 mt-3 py-1.5 px-4 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                <span class="w-2 h-2 rounded-full bg-red-600"></span> Banni
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 mt-3 py-1.5 px-4 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Actif
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Reputation Section --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 mt-8">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 text-center">
                        <i class="fa-solid fa-star text-yellow-500 mr-2"></i>
                        Réputation
                    </h3>

                    <div class="flex items-center justify-center mb-6">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center {{ $user->reputation > 0 ? 'bg-emerald-50' : ($user->reputation < 0 ? 'bg-red-50' : 'bg-slate-50') }}">
                            <span class="text-3xl font-bold {{ $user->reputation > 0 ? 'text-emerald-600' : ($user->reputation < 0 ? 'text-red-600' : 'text-slate-600') }}">
                                {{ $user->reputation > 0 ? '+' : '' }}{{ $user->reputation }}
                            </span>
                        </div>
                    </div>

                    <div class="text-center">
                        @if($user->reputation > 2)
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-2xl">
                                <i class="fa-solid fa-shield-check"></i>
                                <span class="text-sm font-bold">Excellent colocataire</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-3">Ce colocataire est très fiable et règle toujours ses dettes.</p>
                        @elseif($user->reputation > 0)
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-2xl">
                                <i class="fa-solid fa-thumbs-up"></i>
                                <span class="text-sm font-bold">Bon colocataire</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-3">Ce colocataire a une bonne réputation.</p>
                        @elseif($user->reputation == 0)
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-600 rounded-2xl">
                                <i class="fa-solid fa-minus"></i>
                                <span class="text-sm font-bold">Réputation neutre</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-3">Ce colocataire n'a pas encore d'historique significatif.</p>
                        @elseif($user->reputation > -3)
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-700 rounded-2xl">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span class="text-sm font-bold">Quelques dettes passées</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-3">Ce colocataire a eu quelques impayés par le passé.</p>
                        @else
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 rounded-2xl">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span class="text-sm font-bold">Réputation préoccupante</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-3">Ce colocataire a un historique de dettes impayées.</p>
                        @endif
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 gap-6 mt-8">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 text-center">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mx-auto mb-3">
                            <i class="fa-solid fa-building-user text-lg"></i>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">{{ $activeGroupsCount }}</p>
                        <p class="text-sm text-slate-500">Groupes actifs</p>
                    </div>
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 text-center">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 mx-auto mb-3">
                            <i class="fa-solid fa-users text-lg"></i>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">{{ $totalGroupsJoined }}</p>
                        <p class="text-sm text-slate-500">Total groupes rejoints</p>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
