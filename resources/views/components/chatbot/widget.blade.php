<div class="fixed bottom-5 right-5 z-[999] font-sans">
    <div id="bertani-chat-panel" class="mb-3 hidden w-[92vw] max-w-sm overflow-hidden rounded-2xl border border-green-100 bg-white shadow-2xl">
        <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-4 py-3 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold">🌱 Bertani</p>
                    <p class="text-xs text-green-50">Asisten pertanian TaniDirect</p>
                </div>
                <button id="bertani-chat-close" type="button" class="rounded-full p-1 hover:bg-white/20">✕</button>
            </div>
        </div>

        <div class="flex h-80 flex-col">
            <div id="bertani-chat-messages" class="flex-1 space-y-3 overflow-y-auto bg-[#f8faf5] p-4 text-sm">
                <div class="max-w-[85%] rounded-2xl bg-green-100 p-3 text-gray-700">
                    Halo! Saya Bertani. Saya bisa membantu Anda mencari produk, mengecek harga pasar, memahami Order Pool, dan menjelaskan cara memakai TaniDirect.
                </div>
            </div>

            <div class="border-t border-green-100 bg-white p-3">
                <form id="bertani-chat-form" class="flex gap-2">
                    <input id="bertani-chat-input" type="text" placeholder="Tanya tentang produk atau harga..." class="flex-1 rounded-full border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none">
                    <button type="submit" class="rounded-full bg-green-600 px-3 py-2 text-sm font-semibold text-white hover:bg-green-700">Kirim</button>
                </form>
            </div>
        </div>
    </div>

    <button id="bertani-chat-toggle" type="button" class="flex items-center gap-2 rounded-full bg-green-600 px-4 py-3 text-sm font-semibold text-white shadow-lg transition hover:scale-105 hover:bg-green-700">
        <span class="text-lg">🌱</span>
        <span>Bertani</span>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const panel = document.getElementById('bertani-chat-panel');
        const toggle = document.getElementById('bertani-chat-toggle');
        const close = document.getElementById('bertani-chat-close');
        const form = document.getElementById('bertani-chat-form');
        const input = document.getElementById('bertani-chat-input');
        const messages = document.getElementById('bertani-chat-messages');

        if (!panel || !toggle || !close || !form || !input || !messages) {
            return;
        }

        toggle.addEventListener('click', () => {
            panel.classList.toggle('hidden');
        });

        close.addEventListener('click', () => {
            panel.classList.add('hidden');
        });

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const text = input.value.trim();
            if (!text) {
                return;
            }

            const userBubble = document.createElement('div');
            userBubble.className = 'ml-auto max-w-[85%] rounded-2xl bg-green-600 p-3 text-white';
            userBubble.textContent = text;
            messages.appendChild(userBubble);

            input.value = '';

            const loadingBubble = document.createElement('div');
            loadingBubble.className = 'max-w-[85%] rounded-2xl bg-white p-3 text-gray-700 shadow-sm';
            loadingBubble.textContent = 'Bertani sedang menyiapkan jawaban...';
            messages.appendChild(loadingBubble);

            try {
                const response = await fetch('{{ route('chatbot.message') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ message: text })
                });

                const rawText = await response.text();
                let data = {};

                try {
                    data = rawText ? JSON.parse(rawText) : {};
                } catch (parseError) {
                    data = { reply: rawText || 'Maaf, saya sedang tidak bisa menjawab saat ini.' };
                }

                const reply = (data.reply || 'Maaf, saya sedang tidak bisa menjawab saat ini.').replace(/\n/g, '<br>');

                loadingBubble.remove();

                const assistantBubble = document.createElement('div');
                assistantBubble.className = 'max-w-[85%] rounded-2xl bg-white p-3 text-gray-700 shadow-sm';
                assistantBubble.innerHTML = reply;
                messages.appendChild(assistantBubble);
            } catch (error) {
                loadingBubble.remove();

                const assistantBubble = document.createElement('div');
                assistantBubble.className = 'max-w-[85%] rounded-2xl bg-white p-3 text-gray-700 shadow-sm';
                assistantBubble.textContent = 'Maaf, saya sedang tidak bisa menjawab saat ini. Silakan coba lagi.';
                messages.appendChild(assistantBubble);
            }

            messages.scrollTop = messages.scrollHeight;
        });
    });
</script>
