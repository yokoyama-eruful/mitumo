@php
    if (! isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = ($scrollTo !== false)
        ? <<<JS
           (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
        JS
        : '';
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="ページ送り" class="flex items-center justify-between gap-3">
        <div class="text-xs" style="color: var(--new-muted);">
            {{ $paginator->firstItem() }}〜{{ $paginator->lastItem() }} / {{ $paginator->total() }}件
        </div>

        <div class="flex items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="new-ghost-button px-3 py-1.5 text-xs opacity-60">
                    前へ
                </span>
            @else
                <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                    class="new-ghost-button px-3 py-1.5 text-xs">
                    前へ
                </button>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-1 text-xs" style="color: var(--new-muted);">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="inline-flex min-w-[2rem] items-center justify-center rounded-full px-3 py-1.5 text-xs font-semibold text-white"
                                style="background: var(--new-link-active); color: var(--new-link-active-text);">
                                {{ $page }}
                            </span>
                        @else
                            <button type="button"
                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                class="new-ghost-button inline-flex min-w-[2rem] items-center justify-center px-3 py-1.5 text-xs"
                                aria-label="{{ $page }}ページへ">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                    class="new-ghost-button px-3 py-1.5 text-xs">
                    次へ
                </button>
            @else
                <span class="new-ghost-button px-3 py-1.5 text-xs opacity-60">
                    次へ
                </span>
            @endif
        </div>
    </nav>
@endif
