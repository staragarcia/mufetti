@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background pt-24 pb-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Navegação de Retorno --}}
        <div class="mb-8">
            <a href="{{ route('admin.groups.index') }}" class="text-sm text-muted-foreground hover:text-primary flex items-center gap-1 transition-colors">
                ← Back to Admin Groups
            </a>
            <h1 class="text-3xl font-bold text-foreground mt-4">Group Management</h1>
            <p class="text-muted-foreground">Group: <span class="text-foreground font-semibold">{{ $group->name }}</span></p>
        </div>

        {{-- Tabs de Navegação --}}
        <div class="flex border-b border-border mb-8 gap-8">
            <button onclick="switchTab('members')" id="tab-members-btn" class="pb-4 text-sm font-bold border-b-2 border-primary text-primary transition-all">
                Members ({{ $members->total() }})
            </button>
            @if(!$group->is_public)
            <button onclick="switchTab('requests')" id="tab-requests-btn" class="pb-4 text-sm font-bold border-b-2 border-transparent text-muted-foreground hover:text-foreground transition-all flex items-center gap-2">
                Join Requests
                @if($group->join_requests_count > 0)
                    <span class="bg-orange-500 text-white text-[10px] px-1.5 py-0.5 rounded-full animate-pulse">
                        {{ $group->join_requests_count }}
                    </span>
                @endif
            </button>
            @endif
        </div>

        {{-- Secção de Membros --}}
        <div id="tab-members" class="tab-content">
            @if($members->isEmpty())
                <div class="text-center py-12 bg-card border border-border rounded-xl">
                    <p class="text-muted-foreground">No members in this group yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($members as $member)
                        <div class="bg-card border border-border rounded-xl p-4 hover:shadow-sm transition-all group">
                            <div class="flex items-center gap-4">
                                <img src="{{ $member->avatar }}" class="h-12 w-12 rounded-full border border-border object-cover bg-muted" />

                                <div class="flex-1 min-w-0">
                                    <span class="font-bold text-foreground block truncate">{{ $member->name }}</span>
                                    <p class="text-xs text-muted-foreground truncate">{{ '@' . $member->username }}</p>
                                </div>

                                <div class="flex gap-2">
                                    @if($member->id !== $group->owner)
                                        <form action="{{ route('groups.transferOwner', [$group->id, $member->id]) }}" method="POST" onsubmit="return confirm('Transfer group ownership?')">
                                            @csrf @method('PUT')
                                            <button class="px-3 py-1.5 bg-muted text-foreground rounded-lg hover:bg-yellow-500 hover:text-white text-[11px] font-bold transition-all">
                                                Make Owner
                                            </button>
                                        </form>

                                        <form action="{{ route('groups.members.remove', [$group->id, $member->id]) }}" method="POST" onsubmit="return confirm('Remove member?')">
                                            @csrf @method('DELETE')
                                            <button class="px-3 py-1.5 bg-muted text-foreground rounded-lg hover:bg-red-600 hover:text-white text-[11px] font-bold transition-all">
                                                Remove
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 py-1.5 text-primary text-[11px] font-bold uppercase tracking-wider italic">Owner</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $members->links() }}</div>
            @endif
        </div>

        {{-- Secção de Requests (Escondida por defeito) --}}
        <div id="tab-requests" class="tab-content hidden">
            @php $requests = $group->joinRequests; @endphp
            
            @if(!$requests || $requests->isEmpty())
                <div class="text-center py-12 bg-card border border-border rounded-xl">
                    <p class="text-muted-foreground">No pending requests at the moment.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($requests as $request)
                        <div class="bg-card border border-border rounded-xl p-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="{{ $request->user->avatar }}" class="h-10 w-10 rounded-full border border-border object-cover">
                                <div>
                                    <div class="font-bold text-foreground">{{ $request->user->name }}</div>
                                    <div class="text-xs text-muted-foreground">{{ '@' . $request->user->username }}</div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <form action="{{ route('admin.groups.requests.accept', [$group, $request]) }}" method="POST">
                                    @csrf
                                    <button class="px-4 py-1.5 bg-primary text-primary-foreground rounded-lg text-xs font-bold hover:opacity-90 transition">
                                        Accept
                                    </button>
                                </form>
                                <form action="{{ route('admin.groups.requests.reject', [$group, $request]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="px-4 py-1.5 bg-muted text-foreground rounded-lg text-xs font-bold hover:bg-red-50 hover:text-red-600 transition">
                                        Decline
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        // Esconder todos os conteúdos
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // Mostrar o selecionado
        document.getElementById('tab-' + tab).classList.remove('hidden');

        // Resetar estilos dos botões
        const membersBtn = document.getElementById('tab-members-btn');
        const requestsBtn = document.getElementById('tab-requests-btn');

        [membersBtn, requestsBtn].forEach(btn => {
            if(btn) {
                btn.classList.remove('border-primary', 'text-primary');
                btn.classList.add('border-transparent', 'text-muted-foreground');
            }
        });

        // Ativar botão atual
        const activeBtn = document.getElementById('tab-' + tab + '-btn');
        activeBtn.classList.add('border-primary', 'text-primary');
        activeBtn.classList.remove('border-transparent', 'text-muted-foreground');
    }
</script>
@endsection