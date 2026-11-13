<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;

class CommentReplyTest extends ShopTestCase
{
    private function seedDemo(): void
    {
        Artisan::call('db:seed', ['--force' => true]);
    }

    private function postWithComment(): array
    {
        $this->seedDemo();

        $post = Post::query()->where('is_active', 1)->firstOrFail();
        $author = $this->makeUser('reza.ahmadi');

        $comment = Comment::create([
            'user_id' => $author->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'reply_of' => 0,
            'text' => 'نظر تستی برای پاسخ',
            'status' => 1,
            'is_active' => 1,
        ]);

        return [$post, $comment];
    }

    public function test_guest_sees_reply_button_and_login_hint(): void
    {
        [$post, $comment] = $this->postWithComment();

        $response = $this->get('/posts/'.$post->slug);

        $response->assertOk();
        $response->assertSee('mk-reply-btn', false);
        $response->assertSee('data-id="'.$comment->id.'"', false);
        $response->assertSee('برای نظر دادن ابتدا وارد شوید!');
        // مهمان فرم پاسخ ندارد، پس فیلد مخفی و چیپ پاسخ هم نباید باشد
        $response->assertDontSee('id="replyOf"', false);
        $response->assertDontSee('id="replyToChip"', false);
    }

    public function test_logged_in_user_sees_reply_form_and_chip(): void
    {
        [$post, $comment] = $this->postWithComment();

        $response = $this->actingAs($this->makeUser())
            ->get('/posts/'.$post->slug);

        $response->assertOk();
        $response->assertSee('id="replyOf"', false);
        $response->assertSee('id="replyToChip"', false);
        $response->assertSee('data-user="reza.ahmadi"', false);
        $response->assertSee('data-id="'.$comment->id.'"', false);
    }

    public function test_product_reply_button_uses_brand_colour_not_theme_purple(): void
    {
        $this->seedDemo();

        $product = Product::query()->where('is_active', 1)->firstOrFail();
        $comment = Comment::create([
            'user_id' => $this->makeUser()->id,
            'commentable_id' => $product->id,
            'commentable_type' => Product::class,
            'reply_of' => 0,
            'text' => 'نظر تستی محصول',
            'status' => 1,
            'is_active' => 1,
        ]);

        $response = $this->get('/products/'.$product->slug);

        $response->assertOk();
        $response->assertSee('mk-reply-btn', false);
        $response->assertSee('data-id="'.$comment->id.'"', false);
        $response->assertDontSee('nk-btn-color-main-2 replyBtn', false);
    }
}
