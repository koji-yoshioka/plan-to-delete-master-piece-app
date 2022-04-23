<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CityController extends Controller
{
    //TODO: 通すために一旦コメントアウト
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    public function getByPrefectureId($prefectureId): JsonResponse {
        $cities = DB::table('cities')
            ->select(
                'id'
                , 'name'
            )
            ->where('prefecture_id', $prefectureId)
            ->get();
        return response()->json($cities);
    }
}
