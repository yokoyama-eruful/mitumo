   <div>
     <div class="flex items-center justify-between">
       <div class="flex flex-row space-x-5 text-xl">
         <a type="button" href="{{ route('top.type', ['type' => 'futsu']) }}" @class([
             'w-48 text-center ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2',
             'border-blue-400 bg-blue-200' => $type == 'futsu',
         ])>
           <i class="fas fa-fire fa-lg text-blue-500 mr-1"></i>
           普通・総合火災
         </a>
         <a type="button" href="{{ route('top.type', ['type' => 'futsu2']) }}" @class([
             'w-48 text-center ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2',
             'border-b-2 border-green-400 bg-green-200' => $type == 'futsu2',
         ])>
           <i class="fas fa-fire fa-lg text-green-500 mr-1"></i>
           普通火災Ⅱ
         </a>
       </div>
       <label
         class="flex mr-5 w-72 items-center gap-3 rounded-full border border-gray-200 bg-white px-4 py-2 shadow-sm transition focus-within:border-blue-400 focus-within:ring-4 focus-within:ring-blue-100">
         <i class="fas fa-search text-sm text-gray-400" aria-hidden="true"></i>
         <input
           class="min-w-0 flex-1 border-0 bg-transparent p-0 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-0"
           name="keyword" type="text" wire:model.lazy="keyword" placeholder="契約者名で検索" autocomplete="off"
           aria-label="契約者名で検索">
       </label>
     </div>

     <div class="flex flex-col mb-5">
       <div class="min-h-[522px] w-full bg-gray-200 overflow-hidden border border-gray-200 shadow-md">
         <table class="w-full table-fixed border-collapse bg-gray-200 text-left text-sm text-gray-500 leading-normal">
           <thead class="{{ $type == 'futsu' ? 'bg-blue-300' : 'bg-green-300' }}">
             <tr class="">
               <th class="px-2 py-1"><span class="block line-clamp-2 leading-tight">作成日</span></th>
               <th class="px-5 py-1"><span class="block line-clamp-2 leading-tight">所在地</span></th>
               <th class="px-2 py-1"><span class="block line-clamp-2 leading-tight">物件</span></th>
               <th class="px-2 py-1"><span class="block line-clamp-2 leading-tight">級別</span></th>
               <th class="px-2 py-1"><span class="block line-clamp-2 leading-tight">共済期間（年）</span></th>
               <th class="px-2 py-1"><span class="block line-clamp-2 leading-tight">振込方法</span></th>
               <th class="px-2 py-1"><span class="block line-clamp-2 leading-tight">備考</span></th>
               <th class="px-5 py-1"><span class="block line-clamp-2 leading-tight">契約者名</span></th>
               <th class="px-5 py-1"><span class="block line-clamp-2 leading-tight">担当者</span></th>
               <th class="w-32">
               </th>
               <th class="w-16">
               </th>
             </tr>
           </thead>
           <tbody class="w-screen">
             @foreach ($estimates as $estimate)
               <tr class="text-sm text-black bg-white border-b border-gray-200 hover:bg-indigo-100">
                 <td class="p-1 w-20">
                   {{ $estimate->created_at->isoFormat('YYYY') }}
                   <br>
                   {{ $estimate->created_at->isoFormat('MM/DD') }}
                 </td>
                 <td class="p-1 align-middle"><span class="cell-ellipsis">{{ $estimate->location }}</span></td>
                 <td class="p-1 align-middle"><span class="cell-ellipsis">{{ $estimate->property_type }}</span></td>
                 <td class="p-1 align-middle"><span class="cell-ellipsis">{{ $estimate->rank }}</span></td>
                 <td class="p-1 align-middle"><span class="cell-ellipsis">{{ $estimate->insurance_year }}</span></td>
                 <td class="p-1 align-middle"><span class="cell-ellipsis">{{ $estimate->pay_method }}</span></td>
                 <td class="p-1 align-middle"><span class="cell-ellipsis">{{ $estimate->other }}</span></td>
                 <td class="p-1 align-middle"><span class="cell-ellipsis">{{ $estimate->contractee }}</span></td>
                 <td class="p-1 w-28 align-middle"><span class="cell-ellipsis">{{ $estimate->staff_name }}</span></td>
                 <td class="p-1 align-middle">
                   <div class="flex items-center justify-center gap-1">
                     @if ($type == 'futsu')
                       <form action="{{ route('estimate.edit', ['id' => $estimate->id]) }}" method="GET">
                         <button class="p-1 rounded text-indigo-600 hover:text-white hover:bg-indigo-600"
                           type="submit">
                           <i class="fa-solid fa-pen-to-square inline-block"></i>
                           編集
                         </button>
                       </form>
                       <form
                         action="{{ route('estimate.duplication', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                         method="POST">
                         @csrf
                         <button class="p-1 rounded text-green-600 hover:text-white hover:bg-green-600" type="submit"
                           onclick='return confirm("複製しますか？")'>
                           <i class="fa-solid fa-clone"></i>
                           複製
                         </button>
                       </form>
                       <form
                         action="{{ route('estimate.destroy', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                         method="POST">
                         @csrf
                         @method('DELETE')
                         <button class="p-1 rounded text-red-600 hover:text-white hover:bg-red-600" type="submit"
                           onclick='return confirm("本当に削除しますか")'>
                           <i class="fa-solid fa-trash me-1"></i>
                           削除
                         </button>
                       </form>
                     @else
                       <form action="{{ route('futsu2.edit', ['id' => $estimate->id]) }}" method="GET">
                         <button class="p-1 rounded text-indigo-600 hover:text-white hover:bg-indigo-600"
                           type="submit">
                           <i class="fa-solid fa-pen-to-square inline-block"></i>
                           編集
                         </button>
                       </form>
                       <form
                         action="{{ route('futsu2.duplication', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                         method="POST">
                         @csrf
                         <button class="p-1 rounded text-green-600 hover:text-white hover:bg-green-600" type="submit"
                           onclick='return confirm("複製しますか？")'>
                           <i class="fa-solid fa-clone"></i>
                           複製
                         </button>
                       </form>
                       <form action="{{ route('futsu2.destroy', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                         method="POST">
                         @csrf
                         @method('DELETE')
                         <button class="p-1 rounded text-red-600 hover:text-white hover:bg-red-600" type="submit"
                           onclick='return confirm("本当に削除しますか")'>
                           <i class="fa-solid fa-trash me-1"></i>
                           削除
                         </button>
                       </form>
                     @endif
                   </div>
                 </td>
                 <td class="p-1 align-middle text-center">
                   <span class="cell-ellipsis">{{ $this->versionDomain->Comparative($estimate->updated_at) }}</span>
                 </td>
               </tr>
             @endforeach
           </tbody>

         </table>
       </div>
       <div class="flex items-center mt-5 justify-center">
         {{ $estimates->withQueryString()->links('layouts.livewire.pagination') }}
       </div>
     </div>
   </div>
