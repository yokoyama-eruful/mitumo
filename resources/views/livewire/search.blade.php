   <div>
     <div class="mx-auto text-center bg-white mb-3">
       <input
         class="w-64 py-2 ps-5 pe-10 border border-gray-400 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent z-0"
         name="keyword" type="text" wire:model.lazy="keyword" placeholder="検索 （契約者名）" autocomplete="off">
       <i class="fas fa-search text-gray-600 -ms-10 z-10"></i>
     </div>
     <div class="flex flex-row ml-10 space-x-5 text-xl">
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

     <div class="flex flex-col px-4 sm:px-10 mb-5">
       <div class="min-h-[522px] w-full bg-gray-200 overflow-hidden border border-gray-200 shadow-md">
         <table class="w-full border-collapse bg-gray-200 text-left text-sm text-gray-500 leading-normal">
           <thead class="{{ $type == 'futsu' ? 'bg-blue-300' : 'bg-green-300' }}">
             <tr class="">
               <th class="px-2 py-1">
                 作成日
               </th>
               <th class="px-5 py-1">
                 所在地
               </th>
               <th class="px-2 py-1">
                 物件
               </th>
               <th class="px-2 py-1">
                 級別
               </th>
               <th class="px-2 py-1 ">
                 共済期間（年）
               </th>
               <th class="px-2 py-1">
                 振込方法
               </th>
               <th class="px-2 py-1">
                 備考
               </th>
               <th class="px-5 py-1">
                 契約者名
               </th>
               <th class="px-5 py-1">
                 担当者
               </th>
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
                 <td class="p-1">
                   {{ $estimate->location }}
                 </td>
                 <td class="p-1">
                   {{ $estimate->property_type }}
                 </td>
                 <td class="p-1">
                   {{ $estimate->rank }}
                 </td>
                 <td class="p-1">
                   {{ $estimate->insurance_year }}
                 </td>
                 <td class="p-1">
                   {{ $estimate->pay_method }}
                 </td>
                 <td class="p-1">
                   {{ $estimate->other }}
                 </td>
                 <td class="p-1">
                   {{ $estimate->contractee }}
                 </td>
                 <td class="p-1 w-28">
                   {{ $estimate->staff_name }}
                 </td>
                 <td class="flex gap-1">
                   @if ($type == 'futsu')
                     <form action="{{ route('estimate.edit', ['id' => $estimate->id]) }}" method="GET">
                       <button class="p-1 rounded text-indigo-600 hover:text-white hover:bg-indigo-600" type="submit">
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
                     <form action="{{ route('estimate.destroy', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
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
                       <button class="p-1 rounded text-indigo-600 hover:text-white hover:bg-indigo-600" type="submit">
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
                 </td>
                 <td class="p-1">
                   {{ $this->versionDomain->Comparative($estimate->updated_at) }}
                 </td>
               </tr>
             @endforeach
           </tbody>

         </table>
       </div>
       <div class="flex items-center mt-5 justify-center">
         {{ $estimates->links('layouts.pagination') }}
       </div>
     </div>
   </div>
