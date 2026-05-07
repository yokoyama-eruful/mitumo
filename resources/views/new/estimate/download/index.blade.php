@extends('new.layouts.app')

@section('title', 'mitumo Download')

@section('main')
    <section class="new-page-stack">
        <div class="new-preview-header">
            <div>
                <p class="new-page-kicker new-page-kicker-estimate">Download</p>
                <h1 class="new-page-title">見積書の出力</h1>
            </div>
            <a href="{{ route('new.estimate.edit', ['id' => $estimate->id]) }}" class="new-ghost-button">入力へ戻る</a>
        </div>

        <div class="new-download-toolbar">
            <div class="new-download-group">
                <span class="new-download-label">敬称</span>
                <div class="new-download-switch">
                    @foreach (['様', '御中'] as $option)
                        <a href="{{ route('new.estimate.download.index', ['id' => $estimate->id, 'fileType' => $fileType, 'keisyo' => $option]) }}"
                            @class(['new-download-chip', 'new-download-chip-active' => $keisyo === $option])>
                            {{ $option }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="new-download-group">
                <span class="new-download-label">形式</span>
                <div class="new-download-switch">
                    @foreach (['excel' => 'Excel', 'pdf' => 'PDF'] as $value => $label)
                        <a href="{{ route('new.estimate.download.index', ['id' => $estimate->id, 'fileType' => $value, 'keisyo' => $keisyo]) }}"
                            @class(['new-download-chip', 'new-download-chip-active' => $fileType === $value])>
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <section class="new-ui-panel overflow-hidden">
            <div class="new-table-wrap">
                <table class="new-table">
                    <thead class="text-xs uppercase tracking-[0.18em]">
                        <tr>
                            <th class="px-6 py-4 font-medium">見積種類</th>
                            <th class="px-6 py-4 font-medium">敬称</th>
                            <th class="px-6 py-4 font-medium">形式</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estimateTypeList as $estimateType => $enableDisable)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $estimateType }}</td>
                                <td class="px-6 py-4">{{ $keisyo }}</td>
                                <td class="px-6 py-4 uppercase">{{ $fileType }}</td>
                                <td class="px-6 py-4">
                                    @if ($enableDisable === 'disable')
                                        <span class="new-download-disabled">出力不可</span>
                                    @else
                                        <form method="POST"
                                            action="{{ route('new.estimate.download.type', ['id' => $estimate->id, 'category' => $estimateType, 'fileType' => $fileType, 'keisyo' => $keisyo]) }}">
                                            @csrf
                                            <button type="submit" class="new-action-card min-w-[140px]">
                                                ダウンロード
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection
