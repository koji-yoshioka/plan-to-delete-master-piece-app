<?php

use Illuminate\Database\Seeder;

class HolidaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'] as $holiday) {
            DB::table('holidays')->insert([
                'name' => $holiday,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
