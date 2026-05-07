@extends('new.layouts.app')

@section('title', 'mitumo Version')

@section('main')
    <section class="new-ui-panel overflow-hidden">
        <div class="px-6 py-5 sm:px-8" style="border-bottom: 1px solid var(--new-soft-border);">
            <p class="new-page-kicker" style="color: var(--new-muted);">Release Notes</p>
            <h2 class="new-page-title">バージョン確認</h2>
        </div>
        <div class="overflow-x-auto p-3 sm:p-4">
            <table class="new-table min-w-full overflow-hidden rounded-3xl">
                <thead class="text-xs uppercase tracking-[0.2em]">
                    <tr>
                        <th class="px-5 py-4 font-medium">バージョン</th>
                        <th class="px-5 py-4 font-medium">更新日時</th>
                        <th class="px-5 py-4 font-medium">更新内容</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($versionList as $item)
                        <tr>
                            <td class="px-5 py-4 font-semibold">{{ $item['バージョン'] }}</td>
                            <td class="px-5 py-4 whitespace-nowrap">{{ $item['更新日時'] }}</td>
                            <td class="px-5 py-4">{{ $item['更新内容'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
