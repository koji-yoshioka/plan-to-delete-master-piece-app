<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompany;
use App\Models\Company;
use App\Models\Prefecture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Psy\debug;

class CompanyController extends Controller  
{
    //TODO: 通すために一旦コメントアウト
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    public function getByConditions(Request $request): JsonResponse
    {
        // オプション条件：市区町村
        $cities = $request->cities;
        $prefecture = Prefecture::find($request->prefectureId);
        $companies = Company::with(['logo', 'sellingPoints', 'paymentMethods', 'holidays'])
            ->where('companies.prefecture', $prefecture->name)
            ->when($cities, function ($query, $cities) {
                $query->where(function ($query) use ($cities) {
                    foreach ($cities as $value) {
                        $query->orWhere('companies.city', 'like', "{$value}%");
                    }
                });
            })
            ->get();

        return response()->json($companies->map(fn($company) =>
            [
                  'id' => $company->id
                , 'name' => $company->name
                , 'tel' => $company->tel
                , 'prefecture' => $company->prefecture
                , 'city' => $company->city
                , 'restAddress' => $company->rest_address
                , 'nearestStation' => $company->nearest_station
                , 'businessHoursFrom' => $company->business_hours_from
                , 'businessHoursTo' => $company->business_hours_to
                , 'comment' => $company->comment
                , 'logo' => !empty($company->logo) ? $company->logo->file_name : null
                , 'sellingPoints' => $company->sellingPoints->map(fn($sellingPoint) =>
                        [
                              'id' => $sellingPoint->id
                            , 'name' => $sellingPoint->name
                        ]
                    )
                , 'paymentMethods' => $company->paymentMethods->map(fn($paymentMethod) =>
                        [
                              'id' => $paymentMethod->id
                            , 'name' => $paymentMethod->name
                        ]
                    )
                , 'holidays' => $company->holidays->map(fn($holiday) =>
                        [
                              'id' => $holiday->id
                            , 'name' => $holiday->name
                        ]
                    )
            ]
        ));
    }

    public function get($id): JsonResponse
    {
        // 企業情報およびそれに紐づく関連情報
        $company = Company::with(['logo', 'images', 'sellingPoints', 'paymentMethods', 'holidays'])->find($id);
        // セールスポイントマスタ
        $allSellingPoints = DB::table('selling_points')->get();
        // 支払い方法マスタ
        $allPaymentMethods = DB::table('payment_methods')->get();
        // 休業日マスタ
        $allHolidays = DB::table('holidays')->get();

        return response()->json(
            (object)
            [
                  'name' => $company->name
                , 'email' => $company->email
                , 'tel' => $company->tel
                , 'zipCode' => $company->zip_code
                , 'prefecture' => $company->prefecture
                , 'city' => $company->city
                , 'restAddress' => $company->rest_address
                , 'nearestStation' => $company->nearest_station
                , 'businessHoursFrom' => $company->business_hours_from
                , 'businessHoursTo' => $company->business_hours_to
                , 'comment' => $company->comment
                , 'note' => $company->note
                , 'logo' => !empty($company->logo) ? $company->logo->file_name : null
                , 'images' => $company->images->map(fn($image) =>
                        [
                              'displayNo' => $image->display_no
                            , 'fileName' => $image->file_name
                        ]
                    )
                , 'sellingPoints' => $allSellingPoints->map(fn($sellingPoint) =>
                        [
                              'id' => $sellingPoint->id
                            , 'name' => $sellingPoint->name
                            , 'selected' => in_array($sellingPoint->id, $company->sellingPoints->map(fn($sellingPoint) => $sellingPoint->id)->toArray())
                        ]
                    )
                , 'paymentMethods' => $allPaymentMethods->map(fn($paymentMethod) =>
                        [
                              'id' => $paymentMethod->id
                            , 'name' => $paymentMethod->name
                            , 'selected' => in_array($paymentMethod->id, $company->paymentMethods->map(fn($paymentMethod) => $paymentMethod->id)->toArray())
                        ]
                )
                , 'holidays' => $allHolidays->map(fn($holiday) =>
                    [
                          'id' => $holiday->id
                        , 'name' => $holiday->name
                        , 'selected' => in_array($holiday->id, $company->holidays->map(fn($holiday) => $holiday->id)->toArray())
                    ]
                )
            ]
        );
    }

    public function update(UpdateCompany $request, $id)
    {
        Log::debug('>>>>>>>>>>>');
        Log::debug($request);
        Log::debug('<<<<<<<<<<<');

        // TODO:会社テーブル更新
        DB::table('companies')
            ->where('id', $id)
            ->update([
                'name' => $request->name
                , 'email' => $request->email
                , 'tel' => $request->tel
                , 'zip_code' => $request->zipCode
                , 'prefecture' => $request->prefecture
                , 'city' => $request->city
                , 'rest_address' => $request->rest_address
                , 'nearest_station' => $request->nearestStation
                , 'business_hours_from' => $request->businessHoursFrom
                , 'business_hours_to' => $request->businessHoursTo
                , 'comment' => $request->comment
                , 'note' => $request->note
                , 'updated_at' => now()
            ]);

        // TODO:ロゴ更新(UPDATEではなく、DELETE + INSERT)
        $isDeleteLogo = $request->has('logo'); // キーがあれば削除を行う
        $isInsertLogo = $request->has('logo') && !empty(request()->file('logo')); // キーがあり、かつファイルデータもあれば登録も行う
        if ($isDeleteLogo) {
            // テーブル削除
            DB::table('company_logos')
                ->where('company_id', $id)
                ->delete();
            // S3削除
            Storage::cloud()->deleteDirectory("$id/logo");
        }

        if ($isInsertLogo) {
            $file = request()->file('logo');
            $fileName = Str::random(16) . '.' . $file->extension();
            $originalFileName = $file->getClientOriginalName();
            // テーブル登録
            DB::table('company_logos')
                ->insert(
                    [
                        'company_id' => $id
                        , 'file_name' => $fileName
                        , 'original_file_name' => $originalFileName
                        , 'created_at' => now()
                        , 'updated_at' => now()
                    ]
                );
            // S3登録
            Storage::cloud()
                ->putFileAs("$id/logo", $file, $fileName, 'public');
        }

        // 画像テーブル取得
        $images = DB::table('company_images')
            ->where('company_id', $id)
            ->get();
        $images = empty(!$images) ? $images->pluck('file_name', 'display_no') : null;
        $files = $request->images;
        if (!empty($files)) {
            // 画像パラメータが存在する
            foreach ($files as $displayNo => $image) {
                // 削除は共通
                DB::table('company_images')
                    ->where([
                        ['company_id', '=', $id],
                        ['display_no', '=', $displayNo],
                    ])
                    ->delete();
                if (empty($image)) {
                    Log::debug('>>>>>>>>>in');
                    Log::debug($image);
                    // 空文字の場合はs3からも削除
                    Storage::cloud()->delete("$id/images/" . $images[$displayNo]);
                }

                // 中身がファイルであれば登録
                if (!empty($image)) {
                    $fileName = Str::random(16) . '.' . $image->extension();
                    $originalFileName = $image->getClientOriginalName();
                    DB::table('company_images')->insert(
                        [
                            'company_id' => $id
                            , 'display_no' => $displayNo
                            , 'file_name' => $fileName
                            , 'original_file_name' => $originalFileName
                            , 'created_at' => now()
                            , 'updated_at' => now()
                        ]
                    );
                    Storage::cloud()
                        ->putFileAs("$id/images", $image, $fileName, 'public');
                }
            }
        }
        // セールスポイント
        DB::table('company_selling_point')
            ->where('company_id', $id)
            ->delete();
        if ($request->has('sellingPointIds')) {
            foreach ($request->sellingPointIds as $sellingPointId) {
                DB::table('company_selling_point')
                    ->insert(
                        [
                            'company_id' => $id
                            , 'selling_point_id' => $sellingPointId
                            , 'created_at' => now()
                            , 'updated_at' => now()
                        ]
                    );
            }
        }
        // 支払い方法
        DB::table('company_payment_method')
            ->where('company_id', $id)
            ->delete();
        if ($request->has('paymentMethodIds')) {
            foreach ($request->paymentMethodIds as $paymentMethodId) {
                DB::table('company_payment_method')
                    ->insert(
                        [
                            'company_id' => $id
                            , 'payment_method_id' => $paymentMethodId
                            , 'created_at' => now()
                            , 'updated_at' => now()
                        ]
                    );
            }
        }
        // 休業日
        DB::table('company_holiday')
            ->where('company_id', $id)
            ->delete();
        if ($request->has('holidayIds')) {
            foreach ($request->holidayIds as $holidayId) {
                DB::table('company_holiday')
                    ->insert(
                        [
                            'company_id' => $id
                            , 'holiday_id' => $holidayId
                            , 'created_at' => now()
                            , 'updated_at' => now()
                        ]
                    );
            }
        }
    }
}
