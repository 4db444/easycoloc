@extends("layout.base")
@section("main")
    <main class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0">
            <h2 class="text-xl font-bold text-slate-800">Mon Profil</h2>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Profile Card --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 h-24 relative">
                            <div class="absolute -bottom-10 left-6">
                                <img src="https://ui-avatars.com/api/?name={{ $user->first_name }}+{{ $user->last_name }}&background=6366f1&color=fff&size=80&bold=true&font-size=0.4" class="w-20 h-20 rounded-2xl border-4 border-white shadow-lg" alt="Avatar">
                            </div>
                        </div>
                        <div class="pt-14 p-6">
                            <h3 class="text-lg font-bold text-slate-900">{{ $user->first_name }} {{ $user->last_name }}</h3>
                            <p class="text-sm text-slate-500 mt-1">{{ $user->email }}</p>
                            <p class="text-xs text-slate-400 mt-1">Membre depuis {{ $user->created_at->translatedFormat('d F Y') }}</p>

                            {{-- Reputation Badge --}}
                            <div class="mt-6 p-4 bg-slate-50 rounded-2xl">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-semibold text-slate-700">Réputation</span>
                                    <span class="text-2xl font-bold {{ $user->reputation > 0 ? 'text-emerald-600' : ($user->reputation < 0 ? 'text-red-600' : 'text-slate-600') }}">
                                        {{ $user->reputation > 0 ? '+' : '' }}{{ $user->reputation }}
                                    </span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    @php
                                        $repPercent = min(100, max(0, ($user->reputation + 10) * 5));
                                    @endphp
                                    <div class="h-2 rounded-full {{ $user->reputation >= 0 ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ $repPercent }}%"></div>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">
                                    @if($user->reputation > 2)
                                        <i class="fa-solid fa-shield-check text-emerald-500"></i> Excellent colocataire
                                    @elseif($user->reputation > 0)
                                        <i class="fa-solid fa-thumbs-up text-emerald-500"></i> Bon colocataire
                                    @elseif($user->reputation == 0)
                                        <i class="fa-solid fa-minus text-slate-400"></i> Réputation neutre
                                    @elseif($user->reputation > -3)
                                        <i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Quelques dettes passées
                                    @else
                                        <i class="fa-solid fa-circle-exclamation text-red-500"></i> Réputation préoccupante
                                    @endif
                                </p>
                            </div>

                            {{-- Quick Stats --}}
                            <div class="grid grid-cols-2 gap-3 mt-4">
                                <div class="text-center p-3 bg-indigo-50 rounded-xl">
                                    <p class="text-lg font-bold text-indigo-600">{{ $activeGroups->count() }}</p>
                                    <p class="text-xs text-slate-500">Groupes actifs</p>
                                </div>
                                <div class="text-center p-3 bg-emerald-50 rounded-xl">
                                    <p class="text-lg font-bold text-emerald-600">{{ $groupsJoined }}</p>
                                    <p class="text-xs text-slate-500">Total groupes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Forms --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Update Profile --}}
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                        <h3 class="text-lg font-bold text-slate-800 mb-6">
                            <i class="fa-solid fa-user-pen text-indigo-500 mr-2"></i>
                            Modifier mes informations
                        </h3>
                        <form action="/profile" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Prénom</label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                        class="block w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nom</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                        class="block w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent outline-none transition-all">
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Adresse Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="block w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent outline-none transition-all">
                            </div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-indigo-100 transition-all active:scale-[0.97]">
                                <i class="fa-solid fa-floppy-disk mr-2"></i>Sauvegarder
                            </button>
                        </form>
                    </div>

                    {{-- Change Password --}}
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                        <h3 class="text-lg font-bold text-slate-800 mb-6">
                            <i class="fa-solid fa-lock text-amber-500 mr-2"></i>
                            Changer le mot de passe
                        </h3>
                        <form action="/profile/password" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="space-y-6 mb-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Mot de passe actuel</label>
                                    <input type="password" name="current_password"
                                        class="block w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent outline-none transition-all"
                                        placeholder="••••••••">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nouveau mot de passe</label>
                                        <input type="password" name="password"
                                            class="block w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent outline-none transition-all"
                                            placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Confirmer le mot de passe</label>
                                        <input type="password" name="password_confirmation"
                                            class="block w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent outline-none transition-all"
                                            placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-amber-100 transition-all active:scale-[0.97]">
                                <i class="fa-solid fa-key mr-2"></i>Changer le mot de passe
                            </button>
                        </form>
                    </div>

                    {{-- Active Groups --}}
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100">
                            <h3 class="font-bold text-slate-800 text-lg">
                                <i class="fa-solid fa-building-user text-indigo-500 mr-2"></i>
                                Mes Groupes Actifs
                            </h3>
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
                                    <p class="text-sm text-slate-500 font-medium">Aucun groupe actif</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection
