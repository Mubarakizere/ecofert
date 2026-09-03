<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    AI Extension Assistant
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Context-aware agricultural extension advisor for organic waste valorisation and plant care.
                </p>
            </div>
            <a href="{{ route('household.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition">
                &larr; Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{
        newMessage: '',
        loading: false,
        messages: {{ json_encode($messages) }},

        async sendMessage() {
            if (!this.newMessage.trim() || this.loading) return;

            const userText = this.newMessage;
            this.messages.push({ role: 'user', content: userText, created_at: new Date().toISOString() });
            this.newMessage = '';
            this.loading = true;

            this.$nextTick(() => { this.scrollToBottom(); });

            try {
                const response = await fetch('{{ route("household.chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: userText })
                });

                const data = await response.json();
                if (data.message) {
                    this.messages.push(data.message);
                }
            } catch (error) {
                this.messages.push({ role: 'model', content: 'Unable to connect to AI Assistant. Please try again.', created_at: new Date().toISOString() });
            } finally {
                this.loading = false;
                this.$nextTick(() => { this.scrollToBottom(); });
            }
        },

        sendSuggested(text) {
            this.newMessage = text;
            this.sendMessage();
        },

        scrollToBottom() {
            const container = this.$refs.chatContainer;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
    }" x-init="$nextTick(() => scrollToBottom())">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Suggested Topics -->
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="font-semibold text-slate-500 uppercase tracking-wider">Suggested Topics:</span>
                <button type="button" @click="sendSuggested('How do banana peels supply potassium to soil?')" class="px-3 py-1.5 bg-white border border-slate-200 hover:border-emerald-500 text-slate-700 rounded-lg transition shadow-sm">
                    How do banana peels supply potassium to soil?
                </button>
                <button type="button" @click="sendSuggested('How do I grind eggshells for maximum calcium absorption?')" class="px-3 py-1.5 bg-white border border-slate-200 hover:border-emerald-500 text-slate-700 rounded-lg transition shadow-sm">
                    How do I prepare eggshell powder?
                </button>
                <button type="button" @click="sendSuggested('What is the best way to balance coffee grounds acidity?')" class="px-3 py-1.5 bg-white border border-slate-200 hover:border-emerald-500 text-slate-700 rounded-lg transition shadow-sm">
                    Balancing coffee grounds acidity
                </button>
            </div>

            <!-- Chat Window Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[600px]">

                <!-- Chat Messages Scroll Area -->
                <div x-ref="chatContainer" class="flex-1 p-6 overflow-y-auto space-y-4">

                    <template x-if="messages.length === 0">
                        <div class="text-center py-16 space-y-3">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center mx-auto text-emerald-700 font-bold text-lg">
                                AI
                            </div>
                            <h3 class="text-base font-bold text-slate-900">EcoFert Agricultural Assistant</h3>
                            <p class="text-xs text-slate-500 max-w-md mx-auto">
                                Ask questions regarding organic waste preparation, NPK nutrient balance, fermentation days, or plant growth monitoring.
                            </p>
                        </div>
                    </template>

                    <template x-for="(msg, index) in messages" :key="index">
                        <div class="flex flex-col" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold" :class="msg.role === 'user' ? 'text-slate-900' : 'text-emerald-700'" x-text="msg.role === 'user' ? 'You' : 'EcoFert Extension Assistant'"></span>
                            </div>
                            <div class="max-w-2xl px-4 py-3 rounded-2xl text-sm leading-relaxed"
                                 :class="msg.role === 'user' ? 'bg-slate-900 text-white rounded-tr-none' : 'bg-slate-100 text-slate-800 border border-slate-200 rounded-tl-none'">
                                <p x-text="msg.content" class="whitespace-pre-line"></p>
                            </div>
                        </div>
                    </template>

                    <!-- Loading indicator -->
                    <div x-show="loading" class="flex items-start gap-2">
                        <div class="bg-slate-100 border border-slate-200 text-slate-500 px-4 py-2.5 rounded-2xl rounded-tl-none text-xs flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Analyzing agricultural guidance...</span>
                        </div>
                    </div>

                </div>

                <!-- Input Area -->
                <div class="p-4 border-t border-slate-100 bg-slate-50 rounded-b-2xl">
                    <form @submit.prevent="sendMessage()" class="flex items-center gap-3">
                        <input type="text"
                               x-model="newMessage"
                               :disabled="loading"
                               placeholder="Ask about organic fertilizer preparation or plant care..."
                               class="flex-1 text-sm border-slate-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-emerald-500 disabled:bg-slate-100">
                        <button type="submit"
                                :disabled="!newMessage.trim() || loading"
                                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 text-white font-bold text-sm rounded-xl shadow-sm transition">
                            Send
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
