<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - ColocManager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-5xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row">
        
        <div class="lg:w-5/12 bg-indigo-600 p-10 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-indigo-500 rounded-full opacity-50"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-12">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-house-chimney-user text-indigo-600 text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold tracking-tight">ColocManager</span>
                </div>
                
                <h1 class="text-4xl font-extrabold leading-tight mb-6">Simplifiez la vie à plusieurs.</h1>
                
                <ul class="space-y-4 text-indigo-100">
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-check-circle text-emerald-400"></i>
                        <span>Gestion équitable des dépenses</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-check-circle text-emerald-400"></i>
                        <span>Calcul automatique des dettes</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-check-circle text-emerald-400"></i>
                        <span>Système de réputation financier</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="lg:w-7/12 p-8 lg:p-16">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Rejoignez-nous</h2>
                <p class="text-gray-500 mt-2">Créez votre profil pour commencer à gérer votre colocation.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-500 flex-shrink-0"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="text-sm font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form id="registerForm" action="/auth/signup" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Prénom</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fa-regular fa-user"></i>
                            </span>
                            <input type="text" name="first_name" required value="{{ old('first_name') }}"
                                class="block w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-signature"></i>
                            </span>
                            <input type="text" name="last_name" required value="{{ old('last_name') }}"
                                class="block w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Adresse Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" name="email" required value="{{ old('email') }}"
                            class="block w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Mot de passe</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="block w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmation</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-shield-check"></i>
                            </span>
                            <input type="password" name="password_confirmation" required placeholder="••••••••"
                                class="block w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    Créer mon compte
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div class="mt-8 text-center border-t border-gray-100 pt-6">
                <p class="text-gray-600">
                    Déjà un compte ? 
                    <a href="/auth/login" class="text-indigo-600 font-bold hover:text-indigo-800 transition-colors">Se connecter ici</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>