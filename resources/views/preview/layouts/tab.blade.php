<div class="flex flex-row">
    <div>
        <a href="{{ route('estimate.preview.standard', ['id' => $estimate->id]) }}"
            class="rounded-b-lg outline outline-1 outline-gray-200 w-[100px] hover:bg-gray-300 text-sm">
            <button class="rounded-b-lg outline outline-1 outline-gray-200 w-[100px] hover:bg-gray-300 text-sm"
                value="normal">普通</button>
        </a>
    </div>
    <div>
        <a href="{{ route('estimate.preview.comprehensive', ['id' => $estimate->id]) }}">
            <button class="rounded-b-lg outline outline-1 outline-gray-200 w-[100px] hover:bg-gray-300 text-sm"
                value="">総合</button>
        </a>
    </div>
    <div>
        <a href="{{ route('estimate.preview.standardAndComprehensive', ['id' => $estimate->id]) }}">
            <button class="rounded-b-lg outline outline-1 outline-gray-200 w-[100px] hover:bg-gray-300 text-sm"
                value="">普通&総合</button>
        </a>
    </div>
    <div>
        <a href="{{ route('estimate.preview.standardOfRecommend', ['id' => $estimate->id]) }}">
            <button class="rounded-b-lg outline outline-1 outline-gray-200 w-[100px] hover:bg-gray-300 text-sm"
                value="">おすすめ普通</button>
        </a>
    </div>
    <div>
        <a href="{{ route('estimate.preview.comprehensiveOfRecommend', ['id' => $estimate->id]) }}">
            <button class="rounded-b-lg outline outline-1 outline-gray-200 w-[100px] hover:bg-gray-300 text-sm"
                value="">おすすめ総合</button>
        </a>
    </div>
    <div>
        <a
            href="{{ route('estimate.preview.standardOfRecommendAndComprehensiveOfRecommend', ['id' => $estimate->id]) }}">
            <button class="rounded-b-lg outline outline-1 outline-gray-200 w-[100px] hover:bg-gray-300 text-sm"
                value="">おすすめ普&総</button>
        </a>
    </div>
    <div>
        <a href="{{ route('estimate.preview.standardOfEarthquake', ['id' => $estimate->id]) }}">
            <button class="rounded-b-lg outline outline-1 outline-gray-200 w-[100px] hover:bg-gray-300 text-sm"
                value="">地震普通</button>
        </a>
    </div>
    <div>
        <a href="{{ route('estimate.preview.comprehensiveOfEarthquake', ['id' => $estimate->id]) }}">
            <button class="rounded-b-lg outline outline-1 outline-gray-200 w-[100px] hover:bg-gray-300 text-sm"
                value="">地震総合</button>
        </a>
    </div>
</div>

<div class="text-gray-600 body-font">
    <div class="px-5 py-5 mx-auto">
        <div class="flex flex-wrap -m-4">
            <a href="">
                <div class="p-4 w-[800px]">
                    <div
                        class="flex rounded-lg h-full outline outline-1 outline-slate-300 shadow-md bg-gray-100 hover:bg-gray-300 p-8 flex-col">
                        <div class="flex items-center mb-3">
                            <div
                                class="w-8 h-8 mr-3 inline-flex items-center justify-center rounded-full bg-indigo-500 text-white flex-shrink-0">
                                <i class="fa-solid fa-pen"></i>
                            </div>
                            <h2 class="text-gray-900 text-lg title-font font-medium">入力情報の変更</h2>
                        </div>
                        <div class="flex-grow">
                            <p class="leading-relaxed text-base">情報入力画面に戻ります</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
