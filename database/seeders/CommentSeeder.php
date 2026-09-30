<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\Support\SeederLookup;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    use SeederLookup;

    /**
     * نظرات نمونه برای محصولات و مقاله‌ها (همراه با پاسخ‌ها).
     *
     * وضعیت‌ها بر اساس Comment::statusCondition → 1: در دست بررسی، 2: رد شده، 3: منتشر شده.
     * این سیدر قابل اجرای مجدد است و فقط نظراتِ ساخته‌نشده را اضافه می‌کند.
     */
    public function run(): void
    {
        $created = 0;
        $created += $this->seedProductComments();
        $created += $this->seedPostComments();

        $this->command->info("✅ نظرات آماده شدند ({$created} مورد جدید ثبت شد).");
    }

    private function seedProductComments(): int
    {
        $published = [
            'iPhone 15 Pro Max' => [
                ['user' => 'sara.mohammadi', 'text' => 'کیفیت ساخت واقعاً عالیه؛ سبک‌تر از نسخه قبلیه و دوربینش در نور کم فوق‌العاده عمل می‌کنه. از خریدم کاملاً راضی‌ام.', 'replies' => [
                    ['user' => 'admin', 'text' => 'ممنون از همراهی شما؛ خوشحالیم که راضی هستید 🌹'],
                ]],
                ['user' => 'reza.ahmadi', 'text' => 'باتریش یک روز کامل راحت جواب می‌ده. پورت USB-C هم بالاخره اضافه شد که خیلی کمک‌کننده‌ست.'],
                ['user' => 'narges.rezaei', 'text' => 'ارسال سریع بود و بسته‌بندی خیلی مرتب. گوشی کاملاً پلمپ و اصله.'],
            ],
            'Galaxy S24 Ultra' => [
                ['user' => 'mohammad.ghasemi', 'text' => 'دوربین ۲۰۰ مگاپیکسلی‌اش فوق‌العاده‌ست، زوم ۱۰ برابری واقعاً کیفیت خوبی داره. قلم S Pen هم برای یادداشت‌برداری عالیه.', 'replies' => [
                    ['user' => 'admin', 'text' => 'خیلی هم عالی! پیشنهاد می‌کنیم قاب اورجینال سامسونگ رو هم برای محافظت از این پرچم‌دار تهیه کنید.'],
                ]],
                ['user' => 'negar.kazemi', 'text' => 'هوش مصنوعی Galaxy AI در ترجمه و ویرایش عکس خیلی کاربردی‌ه. ارزش خرید بالایی داره.'],
            ],
            'Redmi Note 13 Pro' => [
                ['user' => 'amir.karimi', 'text' => 'در این رده قیمتی بی‌رقیبه؛ نمایشگرش شفاف و شارژ ۶۷ واتش عالیه.'],
                ['user' => 'sina.tehrani', 'text' => 'دوربین ۲۰۰ مگاپیکسلی واقعاً فراتر از انتظارم بود. برای قیمتش حرف نداره.'],
            ],
            'MacBook Air' => [
                ['user' => 'sara.mohammadi', 'text' => 'برای دانشجویی مثل من عالیه؛ سبک، بی‌صدا و باتریش تا آخر روز می‌کشه.', 'replies' => [
                    ['user' => 'admin', 'text' => 'ممنون از بازخوردتون! کیف لپ‌تاپ‌های مگاشاپ هم دقیقاً سایز همین مدله 😊'],
                ]],
            ],
            'AirPods Pro' => [
                ['user' => 'amir.karimi', 'text' => 'حذف نویزش از نسل قبل بهتر شده و صداش بسیار شفافه. با آیفون جفت‌شدنش هم خیلی ساده بود.'],
                ['user' => 'mohammad.ghasemi', 'text' => 'برای ورزش سبک مناسبه و تو گوش محکم می‌مونه. کیفیت مکالمه هم خوبه.'],
            ],
            'PlayStation 5' => [
                ['user' => 'sina.tehrani', 'text' => 'بارگذاری بازی‌ها با SSD چقدر سریع شده! دسته دوال‌سنس هم تجربه رو کاملاً عوض می‌کنه.'],
                ['user' => 'ali.akbari', 'text' => 'بسته‌بندی عالی و کاملاً اورجینال. بازی God of War رو با ۱۲۰ فریم بازی کردم، بی‌نظیر بود.'],
            ],
            'Smart Band 8' => [
                ['user' => 'fatemeh.hosseini', 'text' => 'برای قیمتش عالیه؛ باتریش واقعاً دو هفته می‌کشه و پایش خوابش دقیقه.'],
            ],
            'Flip 6' => [
                ['user' => 'hossein.mousavi', 'text' => 'صداش برای مهمونی و سفر کافیه. ضدآب بودنش هم خیال آدم رو راحت می‌کنه.'],
            ],
            'Watch Series 9' => [
                ['user' => 'maryam.sharifi', 'text' => 'مانیتورش همیشه روشنه که خیلی کاربردیه. سیستم سلامتی‌اش هم دقیق و قابل اعتماده.', 'replies' => [
                    ['user' => 'admin', 'text' => 'ممنون از حس خوبی که به مگاشاپ منتقل کردید ❤️'],
                ]],
            ],
            'DualSense' => [
                ['user' => 'ali.akbari', 'text' => 'بازخورد لمسی این دسته رو باید تجربه کنید تا بفهمید؛ تریگرهای تطبیقی هم فوق‌العاده‌اند.'],
            ],
        ];

        // یک نظر در انتظار بررسی و یک نظر رد شده (برای نمایش در پنل مدیریت)
        $awaiting = [
            'POCO X6 Pro' => 'سلام، این گوشی از بازار ایران گارانتی معتبر داره؟ برای بازی گنشین ایمپکت مناسب هست؟ ممنون.',
        ];
        $rejected = [
            'Nova 12i' => 'فروش ویژه با تخفیف باورنکردنی!!! برای اطلاعات بیشتر با شماره ۰۹xxxxxxxxx تماس بگیرید.',
        ];

        $created = 0;

        foreach ($published as $fragment => $comments) {
            $product = $this->findProductByFragment($fragment);
            if (! $product) {
                continue;
            }

            foreach ($comments as $item) {
                $user = User::where('username', $item['user'])->first();
                if (! $user) {
                    continue;
                }

                $parent = $this->firstOrCreateComment($user->id, $product, $item['text'], 0);
                $created += $parent->wasRecentlyCreated ? 1 : 0;

                foreach ($item['replies'] ?? [] as $reply) {
                    $replyUser = User::where('username', $reply['user'])->first();
                    if (! $replyUser) {
                        continue;
                    }

                    $replyComment = $this->firstOrCreateComment($replyUser->id, $product, $reply['text'], $parent->id);
                    $created += $replyComment->wasRecentlyCreated ? 1 : 0;
                }
            }
        }

        foreach ($awaiting as $fragment => $text) {
            $product = $this->findProductByFragment($fragment);
            $user = User::where('username', 'negar.kazemi')->first();
            if ($product && $user) {
                $comment = $this->firstOrCreateComment($user->id, $product, $text, 0, 1, false);
                $created += $comment->wasRecentlyCreated ? 1 : 0;
            }
        }

        foreach ($rejected as $fragment => $text) {
            $product = $this->findProductByFragment($fragment);
            $user = User::where('username', 'admin')->first();
            if ($product && $user) {
                $comment = $this->firstOrCreateComment($user->id, $product, $text, 0, 2, false);
                $created += $comment->wasRecentlyCreated ? 1 : 0;
            }
        }

        return $created;
    }

    private function seedPostComments(): int
    {
        $postComments = [
            'عمر باتری آیفون' => [
                ['user' => 'ali.akbari', 'text' => 'ترفند محدود کردن شارژ به ۸۰ درصد رو از وقتی انجام دادم، سلامت باتری آیفونم بعد از یک سال هنوز ۹۶ درصده!'],
                ['user' => 'negar.kazemi', 'text' => 'مقاله خیلی کاربردی بود، مخصوصاً بخش مربوط به دما. ممنون از تیم مگاشاپ.'],
            ],
            'مقایسه گلکسی' => [
                ['user' => 'hossein.mousavi', 'text' => 'من هر دو رو داشتم؛ کاملاً با نتیجه‌گیری مقاله موافقم. برای اکوسیستم اپل، آیفون و برای دوربین حرفه‌ای، سامسونگ.', 'replies' => [
                    ['user' => 'admin', 'text' => 'ممنون از اشتراک تجربه‌تون 🙏'],
                ]],
                ['user' => 'maryam.sharifi', 'text' => 'بخش مربوط به پشتیبانی نرم‌افزاری خیلی مهم بود؛ خیلی‌ها این نکته رو نادیده می‌گیرن.'],
            ],
            'راهنمای کامل خرید' => [
                ['user' => 'reza.ahmadi', 'text' => 'با بودجه ۲۰ میلیونی، طبق راهنمای شما Redmi Note 13 Pro خریدم و راضی‌ام. ممنون!'],
            ],
        ];

        $created = 0;

        foreach ($postComments as $fragment => $comments) {
            $post = $this->findPostByFragment($fragment);
            if (! $post) {
                continue;
            }

            foreach ($comments as $item) {
                $user = User::where('username', $item['user'])->first();
                if (! $user) {
                    continue;
                }

                $parent = $this->firstOrCreateComment($user->id, $post, $item['text'], 0);
                $created += $parent->wasRecentlyCreated ? 1 : 0;

                foreach ($item['replies'] ?? [] as $reply) {
                    $replyUser = User::where('username', $reply['user'])->first();
                    if (! $replyUser) {
                        continue;
                    }

                    $replyComment = $this->firstOrCreateComment($replyUser->id, $post, $reply['text'], $parent->id);
                    $created += $replyComment->wasRecentlyCreated ? 1 : 0;
                }
            }
        }

        return $created;
    }

    private function firstOrCreateComment(int $userId, Product|Post $target, string $text, int $replyOf, int $status = 3, bool $isActive = true): Comment
    {
        $existing = Comment::withTrashed()
            ->where('user_id', $userId)
            ->where('commentable_id', $target->id)
            ->where('commentable_type', get_class($target))
            ->where('reply_of', $replyOf)
            ->where('text', $text)
            ->first();

        if ($existing) {
            return $existing;
        }

        return Comment::create([
            'user_id' => $userId,
            'commentable_id' => $target->id,
            'commentable_type' => get_class($target),
            'reply_of' => $replyOf,
            'text' => $text,
            'status' => $status,
            'is_active' => $isActive,
        ]);
    }
}
