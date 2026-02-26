<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Introuvable | ColocManager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-2xl w-full text-center">
        <div class="relative mb-12 flex justify-center">
            <div class="text-[12rem] font-extrabold text-indigo-100 select-none">404</div>
            <div class="absolute inset-0 flex items-center justify-center mt-12">
                <div class="floating bg-white p-6 rounded-3xl shadow-2xl border border-slate-100">
                    <i class="fa-solid fa-house-crack text-6xl text-indigo-600"></i>
                </div>
            </div>
        </div>

        <h1 class="text-4xl font-bold text-slate-900 mb-4 tracking-tight">Oups ! Mauvaise chambre.</h1>
        <p class="text-lg text-slate-600 mb-10 max-w-md mx-auto">
            On dirait que cette page a déménagé sans laisser d'adresse ou que vous essayez d'entrer dans la chambre d'un coloc sans frapper.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/" class="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all active:scale-[0.97] flex items-center justify-center gap-2">
                <i class="fa-solid fa-house"></i>
                Retour au salon
            </a>
            <button onclick="history.back()" class="w-full sm:w-auto px-8 py-4 bg-white border border-slate-200 text-slate-700 font-bold rounded-2xl hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                Page précédente
            </button>
        </div>

        <div class="mt-16 pt-8 border-t border-slate-200 flex flex-col sm:flex-row justify-center gap-8 text-sm text-slate-400">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info"></i>
                <span>Besoin d'aide ? Contactez l'admin global</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Statut des serveurs : OK</span>
            </div>
        </div>
    </div>
</body>
</html>