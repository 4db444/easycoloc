@extends("layout.base")
@section("main")
    <main class="flex-1 flex flex-col min-w-0 relative">
        <header class="h-24 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40">
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $group->name }}</h2>
                <div class="flex items-center gap-4 mt-1">
                    @if(count($adjustedDebts) === 0)
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg flex items-center gap-1">
                            <i class="fa-solid fa-circle text-[6px]"></i> Solde Équilibré
                        </span>
                    @else
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg flex items-center gap-1">
                            <i class="fa-solid fa-circle text-[6px]"></i> {{ count($adjustedDebts) }} règlement(s) en attente
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="flex gap-3">
                @if($group->admin->id === $user->id)
                    <button id="openInviteModal" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-5 rounded-xl transition-all text-sm">
                        <i class="fa-solid fa-user-plus text-indigo-600"></i>
                        Inviter
                    </button>
                    <button id="openCategoryModal" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold py-2.5 px-5 rounded-xl transition-all text-sm">
                        <i class="fa-solid fa-tags text-indigo-500"></i>
                        Catégories
                    </button>
                @endif
                @if($group->admin_id !== $user->id && !$currentUserLeftAt)
                    <form action="/groups/{{ $group->id }}/leave" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir quitter ce groupe ?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold py-2.5 px-5 rounded-xl transition-all text-sm">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Quitter
                        </button>
                    </form>
                @endif
                @if(!$currentUserLeftAt)
                    <button id="openExpenseModal" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-100 transition-all active:scale-95 text-sm">
                        <i class="fa-solid fa-plus"></i>
                        Ajouter une dépense
                    </button>
                @endif
            </div>
        </header>

        <div class="p-8 space-y-10">

            @if (session('success'))
                <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl">
                    <i class="fa-solid fa-circle-check mt-0.5 text-emerald-500 flex-shrink-0"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

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

            {{-- Members Section --}}
            <section>
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Membres du groupe</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach ($activeMembers as $member)
                        <div class="flex items-center gap-2 bg-white border border-slate-200 px-4 py-2.5 rounded-2xl shadow-sm">
                            <div class="w-8 h-8 bg-indigo-600 text-white rounded-lg flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-bold text-slate-700">
                                {{ $member->first_name }} {{ $member->last_name }}
                                @if($member->id === $user->id) <span class="text-slate-400">(Vous)</span> @endif
                                @if($member->id === $group->admin_id) <span class="text-indigo-500 text-xs">👑</span> @endif
                            </span>
                            <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                            @if($group->admin_id === $user->id && $member->id !== $user->id)
                                <form action="/groups/{{ $group->id }}/members/{{ $member->id }}/fire" method="POST" class="ml-1" onsubmit="return confirm('Voulez-vous vraiment exclure {{ $member->first_name }} ?')">
                                    @csrf
                                    <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors flex items-center justify-center w-6 h-6 rounded-full hover:bg-red-50" title="Exclure">
                                        <i class="fa-solid fa-user-minus text-[10px]"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach

                    @foreach ($pastMembers as $member)
                        <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-2xl opacity-60">
                            <div class="w-8 h-8 bg-slate-300 text-slate-600 rounded-lg flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-slate-500">
                                {{ $member->first_name }} {{ $member->last_name }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 bg-slate-200 px-2 py-0.5 rounded-lg">Parti</span>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Categories Section --}}
            <section>
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Catégories du groupe</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse ($categories as $category)
                        <div class="flex items-center gap-2 bg-white border border-slate-200 px-4 py-2 rounded-2xl shadow-sm">
                            <span class="text-sm font-bold text-slate-700">{{$category->name}}</span>
                            @if($group->admin->id === $user->id)
                                <form action="/groups/{{$group->id}}/categories/{{$category->id}}" method="POST" >
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-slate-300 hover:text-red-500 transition-colors">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-slate-300 ">
                            Aucune catégorie
                        </div>
                    @endforelse
                </div>
            </section>
            
            {{-- Group Balance Section --}}
            <section>
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-scale-balanced text-indigo-500"></i>
                    Équilibre du groupe
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
                        <div class="space-y-4">
                            @forelse ($balances as $balance)
                                @php
                                    $isCurrentUser = $balance['user']->id === $user->id;
                                    $isPositive = $balance['net'] >= 0;
                                @endphp
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl {{ !$isPositive && $isCurrentUser ? 'border-l-4 border-red-500' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 {{ $isCurrentUser ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-600' }} rounded-xl flex items-center justify-center font-bold">
                                            {{ strtoupper(substr($balance['user']->first_name, 0, 1) . substr($balance['user']->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">
                                                {{ $balance['user']->first_name }} {{ $balance['user']->last_name }}
                                                @if($isCurrentUser) <span class="text-slate-400">(Vous)</span> @endif
                                            </p>
                                            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">
                                                A payé {{ number_format($balance['total_paid'], 2, ',', ' ') }} € au total
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if(abs($balance['net']) < 0.01)
                                            <p class="text-sm font-black text-slate-400">0,00 €</p>
                                            <p class="text-[10px] text-slate-400 font-bold">Équilibré</p>
                                        @elseif($isPositive)
                                            <p class="text-sm font-black text-emerald-600">+{{ number_format($balance['net'], 2, ',', ' ') }} €</p>
                                            <p class="text-[10px] text-slate-400 font-bold">À recevoir</p>
                                        @else
                                            <p class="text-sm font-black text-red-600">{{ number_format($balance['net'], 2, ',', ' ') }} €</p>
                                            <p class="text-[10px] text-slate-400 font-bold">À rembourser</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">
                                    <i class="fa-solid fa-scale-balanced text-3xl mb-3"></i>
                                    <p class="text-sm font-medium">Aucune dépense enregistrée</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Who owes whom panel --}}
                    <div class="bg-indigo-900 rounded-[2rem] p-6 text-white shadow-xl shadow-indigo-200/50">
                        <h4 class="font-bold mb-4 text-indigo-200 text-sm tracking-widest uppercase">Règlements suggérés</h4>
                        <div class="space-y-4">
                            @forelse ($adjustedDebts as $debt)
                                <div class="p-4 bg-white/10 rounded-2xl border border-white/10">
                                    <p class="text-xs text-indigo-200 mb-2 font-medium">
                                        {{ $debt['from']->first_name }} doit à {{ $debt['to']->first_name }}
                                    </p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-black">{{ number_format($debt['amount'], 2, ',', ' ') }} €</span>
                                        @if($debt['from']->id === Auth::id() && !$currentUserLeftAt)
                                            <form action="/groups/{{ $group->id }}/settlements" method="POST">
                                                @csrf
                                                <input type="hidden" name="to_user_id" value="{{ $debt['to']->id }}">
                                                <input type="hidden" name="amount" value="{{ $debt['amount'] }}">
                                                <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg transition-colors uppercase">
                                                    Marquer comme payé
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-indigo-300">
                                    <i class="fa-solid fa-check-circle text-2xl mb-2"></i>
                                    <p class="text-xs font-medium">Aucun règlement en attente</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            {{-- Expenses History Section --}}
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Historique des dépenses</h3>
                    <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-500">
                        {{ $expenses->count() }} dépense(s)
                    </span>
                </div>

                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Dépense</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Catégorie</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Payé par</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Montant</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($expenses as $expense)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-slate-800">{{ $expense->title }}</p>
                                        @if($expense->description)
                                            <p class="text-[10px] text-slate-400">{{ $expense->description }}</p>
                                        @endif
                                        <p class="text-[10px] text-slate-400">{{ $expense->created_at->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($expense->category)
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-lg border border-amber-100 italic">{{ $expense->category->name }}</span>
                                        @else
                                            <span class="text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-sm text-slate-600">
                                        {{ $expense->user->first_name }} {{ substr($expense->user->last_name, 0, 1) }}.
                                        @if($expense->user_id === $user->id)
                                            <span class="text-slate-400 text-xs">(Vous)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-sm font-black text-slate-900">{{ number_format($expense->amount, 2, ',', ' ') }} €</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if(!$currentUserLeftAt && ($expense->user_id === Auth::id() || $group->admin_id === Auth::id()))
                                            <form action="/groups/{{ $group->id }}/expenses/{{ $expense->id }}" method="POST" onsubmit="return confirm('Supprimer cette dépense ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-slate-300">
                                            <i class="fa-solid fa-receipt text-3xl mb-3"></i>
                                            <p class="text-sm font-medium">Aucune dépense enregistrée</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- Expense Modal --}}
        <div id="expenseModal" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm modal-overlay"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
                <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden pointer-events-auto">
                    <div class="p-8">
                        <h3 class="text-2xl font-black text-slate-900 mb-6">Ajouter une dépense</h3>
                        <form action="/groups/{{ $group->id }}/expenses" method="POST" class="space-y-4">
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
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Description (optionnel)</label>
                                    <input type="text" name="description" placeholder="Détails supplémentaires..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none">
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl mt-4 shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">Enregistrer la dépense</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if($group->admin->id === $user->id)
            {{-- Category Modal --}}
            <div id="categoryModal" class="fixed inset-0 z-50 hidden">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm modal-overlay"></div>
                <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
                    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden pointer-events-auto">
                        <div class="p-8">
                            <h3 class="text-2xl font-black text-slate-900 mb-6 text-center">Nouvelle Catégorie</h3>
                            <form action="/groups/{{$group->id}}/categories" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nom</label>
                                    <input type="text" name="name" required placeholder="Ex: Internet, Loisirs..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                </div>
                                <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-slate-800 transition-all">
                                    Créer la catégorie
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Invite Modal --}}
            <div id="inviteModal" class="fixed inset-0 z-50 hidden">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm modal-overlay"></div>
                <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
                    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden pointer-events-auto">
                        <div class="p-8">
                            <h3 class="text-2xl font-black text-slate-900 mb-2 text-center">Inviter un membre</h3>
                            <p class="text-sm text-slate-500 text-center mb-6 text-balance">Envoyez une invitation par email pour rejoindre la colocation.</p>
                            
                            <form action="/groups/{{$group->id}}/invite" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Email du futur colocataire</label>
                                    <input type="email" name="email" required placeholder="exemple@mail.com" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
                                    Envoyer l'invitation
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif


    </main>
@endsection

@section("script")
    <script>
        const setupModal = (btnId, modalId) => {
            const btn = document.getElementById(btnId);
            const modal = document.getElementById(modalId);
            if (!btn || !modal) return;
            const overlays = modal.querySelectorAll('.modal-overlay');

            btn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            overlays.forEach(o => o.addEventListener('click', () => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }));
        };

        @if($group->admin->id === $user->id)
            setupModal('openCategoryModal', 'categoryModal');
            setupModal('openInviteModal', 'inviteModal'); 
        @endif
        setupModal('openExpenseModal', 'expenseModal');
    </script>
@endsection