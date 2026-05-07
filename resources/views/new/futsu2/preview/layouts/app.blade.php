@extends('new.layouts.app')

@section('title', 'mitumo Preview')

@section('main')
    <section class="new-page-stack">
        <div class="new-preview-header">
            <div>
                <p class="new-page-kicker new-page-kicker-futsu2">Preview</p>
                <h1 class="new-page-title">見積プレビュー</h1>
            </div>
            <a href="{{ route('new.futsu2.edit', ['id' => $estimate->id]) }}" class="new-ghost-button">入力へ戻る</a>
        </div>

        @include('new.futsu2.preview.layouts.tab')

        <section class="new-preview-panel">
            <div class="new-preview-canvas">
                @yield('contents')
            </div>
        </section>
    </section>
@endsection
