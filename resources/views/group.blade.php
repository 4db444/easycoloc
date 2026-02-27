@extends("layout.base")
@section("main")
    <main class="flex-1 flex flex-col min-w-0 relative">
        <header class="h-24 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40">
            <div>
                <h2 class="text-xl font-bold text-slate-800">La Villa des Amis</h2>
                <div class="flex items-center gap-4 mt-1">
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg flex items-center gap-1">
                        <i class="fa-solid fa-circle text-[6px]"></i> Solde Équilibré
                    </span>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button id="openCategoryModal" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold py-2.5 px-5 rounded-xl transition-all text-sm">
                    <i class="fa-solid fa-tags text-indigo-500"></i>
                    Catégories
                </button>
                <button id="openExpenseModal" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-100 transition-all active:scale-95 text-sm">
                    <i class="fa-solid fa-plus"></i>
                    Ajouter une dépense
                </button>
            </div>
        </header>

        <div class="p-8 space-y-10">
            
            <section>
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-scale-balanced text-indigo-500"></i>
                    Équilibre du groupe
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold">JD</div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">Jean Dupont</p>
                                        <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">A payé 450€ au total</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-emerald-600">+120.50 €</p>
                                    <p class="text-[10px] text-slate-400 font-bold">À recevoir</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border-l-4 border-red-500">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-slate-200 text-slate-600 rounded-xl flex items-center justify-center font-bold">SM</div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">Sophie Martin (Vous)</p>
                                        <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">A payé 80€ au total</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-red-600">-215.00 €</p>
                                    <p class="text-[10px] text-slate-400 font-bold">À rembourser</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-900 rounded-[2rem] p-6 text-white shadow-xl shadow-indigo-200/50">
                        <h4 class="font-bold mb-4 text-indigo-200 text-sm tracking-widest uppercase">Règlements suggérés</h4>
                        <div class="space-y-4">
                            <div class="p-4 bg-white/10 rounded-2xl border border-white/10">
                                <p class="text-xs text-indigo-200 mb-2 font-medium">Vous devez à **Jean**</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-black">112.00 €</span>
                                    <button class="bg-emerald-500 hover:bg-emerald-400 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg transition-colors uppercase">
                                        Marquer comme payé
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Historique des dépenses</h3>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-500">Ce mois</span>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Dépense</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Catégorie</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Payé par</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer group">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-slate-800">Courses Carrefour</p>
                                    <p class="text-[10px] text-slate-400">24 Février 2026</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-lg border border-amber-100 italic">Alimentation</span>
                                </td>
                                <td class="px-6 py-4 font-bold text-sm text-slate-600">Jean D.</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-black text-slate-900">84.20 €</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div id="expenseModal" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm modal-overlay"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
                <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden pointer-events-auto">
                    <div class="p-8">
                        <h3 class="text-2xl font-black text-slate-900 mb-6">Ajouter une dépense</h3>
                        <form action="/expenses" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Titre</label>
                                    <input type="text" name="title" required placeholder="Ex: Loyer, Pizza..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Montant (€)</label>
                                    <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Catégorie</label>
                                    <select name="category_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none appearance-none">
                                        <option value="">Sélectionner...</option>
                                        </select>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl mt-4 shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">Enregistrer la dépense</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="categoryModal" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm modal-overlay"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
                <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden pointer-events-auto">
                    <div class="p-8">
                        <h3 class="text-2xl font-black text-slate-900 mb-6">Catégories</h3>
                        <form action="/categories" method="POST" class="flex gap-2 mb-6">
                            @csrf
                            <input type="text" name="name" required placeholder="Nouvelle catégorie..." class="flex-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                            <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-xl font-bold text-xs">Ajouter</button>
                        </form>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <span class="text-sm font-bold text-slate-700">Alimentation</span>
                                <button class="text-red-400 hover:text-red-600"><i class="fa-solid fa-trash-can text-xs"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection

@section("script")
    <script>
        const setupModal = (btnId, modalId) => {
            const btn = document.getElementById(btnId);
            const modal = document.getElementById(modalId);
            const overlays = modal.querySelectorAll('.modal-overlay');

            btn.addEventListener('click', () => modal.classList.remove('hidden'));
            overlays.forEach(o => o.addEventListener('click', () => modal.classList.add('hidden')));
        };

        setupModal('openExpenseModal', 'expenseModal');
        setupModal('openCategoryModal', 'categoryModal');
    </script>
@endsection