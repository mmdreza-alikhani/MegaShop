<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;

class AdminCommentTest extends ShopTestCase
{
    private function admin(): User
    {
        $this->seed([
            \Database\Seeders\PermissionSeeder::class,
            \Database\Seeders\RoleSeeder::class,
            \Database\Seeders\AdminUserSeeder::class,
        ]);

        return User::where('username', 'admin')->firstOrFail();
    }

    private function comment(User $author, array $attributes = []): Comment
    {
        $product = $this->makeProduct();

        return Comment::create(array_merge([
            'user_id' => $author->id,
            'commentable_id' => $product->id,
            'commentable_type' => \App\Models\Product::class,
            'text' => 'نظر تستی',
            'status' => 1,
            'is_active' => 0,
        ], $attributes));
    }

    public function test_comments_index_loads(): void
    {
        $admin = $this->admin();
        $comment = $this->comment($this->makeUser());

        $this->actingAs($admin)
            ->get('/management/comments')
            ->assertOk()
            ->assertSee($comment->text);
    }

    public function test_admin_can_approve_and_reject_a_comment(): void
    {
        $admin = $this->admin();
        $comment = $this->comment($this->makeUser());

        $this->actingAs($admin)
            ->patch('/management/comments/'.$comment->id.'/approve')
            ->assertRedirect();

        $this->assertSame(3, (int) $comment->fresh()->getRawOriginal('status'));
        $this->assertSame(1, (int) $comment->fresh()->getRawOriginal('is_active'));

        $this->actingAs($admin)
            ->patch('/management/comments/'.$comment->id.'/reject')
            ->assertRedirect();

        $this->assertSame(2, (int) $comment->fresh()->getRawOriginal('status'));
        $this->assertSame(0, (int) $comment->fresh()->getRawOriginal('is_active'));
    }

    public function test_admin_can_delete_a_comment(): void
    {
        $admin = $this->admin();
        $comment = $this->comment($this->makeUser());

        $this->actingAs($admin)
            ->delete('/management/comments/'.$comment->id)
            ->assertRedirect();

        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
    }
}
