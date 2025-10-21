<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        DB::table('provinces')->truncate();

        $provinces = [
            ['id' => 1, 'name' => 'اردبیل'],
            ['id' => 2, 'name' => 'اصفهان'],
            ['id' => 3, 'name' => 'البرز'],
            ['id' => 4, 'name' => 'ایلام'],
            ['id' => 5, 'name' => 'آذربایجان شرقی'],
            ['id' => 6, 'name' => 'آذربایجان غربی'],
            ['id' => 7, 'name' => 'بوشهر'],
            ['id' => 8, 'name' => 'تهران'],
            ['id' => 9, 'name' => 'چهارمحال وبختیاری'],
            ['id' => 10, 'name' => 'خراسان جنوبی'],
            ['id' => 11, 'name' => 'خراسان رضوی'],
            ['id' => 12, 'name' => 'خراسان شمالی'],
            ['id' => 13, 'name' => 'خوزستان'],
            ['id' => 14, 'name' => 'زنجان'],
            ['id' => 15, 'name' => 'سمنان'],
            ['id' => 16, 'name' => 'سیستان وبلوچستان'],
            ['id' => 17, 'name' => 'فارس'],
            ['id' => 18, 'name' => 'قزوین'],
            ['id' => 19, 'name' => 'قم'],
            ['id' => 20, 'name' => 'کردستان'],
            ['id' => 21, 'name' => 'کرمان'],
            ['id' => 22, 'name' => 'کرمانشاه'],
            ['id' => 23, 'name' => 'کهگیلویه وبویراحمد'],
            ['id' => 24, 'name' => 'گلستان'],
            ['id' => 25, 'name' => 'گیلان'],
            ['id' => 26, 'name' => 'لرستان'],
            ['id' => 27, 'name' => 'مازندران'],
            ['id' => 28, 'name' => 'مرکزی'],
            ['id' => 29, 'name' => 'هرمزگان'],
            ['id' => 30, 'name' => 'همدان'],
            ['id' => 31, 'name' => 'یزد'],
        ];

        $now = now();
        foreach ($provinces as &$province) {
            $province['created_at'] = $now;
            $province['updated_at'] = $now;
        }

        DB::table('provinces')->insert($provinces);

        $this->command->info('✅ استان‌ها با موفقیت ایجاد شدند!');
    }
}
