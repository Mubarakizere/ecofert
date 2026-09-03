<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading text-2xl sm:text-3xl font-bold text-gray-800 tracking-wide">
                    Waste Inventory & Production
                </h2>
                <p class="text-sm text-gray-500 font-body mt-1">
                    Welcome back, <span class="font-semibold text-emerald-700">{{ Auth::user()->name }}</span> &middot; Home Gardener Dashboard
                </p>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold font-body">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Inventory Active
            </div>
        </div>
    </x-slot>

    <div class="py-8 relative" x-data="{ chatOpen: false, chatMessages: [], chatInput: '', sending: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ── Banner Notice ────────────────── --}}
            @if(session('info'))
                <div class="rounded-xl border border-amber-200 bg-amber-50/90 p-4 text-amber-800 text-sm flex items-center gap-3 shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold">Notice</p>
                        <p class="text-xs text-amber-700 mt-0.5">{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            {{-- ── AI Recipe Generation Card ── --}}
            @if(session('ai_recipe'))
                @php
                    $recipeData = session('ai_recipe');
                @endphp
                <div class="card-dark overflow-hidden border-2 border-emerald-500/80 shadow-lg relative bg-white">
                    <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 p-6 text-white relative overflow-hidden">
                        <div class="flex items-center justify-between relative z-10">
                            <div>
                                <span class="text-[0.65rem] font-bold uppercase tracking-widest bg-emerald-500/30 text-emerald-200 px-2 py-0.5 rounded">
                                    AI Generated Mix
                                </span>
                                <h3 class="font-heading text-xl sm:text-2xl font-bold text-white mt-1">
                                    Custom Fertilizer Recipe
                                </h3>
                            </div>
                            <form method="POST" action="{{ route('household.waste-logs.produce') }}">
                                @csrf
                                @if(isset($recipeData['used']) && is_array($recipeData['used']))
                                    @foreach($recipeData['used'] as $i => $item)
                                        <input type="hidden" name="used[{{ $i }}][waste_type]" value="{{ $item['waste_type'] }}">
                                        <input type="hidden" name="used[{{ $i }}][quantity]" value="{{ $item['quantity'] }}">
                                    @endforeach
                                @endif
                                <button type="submit" class="btn-gold px-5 py-2.5 rounded-xl font-bold tracking-wide shadow-md hover:shadow-lg transition flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                    </svg>
                                    Confirm &amp; Deduct Stock
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-gray-800 font-body leading-relaxed whitespace-pre-line bg-gray-50 rounded-lg p-5 border border-gray-200 shadow-inner">
                            {{ $recipeData['recipe'] ?? 'No recipe details provided.' }}
                        </div>
                        
                        <div class="mt-5 border-t border-gray-100 pt-5">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Stock to be Used:</h4>
                            <div class="flex flex-wrap gap-3">
                                @if(isset($recipeData['used']) && is_array($recipeData['used']))
                                    @foreach($recipeData['used'] as $item)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border bg-emerald-50 text-emerald-800 border-emerald-200">
                                            {{ $item['quantity'] }}kg of {{ $item['waste_type'] }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Main Two-Column Layout ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- ── Left Column: Stock & Submission (5 cols) ──────────────── --}}
                <div class="lg:col-span-5 space-y-6">
                    
                    {{-- Current Stock Dashboard --}}
                    <div class="card-dark p-6 border-t-4 border-emerald-500">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <p class="section-label">Inventory</p>
                                <h3 class="font-heading text-xl font-bold text-gray-800 mt-0.5">My Current Stock</h3>
                            </div>
                            <form method="POST" action="{{ route('household.waste-logs.recipe') }}">
                                @csrf
                                <button type="submit" class="p-2 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 transition" title="Generate Recipe from Stock">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.428-1.428L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.428-1.428l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.428 1.428l1.183.394-1.183.394a2.25 2.25 0 0 0-1.428 1.428Z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-3">
                            <div class="stat-card stat-card--gold p-4 text-center rounded-xl bg-white border border-gray-200 shadow-2xs">
                                <span class="text-xl">🍌</span>
                                <p class="text-2xl font-heading font-bold text-amber-700 mt-1">{{ number_format(isset($wasteStocks['Banana Peels']) ? $wasteStocks['Banana Peels']->quantity : 0, 1) }}<span class="text-xs">kg</span></p>
                                <p class="text-[0.62rem] text-gray-400 font-body font-semibold uppercase tracking-wider mt-0.5">Banana Peels</p>
                            </div>
                            <div class="stat-card stat-card--emerald p-4 text-center rounded-xl bg-white border border-gray-200 shadow-2xs">
                                <span class="text-xl">🥚</span>
                                <p class="text-2xl font-heading font-bold text-emerald-700 mt-1">{{ number_format(isset($wasteStocks['Eggshells']) ? $wasteStocks['Eggshells']->quantity : 0, 1) }}<span class="text-xs">kg</span></p>
                                <p class="text-[0.62rem] text-gray-400 font-body font-semibold uppercase tracking-wider mt-0.5">Eggshells</p>
                            </div>
                            <div class="stat-card stat-card--amber p-4 text-center rounded-xl bg-white border border-gray-200 shadow-2xs">
                                <span class="text-xl">☕</span>
                                <p class="text-2xl font-heading font-bold text-amber-600 mt-1">{{ number_format(isset($wasteStocks['Coffee Grounds']) ? $wasteStocks['Coffee Grounds']->quantity : 0, 1) }}<span class="text-xs">kg</span></p>
                                <p class="text-[0.62rem] text-gray-400 font-body font-semibold uppercase tracking-wider mt-0.5">Coffee Grounds</p>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('household.waste-logs.recipe') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full btn-gold rounded-xl px-4 py-2 text-sm font-bold shadow transition flex justify-center items-center gap-2">
                                Generate AI Recipe from Stock
                            </button>
                        </form>
                    </div>

                    {{-- Record Waste Form --}}
                    @if(Auth::user()->can('log_waste'))
                    <div class="card-dark p-6">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <p class="section-label">Action</p>
                                <h3 class="font-heading text-xl font-bold text-gray-800 mt-0.5">Log New Kitchen Waste</h3>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('household.waste-logs.store') }}" id="waste-log-form">
                            @csrf
                            {{-- Visual Choice Cards for Waste Type --}}
                            <div class="mb-5">
                                <div class="space-y-3" x-data="{ selectedType: '{{ old('waste_type', 'Banana Peels') }}' }">
                                    <label @click="selectedType = 'Banana Peels'" :class="selectedType === 'Banana Peels' ? 'border-amber-500 bg-amber-50/50 ring-1 ring-amber-400' : 'border-gray-200 bg-white'" class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition-all duration-150">
                                        <div class="flex items-center gap-3">
                                            <span class="text-2xl p-2 rounded-lg bg-yellow-100/80">🍌</span>
                                            <div>
                                                <p class="font-body font-semibold text-sm text-gray-800">Banana Peels</p>
                                            </div>
                                        </div>
                                        <input type="radio" name="waste_type" value="Banana Peels" x-model="selectedType" class="w-4 h-4 text-emerald-600">
                                    </label>

                                    <label @click="selectedType = 'Eggshells'" :class="selectedType === 'Eggshells' ? 'border-orange-500 bg-orange-50/50 ring-1 ring-orange-400' : 'border-gray-200 bg-white'" class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition-all duration-150">
                                        <div class="flex items-center gap-3">
                                            <span class="text-2xl p-2 rounded-lg bg-orange-100/80">🥚</span>
                                            <div>
                                                <p class="font-body font-semibold text-sm text-gray-800">Eggshells</p>
                                            </div>
                                        </div>
                                        <input type="radio" name="waste_type" value="Eggshells" x-model="selectedType" class="w-4 h-4 text-emerald-600">
                                    </label>

                                    <label @click="selectedType = 'Coffee Grounds'" :class="selectedType === 'Coffee Grounds' ? 'border-amber-700 bg-amber-50/70 ring-1 ring-amber-600' : 'border-gray-200 bg-white'" class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition-all duration-150">
                                        <div class="flex items-center gap-3">
                                            <span class="text-2xl p-2 rounded-lg bg-amber-100/80">☕</span>
                                            <div>
                                                <p class="font-body font-semibold text-sm text-gray-800">Coffee Grounds</p>
                                            </div>
                                        </div>
                                        <input type="radio" name="waste_type" value="Coffee Grounds" x-model="selectedType" class="w-4 h-4 text-emerald-600">
                                    </label>
                                </div>
                                @error('waste_type')<p class="mt-2 text-xs text-red-600 font-body">{{ $message }}</p>@enderror
                            </div>

                            <div class="mb-5 grid grid-cols-3 gap-3">
                                <div class="col-span-2">
                                    <label for="quantity" class="block text-xs font-semibold uppercase text-gray-500 mb-1.5">Quantity</label>
                                    <input id="quantity" type="number" name="quantity" step="0.01" min="0.01" value="{{ old('quantity') }}" class="w-full rounded-xl border-gray-200 focus:ring-emerald-400 shadow-2xs">
                                </div>
                                <div>
                                    <label for="unit" class="block text-xs font-semibold uppercase text-gray-500 mb-1.5">Unit</label>
                                    <select id="unit" name="unit" class="w-full rounded-xl border-gray-200 focus:ring-emerald-400 shadow-2xs">
                                        <option value="kg">kg</option>
                                        <option value="g">g</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-emerald-600 text-white rounded-xl px-5 py-3 text-sm font-bold shadow hover:bg-emerald-700 transition">
                                Add to Inventory
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                {{-- ── Right Column: Log History ───────────── --}}
                <div class="lg:col-span-7 space-y-4">
                    <div class="flex items-center justify-between mb-1">
                        <div>
                            <p class="section-label">Log History</p>
                            <h3 class="font-heading text-xl font-bold text-gray-800 mt-0.5">Transactions</h3>
                        </div>
                    </div>

                    @if($wasteLogs->isEmpty())
                        <div class="card-dark p-12 flex flex-col items-center justify-center text-center">
                            <h4 class="font-heading font-bold text-gray-800 text-lg">No Transactions Yet</h4>
                        </div>
                    @else
                        <div class="card-dark overflow-hidden shadow-2xs">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50/80 border-b border-gray-200">
                                            <th class="px-5 py-3.5 text-[0.7rem] font-bold uppercase text-gray-400">Type</th>
                                            <th class="px-5 py-3.5 text-[0.7rem] font-bold uppercase text-gray-400">Item</th>
                                            <th class="px-5 py-3.5 text-[0.7rem] font-bold uppercase text-gray-400">Amount</th>
                                            <th class="px-5 py-3.5 text-[0.7rem] font-bold uppercase text-gray-400">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @foreach($wasteLogs as $log)
                                            <tr class="hover:bg-gray-50/60">
                                                <td class="px-5 py-4">
                                                    @if($log->transaction_type === 'added')
                                                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">+ Added</span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">- Used</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4 text-sm font-semibold">
                                                    {{ $log->waste_type }}
                                                </td>
                                                <td class="px-5 py-4 text-sm font-bold text-gray-800">
                                                    {{ $log->quantity + 0 }} {{ $log->unit }}
                                                </td>
                                                <td class="px-5 py-4 text-sm text-gray-500">
                                                    {{ $log->date_recorded ? $log->date_recorded->format('M d, Y') : now()->format('M d, Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

            </div>{{-- /grid --}}
        </div>
        
        {{-- Floating AI Assistant Widget --}}
        <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
            <!-- Chat Window -->
            <div x-show="chatOpen" style="display: none; height: 500px; max-height: 70vh;" class="mb-4 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col">
                <!-- Header -->
                <div class="bg-gradient-to-r from-emerald-800 to-teal-700 p-4 text-white flex items-center justify-between shadow-md z-10">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🤖</span>
                        <h3 class="font-bold font-heading">EcoFert AI Assistant</h3>
                    </div>
                    <button @click="chatOpen = false" class="text-emerald-200 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-4 bg-gray-50/50 space-y-4" id="chat-messages-container">
                    <template x-for="(msg, index) in chatMessages" :key="index">
                        <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <div :class="msg.role === 'user' ? 'bg-emerald-600 text-white rounded-l-xl rounded-tr-xl' : 'bg-white border border-gray-200 text-gray-800 rounded-r-xl rounded-tl-xl'" class="px-4 py-2.5 max-w-[85%] shadow-sm text-sm font-body whitespace-pre-line" x-text="msg.content"></div>
                        </div>
                    </template>
                    <div x-show="sending" class="flex justify-start">
                        <div class="bg-white border border-gray-200 text-gray-500 rounded-r-xl rounded-tl-xl px-4 py-2.5 shadow-sm text-xs flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"></span>
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Input -->
                <div class="p-3 bg-white border-t border-gray-200">
                    <form @submit.prevent="
                        if(!chatInput.trim() || sending) return;
                        chatMessages.push({role: 'user', content: chatInput});
                        let input = chatInput;
                        chatInput = '';
                        sending = true;
                        
                        setTimeout(() => {
                            let container = document.getElementById('chat-messages-container');
                            container.scrollTop = container.scrollHeight;
                        }, 50);
                        
                        fetch('{{ route('household.chat.send') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({message: input})
                        })
                        .then(res => res.json())
                        .then(data => {
                            chatMessages.push(data.message);
                            sending = false;
                            setTimeout(() => {
                                let container = document.getElementById('chat-messages-container');
                                container.scrollTop = container.scrollHeight;
                            }, 50);
                        })
                        .catch(err => {
                            sending = false;
                            chatMessages.push({role: 'model', content: 'Error connecting to AI service.'});
                        });
                    " class="flex items-center gap-2">
                        <input type="text" x-model="chatInput" placeholder="Ask about composting..." class="flex-1 rounded-xl border border-gray-300 px-3 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <button type="submit" :disabled="sending" class="p-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 disabled:opacity-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Floating Button -->
            <button @click="
                chatOpen = !chatOpen; 
                if(chatOpen && chatMessages.length === 0) {
                    fetch('{{ route('household.chat.history') }}')
                        .then(res => res.json())
                        .then(data => {
                            chatMessages = data;
                            setTimeout(() => {
                                let container = document.getElementById('chat-messages-container');
                                container.scrollTop = container.scrollHeight;
                            }, 50);
                        });
                }
            " class="w-14 h-14 bg-emerald-600 rounded-full shadow-lg flex items-center justify-center text-white hover:bg-emerald-700 hover:scale-105 transition transform">
                <svg x-show="!chatOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                <svg x-show="chatOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </button>
        </div>
    </div>
</x-admin-layout>
