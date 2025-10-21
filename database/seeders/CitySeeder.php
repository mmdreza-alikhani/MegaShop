<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        DB::table('cities')->truncate();

        $raw = Storage::get('public/files/cities.txt');

        // استخراج بخش VALUES
        preg_match('/VALUES\s*(.*);/s', $raw, $matches);
        $values = $matches[1] ?? '';

        // جدا کردن ردیف‌ها
        $rows = explode("),", $values);
        $cities = [];

        foreach ($rows as $row) {
            $row = trim($row, " \n\r\t(),;");
            $parts = explode(",", $row);

            if (count($parts) >= 4) {
                $cities[] = [
                    'id' => (int) trim($parts[0]),
                    'name' => trim(trim($parts[1]), "'"),
                    'province_id' => (int) trim($parts[2]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('cities')->insert($cities);

        $this->command->info('✅ شهرها از فایل txt با موفقیت وارد شدند!');
    }
}
