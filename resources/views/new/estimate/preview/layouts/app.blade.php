@extends('new.layouts.app')

@section('title', 'mitumo Preview')

@section('main')
    <div class="new-preview-header mb-4">
        <div>
            <p class="new-page-kicker new-page-kicker-estimate">Preview</p>
            <h1 class="new-page-title">見積プレビュー</h1>
        </div>
        <a href="{{ route('new.estimate.edit', ['id' => $estimate->id]) }}" class="new-ghost-button">入力へ戻る</a>
    </div>

    <section class="new-page-stack flex items-center justify-center">

        @include('new.estimate.preview.layouts.tab')

        {{-- <section class="new-preview-panel">
            <div class="new-preview-canvas"> --}}
                <div class="bg-white p-4 w-fit rounded-lg">
                @yield('contents')
                </div>
            {{-- </div>
        </section> --}}
    </section>
@endsection
