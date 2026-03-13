<style>
    @keyframes float {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(2deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    .chat-window-shadow {
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }
</style>

<div id="ai-chat-widget" style="position: fixed !important; bottom: 30px !important; right: 30px !important; z-index: 99999 !important;">
    <!-- Animated Chat Toggle Button -->
    <button id="chat-toggle"
        class="animate-float flex flex-col items-center justify-center transition-all hover:scale-110 active:scale-95 group relative z-50">
        
        {{-- Custom User Logo --}}
        <div class="bg-white rounded-full shadow-2xl border-4 border-white dark:border-gray-700 w-16 h-16 flex items-center justify-center overflow-hidden">
            <img src="{{ asset('images/chatbot.svg') }}" alt="TIF AI Chatbot" class="w-full h-full object-contain hover:opacity-90 transition-opacity">
        </div>
    </button>

    <!-- Chat Window - Fixed to open UPWARDS (Guaranteed Inline Styles) -->
    <div id="chat-window"
        class="hidden fixed bg-white dark:bg-gray-800 rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-200/60 dark:border-gray-700 transition-all duration-300 transform translate-y-4 opacity-0"
        style="width: 340px !important; height: 480px !important; bottom: 85px !important; right: 20px !important; z-index: 99999 !important;">



        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-800 p-4 text-white flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewbox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm tracking-wide">TIF Connect AI</h4>
                    <p class="text-[10px] text-red-100 flex items-center font-medium mt-0.5">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5 animate-pulse"></span> Online
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button id="chat-clear" class="text-white/60 hover:text-white transition-colors p-1" title="Hapus Chat">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
                <button id="chat-close" class="text-white/80 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Area -->
        <div id="chat-messages" class="flex-1 overflow-y-auto bg-gray-50/50 dark:bg-gray-900 scroll-smooth border-b border-gray-100 dark:border-gray-700" style="padding: 16px; gap: 12px; display: flex; flex-direction: column;">
            {{-- Loaded via JS --}}
        </div>

        <!-- Input Area -->
        <div class="bg-white dark:bg-gray-800 relative z-10" style="padding: 12px;">
            <form id="chat-form" class="flex items-center space-x-2 m-0 relative">
                <input type="text" id="chat-input" placeholder="Ketik pesan..."
                    class="flex-1 bg-gray-100 dark:bg-gray-700 border border-transparent focus:border-red-300 dark:focus:border-red-500 rounded-full focus:ring-2 focus:ring-red-100 dark:focus:ring-red-900/30 dark:text-white transition-all duration-200 ease-in-out"
                    style="padding: 8px 16px; font-size: 12px; height: 38px; outline: none; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);"
                    required autocomplete="off">
                <button type="submit" id="chat-submit"
                    class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-full hover:from-red-700 hover:to-red-800 transition-all duration-200 flex items-center justify-center shrink-0 shadow-md hover:shadow-lg transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                    style="width: 38px; height: 38px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform translate-x-[-1px] translate-y-[1px]" fill="none" viewbox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('chat-toggle');
        const closeBtn = document.getElementById('chat-close');
        const chatWindow = document.getElementById('chat-window');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const chatMessages = document.getElementById('chat-messages');
        const chatSubmit = document.getElementById('chat-submit');
        const clearBtn = document.getElementById('chat-clear');

        // Automatically clear chat history on reload
        fetch('{{ route("ai.chat.clear") }}', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).catch(err => console.error('Error auto-clearing chat:', err));

        // Toggle chat window with smooth transitions
        toggleBtn.addEventListener('click', () => {
            const isHidden = chatWindow.classList.contains('hidden');
            
            if (isHidden) {
                chatWindow.classList.remove('hidden');
                // Trigger animation reflow
                setTimeout(() => {
                    chatWindow.classList.remove('opacity-0', 'translate-y-4');
                    chatWindow.classList.add('opacity-100', 'translate-y-0');
                }, 10);
                loadHistory();
                chatInput.focus();
            } else {
                chatWindow.classList.add('opacity-0', 'translate-y-4');
                chatWindow.classList.remove('opacity-100', 'translate-y-0');
                setTimeout(() => {
                    chatWindow.classList.add('hidden');
                }, 300);
            }
        });

        // Clear Chat History
        clearBtn.addEventListener('click', async () => {
            if (confirm('Hapus semua riwayat percakapan?')) {
                try {
                    await fetch('{{ route("ai.chat.clear") }}', {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    chatMessages.innerHTML = '';
                    loadHistory();
                } catch (error) {
                    console.error('Error clearing history:', error);
                }
            }
        });

        closeBtn.addEventListener('click', () => {
            chatWindow.classList.add('opacity-0', 'translate-y-4');
            chatWindow.classList.remove('opacity-100', 'translate-y-0');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
            }, 300);
        });

        // Load History
        async function loadHistory() {
            try {
                const response = await fetch('{{ route("ai.chat.history") }}');
                const history = await response.json();
                chatMessages.innerHTML = '';

                history.forEach(msg => {
                    appendMessage(msg.message, msg.is_ai);
                });

                scrollToBottom();
            } catch (error) {
                console.error('Error loading history:', error);
            }
        }

        // Send Message — optimized JSON request
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (!message) return;

            appendMessage(message, false);
            chatInput.value = '';
            chatSubmit.disabled = true;

            // Show typing indicator
            const typingId = 'typing-' + Date.now();
            const typingHtml = `<div id="${typingId}" class="flex justify-start">
                <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-400 border border-gray-100 dark:border-gray-700">
                    <span class="animate-bounce inline-block">•</span>
                    <span class="animate-bounce inline-block delay-75">•</span>
                    <span class="animate-bounce inline-block delay-150">•</span>
                </div>
            </div>`;
            chatMessages.insertAdjacentHTML('beforeend', typingHtml);
            scrollToBottom();

            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 20000); // 20s timeout

            try {
                const response = await fetch('{{ route("ai.chat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    signal: controller.signal,
                    body: JSON.stringify({ message })
                });

                clearTimeout(timeoutId);

                const typingEl = document.getElementById(typingId);
                if (typingEl) typingEl.remove();

                if (!response.ok) {
                    throw new Error('Server returned ' + response.status);
                }

                const data = await response.json();
                if (data.error) {
                    appendMessage('Maaf Bapak/Ibu, terjadi kendala: ' + data.error, true);
                } else {
                    appendMessage(data.message || 'Maaf, saya belum bisa memberikan jawaban untuk saat ini.', true);
                }
            } catch (error) {
                clearTimeout(timeoutId);
                const typingEl = document.getElementById(typingId);
                if (typingEl) typingEl.remove();
                
                if (error.name === 'AbortError') {
                    appendMessage('Mohon maaf Bapak/Ibu, respon AI sedang lambat karena trafik tinggi. Silakan coba sesaat lagi.', true);
                } else {
                    appendMessage('Mohon maaf Bapak/Ibu, sepertinya sedang ada gangguan koneksi. Silakan coba lagi nanti.', true);
                }
            }

            chatSubmit.disabled = false;
            scrollToBottom();
        });

        function createAiMessageBubble() {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex justify-start';
            const bubble = document.createElement('div');
            bubble.className = 'bg-white dark:bg-gray-800 rounded-2xl rounded-bl-none border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200 p-3 shadow-sm text-sm max-w-[85%] whitespace-pre-wrap';
            bubble.textContent = '';
            wrapper.appendChild(bubble);
            chatMessages.appendChild(wrapper);
            return bubble;
        }

        function appendMessage(text, isAi) {
            const flexClass = isAi ? 'justify-start' : 'justify-end';
            const bgClass = isAi
                ? 'bg-white dark:bg-gray-800 rounded-bl-none border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200 shadow-sm'
                : 'bg-gradient-to-br from-red-500 to-red-600 rounded-br-none text-white shadow-md border border-red-500';

            const wrapper = document.createElement('div');
            wrapper.className = `flex ${flexClass}`;
            wrapper.style.marginBottom = "12px";
            
            const bubble = document.createElement('div');
            bubble.className = `${bgClass} rounded-2xl whitespace-pre-wrap leading-relaxed`;
            bubble.style.padding = "10px 14px";
            bubble.style.fontSize = "12px";
            bubble.style.maxWidth = "85%";
            
            bubble.textContent = text;
            wrapper.appendChild(bubble);
            chatMessages.appendChild(wrapper);
        }

        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>