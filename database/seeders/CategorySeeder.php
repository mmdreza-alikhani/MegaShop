<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * ساخت درخت دسته‌بندی فروشگاه (دسته‌های اصلی و فرعی).
     */
    public function run(): void
    {
        $tree = [
            [
                'title' => 'موبایل',
                'description' => 'جدیدترین گوشی‌های موبایل برندهای معتبر جهان با گارانتی معتبر و قیمت رقابتی.',
                'children' => [
                    ['title' => 'گوشی موبایل اپل', 'description' => 'آیفون‌های اورجینال اپل؛ از سری محبوب ۱۳ تا پرچمداران نسل جدید با تراشه‌های قدرتمند.'],
                    ['title' => 'گوشی موبایل سامسونگ', 'description' => 'گلکسی‌های سامسونگ؛ از سری اقتصادی A تا پرچمداران اولترا با دوربین‌های حرفه‌ای.'],
                    ['title' => 'گوشی موبایل شیائومی', 'description' => 'ردمی و پوکو با بهترین نسبت قیمت به کارایی در بازار موبایل.'],
                    ['title' => 'گوشی موبایل هواوی', 'description' => 'گوشی‌های سری نوا هواوی با طراحی شیک، باتری پرظرفیت و دوربین قدرتمند.'],
                ],
            ],
            [
                'title' => 'لپ‌تاپ و کامپیوتر',
                'description' => 'لپ‌تاپ، مانیتور و تجهیزات کامپیوتری برای کار، درس و گیمینگ.',
                'children' => [
                    ['title' => 'لپ‌تاپ', 'description' => 'لپ‌تاپ‌های اپل، ایسوس، لنوو و اچ‌پی برای هر کاربری و هر بودجه‌ای.'],
                    ['title' => 'مانیتور', 'description' => 'مانیتورهای گیمینگ و حرفه‌ای با پنل‌های IPS و نرخ نوسازی بالا.'],
                    ['title' => 'کیبورد و ماوس', 'description' => 'کیبورد مکانیکال و ماوس‌های دقیق گیمینگ از برندهای مطرح جهان.'],
                ],
            ],
            [
                'title' => 'تبلت',
                'description' => 'تبلت‌های قدرتمند برای مطالعه، طراحی و سرگرمی.',
                'children' => [
                    ['title' => 'تبلت اپل', 'description' => 'آیپدهای اپل با تراشه‌های سریع، صفحه‌نمایش بی‌نظیر و پشتیبانی از قلم.'],
                    ['title' => 'تبلت سامسونگ', 'description' => 'تبلت‌های گلکسی تب با قلم S Pen و قابلیت‌های حرفه‌ای برای کار و خلاقیت.'],
                ],
            ],
            [
                'title' => 'ساعت و مچ‌بند هوشمند',
                'description' => 'ساعت و مچ‌بندهای هوشمند برای پایش سلامتی، ورزش و اعلان‌ها.',
                'children' => [
                    ['title' => 'ساعت هوشمند', 'description' => 'اپل واچ و گلکسی واچ؛ ترکیب زیبایی، سلامتی و فناوری روی مچ دست شما.'],
                    ['title' => 'مچ‌بند هوشمند', 'description' => 'مچ‌بندهای سبک و خوش‌قیمت با عمر باتری طولانی برای سبک زندگی فعال.'],
                ],
            ],
            [
                'title' => 'صوتی و تصویری',
                'description' => 'هدفون، هندزفری و اسپیکرهای باکیفیت برای هر سلیقه و هر بودجه.',
                'children' => [
                    ['title' => 'هدفون و هندزفری', 'description' => 'از ایرپاد و سونی تا جی‌بی‌ال؛ صدای ناب و حذف نویز فعال.'],
                    ['title' => 'اسپیکر', 'description' => 'اسپیکرهای بلوتوثی قابل حمل با صدای قدرتمند و ضدآب.'],
                ],
            ],
            [
                'title' => 'کنسول و بازی',
                'description' => 'کنسول‌ها و لوازم گیمینگ نسل جدید برای تجربه‌ای متفاوت.',
                'children' => [
                    ['title' => 'کنسول بازی', 'description' => 'پلی‌استیشن ۵، ایکس‌باکس سری X و نینتندو سوییچ با گارانتی.'],
                    ['title' => 'دسته بازی', 'description' => 'دسته‌های اورجینال و حرفه‌ای گیمینگ با لرزش لمسی و تریگر.'],
                ],
            ],
            [
                'title' => 'لوازم جانبی',
                'description' => 'شارژر، پاوربانک، قاب و محافظ؛ هر چیزی که کنار گجت‌های شما لازم است.',
                'children' => [
                    ['title' => 'پاوربانک و شارژر', 'description' => 'شارژ سریع و پاوربانک‌های مطمئن انکر و بیسوس با گارانتی.'],
                    ['title' => 'قاب و محافظ', 'description' => 'قاب، گلس و محافظ‌های اصل برای گوشی‌های پرطرفدار.'],
                ],
            ],
        ];

        $created = 0;
        foreach ($tree as $parentData) {
            $parent = $this->findOrCreate(0, $parentData['title'], $parentData['description']);
            $created += $parent->wasRecentlyCreated ? 1 : 0;

            foreach ($parentData['children'] as $childData) {
                $child = $this->findOrCreate($parent->id, $childData['title'], $childData['description']);
                $created += $child->wasRecentlyCreated ? 1 : 0;
            }
        }

        $this->command->info("✅ دسته‌بندی‌ها آماده شدند ({$created} مورد جدید ثبت شد).");
    }

    private function findOrCreate(int $parentId, string $title, ?string $description): Category
    {
        $category = Category::where('parent_id', $parentId)->where('title', $title)->first();

        if ($category) {
            return $category;
        }

        return Category::create([
            'title' => $title,
            'description' => $description,
            'parent_id' => $parentId,
            'status' => 1,
            'is_active' => true,
        ]);
    }
}
