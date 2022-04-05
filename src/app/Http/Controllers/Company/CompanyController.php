<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompany;
use App\Models\Company;
use App\Models\CompanyImage;
use App\Models\CompanyLogo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\ErrorHandler\Debug;
use function PHPUnit\Framework\isEmpty;

class CompanyController extends Controller
{
    //TODO: 通すために一旦コメントアウト
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    public function get($id): JsonResponse
    {
        $data = Company::with(['logo', 'images'])
            ->find($id)
            ->get()
            ->map(fn($company) => [
                'name' => $company->name
                , 'email' => $company->email
                , 'tel' => $company->tel
                , 'zipCode' => $company->zip_code
                , 'prefecture' => $company->prefecture
                , 'city' => $company->city
                , 'restAddress' => $company->rest_address
                , 'logo' => [
                    'url' => !empty($company->logo) ? $company->logo->file_name : null
                ]
                , 'images' => $company->images->map(fn($image) => [
                    'displayNo' => $image->display_no
                    , 'url' => $image->file_name
                ]),
            ])[0];
        return response()->json($data);
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
                , 'updated_at' => now()
            ]);

        // TODO:ロゴテーブル更新
        if ($request->has('logo')) {
            // キーがある場合のみ、処理を実行
            // 削除
            DB::table('company_logos')
                ->where('company_id', $id)
                ->delete();
            Storage::cloud()->deleteDirectory("$id/logo");
            // 登録
            $file = request()->file('logo');
            if ($file) {
                $fileName = Str::random(16) . '.' . $file->extension();
                $originalFileName = $file->getClientOriginalName();
                Storage::cloud()
                    ->putFileAs("$id/logo", $file, $fileName, 'public');
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
            }
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
                    Storage::cloud()->delete("$id/images/".$images[$displayNo]);
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
    }
}
