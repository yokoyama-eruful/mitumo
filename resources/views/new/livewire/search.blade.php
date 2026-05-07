<div x-data="newActionModal()" class="new-ui-panel overflow-hidden">
    <div class="new-filter-bar p-5 sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex justify-start">
                <div class="new-filter-tabs">
                    <a href="{{ route($topRouteName, ['type' => 'futsu']) }}" @class([
                        'new-filter-tab',
                        'new-filter-tab-active' => $type === 'futsu',
                    ])>
                        普通・総合火災共済
                    </a>
                    <a href="{{ route($topRouteName, ['type' => 'futsu2']) }}" @class([
                        'new-filter-tab',
                        'new-filter-tab-active' => $type === 'futsu2',
                    ])>
                        普通火災共済II
                    </a>
                </div>
            </div>
            <div class="flex justify-end">
                <label class="relative block w-full lg:w-[22rem]">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4"
                        style="color: var(--new-muted);">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input class="new-search-input" name="keyword" type="text" wire:model.live.debounce.300ms="keyword"
                        placeholder="契約者名で検索" autocomplete="off">
                </label>
            </div>
        </div>
    </div>

    <div class="grid gap-3 p-3 sm:p-4 xl:hidden">
        @foreach ($estimates as $estimate)
            <article class="rounded-[1.5rem] border p-4 shadow-sm"
                style="border-color: var(--new-soft-border); background: var(--new-input-bg);">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] uppercase tracking-[0.18em]" style="color: var(--new-muted);">
                            {{ $estimate->created_at->format('Y/m/d') }}</p>
                        <h4 class="mt-1 text-base font-semibold" style="color: var(--new-text);">{{ $estimate->contractee }}
                        </h4>
                    </div>
                    <span class="new-table-chip">
                        {{ $estimate->staff_name }}
                    </span>
                </div>
                <dl class="mt-3 grid grid-cols-2 gap-2 text-sm" style="color: var(--new-muted);">
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.16em]" style="color: var(--new-muted);">所在地</dt>
                        <dd class="mt-0.5 text-sm" style="color: var(--new-text);">{{ $estimate->location }}</dd>
                    </div>
                </dl>
                <div class="mt-4 flex flex-wrap gap-2">
                    @if ($type === 'futsu')
                        <a href="{{ route('new.estimate.edit', ['id' => $estimate->id]) }}"
                            class="new-table-action new-table-action-edit">
                            編集
                        </a>
                        <form action="{{ route('new.estimate.duplication', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="new-table-action new-table-action-copy"
                                x-on:click.prevent="open($event.currentTarget.form, '複製しますか？')">
                                複製
                            </button>
                        </form>
                        <form action="{{ route('new.estimate.destroy', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="new-table-action new-table-action-delete"
                                x-on:click.prevent="open($event.currentTarget.form, '本当に削除しますか？')">
                                削除
                            </button>
                        </form>
                    @else
                        <a href="{{ route('new.futsu2.edit', ['id' => $estimate->id]) }}"
                            class="new-table-action new-table-action-edit">
                            編集
                        </a>
                        <form action="{{ route('new.futsu2.duplication', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="new-table-action new-table-action-copy"
                                x-on:click.prevent="open($event.currentTarget.form, '複製しますか？')">
                                複製
                            </button>
                        </form>
                        <form action="{{ route('new.futsu2.destroy', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="new-table-action new-table-action-delete"
                                x-on:click.prevent="open($event.currentTarget.form, '本当に削除しますか？')">
                                削除
                            </button>
                        </form>
                    @endif
                </div>
            </article>
        @endforeach
    </div>

    <div class="hidden overflow-x-auto xl:block">
        <table class="new-table">
            <thead class="text-[11px] uppercase tracking-[0.14em]">
                <tr>
                    <th class="px-4 py-2.5 font-medium">作成日</th>
                    <th class="px-4 py-2.5 font-medium">契約者名</th>
                    <th class="px-4 py-2.5 font-medium">所在地</th>
                    <th class="px-4 py-2.5"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($estimates as $estimate)
                    <tr>
                        <td class="px-4 py-2.5 whitespace-nowrap text-sm">{{ $estimate->created_at->format('Y/m/d') }}</td>
                        <td class="px-4 py-2.5 text-sm font-medium">{{ $estimate->contractee }}</td>
                        <td class="px-4 py-2.5 text-sm">{{ $estimate->location }}</td>
                        <td class="px-4 py-2 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if ($type === 'futsu')
                                    <a href="{{ route('new.estimate.edit', ['id' => $estimate->id]) }}"
                                        class="new-table-action new-table-action-edit">
                                        編集
                                    </a>
                                    <form
                                        action="{{ route('new.estimate.duplication', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="new-table-action new-table-action-copy"
                                            x-on:click.prevent="open($event.currentTarget.form, '複製しますか？')">
                                            複製
                                        </button>
                                    </form>
                                    <form
                                        action="{{ route('new.estimate.destroy', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="new-table-action new-table-action-delete"
                                            x-on:click.prevent="open($event.currentTarget.form, '本当に削除しますか？')">
                                            削除
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('new.futsu2.edit', ['id' => $estimate->id]) }}"
                                        class="new-table-action new-table-action-edit">
                                        編集
                                    </a>
                                    <form
                                        action="{{ route('new.futsu2.duplication', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="new-table-action new-table-action-copy"
                                            x-on:click.prevent="open($event.currentTarget.form, '複製しますか？')">
                                            複製
                                        </button>
                                    </form>
                                    <form
                                        action="{{ route('new.futsu2.destroy', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="new-table-action new-table-action-delete"
                                            x-on:click.prevent="open($event.currentTarget.form, '本当に削除しますか？')">
                                            削除
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 sm:px-6" style="border-top: 1px solid var(--new-soft-border);">
        {{ $estimates->withQueryString()->links('new.layouts.livewire.pagination') }}
    </div>

    <div x-cloak x-show="isOpen" x-on:keydown.escape.window="close()"
        class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div x-show="isOpen" x-transition.opacity class="absolute inset-0 bg-slate-950/45 backdrop-blur-sm"></div>
        <div x-show="isOpen" x-transition class="new-modal">
            <p class="new-modal-label">Action</p>
            <p class="new-modal-title">この操作を実行しますか？</p>
            <p class="new-modal-body" x-text="message"></p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="new-ghost-button" x-on:click="close()">キャンセル</button>
                <button type="button" class="new-primary-button"
                    style="background: var(--new-link-active); color: var(--new-link-active-text);"
                    x-on:click="confirm()">実行</button>
            </div>
        </div>
    </div>
</div>

<script>
    function newActionModal() {
        return {
            isOpen: false,
            form: null,
            message: '',
            open(form, message) {
                this.form = form;
                this.message = message;
                this.isOpen = true;
            },
            close() {
                this.isOpen = false;
                this.form = null;
                this.message = '';
            },
            confirm() {
                if (this.form) {
                    this.form.submit();
                }
                this.close();
            },
        };
    }
</script>
