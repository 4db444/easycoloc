@extends("layout.base")
@section("main")
    <main class="flex-1 flex flex-col min-w-0 relative">
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Mes Colocations</h2>
                <p class="text-xs text-slate-500 font-medium">Gérez vos groupes et vos finances</p>
            </div>
            
            <button id="openModalBtn" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-100 transition-all active:scale-95 text-sm">
                <i class="fa-solid fa-plus"></i>
                Nouvelle Coloc
            </button>
        </header>

        <div class="p-8 space-y-8">

            @if ($errors->any())
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-500 flex-shrink-0"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="text-sm font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl">
                    <i class="fa-solid fa-circle-check mt-0.5 text-emerald-500 flex-shrink-0"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <section>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-indigo-500"></i>
                        Vos groupes actuels
                    </h3>
                </div>

                @forelse ($groups as $group)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                            
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex flex-col gap-1">
                                    <h4 class="text-xl font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors">{{$group->name}}</h4>
                                </div>
                                <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase px-3 py-1.5 rounded-full border border-indigo-100">
                                    Propriétaire
                                </span>
                            </div>

                            <div class="grid grid-cols-1 gap-4 mb-6">
                                <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100/50">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Membres</p>
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-users text-indigo-500 text-sm"></i>
                                        <span class="text-sm font-bold text-slate-700">{{count($group->activeMembers)}} Active Memebers</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end pt-5 border-t border-slate-50">
                                <a href="/groups/{{$group->id}}" class="inline-flex items-center gap-2 bg-slate-900 text-white text-xs font-bold py-2 px-4 rounded-xl hover:bg-indigo-600 transition-colors group/btn">
                                    Gérer
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                @empty
                    <div id="no-coloc" class="bg-white border-2 border-dashed border-slate-200 rounded-[2.5rem] p-12 text-center">
                        <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-house-user text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Aucun groupe actif</h3>
                        <p class="text-slate-500 max-w-sm mx-auto text-balance">
                            Vous ne faites partie d'aucune colocation pour le moment. Créez-en une ou rejoignez vos amis via un lien.
                        </p>
                    </div>
                @endforelse
            </section>

        </div>

        <div id="createModal" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden transform transition-all">
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-slate-900">Nouvelle Coloc</h3>
                            <button id="closeModalBtn" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>
                        <form action="/groups" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nom de la colocation</label>
                                <input type="text" name="name" required placeholder="Ex: Appartement Paris 11" 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-100 transition-all flex items-center justify-center gap-2">
                                Confirmer la création
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section("script")
    <script>
        const modal = document.getElementById('createModal');
        const openBtn = document.getElementById('openModalBtn');
        const closeBtn = document.getElementById('closeModalBtn');

        // Ouvrir la modale
        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Empêche le scroll en arrière-plan
        });

        // Fermer la modale (Bouton X)
        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });

        // Fermer la modale en cliquant sur l'overlay
        modal.addEventListener('click', (e) => {
            if (e.target === modal.querySelector('.absolute.inset-0.bg-slate-900\\/60')) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    </script>
@endsection