@extends('preview.layouts.app')
@section('計算', 'text-white bg-blue-400 hover:bg-blue-400')

@section('contents')
    <h1 class="text-[26px] font-bold mb-2 -mt-3">計算</h1>

    <div class="my-5">
        <div class="flex flex-row">
            <div class="flex justify-center items-center h-10 w-24"></div>
            <div class="flex justify-center items-center h-10 w-16 outline-gray-500"></div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                基本率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                総合割増
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                長期率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                適用率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                掛金
            </div>
        </div>
        <div class="flex flex-row h-20">
            <div class="flex justify-center items-center w-24 outline outline-1 outline-gray-500">建物</div>
            <div class="flex flex-col justify-center items-center w-16 outline-gray-500">
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">普通</div>
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">総合</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    <div class="absolute w-28 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
        </div>
    </div>

    <div class="my-5">
        <div class="flex flex-row">
            <div class="flex justify-center items-center h-10 w-24"></div>
            <div class="flex justify-center items-center h-10 w-16 outline-gray-500"></div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                基本率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                総合割増
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                長期率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                適用率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                掛金
            </div>
        </div>
        <div class="flex flex-row h-20">
            <div class="flex justify-center items-center w-24 outline outline-1 outline-gray-500">家財</div>
            <div class="flex flex-col justify-center items-center w-16 outline-gray-500">
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">普通</div>
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">総合</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    <div class="absolute w-28 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
        </div>
    </div>

    <div class="my-5">
        <div class="flex flex-row">
            <div class="flex justify-center items-center h-10 w-24"></div>
            <div class="flex justify-center items-center h-10 w-16 outline-gray-500"></div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                基本率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                総合割増
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                長期率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                適用率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                掛金
            </div>
        </div>
        <div class="flex flex-row h-20">
            <div class="flex justify-center items-center w-24 outline outline-1 outline-gray-500">設備・什器等</div>
            <div class="flex flex-col justify-center items-center w-16 outline-gray-500">
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">普通</div>
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">総合</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    <div class="absolute w-28 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
        </div>
    </div>

    <div class="my-5">
        <div class="flex flex-row">
            <div class="flex justify-center items-center h-10 w-24"></div>
            <div class="flex justify-center items-center h-10 w-16 outline-gray-500"></div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                基本率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                総合割増
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                長期率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                適用率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                掛金
            </div>
        </div>
        <div class="flex flex-row h-20">
            <div class="flex justify-center items-center w-24 outline outline-1 outline-gray-500">商品・製品等</div>
            <div class="flex flex-col justify-center items-center w-16 outline-gray-500">
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">普通</div>
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">総合</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    <div class="absolute w-28 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500"></div>
            </div>
        </div>
    </div>

    <div class="flex">
        <div class="">
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 outline outline-1 outline-gray-500">
                    <div class="absolute w-32 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                    料率
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    基本率（建物）
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">

                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    基本率（動産）
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">

                </div>
            </div>

        </div>
        <div class="ms-10">
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 outline outline-1 outline-gray-500">
                    <div class="absolute w-32 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                    料率
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    職業割増
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">

                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    作業割増
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">

                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    動産割増
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">

                </div>
            </div>

        </div>
        <div class="ms-10">
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 outline outline-1 outline-gray-500">
                    <div class="absolute w-32 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                    料率
                </div>
            </div>

            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    その他割引
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">

                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    短期係数
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">

                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    長期係数
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">

                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    実損払係数
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">

                </div>
            </div>
        </div>
    </div>

@endsection
