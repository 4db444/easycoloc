@extends("layout.base")
@section("main")
    <main class="flex-1 flex items-center justify-center p-6 bg-slate-50/50">
        <div class="w-full max-w-lg">
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-indigo-100/50 border border-slate-100 overflow-hidden transform transition-all">
                
                <div class="bg-indigo-600 p-10 text-center relative">
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
                        </svg>
                    </div>
                    
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-md text-white rounded-[2rem] flex items-center justify-center mx-auto mb-4 border border-white/30 shadow-xl">
                        <i class="fa-solid fa-house-chimney-user text-3xl"></i>
                    </div>
                    <h2 class="text-white text-2xl font-black italic tracking-tight">Invitation Reçue !</h2>
                </div>

                <div class="p-10 text-center">
                    <p class="text-slate-500 font-medium mb-2 uppercase text-[10px] tracking-[0.2em]">Vous avez été invité à rejoindre</p>
                    <h3 class="text-3xl font-black text-slate-900 mb-4">{{ $group->name }}</h3>
                    
                    @if ($alreadyMember)
                        <div class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-2xl mb-8 flex items-start gap-3">
                            <i class="fa-solid fa-triangle-exclamation mt-0.5 text-amber-500 flex-shrink-0"></i>
                            <p class="text-sm font-medium text-left">Vous êtes déjà membre de ce groupe. Pas besoin de rejoindre à nouveau !</p>
                        </div>

                        <a href="/groups/{{ $group->id }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
                            <span>Aller au groupe</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @else
                        <div class="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-100">
                            <p class="text-slate-600 leading-relaxed italic">
                                "Salut ! On a créé ce groupe pour gérer nos dépenses communes plus facilement. Rejoins-nous pour qu'on soit tous à jour !"
                            </p>
                        </div>

                        <form action="/groups/{{$group->id}}/join/" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="/dashboard" class="flex-1 px-8 py-4 rounded-2xl font-bold text-slate-400 hover:bg-slate-50 hover:text-slate-600 transition-all flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-xmark"></i>
                                    Plus tard
                                </a>

                                <button type="submit" class="flex-[2] bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center gap-3">
                                    <span>Rejoindre le groupe</span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    @endif

                    <p class="mt-8 text-[11px] text-slate-400">
                        En rejoignant ce groupe, vos dépenses seront visibles par les autres membres. 
                        Vous pouvez quitter le groupe à tout moment.
                    </p>
                </div>
            </div>

            <div class="text-center mt-6">
                <a href="/dashboard" class="text-slate-400 hover:text-indigo-600 text-sm font-bold transition-colors">
                    <i class="fa-solid fa-chevron-left text-[10px] mr-1"></i>
                    Retour au tableau de bord
                </a>
            </div>
        </div>
    </main>
@endsection