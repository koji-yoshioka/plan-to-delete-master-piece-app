<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'テスト1株式会社',
            'email'      => 'test-company1@test.com',
            'password'   => Hash::make('password'),
            'tel' => '0364066000',
            'zip_code' => '1066108',
            'prefecture' => '東京都',
            'city' => '港区',
            'rest_address' => '六本木六本木ヒルズ森タワー',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('companies')->insert($param);
    }
}
