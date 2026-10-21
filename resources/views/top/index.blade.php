@extends('layouts.app')

@section('main')
  <main class="min-h-[800px] flex flex-col overflow-y-auto overflow-x-hidden">
    <div class="flex flex-row justify-center space-x-10 w-screen mt-3">
      <div class="flex justify-center">
        <a class="transition px-14 bg-gray-200 border-2 border-gray-300 rounded shadow p-2 hover:bg-gray-100 hover:-translate-y-1 hover:-translate-x-1 hover:shadow-lg"
          href="{{ route('estimate.create') }}">
          <div class="flex flex-row items-center justify-center">
            <div class="flex-shrink pr-4">
              <div class="rounded p-3 bg-blue-600"><i class="fa fa-file-text fa-2x fa-fw fa-inverse"></i></div>
            </div>
            <div class="text-center">
              <h3 class="font-bold text-3xl">普通・総合火災共済</h3>
              <h5 class="font-bold uppercase text-gray-500">見積書を作成</h5>
            </div>
          </div>
        </a>
      </div>
      <div class="flex justify-center">
        <a class="transition px-14 bg-gray-200 border-2 border-gray-300 rounded shadow p-2 hover:bg-gray-100 hover:-translate-y-1 hover:-translate-x-1 hover:shadow-lg"
          href="{{ route('futsu2.create') }}">
          <div class="flex flex-row items-center justify-center">
            <div class="flex-shrink pr-4">
              <div class="rounded p-3 bg-green-600"><i class="fa fa-file-text fa-2x fa-fw fa-inverse"></i>
              </div>
            </div>
            <div class="text-center">
              <h3 class="font-bold text-3xl">普通火災共済Ⅱ</h3>
              <h5 class="font-bold uppercase text-gray-500">見積書を作成</h5>
            </div>
          </div>
        </a>
      </div>
    </div>

    <hr class="w-screen border border-gray-300 mt-5 mb-5 mx-5">
    @livewire('Search', ['type' => $type])
  </main>
@endsection
