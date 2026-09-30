<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Product;
use App\Models\ProductFilter;
use App\Models\ProductImage;
use App\Models\ProductRate;
use App\Models\ProductVariation;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\Support\PlaceholderImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * کاتالوگ کامل محصولات فروشگاه به همراه تنوع‌ها، تصاویر، فیلترها، برچسب‌ها و امتیازها.
     */
    public function run(): void
    {
        $catalog = $this->catalog();

        $createdProducts = 0;
        $createdVariations = 0;

        foreach ($catalog as $data) {
            $brand = Brand::where('title', $data['brand'])->first();
            $category = Category::where('title', $data['category'])->first();
            $platform = isset($data['platform']) ? Platform::where('title', $data['platform'])->first() : null;

            if (! $brand || ! $category) {
                $this->command->warn("⚠️ برند یا دسته «{$data['title']}» یافت نشد؛ رد شد.");

                continue;
            }

            // تصویر اصلی (در صورت نبود فایل ساخته می‌شود)
            $primaryImage = PlaceholderImage::make(
                "products/images/{$data['code']}-1.jpg",
                900,
                900,
                $data['latin'],
                $data['subtitle'] ?? '',
                PlaceholderImage::paletteIndex($data['code'])
            );

            $product = Product::firstOrCreate(
                ['title' => $data['title']],
                [
                    'brand_id' => $brand->id,
                    'category_id' => $category->id,
                    'platform_id' => $platform?->id,
                    'primary_image' => $primaryImage,
                    'description' => $data['description'],
                    'status' => 1,
                    'is_active' => true,
                    'delivery_amount' => $data['delivery'],
                ]
            );

            if ($product->wasRecentlyCreated) {
                $createdProducts++;
                DB::table('products')->where('id', $product->id)->update([
                    'created_at' => now()->subDays($data['days_ago'] ?? 30),
                    'updated_at' => now()->subDays($data['days_ago'] ?? 30),
                ]);
            }

            // تصاویر گالری
            $galleryCount = $data['gallery'] ?? 3;
            for ($i = 2; $i <= $galleryCount + 1; $i++) {
                $galleryImage = PlaceholderImage::make(
                    "products/images/{$data['code']}-{$i}.jpg",
                    900,
                    900,
                    $data['latin'],
                    "VIEW {$i}",
                    PlaceholderImage::paletteIndex($data['code'])
                );

                ProductImage::firstOrCreate(
                    ['product_id' => $product->id, 'image' => $galleryImage]
                );
            }

            // تنوع‌ها (روی یک ویژگی اصلی مثل حافظه داخلی یا رنگ)
            $variationAttribute = Attribute::where('title', $data['variation_attr'])->first();
            if ($variationAttribute) {
                $skuBase = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $data['code']), 0, 12));

                foreach ($data['variations'] as $index => $variation) {
                    $sku = "MS-{$skuBase}-" . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                    $hasSale = ! empty($variation['sale']);

                    $model = ProductVariation::updateOrCreate(
                        ['sku' => $sku],
                        [
                            'product_id' => $product->id,
                            'attribute_id' => $variationAttribute->id,
                            'value' => $variation['value'],
                            'price' => $variation['price'],
                            'quantity' => $variation['qty'],
                            'sale_price' => $hasSale ? $variation['sale'] : null,
                            'date_on_sale_from' => $hasSale ? now()->subDays(6 + $index) : null,
                            'date_on_sale_to' => $hasSale ? now()->addDays(18 + $index) : null,
                        ]
                    );

                    if ($model->wasRecentlyCreated) {
                        $createdVariations++;
                    }
                }
            }

            // فیلترها
            foreach ($data['filters'] ?? [] as $attributeTitle => $value) {
                $attribute = Attribute::where('title', $attributeTitle)->first();
                if (! $attribute) {
                    continue;
                }

                ProductFilter::updateOrCreate(
                    ['product_id' => $product->id, 'attribute_id' => $attribute->id],
                    ['value' => $value, 'is_active' => true]
                );
            }

            // برچسب‌ها
            $tagIds = Tag::whereIn('title', $data['tags'] ?? [])->pluck('id')->all();
            if (! empty($tagIds)) {
                $product->tags()->syncWithoutDetaching($tagIds);
            }

            // امتیازها
            foreach ($data['rates'] ?? [] as $username => $rate) {
                $user = User::where('username', $username)->first();
                if (! $user) {
                    continue;
                }

                ProductRate::updateOrCreate(
                    ['user_id' => $user->id, 'product_id' => $product->id],
                    ['rate' => $rate]
                );
            }
        }

        $this->command->info("✅ محصولات آماده شدند ({$createdProducts} محصول و {$createdVariations} تنوع جدید ثبت شد).");
    }

    /**
     * کاتالوگ محصولات؛ هر آیتم شامل مشخصات، تنوع‌ها، فیلترها، برچسب‌ها و امتیازها است.
     */
    private function catalog(): array
    {
        return [
            [
                'code' => 'iphone-15-pro-max', 'title' => 'گوشی موبایل اپل مدل iPhone 15 Pro Max ظرفیت ۲۵۶ گیگابایت', 'latin' => 'iPhone 15 Pro Max', 'subtitle' => 'SMARTPHONE',
                'brand' => 'اپل', 'category' => 'گوشی موبایل اپل', 'platform' => 'iOS', 'delivery' => 45000, 'gallery' => 4, 'days_ago' => 20,
                'description' => '<p>گوشی موبایل اپل iPhone 15 Pro Max، پرچم‌دار اپل، با بدنه تیتانیومی سبک و مقاوم، تراشه A17 Pro و دوربین اصلی ۴۸ مگاپیکسل، تجربه‌ای بی‌نظیر از سرعت و کیفیت را ارائه می‌دهد. این گوشی برای کاربرانی که بالاترین سطح دوربین، عملکرد و دوام را می‌خواهند، انتخابی ایده‌آل است.</p><p>نمایشگر ۶.۷ اینچی Super Retina XDR با نرخ نوسازی ۱۲۰ هرتز و پورت USB-C با پشتیبانی از انتقال سریع داده، آن را به گزینه‌ای حرفه‌ای برای عکاسی، بازی و کار تبدیل کرده است.</p><ul><li>تراشه: A17 Pro</li><li>نمایشگر: ۶.۷ اینچ Super Retina XDR</li><li>دوربین: ۴۸+۱۲+۱۲ مگاپیکسل</li><li>باتری: تا ۲۹ ساعت پخش ویدیو</li><li>مقاومت: IP68</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۲۵۶ گیگابایت', 'price' => 163800000, 'sale' => 154900000, 'qty' => 8],
                    ['value' => '۵۱۲ گیگابایت', 'price' => 189500000, 'qty' => 5],
                ],
                'filters' => ['ضدآب' => 'دارد (IP68)', 'سازگار با شارژ سریع' => 'دارد (۳۰ وات)'],
                'tags' => ['پرفروش', 'اورجینال', 'پیشنهاد ویژه'],
                'rates' => ['sara.mohammadi' => 5, 'reza.ahmadi' => 4, 'narges.rezaei' => 5],
            ],
            [
                'code' => 'iphone-15', 'title' => 'گوشی موبایل اپل مدل iPhone 15 ظرفیت ۱۲۸ گیگابایت', 'latin' => 'iPhone 15', 'subtitle' => 'SMARTPHONE',
                'brand' => 'اپل', 'category' => 'گوشی موبایل اپل', 'platform' => 'iOS', 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 26,
                'description' => '<p>گوشی موبایل اپل iPhone 15 با تراشه A16 Bionic و دوربین ۴۸ مگاپیکسلی، ترکیبی متعادل از قدرت و زیبایی است. داینامیک آیلند و پورت USB-C، تجربه کاربری این نسل از آیفون را کامل‌تر کرده‌اند.</p><p>بدنه شیشه‌ای مات و مقاومت IP68، آیفون ۱۵ را برای استفاده روزمره و حرفه‌ای مناسب می‌کند.</p><ul><li>تراشه: A16 Bionic</li><li>نمایشگر: ۶.۱ اینچ Super Retina XDR</li><li>دوربین: ۴۸+۱۲ مگاپیکسل</li><li>مقاومت: IP68</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۱۲۸ گیگابایت', 'price' => 112500000, 'sale' => 106900000, 'qty' => 6],
                    ['value' => '۲۵۶ گیگابایت', 'price' => 124900000, 'qty' => 4],
                ],
                'filters' => ['ضدآب' => 'دارد (IP68)', 'سازگار با شارژ سریع' => 'دارد (۲۰ وات)'],
                'tags' => ['پرفروش', 'اورجینال'],
                'rates' => ['amir.karimi' => 4, 'elham.nouri' => 5],
            ],
            [
                'code' => 'iphone-13', 'title' => 'گوشی موبایل اپل مدل iPhone 13 ظرفیت ۱۲۸ گیگابایت', 'latin' => 'iPhone 13', 'subtitle' => 'SMARTPHONE',
                'brand' => 'اپل', 'category' => 'گوشی موبایل اپل', 'platform' => 'iOS', 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 90,
                'description' => '<p>iPhone 13 یکی از محبوب‌ترین آیفون‌های چند سال اخیر است؛ ترکیب تراشه A15 Bionic، باتری بادوام و دوربین دوگانه ۱۲ مگاپیکسلی، آن را به گزینه‌ای مطمئن در میان‌رده تبدیل کرده است.</p><p>اگر به دنبال یک آیفون خوش‌قیمت با عملکرد روان و پشتیبانی طولانی‌مدت هستید، iPhone 13 انتخاب هوشمندانه‌ای است.</p><ul><li>تراشه: A15 Bionic</li><li>نمایشگر: ۶.۱ اینچ Super Retina XDR</li><li>باتری: تا ۱۹ ساعت پخش ویدیو</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۱۲۸ گیگابایت', 'price' => 68900000, 'qty' => 10],
                    ['value' => '۲۵۶ گیگابایت', 'price' => 76400000, 'qty' => 6],
                ],
                'filters' => ['ضدآب' => 'دارد (IP68)', 'سازگار با شارژ سریع' => 'دارد (۲۰ وات)'],
                'tags' => ['اورجینال'],
                'rates' => ['ali.akbari' => 5, 'maryam.sharifi' => 4, 'sina.tehrani' => 5],
            ],
            [
                'code' => 'galaxy-s24-ultra', 'title' => 'گوشی موبایل سامسونگ مدل Galaxy S24 Ultra ظرفیت ۲۵۶ گیگابایت', 'latin' => 'Galaxy S24 Ultra', 'subtitle' => 'SMARTPHONE',
                'brand' => 'سامسونگ', 'category' => 'گوشی موبایل سامسونگ', 'platform' => 'اندروید', 'delivery' => 45000, 'gallery' => 4, 'days_ago' => 18,
                'description' => '<p>گلکسی S24 اولترا پرچم‌دار سامسونگ با قابلیت‌های هوش مصنوعی Galaxy AI، قلم S Pen و دوربین ۲۰۰ مگاپیکسلی، مرزهای گوشی‌های هوشمند را جابه‌جا کرده است. بدنه تیتانیومی و نمایشگر ۶.۸ اینچی با روشنایی فوق‌العاده، تجربه‌ای پریمیوم ارائه می‌دهد.</p><p>ترجمه هم‌زمان مکالمات، جستجوی دایره‌ای و ویرایش هوشمند تصاویر، تنها بخشی از قابلیت‌های نسل جدید این پرچم‌دار است.</p><ul><li>تراشه: Snapdragon 8 Gen 3 for Galaxy</li><li>نمایشگر: ۶.۸ اینچ Dynamic AMOLED 2X</li><li>دوربین: ۲۰۰+۵۰+۱۲+۱۰ مگاپیکسل</li><li>باتری: ۵۰۰۰ میلی‌آمپرساعت</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۲۵۶ گیگابایت', 'price' => 97500000, 'sale' => 92900000, 'qty' => 7],
                    ['value' => '۵۱۲ گیگابایت', 'price' => 105900000, 'qty' => 4],
                ],
                'filters' => ['ضدآب' => 'دارد (IP68)', 'سازگار با شارژ سریع' => 'دارد (۴۵ وات)'],
                'tags' => ['پرفروش', 'پیشنهاد ویژه'],
                'rates' => ['mohammad.ghasemi' => 5, 'negar.kazemi' => 5, 'hossein.mousavi' => 4],
            ],
            [
                'code' => 'galaxy-a55', 'title' => 'گوشی موبایل سامسونگ مدل Galaxy A55 ظرفیت ۱۲۸ گیگابایت', 'latin' => 'Galaxy A55', 'subtitle' => 'SMARTPHONE',
                'brand' => 'سامسونگ', 'category' => 'گوشی موبایل سامسونگ', 'platform' => 'اندروید', 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 45,
                'description' => '<p>گلکسی A55 محبوب‌ترین میان‌رده سامسونگ است؛ با بدنه فلزی، دوربین سه‌گانه ۵۰ مگاپیکسلی و باتری ۵۰۰۰ میلی‌آمپرساعتی، هر آنچه از یک گوشی روزمره انتظار دارید را ارائه می‌دهد.</p><p>نمایشگر Super AMOLED با نرخ نوسازی ۱۲۰ هرتز، تجربه‌ای روان و باکیفیت در تماشای ویدیو و بازی فراهم می‌کند.</p><ul><li>تراشه: Exynos 1480</li><li>نمایشگر: ۶.۶ اینچ Super AMOLED</li><li>دوربین: ۵۰+۱۲+۵ مگاپیکسل</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۱۲۸ گیگابایت', 'price' => 22800000, 'qty' => 14],
                    ['value' => '۲۵۶ گیگابایت', 'price' => 25600000, 'qty' => 9],
                ],
                'filters' => ['ضدآب' => 'دارد (IP67)', 'سازگار با شارژ سریع' => 'دارد (۲۵ وات)'],
                'tags' => ['پرفروش'],
                'rates' => ['fatemeh.hosseini' => 4, 'sara.mohammadi' => 4],
            ],
            [
                'code' => 'galaxy-s23-fe', 'title' => 'گوشی موبایل سامسونگ مدل Galaxy S23 FE ظرفیت ۲۵۶ گیگابایت', 'latin' => 'Galaxy S23 FE', 'subtitle' => 'SMARTPHONE',
                'brand' => 'سامسونگ', 'category' => 'گوشی موبایل سامسونگ', 'platform' => 'اندروید', 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 60,
                'description' => '<p>گلکسی S23 FE پلی است بین سری پرچم‌دار و میان‌رده سامسونگ؛ با تراشه قدرتمند، دوربین ۵۰ مگاپیکسلی و شارژ سریع ۲۵ وات، تجربه‌ای نزدیک به پرچم‌دار را با قیمتی منطقی ارائه می‌دهد.</p><p>مقاومت IP68 و شارژ بی‌سیم، از ویژگی‌هایی است که کمتر در این رده قیمتی دیده می‌شود.</p><ul><li>تراشه: Exynos 2200</li><li>نمایشگر: ۶.۴ اینچ Dynamic AMOLED 2X</li><li>شارژ بی‌سیم: پشتیبانی می‌کند</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۲۵۶ گیگابایت', 'price' => 38900000, 'sale' => 35900000, 'qty' => 6],
                ],
                'filters' => ['ضدآب' => 'دارد (IP68)', 'سازگار با شارژ سریع' => 'دارد (۲۵ وات)'],
                'tags' => ['تخفیف‌دار'],
                'rates' => ['elham.nouri' => 4, 'reza.ahmadi' => 4],
            ],
            [
                'code' => 'redmi-note-13-pro', 'title' => 'گوشی موبایل شیائومی مدل Redmi Note 13 Pro ظرفیت ۲۵۶ گیگابایت', 'latin' => 'Redmi Note 13 Pro', 'subtitle' => 'SMARTPHONE',
                'brand' => 'شیائومی', 'category' => 'گوشی موبایل شیائومی', 'platform' => 'اندروید', 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 35,
                'description' => '<p>ردمی نوت ۱۳ پرو با دوربین ۲۰۰ مگاپیکسلی و شارژ سریع ۶۷ وات، استانداردهای میان‌رده را جابه‌جا کرده است. نمایشگر AMOLED با نرخ نوسازی ۱۲۰ هرتز و روشنایی بالا، آن را برای مصرف روزانه ایده‌آل می‌کند.</p><p>نسبت قیمت به امکانات این گوشی، آن را به یکی از پرفروش‌ترین‌های بازار تبدیل کرده است.</p><ul><li>تراشه: Snapdragon 7s Gen 2</li><li>دوربین: ۲۰۰+۸+۲ مگاپیکسل</li><li>شارژ سریع: ۶۷ وات</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۲۵۶ گیگابایت', 'price' => 16900000, 'qty' => 18],
                    ['value' => '۵۱۲ گیگابایت', 'price' => 19600000, 'qty' => 8],
                ],
                'filters' => ['ضدآب' => 'دارد (IP54)', 'سازگار با شارژ سریع' => 'دارد (۶۷ وات)'],
                'tags' => ['پرفروش', 'تخفیف‌دار'],
                'rates' => ['amir.karimi' => 5, 'sina.tehrani' => 4, 'narges.rezaei' => 4],
            ],
            [
                'code' => 'xiaomi-14', 'title' => 'گوشی موبایل شیائومی مدل Xiaomi 14 ظرفیت ۲۵۶ گیگابایت', 'latin' => 'Xiaomi 14', 'subtitle' => 'SMARTPHONE',
                'brand' => 'شیائومی', 'category' => 'گوشی موبایل شیائومی', 'platform' => 'اندروید', 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 40,
                'description' => '<p>شیائومی ۱۴ یک پرچم‌دار جمع‌وجور است؛ با لنزهای Leica، تراشه Snapdragon 8 Gen 3 و نمایشگر ۱۲۰ هرتزی، کیفیت عکاسی و سرعت را در ابعادی کوچک ارائه می‌دهد.</p><p>شارژ سریع ۹۰ واتی، باتری آن را در کمتر از ۳۵ دقیقه به شارژ کامل می‌رساند.</p><ul><li>تراشه: Snapdragon 8 Gen 3</li><li>دوربین: سه‌گانه ۵۰ مگاپیکسل با لنزهای Leica</li><li>شارژ سریع: ۹۰ وات</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۲۵۶ گیگابایت', 'price' => 52800000, 'sale' => 49500000, 'qty' => 5],
                ],
                'filters' => ['ضدآب' => 'دارد (IP68)', 'سازگار با شارژ سریع' => 'دارد (۹۰ وات)'],
                'tags' => ['پیشنهاد ویژه'],
                'rates' => ['mohammad.ghasemi' => 5, 'maryam.sharifi' => 5],
            ],
            [
                'code' => 'poco-x6-pro', 'title' => 'گوشی موبایل شیائومی مدل POCO X6 Pro ظرفیت ۲۵۶ گیگابایت', 'latin' => 'POCO X6 Pro', 'subtitle' => 'SMARTPHONE',
                'brand' => 'شیائومی', 'category' => 'گوشی موبایل شیائومی', 'platform' => 'اندروید', 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 28,
                'description' => '<p>پوکو X6 پرو با تراشه Dimensity 8300 Ultra، انتخاب اول گیمرهای میان‌رده است. نمایشگر AMOLED ۱.۵K با نرخ نوسازی ۱۲۰ هرتز و لمس ۲۱۶۰ هرتزی، کنترل دقیقی در بازی‌ها می‌دهد.</p><p>شارژ ۶۷ وات و باتری ۵۰۰۰ میلی‌آمپرساعتی، ترکیبی عالی برای مصرف سنگین روزانه است.</p><ul><li>تراشه: Dimensity 8300 Ultra</li><li>نمایشگر: ۶.۶۷ اینچ AMOLED ۱.۵K</li><li>شارژ سریع: ۶۷ وات</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۲۵۶ گیگابایت', 'price' => 18700000, 'qty' => 11],
                ],
                'filters' => ['ضدآب' => 'دارد (IP54)', 'سازگار با شارژ سریع' => 'دارد (۶۷ وات)'],
                'tags' => ['جدید'],
                'rates' => ['ali.akbari' => 4, 'hossein.mousavi' => 4],
            ],
            [
                'code' => 'nova-12i', 'title' => 'گوشی موبایل هواوی مدل Nova 12i ظرفیت ۱۲۸ گیگابایت', 'latin' => 'Huawei Nova 12i', 'subtitle' => 'SMARTPHONE',
                'brand' => 'هواوی', 'category' => 'گوشی موبایل هواوی', 'platform' => 'اندروید', 'delivery' => 45000, 'gallery' => 2, 'days_ago' => 50,
                'description' => '<p>هواوی نوا ۱۲i با طراحی شیک، باتری پرظرفیت ۵۰۰۰ میلی‌آمپرساعتی و شارژ سریع ۴۰ وات، گزینه‌ای مقرون‌به‌صرفه برای کاربران روزمره است.</p><p>دوربین ۵۰ مگاپیکسلی و نمایشگر بزرگ ۶.۷ اینچی، تجربه‌ای راحت در استفاده روزانه فراهم می‌کند.</p><ul><li>نمایشگر: ۶.۷ اینچ</li><li>دوربین: ۵۰+۲ مگاپیکسل</li><li>باتری: ۵۰۰۰ میلی‌آمپرساعت</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۱۲۸ گیگابایت', 'price' => 11900000, 'qty' => 13],
                ],
                'filters' => ['ضدآب' => 'دارد (IP53)', 'سازگار با شارژ سریع' => 'دارد (۴۰ وات)'],
                'tags' => ['جدید'],
                'rates' => ['fatemeh.hosseini' => 5, 'negar.kazemi' => 4],
            ],
            [
                'code' => 'macbook-air-m3', 'title' => 'لپ‌تاپ اپل مدل MacBook Air 13 اینچ M3 ظرفیت ۲۵۶ گیگابایت', 'latin' => 'MacBook Air M3', 'subtitle' => 'LAPTOP',
                'brand' => 'اپل', 'category' => 'لپ‌تاپ', 'platform' => 'macOS', 'delivery' => 85000, 'gallery' => 3, 'days_ago' => 22,
                'description' => '<p>مک‌بوک ایر M3 ترکیبی از باریکی، سبکی و قدرت است. تراشه M3 با گرافیک نسل جدید، عملکردی روان در کارهای سنگین و بازی‌ها ارائه می‌دهد؛ در حالی که دمای دستگاه خنک و بی‌صدا باقی می‌ماند.</p><p>باتری تا ۱۸ ساعت، نمایشگر Liquid Retina و بدنه آلومینیومی بازیافتی، آن را به انتخاب اول دانشجویان و حرفه‌ای‌ها تبدیل کرده است.</p><ul><li>تراشه: Apple M3</li><li>نمایشگر: ۱۳.۶ اینچ Liquid Retina</li><li>باتری: تا ۱۸ ساعت</li><li>وزن: ۱.۲۴ کیلوگرم</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۲۵۶ گیگابایت', 'price' => 78500000, 'qty' => 5],
                    ['value' => '۵۱۲ گیگابایت', 'price' => 92900000, 'qty' => 3],
                ],
                'filters' => ['سازگار با شارژ سریع' => 'دارد (۶۷ وات)', 'نوع کاربری' => 'دانشجویی'],
                'tags' => ['پرفروش', 'اورجینال', 'دانشجویی'],
                'rates' => ['sara.mohammadi' => 5, 'mohammad.ghasemi' => 5],
            ],
            [
                'code' => 'macbook-pro-14-m3-pro', 'title' => 'لپ‌تاپ اپل مدل MacBook Pro 14 اینچ M3 Pro', 'latin' => 'MacBook Pro 14', 'subtitle' => 'LAPTOP',
                'brand' => 'اپل', 'category' => 'لپ‌تاپ', 'platform' => 'macOS', 'delivery' => 85000, 'gallery' => 3, 'days_ago' => 15,
                'description' => '<p>مک‌بوک پرو ۱۴ اینچ با تراشه M3 Pro، برای کارهای حرفه‌ای مثل تدوین ویدیو، برنامه‌نویسی و رندر سه‌بعدی ساخته شده است. نمایشگر Liquid Retina XDR با نرخ نوسازی ProMotion، تصویری خیره‌کننده ارائه می‌دهد.</p><p>پورت‌های متنوع (HDMI، SDXC و Thunderbolt 4) و سیستم صوتی شش‌باندی، آن را به یک ایستگاه کاری کامل تبدیل کرده است.</p><ul><li>تراشه: Apple M3 Pro</li><li>نمایشگر: ۱۴.۲ اینچ Liquid Retina XDR</li><li>پورت‌ها: HDMI، SDXC، ۳× Thunderbolt 4</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۵۱۲ گیگابایت', 'price' => 142000000, 'sale' => 138500000, 'qty' => 3],
                ],
                'filters' => ['سازگار با شارژ سریع' => 'دارد (۹۶ وات)', 'نوع کاربری' => 'حرفه‌ای'],
                'tags' => ['پیشنهاد ویژه', 'اورجینال'],
                'rates' => ['reza.ahmadi' => 5, 'elham.nouri' => 4],
            ],
            [
                'code' => 'asus-rog-strix-g16', 'title' => 'لپ‌تاپ ایسوس مدل ROG Strix G16', 'latin' => 'ROG Strix G16', 'subtitle' => 'GAMING LAPTOP',
                'brand' => 'ایسوس', 'category' => 'لپ‌تاپ', 'platform' => 'ویندوز', 'delivery' => 85000, 'gallery' => 3, 'days_ago' => 30,
                'description' => '<p>لپ‌تاپ گیمینگ ایسوس ROG Strix G16 با پردازنده Core i7 نسل ۱۳ و کارت گرافیک RTX 4060، برای اجرای سنگین‌ترین بازی‌ها و نرم‌افزارهای رندر آماده است. سیستم خنک‌کننده سه‌فنی ROG، دمای دستگاه را در بار سنگین کنترل می‌کند.</p><p>نمایشگر ۱۶ اینچی ۱۶۵ هرتزی و کیبورد RGB، تجربه گیمینگ کامل را تکمیل می‌کنند.</p><ul><li>پردازنده: Core i7-13650HX</li><li>گرافیک: RTX 4060 8GB</li><li>نمایشگر: ۱۶ اینچ FHD ۱۶۵Hz</li><li>حافظه: ۱۶GB RAM / 1TB SSD</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۱ ترابایت', 'price' => 118500000, 'qty' => 4],
                ],
                'filters' => ['سازگار با شارژ سریع' => 'دارد (۲۴۰ وات)', 'نوع کاربری' => 'گیمینگ'],
                'tags' => ['گیمینگ', 'پرفروش'],
                'rates' => ['ali.akbari' => 5, 'hossein.mousavi' => 5, 'sina.tehrani' => 4],
            ],
            [
                'code' => 'lenovo-ideapad-slim-3', 'title' => 'لپ‌تاپ لنوو مدل IdeaPad Slim 3', 'latin' => 'IdeaPad Slim 3', 'subtitle' => 'LAPTOP',
                'brand' => 'لنوو', 'category' => 'لپ‌تاپ', 'platform' => 'ویندوز', 'delivery' => 85000, 'gallery' => 2, 'days_ago' => 55,
                'description' => '<p>لپ‌تاپ لنوو آیدیاپد اسلیم ۳، گزینه‌ای اقتصادی و قابل اعتماد برای دانشجویان و کارهای اداری است. پردازنده Core i5، رم ۱۶ گیگابایت و SSD سریع، پاسخ‌گویی روان در کارهای روزمره را تضمین می‌کند.</p><p>وزن سبک و باتری مناسب، آن را برای حمل روزانه به دانشگاه یا محل کار ایده‌آل کرده است.</p><ul><li>پردازنده: Core i5-12450H</li><li>حافظه: 16GB RAM / 512GB SSD</li><li>نمایشگر: ۱۵.۶ اینچ FHD</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۵۱۲ گیگابایت', 'price' => 34500000, 'sale' => 32900000, 'qty' => 8],
                ],
                'filters' => ['سازگار با شارژ سریع' => 'دارد (۶۵ وات)', 'نوع کاربری' => 'دانشجویی'],
                'tags' => ['دانشجویی', 'تخفیف‌دار'],
                'rates' => ['fatemeh.hosseini' => 4, 'maryam.sharifi' => 4],
            ],
            [
                'code' => 'hp-victus-15', 'title' => 'لپ‌تاپ اچ‌پی مدل Victus 15', 'latin' => 'HP Victus 15', 'subtitle' => 'GAMING LAPTOP',
                'brand' => 'اچ‌پی', 'category' => 'لپ‌تاپ', 'platform' => 'ویندوز', 'delivery' => 85000, 'gallery' => 2, 'days_ago' => 42,
                'description' => '<p>لپ‌تاپ گیمینگ اچ‌پی Victus 15، ورودی مقرون‌به‌صرفه دنیای گیمینگ است. کارت گرافیک RTX 3050 و پردازنده Ryzen 5، اجرای روان بازی‌های روز را در تنظیمات متوسط تا بالا ممکن می‌کند.</p><p>نمایشگر ۱۴۴ هرتزی و سیستم خنک‌کننده بهبودیافته، تجربه بازی راحت‌تری ارائه می‌دهد.</p><ul><li>پردازنده: Ryzen 5 7535HS</li><li>گرافیک: RTX 3050 6GB</li><li>نمایشگر: ۱۵.۶ اینچ ۱۴۴Hz</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۵۱۲ گیگابایت', 'price' => 47900000, 'qty' => 5],
                ],
                'filters' => ['سازگار با شارژ سریع' => 'دارد (۲۰۰ وات)', 'نوع کاربری' => 'گیمینگ'],
                'tags' => ['گیمینگ'],
                'rates' => ['reza.ahmadi' => 4, 'amir.karimi' => 4],
            ],
            [
                'code' => 'ipad-air-5', 'title' => 'تبلت اپل مدل iPad Air 5 ظرفیت ۶۴ گیگابایت', 'latin' => 'iPad Air 5', 'subtitle' => 'TABLET',
                'brand' => 'اپل', 'category' => 'تبلت اپل', 'platform' => 'iOS', 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 65,
                'description' => '<p>آیپد ایر ۵ با تراشه M1 — همان تراشه مک‌بوک ایر — قدرت فوق‌العاده‌ای در بدنه‌ای باریک ارائه می‌دهد. برای طراحی، یادداشت‌برداری و تماشای فیلم، ترکیب کاملی از سرعت و کیفیت است.</p><p>پشتیبانی از Apple Pencil نسل دوم و Magic Keyboard، آن را به یک ابزار کاری کامل تبدیل می‌کند.</p><ul><li>تراشه: Apple M1</li><li>نمایشگر: ۱۰.۹ اینچ Liquid Retina</li><li>پشتیبانی از Apple Pencil 2</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۶۴ گیگابایت', 'price' => 39800000, 'qty' => 6],
                    ['value' => '۲۵۶ گیگابایت', 'price' => 51500000, 'qty' => 3],
                ],
                'filters' => ['ضدآب' => 'ندارد', 'سازگار با شارژ سریع' => 'دارد (۲۰ وات)'],
                'tags' => ['پرفروش'],
                'rates' => ['narges.rezaei' => 5, 'sina.tehrani' => 5],
            ],
            [
                'code' => 'galaxy-tab-s9-fe', 'title' => 'تبلت سامسونگ مدل Galaxy Tab S9 FE ظرفیت ۱۲۸ گیگابایت', 'latin' => 'Galaxy Tab S9 FE', 'subtitle' => 'TABLET',
                'brand' => 'سامسونگ', 'category' => 'تبلت سامسونگ', 'platform' => 'اندروید', 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 33,
                'description' => '<p>گلکسی تب S9 FE با قلم S Pen همراه، انتخاب عالی برای یادداشت‌برداری و طراحی است. نمایشگر ۱۰.۹ اینچی TFT با نرخ نوسازی ۹۰ هرتز و باتری ۸۰۰۰ میلی‌آمپرساعتی، همراهی مطمئن برای تمام روز است.</p><p>مقاومت IP68 در بین تبلت‌ها کمنظیر است و خیال شما از پاشش آب راحت می‌کند.</p><ul><li>نمایشگر: ۱۰.۹ اینچ ۹۰Hz</li><li>قلم S Pen همراه دستگاه</li><li>باتری: ۸۰۰۰ میلی‌آمپرساعت</li></ul>',
                'variation_attr' => 'حافظه داخلی',
                'variations' => [
                    ['value' => '۱۲۸ گیگابایت', 'price' => 26500000, 'sale' => 24900000, 'qty' => 5],
                ],
                'filters' => ['ضدآب' => 'دارد (IP68)', 'سازگار با شارژ سریع' => 'دارد (۴۵ وات)'],
                'tags' => ['تخفیف‌دار'],
                'rates' => ['negar.kazemi' => 4, 'ali.akbari' => 4],
            ],
            [
                'code' => 'apple-watch-s9', 'title' => 'ساعت هوشمند اپل مدل Watch Series 9 سایز ۴۵ میلی‌متر', 'latin' => 'Apple Watch S9', 'subtitle' => 'SMARTWATCH',
                'brand' => 'اپل', 'category' => 'ساعت هوشمند', 'platform' => 'iOS', 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 25,
                'description' => '<p>اپل واچ سری ۹ با تراشه S9 SiP و نمایشگر همیشه‌روشن، سریع‌ترین و دقیق‌ترین اپل واچ تاکنون است. ژست دو ضربه‌ای انگشتان، پاسخ‌گویی به تماس و اعلان‌ها را ساده‌تر کرده است.</p><p>پایش ضربان قلب، اکسیژن خون، خواب و بیش از صد نوع تمرین ورزشی، آن را به همراه سلامتی شما تبدیل می‌کند.</p><ul><li>تراشه: S9 SiP</li><li>سایز: ۴۵ میلی‌متر</li><li>مقاومت: ۵۰ متر ضدآب</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'مشکی', 'price' => 32900000, 'sale' => 30900000, 'qty' => 5],
                    ['value' => 'نقره‌ای', 'price' => 32900000, 'qty' => 4],
                ],
                'filters' => ['ضدآب' => 'دارد (۵۰ متر)', 'نوع اتصال' => 'بی‌سیم'],
                'tags' => ['پرفروش', 'پیشنهاد ویژه', 'هدیه'],
                'rates' => ['sara.mohammadi' => 5, 'maryam.sharifi' => 5],
            ],
            [
                'code' => 'galaxy-watch-6', 'title' => 'ساعت هوشمند سامسونگ مدل Galaxy Watch 6 سایز ۴۴ میلی‌متر', 'latin' => 'Galaxy Watch 6', 'subtitle' => 'SMARTWATCH',
                'brand' => 'سامسونگ', 'category' => 'ساعت هوشمند', 'platform' => 'اندروید', 'delivery' => 45000, 'gallery' => 2, 'days_ago' => 70,
                'description' => '<p>گلکسی واچ ۶ با نمایشگر بزرگ‌تر و حاشیه باریک‌تر، زیبایی و کارایی را هم‌زمان ارائه می‌دهد. حسگر BioActive برای پایش دقیق خواب، ضربان قلب و ترکیب بدن، و قابلیت ECG از ویژگی‌های مهم آن است.</p><p>باتری بهبودیافته و شارژ سریع، استفاده مداوم را ممکن می‌کند.</p><ul><li>سایز: ۴۴ میلی‌متر</li><li>حسگر: BioActive (ضربان، خواب، ترکیب بدن)</li><li>مقاومت: ۵AATM + IP68</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'مشکی', 'price' => 18900000, 'qty' => 6],
                    ['value' => 'نقره‌ای', 'price' => 18900000, 'qty' => 4],
                ],
                'filters' => ['ضدآب' => 'دارد (۵۰ متر)', 'نوع اتصال' => 'بی‌سیم'],
                'tags' => ['هدیه'],
                'rates' => ['hossein.mousavi' => 4, 'elham.nouri' => 4],
            ],
            [
                'code' => 'smart-band-8', 'title' => 'مچ‌بند هوشمند شیائومی مدل Smart Band 8', 'latin' => 'Smart Band 8', 'subtitle' => 'FITNESS BAND',
                'brand' => 'شیائومی', 'category' => 'مچ‌بند هوشمند', 'platform' => 'اندروید', 'delivery' => 35000, 'gallery' => 2, 'days_ago' => 48,
                'description' => '<p>مچ‌بند شیائومی Smart Band 8 با نمایشگر AMOLED ۱.۶۲ اینچی، ۱۵۰ حالت ورزشی و عمر باتری تا ۱۶ روز، همراهی سبک و خوش‌قیمت برای سلامتی شماست.</p><p>پایش مداوم ضربان قلب و اکسیژن خون، خواب و استرس، از قابلیت‌های مهم این نسل است.</p><ul><li>نمایشگر: ۱.۶۲ اینچ AMOLED</li><li>باتری: تا ۱۶ روز</li><li>۱۵۰ حالت ورزشی</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'مشکی', 'price' => 2450000, 'qty' => 25],
                    ['value' => 'آبی', 'price' => 2450000, 'qty' => 15],
                ],
                'filters' => ['ضدآب' => 'دارد (۵۰ متر)'],
                'tags' => ['پرفروش', 'هدیه'],
                'rates' => ['fatemeh.hosseini' => 5, 'ali.akbari' => 5, 'sina.tehrani' => 4],
            ],
            [
                'code' => 'airpods-pro-2', 'title' => 'هدفون بی‌سیم اپل مدل AirPods Pro 2', 'latin' => 'AirPods Pro 2', 'subtitle' => 'EARBUDS',
                'brand' => 'اپل', 'category' => 'هدفون و هندزفری', 'platform' => 'iOS', 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 24,
                'description' => '<p>ایرپاد پرو ۲ با تراشه H2، حذف نویز فعال تا دو برابر قوی‌تر از نسل قبل و حالت Transparency طبیعی، تجربه‌ای بی‌نظیر از صدا ارائه می‌دهد. کیس شارژ USB-C با قابلیت Find My و بلندگوی داخلی، پیدا کردن ایرپاد را آسان می‌کند.</p><p>صدای فضایی شخصی‌سازی‌شده با ردیابی حرکت سر، تماشای فیلم را به یک تجربه سینمایی تبدیل می‌کند.</p><ul><li>تراشه: H2</li><li>حذف نویز فعال (ANC)</li><li>باتری: تا ۶ ساعت (۳۰ ساعت با کیس)</li><li>مقاومت: IP54</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'سفید', 'price' => 18900000, 'sale' => 17900000, 'qty' => 9],
                ],
                'filters' => ['نوع اتصال' => 'بی‌سیم', 'ضدآب' => 'دارد (IP54)'],
                'tags' => ['پرفروش', 'هدیه'],
                'rates' => ['amir.karimi' => 5, 'narges.rezaei' => 5, 'mohammad.ghasemi' => 4],
            ],
            [
                'code' => 'sony-wh1000xm5', 'title' => 'هدفون بی‌سیم سونی مدل WH-1000XM5', 'latin' => 'Sony WH-1000XM5', 'subtitle' => 'HEADPHONES',
                'brand' => 'سونی', 'category' => 'هدفون و هندزفری', 'platform' => null, 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 38,
                'description' => '<p>هدفون سونی WH-1000XM5، استاندارد طلایی حذف نویز در دنیا است؛ با هشت میکروفون و دو پردازنده، صدای محیط را به شکلی فوق‌العاده حذف می‌کند. صدای Hi-Res و تنظیمات EQ حرفه‌ای، لذت موسیقی را چند برابر می‌کند.</p><p>عمر باتری ۳۰ ساعته و شارژ سریع (۳ ساعت پخش با ۳ دقیقه شارژ)، آن را برای سفر و کار ایده‌آل کرده است.</p><ul><li>حذف نویز فعال پیشرفته</li><li>باتری: ۳۰ ساعت</li><li>پشتیبانی از LDAC و Hi-Res Audio</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'مشکی', 'price' => 24500000, 'qty' => 7],
                    ['value' => 'نقره‌ای', 'price' => 24500000, 'qty' => 4],
                ],
                'filters' => ['نوع اتصال' => 'بی‌سیم', 'ضدآب' => 'ندارد'],
                'tags' => ['پیشنهاد ویژه'],
                'rates' => ['reza.ahmadi' => 5, 'sara.mohammadi' => 5],
            ],
            [
                'code' => 'jbl-tune-buds', 'title' => 'هندزفری بلوتوثی جی‌بی‌ال مدل Tune Buds', 'latin' => 'JBL Tune Buds', 'subtitle' => 'EARBUDS',
                'brand' => 'جی‌بی‌ال', 'category' => 'هدفون و هندزفری', 'platform' => null, 'delivery' => 35000, 'gallery' => 2, 'days_ago' => 58,
                'description' => '<p>هندزفری جی‌بی‌ال Tune Buds با حذف نویز فعال، صدای JBL Pure Bass و باتری ۴۸ ساعته (با کیس)، انتخابی عالی در رده قیمتی خود است.</p><p>مقاومت IP54 و اپلیکیشن JBL Headphones با اکولایزر اختصاصی، تجربه کاربری کاملی ارائه می‌دهد.</p><ul><li>حذف نویز فعال</li><li>باتری: تا ۴۸ ساعت</li><li>مقاومت: IP54</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'مشکی', 'price' => 4890000, 'qty' => 16],
                    ['value' => 'سفید', 'price' => 4890000, 'qty' => 10],
                    ['value' => 'آبی', 'price' => 4890000, 'qty' => 8],
                ],
                'filters' => ['نوع اتصال' => 'بی‌سیم', 'ضدآب' => 'دارد (IP54)'],
                'tags' => ['تخفیف‌دار'],
                'rates' => ['ali.akbari' => 4, 'negar.kazemi' => 4],
            ],
            [
                'code' => 'jbl-flip-6', 'title' => 'اسپیکر بلوتوثی جی‌بی‌ال مدل Flip 6', 'latin' => 'JBL Flip 6', 'subtitle' => 'SPEAKER',
                'brand' => 'جی‌بی‌ال', 'category' => 'اسپیکر', 'platform' => null, 'delivery' => 45000, 'gallery' => 3, 'days_ago' => 52,
                'description' => '<p>اسپیکر جی‌بی‌ال Flip 6 با صدای قدرتمند دو طرفه، بیس عمیق و بدنه ضدآب IP67، همراه ایده‌آل مهمانی و سفر است. اتصال دو اسپیکر برای صدای استریو و باتری ۱۲ ساعته، آن را به یکی از محبوب‌ترین اسپیکرهای بازار تبدیل کرده است.</p><ul><li>توان: ۳۰ وات</li><li>مقاومت: IP67 (ضدآب و گردوغبار)</li><li>باتری: ۱۲ ساعت</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'مشکی', 'price' => 7450000, 'qty' => 12],
                    ['value' => 'قرمز', 'price' => 7450000, 'qty' => 6],
                    ['value' => 'آبی', 'price' => 7450000, 'qty' => 7],
                ],
                'filters' => ['نوع اتصال' => 'بی‌سیم', 'ضدآب' => 'دارد (IP67)'],
                'tags' => ['جدید'],
                'rates' => ['hossein.mousavi' => 5, 'fatemeh.hosseini' => 4],
            ],
            [
                'code' => 'playstation-5', 'title' => 'کنسول بازی سونی مدل PlayStation 5 نسخه دیسک‌خور', 'latin' => 'PlayStation 5', 'subtitle' => 'CONSOLE',
                'brand' => 'سونی', 'category' => 'کنسول بازی', 'platform' => 'پلی‌استیشن', 'delivery' => 85000, 'gallery' => 3, 'days_ago' => 12,
                'description' => '<p>پلی‌استیشن ۵ با پردازنده هشت‌هسته‌ای Zen 2، SSD فوق‌سریع و قابلیت اجرای بازی‌ها با کیفیت 4K و ۱۲۰ فریم، نسل جدید گیمینگ را رقم زده است. دسته DualSense با بازخورد لمسی و تریگرهای تطبیقی، حس بازی را به واقعیت نزدیک می‌کند.</p><p>نسخه دیسک‌خور امکان اجرای بازی‌های فیزیکی و تماشای بلوری را هم فراهم می‌کند.</p><ul><li>معماری: AMD Zen 2 + RDNA 2</li><li>حافظه: 1TB SSD فوق‌سریع</li><li>خروجی: 4K@120Hz / 8K</li><li>همراه با یک دسته DualSense</li></ul>',
                'variation_attr' => 'نسخه',
                'variations' => [
                    ['value' => 'دیسک‌خور', 'price' => 54900000, 'qty' => 5],
                    ['value' => 'دیجیتال', 'price' => 47900000, 'qty' => 4],
                ],
                'filters' => [],
                'tags' => ['پرفروش', 'گیمینگ'],
                'rates' => ['sina.tehrani' => 5, 'ali.akbari' => 5, 'amir.karimi' => 4],
            ],
            [
                'code' => 'xbox-series-x', 'title' => 'کنسول بازی مایکروسافت مدل Xbox Series X', 'latin' => 'Xbox Series X', 'subtitle' => 'CONSOLE',
                'brand' => 'مایکروسافت', 'category' => 'کنسول بازی', 'platform' => 'ایکس‌باکس', 'delivery' => 85000, 'gallery' => 2, 'days_ago' => 72,
                'description' => '<p>ایکس‌باکس سری X قدرتمندترین کنسول تاریخ مایکروسافت است؛ با ۱۲ ترافلاپس قدرت پردازشی، SSD ۱ ترابایتی و پشتیبانی از Quick Resume، تجربه‌ای روان و سریع ارائه می‌دهد.</p><p>سرویس Game Pass با صدها بازی، ارزش خرید این کنسول را دوچندان می‌کند.</p><ul><li>پردازنده: ۸ هسته‌ای Zen 2</li><li>حافظه: 1TB SSD</li><li>خروجی: 4K@120Hz</li></ul>',
                'variation_attr' => 'نسخه',
                'variations' => [
                    ['value' => '۱ ترابایت', 'price' => 49500000, 'qty' => 4],
                ],
                'filters' => [],
                'tags' => ['گیمینگ'],
                'rates' => ['reza.ahmadi' => 5, 'hossein.mousavi' => 4],
            ],
            [
                'code' => 'switch-oled', 'title' => 'کنسول بازی نینتندو مدل Switch OLED', 'latin' => 'Nintendo Switch OLED', 'subtitle' => 'CONSOLE',
                'brand' => 'نینتندو', 'category' => 'کنسول بازی', 'platform' => 'نینتندو', 'delivery' => 85000, 'gallery' => 2, 'days_ago' => 88,
                'description' => '<p>نینتندو سوییچ OLED با نمایشگر ۷ اینچی OLED، رنگ‌های زنده و پایه پهن‌تر، بهترین نسخه سوییچ برای بازی در خانه و بیرون است. کتابخانه بازی‌های انحصاری نینتندو مثل Zelda و Mario، آن را برای خانواده‌ها و علاقه‌مندان به بازی‌های سرگرم‌کننده بی‌رقیب کرده است.</p><ul><li>نمایشگر: ۷ اینچ OLED</li><li>حافظه: ۶۴ گیگابایت</li><li>حالت‌ها: دستی، میزی و تلویزیون</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'نئون', 'price' => 28900000, 'qty' => 4],
                    ['value' => 'سفید', 'price' => 28900000, 'qty' => 3],
                ],
                'filters' => [],
                'tags' => ['گیمینگ', 'هدیه'],
                'rates' => ['sara.mohammadi' => 5, 'fatemeh.hosseini' => 5],
            ],
            [
                'code' => 'dualsense', 'title' => 'دسته بازی سونی مدل DualSense', 'latin' => 'Sony DualSense', 'subtitle' => 'CONTROLLER',
                'brand' => 'سونی', 'category' => 'دسته بازی', 'platform' => 'پلی‌استیشن', 'delivery' => 35000, 'gallery' => 2, 'days_ago' => 44,
                'description' => '<p>دسته دوال‌سنس با بازخورد لمسی دقیق، تریگرهای تطبیقی و میکروفون داخلی، عمیق‌ترین حس بازی را در پلی‌استیشن ۵ ارائه می‌دهد. طراحی ارگونومیک آن برای بازی‌های طولانی بسیار راحت است.</p><ul><li>بازخورد لمسی و تریگر تطبیقی</li><li>میکروفون و جک هدفون داخلی</li><li>اتصال: بی‌سیم و USB-C</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'سفید', 'price' => 6450000, 'qty' => 10],
                    ['value' => 'مشکی', 'price' => 6450000, 'qty' => 8],
                ],
                'filters' => ['نوع اتصال' => 'بی‌سیم'],
                'tags' => ['گیمینگ'],
                'rates' => ['ali.akbari' => 4, 'mohammad.ghasemi' => 5],
            ],
            [
                'code' => 'anker-powercore-20000', 'title' => 'پاوربانک انکر مدل PowerCore 20000 میلی‌آمپرساعت', 'latin' => 'Anker PowerCore', 'subtitle' => 'POWER BANK',
                'brand' => 'انکر', 'category' => 'پاوربانک و شارژر', 'platform' => null, 'delivery' => 35000, 'gallery' => 2, 'days_ago' => 36,
                'description' => '<p>پاوربانک انکر PowerCore با ظرفیت ۲۰۰۰۰ میلی‌آمپرساعت، گوشی شما را چندین بار شارژ می‌کند. فناوری PowerIQ و VoltageBoost، سریع‌ترین شارژ ممکن را برای هر دستگاهی فراهم می‌کند.</p><p>بدنه مقاوم و سیستم حفاظت چندلایه MultiProtect، امنیت کامل هنگام شارژ را تضمین می‌کند.</p><ul><li>ظرفیت: ۲۰۰۰۰ میلی‌آمپرساعت</li><li>دو خروجی USB + ورودی USB-C</li><li>فناوری PowerIQ</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'مشکی', 'price' => 3290000, 'qty' => 20],
                ],
                'filters' => ['سازگار با شارژ سریع' => 'دارد (۲۰ وات)'],
                'tags' => ['پرفروش'],
                'rates' => ['amir.karimi' => 5, 'elham.nouri' => 4],
            ],
            [
                'code' => 'anker-nano-ii', 'title' => 'شارژر دیواری انکر مدل Nano II 65 وات', 'latin' => 'Anker Nano II 65W', 'subtitle' => 'CHARGER',
                'brand' => 'انکر', 'category' => 'پاوربانک و شارژر', 'platform' => null, 'delivery' => 35000, 'gallery' => 2, 'days_ago' => 34,
                'description' => '<p>شارژر انکر Nano II با توان ۶۵ وات و فناوری GaN، در ابعادی بسیار کوچک‌تر از شارژرهای معمولی، لپ‌تاپ و گوشی شما را با تمام سرعت شارژ می‌کند. سه پورت (دو USB-C و یک USB-A) امکان شارژ هم‌زمان چند دستگاه را فراهم می‌کند.</p><ul><li>توان: ۶۵ وات</li><li>فناوری GaN (کوچک و خنک)</li><li>پورت‌ها: 2×USB-C + 1×USB-A</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'سفید', 'price' => 1890000, 'qty' => 30],
                ],
                'filters' => ['سازگار با شارژ سریع' => 'دارد (۶۵ وات)'],
                'tags' => ['جدید'],
                'rates' => ['sina.tehrani' => 5, 'fatemeh.hosseini' => 4],
            ],
            [
                'code' => 'logitech-g413', 'title' => 'کیبورد مکانیکال لاجیتک مدل G413 SE', 'latin' => 'Logitech G413 SE', 'subtitle' => 'KEYBOARD',
                'brand' => 'لاجیتک', 'category' => 'کیبورد و ماوس', 'platform' => 'ویندوز', 'delivery' => 35000, 'gallery' => 2, 'days_ago' => 46,
                'description' => '<p>کیبورد مکانیکال لاجیتک G413 SE با سوییچ‌های مکانیکال لمسی، بدنه آلومینیومی و نورپردازی سفید، ترکیبی از دوام و دقت برای گیمرها است. ضدسایه‌زنی کامل و کلیدهای رسپانسیو، تجربه تایپ و بازی دقیقی ارائه می‌دهد.</p><ul><li>سوییچ مکانیکال لمسی</li><li>بدنه آلومینیوم-منیزیم</li><li>نورپردازی سفید LED</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'مشکی', 'price' => 2790000, 'sale' => 2590000, 'qty' => 10],
                ],
                'filters' => ['نوع اتصال' => 'باسیم', 'نوع کاربری' => 'گیمینگ'],
                'tags' => ['گیمینگ', 'تخفیف‌دار'],
                'rates' => ['ali.akbari' => 5, 'reza.ahmadi' => 4],
            ],
            [
                'code' => 'asus-tuf-vg27aq', 'title' => 'مانیتور گیمینگ ایسوس مدل TUF VG27AQ سایز ۲۷ اینچ', 'latin' => 'ASUS TUF VG27AQ', 'subtitle' => 'MONITOR',
                'brand' => 'ایسوس', 'category' => 'مانیتور', 'platform' => 'ویندوز', 'delivery' => 85000, 'gallery' => 2, 'days_ago' => 68,
                'description' => '<p>مانیتور گیمینگ ایسوس TUF VG27AQ با پنل ۲۷ اینچی QHD، نرخ نوسازی ۱۶۵ هرتز و فناوری ELMB Sync، تصویری روان و بدون کشیدگی در بازی‌ها ارائه می‌دهد. پوشش ۱۰۰٪ sRGB، آن را برای کارهای گرافیکی هم مناسب می‌کند.</p><ul><li>پنل: ۲۷ اینچ QHD IPS</li><li>نرخ نوسازی: ۱۶۵ هرتز</li><li>فناوری‌ها: ELMB Sync, G-SYNC Compatible</li></ul>',
                'variation_attr' => 'سایز صفحه',
                'variations' => [
                    ['value' => '۲۷ اینچ', 'price' => 21900000, 'qty' => 5],
                ],
                'filters' => ['نوع کاربری' => 'گیمینگ'],
                'tags' => ['گیمینگ', 'پیشنهاد ویژه'],
                'rates' => ['hossein.mousavi' => 5, 'ali.akbari' => 5],
            ],
            [
                'code' => 'baseus-crystal-case', 'title' => 'قاب محافظ بیسوس مدل Crystal برای گوشی iPhone 15', 'latin' => 'Baseus Crystal Case', 'subtitle' => 'CASE',
                'brand' => 'بیسوس', 'category' => 'قاب و محافظ', 'platform' => 'iOS', 'delivery' => 35000, 'gallery' => 2, 'days_ago' => 16,
                'description' => '<p>قاب محافظ بیسوس مدل Crystal با طراحی شفاف و بدنه مقاوم، از گوشی iPhone 15 شما در برابر ضربه و خط‌وخش محافظت می‌کند. لبه‌های برجسته دور دوربین و صفحه‌نمایش، آسیب‌پذیری دستگاه را به حداقل می‌رسانند.</p><ul><li>سازگار با iPhone 15</li><li>جنس: پلی‌کربنات سخت + TPU نرم</li><li>پشتیبانی از شارژ بی‌سیم</li></ul>',
                'variation_attr' => 'رنگ',
                'variations' => [
                    ['value' => 'شفاف', 'price' => 890000, 'qty' => 40],
                    ['value' => 'مشکی مات', 'price' => 990000, 'qty' => 25],
                ],
                'filters' => [],
                'tags' => ['جدید'],
                'rates' => ['narges.rezaei' => 5, 'negar.kazemi' => 4],
            ],
        ];
    }
}
