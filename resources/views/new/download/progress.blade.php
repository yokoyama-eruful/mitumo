<div data-download-progress class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    <div class="absolute inset-0 bg-slate-950/45 backdrop-blur-sm"></div>
    <div class="new-download-progress-panel relative w-full max-w-md">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="new-modal-label">Download</p>
                <p class="new-modal-title" data-download-progress-title>ダウンロード準備中</p>
            </div>
            <p class="new-download-progress-percent" data-download-progress-percent>0%</p>
        </div>
        <div class="mt-5 h-3 overflow-hidden rounded-full bg-slate-200/80 dark:bg-slate-700/80">
            <div class="new-download-progress-bar h-full w-0 rounded-full" data-download-progress-bar></div>
        </div>
        <p class="new-modal-body mt-4" data-download-progress-message>
            ファイルを作成しています。このままお待ちください。
        </p>
    </div>
</div>

<script>
    (() => {
        const overlay = document.querySelector('[data-download-progress]');
        const bar = document.querySelector('[data-download-progress-bar]');
        const percent = document.querySelector('[data-download-progress-percent]');
        const title = document.querySelector('[data-download-progress-title]');
        const message = document.querySelector('[data-download-progress-message]');

        if (!overlay || !bar || !percent || !title || !message) {
            return;
        }

        let currentProgress = 0;
        let timerId = null;

        const setProgress = (value) => {
            currentProgress = Math.max(currentProgress, Math.min(100, Math.round(value)));
            bar.style.width = `${currentProgress}%`;
            percent.textContent = `${currentProgress}%`;
        };

        const startEstimatedProgress = () => {
            window.clearInterval(timerId);
            timerId = window.setInterval(() => {
                if (currentProgress < 45) {
                    setProgress(currentProgress + 4);
                    return;
                }

                if (currentProgress < 75) {
                    setProgress(currentProgress + 2);
                    return;
                }

                if (currentProgress < 95) {
                    setProgress(currentProgress + 1);
                }
            }, 320);
        };

        const stopEstimatedProgress = () => {
            window.clearInterval(timerId);
            timerId = null;
        };

        const showProgress = () => {
            currentProgress = 0;
            setProgress(0);
            title.textContent = 'ダウンロード準備中';
            message.textContent = 'ファイルを作成しています。このままお待ちください。';
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            startEstimatedProgress();
        };

        const hideProgress = () => {
            stopEstimatedProgress();
            window.setTimeout(() => {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }, 600);
        };

        const getFileName = (response) => {
            const disposition = response.headers.get('content-disposition') || '';
            const encodedName = disposition.match(/filename\*=UTF-8''([^;]+)/i);

            if (encodedName) {
                return decodeURIComponent(encodedName[1]);
            }

            const quotedName = disposition.match(/filename="?([^"]+)"?/i);

            if (quotedName) {
                return quotedName[1];
            }

            return 'download';
        };

        const saveBlob = (blob, fileName) => {
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            link.remove();
            window.URL.revokeObjectURL(url);
        };

        const readResponse = async (response) => {
            const contentLength = Number(response.headers.get('content-length'));

            if (!response.body || !contentLength) {
                return response.blob();
            }

            const reader = response.body.getReader();
            const chunks = [];
            let receivedLength = 0;

            while (true) {
                const { done, value } = await reader.read();

                if (done) {
                    break;
                }

                chunks.push(value);
                receivedLength += value.length;
                title.textContent = 'ダウンロード中';
                message.textContent = 'ファイルを受信しています。';
                setProgress((receivedLength / contentLength) * 100);
            }

            return new Blob(chunks, {
                type: response.headers.get('content-type') || 'application/octet-stream',
            });
        };

        const handleSubmit = async (event) => {
            const form = event.target;

            if (!form.matches('[data-download-form]')) {
                return;
            }

            event.preventDefault();

            const submitButton = form.querySelector('button[type="submit"]');
            submitButton?.setAttribute('disabled', 'disabled');
            showProgress();

            try {
                const response = await fetch(form.action, {
                    method: form.method,
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error('ダウンロードに失敗しました。');
                }

                const fileName = getFileName(response);
                const blob = await readResponse(response);
                stopEstimatedProgress();
                title.textContent = 'ダウンロード完了';
                message.textContent = '保存を開始しました。';
                setProgress(100);
                saveBlob(blob, fileName);
                hideProgress();
            } catch (error) {
                stopEstimatedProgress();
                title.textContent = 'ダウンロードできませんでした';
                message.textContent = error.message || '時間をおいて再度お試しください。';
                setProgress(100);
                window.setTimeout(hideProgress, 1600);
            } finally {
                submitButton?.removeAttribute('disabled');
            }
        };

        document.addEventListener('submit', handleSubmit);
    })();
</script>
