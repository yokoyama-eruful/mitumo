@php
    $feedbackMessages = $errors->getBag('default')->getMessages();
    $feedbackErrors = collect($feedbackMessages)->except('alert')->flatten()->all();
    $feedbackAlerts = $errors->get('alert');
    $feedbackMessage = Session::get('message');
    $feedbackClass = $feedbackClass ?? 'mx-5 mt-5 mb-0';
@endphp

<div data-validation-error-keys="@json(array_keys($feedbackMessages))" hidden></div>

@if ($feedbackMessage || count($feedbackErrors))
    <section
        class="feedback-enter relative {{ $feedbackClass }} overflow-hidden rounded-2xl border border-slate-200 bg-white pr-12 shadow-lg shadow-slate-900/5"
        role="{{ count($feedbackErrors) ? 'alert' : 'status' }}"
        aria-live="{{ count($feedbackErrors) ? 'assertive' : 'polite' }}">
        <button type="button" data-form-feedback-banner-close
            class="absolute right-3 top-3 z-10 rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400"
            aria-label="通知を閉じる">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12M18 6 6 18" />
            </svg>
        </button>
        @if (count($feedbackErrors))
            <div class="flex gap-4 border-l-4 border-rose-500 p-4 sm:p-5">
                <span aria-hidden="true" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <h2 class="text-sm font-semibold text-slate-900">入力内容を確認してください</h2>
                    <p class="mt-1 text-sm text-slate-500">以下の項目を修正して、もう一度お試しください。</p>
                    <ul class="mt-3 space-y-2">
                        @foreach ($feedbackErrors as $error)
                            <li class="flex items-start gap-2 rounded-lg bg-rose-50/80 px-3 py-2 text-sm leading-5 text-rose-800">
                                <span aria-hidden="true" class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-rose-500"></span>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if ($feedbackMessage)
            <div class="flex gap-4 border-l-4 border-emerald-500 p-4 sm:p-5 {{ count($feedbackErrors) ? 'border-t border-slate-100' : '' }}">
                <span aria-hidden="true" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 12.5 4.5 4.5L19 7.5" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                </span>
                <div class="min-w-0 self-center">
                    <h2 class="text-sm font-semibold text-slate-900">処理が完了しました</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ $feedbackMessage }}</p>
                </div>
            </div>
        @endif
    </section>
@endif

<div id="form-feedback-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 opacity-0 backdrop-blur-sm transition-opacity duration-200 ease-out motion-reduce:transition-none"
    role="dialog" aria-modal="true" aria-labelledby="form-feedback-modal-title"
    @if (!count($feedbackAlerts)) style="display: none" @endif>
    <div class="form-feedback-modal-panel w-full max-w-md scale-95 transform overflow-hidden rounded-2xl bg-white opacity-0 shadow-2xl shadow-slate-950/20 ring-1 ring-black/5 transition duration-200 ease-out motion-reduce:transition-none">
        <div class="flex items-start gap-4 p-5 sm:p-6">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
            </span>
            <div class="min-w-0 flex-1 pt-0.5">
                <h2 id="form-feedback-modal-title" class="text-base font-semibold text-slate-900">確認してください</h2>
                <div id="form-feedback-modal-messages" class="mt-2 space-y-2 text-sm leading-6 text-slate-600">
                    @foreach ($feedbackAlerts as $feedbackAlert)
                        <p>{{ $feedbackAlert }}</p>
                    @endforeach
                </div>
            </div>
            <button type="button" data-form-feedback-close
                class="-mr-2 -mt-2 rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400"
                aria-label="閉じる">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 6 12 12M18 6 6 18" />
                </svg>
            </button>
        </div>
        <div class="flex justify-end border-t border-slate-100 bg-slate-50/70 px-5 py-3 sm:px-6">
            <button type="button" data-form-feedback-close
                class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                確認
            </button>
        </div>
    </div>
</div>

<script>
    (() => {
        if (window.formFeedbackModalInitialized) return;
        window.formFeedbackModalInitialized = true;

        const initializeFormFeedbackModal = () => {
            const markInvalidFields = () => {
                const normalize = (value) => String(value ?? '').replace(/[^\p{L}\p{N}]/gu, '').toLowerCase();
                const errorKeys = [...document.querySelectorAll('[data-validation-error-keys]')]
                    .flatMap((element) => {
                        try { return JSON.parse(element.dataset.validationErrorKeys || '[]'); }
                        catch { return []; }
                    })
                    .map(normalize);

                document.querySelectorAll('input, select, textarea').forEach((field) => {
                    const names = [field.name, field.id, field.getAttribute('wire:model'), field.getAttribute('wire:model.live')]
                        .map(normalize)
                        .filter(Boolean);
                    const invalid = names.some((name) => errorKeys.includes(name));
                    const wrapper = field.closest('.estimate-input-box');
                    field.classList.toggle('validation-invalid', invalid);
                    field.setAttribute('aria-invalid', invalid ? 'true' : 'false');
                    wrapper?.classList.toggle('validation-invalid-box', invalid);
                });
            };

            markInvalidFields();
            const errorKeyObserver = new MutationObserver(markInvalidFields);
            errorKeyObserver.observe(document.body, {
                attributes: true,
                attributeFilter: ['data-validation-error-keys'],
                childList: true,
                subtree: true,
            });
            document.addEventListener('livewire:navigated', markInvalidFields);

            document.querySelectorAll('[data-form-feedback-banner-close]').forEach((button) => {
                button.addEventListener('click', () => button.closest('section')?.remove());
            });

            const modal = document.getElementById('form-feedback-modal');
            if (!modal) return;

            const messages = document.getElementById('form-feedback-modal-messages');
            const panel = modal.querySelector('.form-feedback-modal-panel');
            let closeTimer;
            const showModal = () => {
                clearTimeout(closeTimer);
                modal.style.display = 'flex';
                requestAnimationFrame(() => {
                    modal.classList.remove('opacity-0');
                    modal.classList.add('opacity-100');
                    panel?.classList.remove('scale-95', 'opacity-0');
                    panel?.classList.add('scale-100', 'opacity-100');
                });
                document.body.classList.add('overflow-hidden');
            };
            const closeModal = () => {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0');
                panel?.classList.remove('scale-100', 'opacity-100');
                panel?.classList.add('scale-95', 'opacity-0');
                document.body.classList.remove('overflow-hidden');
                closeTimer = setTimeout(() => {
                    modal.style.display = 'none';
                }, 200);
            };
            const openModal = (content) => {
                const detail = content?.detail;
                const message = typeof content === 'string'
                    ? content
                    : (typeof detail === 'string' ? detail : (detail?.message ?? detail?.[0] ?? content?.message ?? Object.values(detail ?? content ?? {})[0] ?? ''));
                if (!message) return;

                messages.replaceChildren();
                String(message).split(/<br\s*\/?\s*>/i).forEach((line) => {
                    const paragraph = document.createElement('p');
                    paragraph.textContent = line;
                    messages.appendChild(paragraph);
                });
                showModal();
                modal.querySelector('[data-form-feedback-close]')?.focus();
            };

            modal.querySelectorAll('[data-form-feedback-close]').forEach((button) => button.addEventListener('click', closeModal));
            modal.addEventListener('click', (event) => {
                if (event.target === modal) closeModal();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.style.display !== 'none') closeModal();
            });

            if (window.Livewire) {
                window.Livewire.on('alert', openModal);
            } else {
                document.addEventListener('livewire:init', () => window.Livewire?.on('alert', openModal), { once: true });
            }
            if (modal.style.display !== 'none') showModal();
        };

        initializeFormFeedbackModal();
    })();
</script>
