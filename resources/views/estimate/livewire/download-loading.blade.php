<iframe name="estimate-download-frame" class="hidden" title="ダウンロード"></iframe>

<div id="estimate-download-loading"
    class="fixed inset-0 z-40 hidden items-center justify-center bg-slate-950/40 p-4 backdrop-blur-sm" role="status"
    aria-live="polite">
    <div class="flex w-full max-w-sm items-center gap-4 rounded-2xl bg-white p-5 shadow-2xl shadow-slate-950/20">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
            <svg class="h-6 w-6 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3"></circle>
                <path class="opacity-90" fill="currentColor" d="M12 3a9 9 0 0 1 9 9h-3a6 6 0 0 0-6-6V3Z"></path>
            </svg>
        </span>
        <div>
            <p class="font-semibold text-slate-900">ダウンロード中</p>
            <p class="mt-1 text-sm text-slate-500">しばらくお待ちください</p>
        </div>
    </div>
</div>

<script>
    (() => {
        const overlay = document.getElementById('estimate-download-loading');
        let activeToken = null;
        let completionTimer = null;
        let fallbackTimer = null;

        const hideOverlay = () => {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            activeToken = null;
            window.clearInterval(completionTimer);
            window.clearTimeout(fallbackTimer);
        };

        document.querySelectorAll('form[target="estimate-download-frame"]').forEach((form) => {
            form.addEventListener('submit', () => {
                activeToken = Array.from(crypto.getRandomValues(new Uint8Array(16)))
                    .map((byte) => byte.toString(16).padStart(2, '0')).join('');
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = 'download_token';
                tokenInput.value = activeToken;
                form.appendChild(tokenInput);

                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                fallbackTimer = window.setTimeout(hideOverlay, 15000);

                completionTimer = window.setInterval(() => {
                    const cookie = document.cookie.split('; ').find((item) => item.startsWith('estimate_download_complete='));
                    if (!cookie || decodeURIComponent(cookie.split('=').slice(1).join('=')) !== activeToken) return;

                    document.cookie = 'estimate_download_complete=; Max-Age=0; path=/; SameSite=Lax';
                    hideOverlay();
                }, 250);
            });
        });

    })();
</script>
