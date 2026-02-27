<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ColocManager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen">

    <aside class="w-64 bg-slate-900 text-slate-300 flex-shrink-0 hidden md:flex flex-col border-r border-slate-800">
        <div class="p-6 flex items-center gap-3 border-b border-slate-800">
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-house-chimney-user text-white"></i>
            </div>
            <span class="text-xl font-bold text-white tracking-tight">ColocManager</span>
        </div>

        <nav class="flex-1 p-4 space-y-2 mt-4">
            <a href="/dashboard" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('dashboard*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white group' }}">
                <i class="fa-solid fa-chart-pie w-5 {{ request()->is('dashboard*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"></i>
                <span>Vue d'ensemble</span>
            </a>

            <a href="/users" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('users*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white group' }}">
                <i class="fa-solid fa-users w-5 {{ request()->is('users*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"></i>
                <span>Utilisateurs</span>
            </a>

            <a href="/groups" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('groups*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white group' }}">
                <i class="fa-solid fa-building-user w-5 {{ request()->is('groups*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"></i>
                <span>Groups</span>
            </a>

            <a href="/expenses" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('expenses*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white group' }}">
                <i class="fa-solid fa-receipt w-5 {{ request()->is('expenses*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400' }}"></i>
                <span>Dépenses</span>
            </a>

            <div class="pt-4 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider pl-4">Modération</div>

            <a href="/bans" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('bans*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white group' }}">
                <i class="fa-solid fa-ban w-5 {{ request()->is('bans*') ? 'text-white' : 'text-slate-500 group-hover:text-red-400' }}"></i>
                <span>Bannissements</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800">
            <div class="flex items-center gap-3 p-2 bg-slate-800/50 rounded-2xl">
                <img src="https://ui-avatars.com/api/?name={{$user->first_name}}+{{$user->last_name}}&background=6366f1&color=fff" class="w-10 h-10 rounded-xl" alt="Admin">
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{$user->first_name}} {{$user->last_name}}</p>
                    <p class="text-xs text-slate-500 truncate">{{$user->email}}</p>
                </div>
            </div>
            <form action="auth/logout" method="POST" class="flex items-center gap-3  mt-2 text-red-400 hover:bg-red-500/10 rounded-xl transition-all">
                @csrf
                <button class="px-4 py-3 w-full h-full" type="submit">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    @yield("main")

    <button class="md:hidden fixed bottom-6 right-6 w-14 h-14 bg-indigo-600 text-white rounded-full shadow-2xl z-50 flex items-center justify-center text-xl">
        <i class="fa-solid fa-bars"></i>
    </button>

    @yield("script")
</body>
</html>