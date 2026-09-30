<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressSeeder extends Seeder
{
    /**
     * آدرس‌های نمونه برای کاربران.
     */
    public function run(): void
    {
        $addresses = [
            [
                'username' => 'sara.mohammadi',
                'title' => 'خانه',
                'province' => 'تهران',
                'city' => 'تهران',
                'address' => 'تهران، سعادت‌آباد، بلوار دریا، کوچه بهار، پلاک ۱۲، واحد ۳',
                'postal_code' => '1998712345',
                'phone' => '09123456789',
            ],
            [
                'username' => 'sara.mohammadi',
                'title' => 'محل کار',
                'province' => 'تهران',
                'city' => 'تهران',
                'address' => 'تهران، تهرانپارس، فلکه دوم، خیابان ۱۹۶ غربی، پلاک ۴۵، طبقه ۲',
                'postal_code' => '1653784512',
                'phone' => '02177889900',
            ],
            [
                'username' => 'amir.karimi',
                'title' => 'خانه',
                'province' => 'تهران',
                'city' => 'تهران',
                'address' => 'تهران، پونک، بلوار میرزابابایی، خیابان همیلا، مجتمع نگین، واحد ۸',
                'postal_code' => '1476893214',
                'phone' => '09121234567',
            ],
            [
                'username' => 'reza.ahmadi',
                'title' => 'خانه',
                'province' => 'کرمانشاه',
                'city' => 'کرمانشاه',
                'address' => 'کرمانشاه، بلوار شهید بهشتی، کوی گلستان، پلاک ۲۷، واحد ۱',
                'postal_code' => '6715983214',
                'phone' => '09131112233',
            ],
            [
                'username' => 'narges.rezaei',
                'title' => 'خانه',
                'province' => 'خراسان رضوی',
                'city' => 'مشهد',
                'address' => 'مشهد، بلوار وکیل‌آباد، بین دانشجو ۱۸ و ۲۰، مجتمع آسمان، واحد ۵',
                'postal_code' => '9177956321',
                'phone' => '09132223344',
            ],
            [
                'username' => 'mohammad.ghasemi',
                'title' => 'خانه',
                'province' => 'اصفهان',
                'city' => 'اصفهان',
                'address' => 'اصفهان، خیابان چهارباغ بالا، کوچه شکوفه، پلاک ۲۲، طبقه ۳',
                'postal_code' => '8173954216',
                'phone' => '09151234567',
            ],
            [
                'username' => 'elham.nouri',
                'title' => 'خانه',
                'province' => 'فارس',
                'city' => 'شیراز',
                'address' => 'شیراز، بلوار زند، روبه‌روی بیمارستان نمازی، ساختمان پزشکان، واحد ۷',
                'postal_code' => '7134759821',
                'phone' => '09159876543',
            ],
            [
                'username' => 'hossein.mousavi',
                'title' => 'خانه',
                'province' => 'آذربایجان شرقی',
                'city' => 'تبریز',
                'address' => 'تبریز، خیابان ولیعصر، نرسیده به میدان بزرگ، پلاک ۸۸، واحد ۴',
                'postal_code' => '5157693214',
                'phone' => '09161234567',
            ],
            [
                'username' => 'fatemeh.hosseini',
                'title' => 'خانه',
                'province' => 'قم',
                'city' => 'قم',
                'address' => 'قم، خیابان شهدا، کوچه ۱۴، پلاک ۹، واحد ۲',
                'postal_code' => '3719852147',
                'phone' => '09165554433',
            ],
            [
                'username' => 'ali.akbari',
                'title' => 'خانه',
                'province' => 'خوزستان',
                'city' => 'اهواز',
                'address' => 'اهواز، کیانپارس، خیابان اصلی، بین خیابان ۷ و ۸، ساختمان آرین، واحد ۱۰',
                'postal_code' => '6155931478',
                'phone' => '09171234567',
            ],
            [
                'username' => 'maryam.sharifi',
                'title' => 'خانه',
                'province' => 'یزد',
                'city' => 'یزد',
                'address' => 'یزد، بلوار دهه فجر، کوی امیرکبیر، خیابان ۱۳، پلاک ۳۱',
                'postal_code' => '8916732145',
                'phone' => '09172223344',
            ],
            [
                'username' => 'sina.tehrani',
                'title' => 'خانه',
                'province' => 'البرز',
                'city' => 'کرج',
                'address' => 'کرج، گوهردشت، فاز ۳، خیابان یاس، ساختمان نیلوفر، طبقه ۲',
                'postal_code' => '3149856712',
                'phone' => '09183334455',
            ],
        ];

        $created = 0;
        foreach ($addresses as $data) {
            $user = User::where('username', $data['username'])->first();
            if (! $user) {
                continue;
            }

            $province = Province::where('name', $data['province'])->first();
            if (! $province) {
                continue;
            }

            $cityId = $this->findCityId($province->id, $data['city']);
            if (! $cityId) {
                continue;
            }

            $exists = UserAddress::where('user_id', $user->id)->where('title', $data['title'])->exists();
            if ($exists) {
                continue;
            }

            UserAddress::create([
                'title' => $data['title'],
                'address' => $data['address'],
                'postal_code' => $data['postal_code'],
                'phone_number' => $data['phone'],
                'user_id' => $user->id,
                'province_id' => $province->id,
                'city_id' => $cityId,
            ]);

            $created++;
        }

        $this->command->info("✅ آدرس‌های کاربران آماده شدند ({$created} مورد جدید ثبت شد).");
    }

    /**
     * یافتن شناسه شهر با تحمل تفاوت حروف عربی/فارسی (ي/ی و ك/ک).
     */
    private function findCityId(int $provinceId, string $cityName): ?int
    {
        $normalize = fn (?string $value): string => str_replace(['ك', 'ي', 'ى'], ['ک', 'ی', 'ی'], (string) $value);

        $cities = City::where('province_id', $provinceId)->get(['id', 'name']);
        if ($cities->isEmpty()) {
            return null;
        }

        $target = $normalize($cityName);

        $city = $cities->first(fn ($item) => $normalize($item->name) === $target)
            ?? $cities->first(fn ($item) => str_contains($normalize($item->name), $target));

        return ($city ?? $cities->first())->id;
    }
}
