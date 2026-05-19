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

            const storedSidebar = localStorage.getItem('mitumo-new-sidebar');
            document.documentElement.dataset.newSidebar = storedSidebar === 'collapsed' ? 'collapsed' : 'expanded';
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
                        <div class="new-sidebar-collapsible-text">
                            <h1 class="new-brand-title">mitumo</h1>
                        </div>
                    </a>

                    <div class="new-sidebar-actions">
                        <button type="button" class="new-sidebar-toggle" data-sidebar-toggle aria-label="サイドメニューをたたむ">
                            <i class="fa-solid fa-chevron-left" data-sidebar-toggle-icon aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-5">
                    <button type="button" class="new-theme-toggle" data-theme-toggle>
                        <i class="fa-solid fa-circle-half-stroke" aria-hidden="true"></i>
                        <span class="new-theme-label" data-theme-label>ダーク</span>
                    </button>
                </div>

                <div class="new-sidebar-group">
                    <p class="new-sidebar-label">Estimate</p>
                    <nav class="mt-3 flex flex-col gap-2">
                        <a href="{{ route('new.top.type', ['type' => 'futsu']) }}" @class([
                            'new-side-link',
                            'new-side-link-active' =>
                                request()->routeIs('new.index', 'new.top.index', 'new.top.type') &&
                                request()->route('type', 'futsu') === 'futsu',
                        ])
                            title="普通・総合火災共済">
                            <i class="new-side-link-icon fa-solid fa-file-lines" aria-hidden="true"></i>
                            <span class="new-side-link-text">
                                <span class="new-side-link-title">普通・総合火災共済</span>
                                <span class="new-side-link-sub">一覧と新規作成</span>
                            </span>
                        </a>
                        <a href="{{ route('new.top.type', ['type' => 'futsu2']) }}" @class([
                            'new-side-link',
                            'new-side-link-active' =>
                                request()->routeIs('new.top.type') &&
                                request()->route('type') === 'futsu2',
                        ])
                            title="普通火災共済II">
                            <i class="new-side-link-icon fa-solid fa-file-contract" aria-hidden="true"></i>
                            <span class="new-side-link-text">
                                <span class="new-side-link-title">普通火災共済II</span>
                                <span class="new-side-link-sub">一覧と新規作成</span>
                            </span>
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
                            ]) title="ユーザー管理">
                                <i class="new-side-link-icon fa-solid fa-users" aria-hidden="true"></i>
                                <span class="new-side-link-text">
                                    <span class="new-side-link-title">ユーザー管理</span>
                                    <span class="new-side-link-sub">アカウント一覧と編集</span>
                                </span>
                            </a>
                        @endif
                        <a href="{{ route('new.top.show') }}" @class([
                            'new-side-link',
                            'new-side-link-active' => request()->routeIs('new.top.show'),
                        ]) title="バージョン">
                            <i class="new-side-link-icon fa-solid fa-code-branch" aria-hidden="true"></i>
                            <span class="new-side-link-text">
                                <span class="new-side-link-title">バージョン</span>
                                <span class="new-side-link-sub">更新情報の確認</span>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="new-sidebar-footer">
                    <a href="{{ route('top.index') }}" class="new-side-utility" title="旧UIへ">
                        <i class="new-side-link-icon fa-solid fa-clock-rotate-left" aria-hidden="true"></i>
                        <span class="new-side-utility-text">旧UIへ</span>
                    </a>
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="new-side-utility new-side-utility-danger" title="ログアウト">
                            <i class="new-side-link-icon fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                            <span class="new-side-utility-text">ログアウト</span>
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
            const sidebarButton = document.querySelector('[data-sidebar-toggle]');
            const sidebarIcon = document.querySelector('[data-sidebar-toggle-icon]');
            const button = document.querySelector('[data-theme-toggle]');
            const label = document.querySelector('[data-theme-label]');

            const syncSidebar = () => {
                if (!sidebarButton || !sidebarIcon) {
                    return;
                }

                const isCollapsed = root.dataset.newSidebar === 'collapsed';
                sidebarButton.setAttribute('aria-label', isCollapsed ? 'サイドメニューを開く' : 'サイドメニューをたたむ');
                sidebarButton.setAttribute('aria-expanded', String(!isCollapsed));
                sidebarIcon.classList.toggle('fa-chevron-left', !isCollapsed);
                sidebarIcon.classList.toggle('fa-chevron-right', isCollapsed);
            };

            if (sidebarButton) {
                sidebarButton.addEventListener('click', () => {
                    const nextState = root.dataset.newSidebar === 'collapsed' ? 'expanded' : 'collapsed';
                    root.dataset.newSidebar = nextState;
                    localStorage.setItem('mitumo-new-sidebar', nextState);
                    syncSidebar();
                });
            }

            if (!button || !label) {
                syncSidebar();
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
            syncSidebar();
        })();
    </script>
</body>

</html>
