@extends('new.layouts.app')

@section('title', 'mitumo User Edit')

@section('main')
    @if ($errors->any())
        <div class="new-danger-panel mb-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <section class="new-ui-panel mx-auto max-w-3xl p-6 sm:p-8">
        <form action="{{ route('new.account.save', ['id' => isset($user) ? $user->id : 'new']) }}" method="POST"
            class="space-y-5">
            @csrf

            <div>
                <label class="new-field-label">職員番号</label>
                <input type="text" name="staff_id"
                    class="new-field-input"
                    value="{{ isset($user) ? $user->staff_id : old('staff_id') }}" required>
            </div>

            <div>
                <label class="new-field-label">ユーザー名</label>
                <input type="text" name="name"
                    class="new-field-input"
                    value="{{ isset($user) ? $user->name : old('name') }}" required>
            </div>

            <div>
                <label class="new-field-label">メールアドレス</label>
                <input type="email" name="email"
                    class="new-field-input"
                    value="{{ isset($user) ? $user->email : old('email') }}" required>
            </div>

            @if (isset($user))
                <p class="text-sm font-medium" style="color: #d97706;">パスワードは変更時のみ入力してください。</p>
                <div>
                    <label class="new-field-label">パスワード</label>
                    <input type="password" name="password" class="new-field-input">
                </div>
                <div>
                    <label class="new-field-label">パスワード確認</label>
                    <input type="password" name="password_confirmation" class="new-field-input">
                </div>
            @else
                <div>
                    <label class="new-field-label">パスワード</label>
                    <input type="password" name="password" class="new-field-input" required>
                </div>
                <div>
                    <label class="new-field-label">パスワード確認</label>
                    <input type="password" name="password_confirmation" class="new-field-input" required>
                </div>
            @endif

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('new.account.index') }}" class="new-ghost-button">
                    戻る
                </a>
                <button type="submit" class="new-primary-button" style="background: #0284c7;">
                    保存
                </button>
            </div>
        </form>
    </section>
@endsection
