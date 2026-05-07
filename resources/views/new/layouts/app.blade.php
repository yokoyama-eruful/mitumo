<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'mitumo New UI')</title>
    <script>
        (() => {
            const storedTheme = localStorage.getItem('mitumo-new-theme');
            const theme = storedTheme === 'dark' ? 'dark' : 'light';
            document.documentElement.dataset.newTheme = theme;
        })();
    </script>
    @vite(['resources/css/app.css'])
</head>

<body class="new-ui-shell h-screen overflow-hidden text-slate-900">
    <div class="new-ui-background"></div>
    <div class="new-layout relative h-screen lg:grid lg:grid-cols-[320px_minmax(0,1fr)]">
        <aside class="new-sidebar-shell lg:h-screen">
            <div class="new-sidebar">
                <div class="new-sidebar-top">
                    <a href="{{ route('new.top.index') }}" class="inline-flex items-center gap-3 no-underline">
                        <span class="new-brand-mark">M</span>
                        <div>
                            <h1 class="new-brand-title">mitumo</h1>
                        </div>
                    </a>

                    <button type="button" class="new-theme-toggle" data-theme-toggle>
                        <span data-theme-label>ダーク</span>
                    </button>
                </div>

                <div class="new-sidebar-group">
                    <p class="new-sidebar-label">Estimate</p>
                    <nav class="mt-3 flex flex-col gap-2">
                        <a href="{{ route('new.top.type', ['type' => 'futsu']) }}" @class([
                            'new-side-link',
                            'new-side-link-active' => request()->routeIs('new.index', 'new.top.index', 'new.top.type') && request()->route('type', 'futsu') === 'futsu',
                        ])>
                            <span class="new-side-link-title">普通・総合火災共済</span>
                            <span class="new-side-link-sub">一覧と新規作成</span>
                        </a>
                        <a href="{{ route('new.top.type', ['type' => 'futsu2']) }}" @class([
                            'new-side-link',
                            'new-side-link-active' => request()->routeIs('new.top.type') && request()->route('type') === 'futsu2',
                        ])>
                            <span class="new-side-link-title">普通火災共済II</span>
                            <span class="new-side-link-sub">一覧と新規作成</span>
                        </a>
                    </nav>
                </div>

                <div class="new-sidebar-group">
                    <p class="new-sidebar-label">Manage</p>
                    <div class="mt-3 flex flex-col gap-2">
                        @if (Auth::user()->id == 1)
                            <a href="{{ route('new.account.index') }}" @class([
                                'new-side-link',
                                'new-side-link-active' => request()->routeIs('new.account.*'),
                            ])>
                                <span class="new-side-link-title">ユーザー管理</span>
                                <span class="new-side-link-sub">アカウント一覧と編集</span>
                            </a>
                        @endif
                        <a href="{{ route('new.top.show') }}" @class([
                            'new-side-link',
                            'new-side-link-active' => request()->routeIs('new.top.show'),
                        ])>
                            <span class="new-side-link-title">バージョン</span>
                            <span class="new-side-link-sub">更新情報の確認</span>
                        </a>
                    </div>
                </div>

                <div class="new-sidebar-footer">
                    <a href="{{ route('top.index') }}" class="new-side-utility">
                        旧UIへ
                    </a>
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="new-side-utility new-side-utility-danger">
                            ログアウト
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="new-main min-w-0 overflow-y-auto px-4 py-5 sm:px-6 lg:h-screen lg:px-8">
            <div class="pb-8">
                @yield('main')
            </div>
        </main>
    </div>
    <script>
        (() => {
            const root = document.documentElement;
            const button = document.querySelector('[data-theme-toggle]');
            const label = document.querySelector('[data-theme-label]');

            if (!button || !label) {
                return;
            }

            const sync = () => {
                const isDark = root.dataset.newTheme === 'dark';
                label.textContent = isDark ? 'ライト' : 'ダーク';
                button.setAttribute('aria-label', isDark ? 'ライトモードに切り替え' : 'ダークモードに切り替え');
            };

            button.addEventListener('click', () => {
                const nextTheme = root.dataset.newTheme === 'dark' ? 'light' : 'dark';
                root.dataset.newTheme = nextTheme;
                localStorage.setItem('mitumo-new-theme', nextTheme);
                sync();
            });

            sync();
        })();
    </script>
</body>

</html>
