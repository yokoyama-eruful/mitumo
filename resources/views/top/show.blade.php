@extends('layouts.app')

@section('main')
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">バージョン</th>
                <th class="py-2 px-4 border-b">更新日時</th>
                <th class="py-2 px-4 border-b">更新内容</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($versionList as $item)
                <tr>
                    <td class="py-auto px-4 border-b">{{ $item['バージョン'] }}</td>
                    <td class="py-auto px-4 border-b">{{ $item['更新日時'] }}</td>
                    <td class="py-auto px-4 border-b">{{ $item['更新内容'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
