<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\User;

class AdminCategoryTest extends ShopTestCase
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

    public function test_root_categories_are_offered_as_parents(): void
    {
        $admin = $this->admin();
        $root = Category::create(['title' => 'دسته ریشه', 'parent_id' => 0, 'is_active' => 1]);

        $this->actingAs($admin)
            ->get('/management/categories/create')
            ->assertOk()
            ->assertSee($root->title);
    }

    public function test_child_category_can_be_created_and_edited(): void
    {
        $admin = $this->admin();
        $root = Category::create(['title' => 'دسته ریشه', 'parent_id' => 0, 'is_active' => 1]);
        $variation = Attribute::create(['title' => 'مدل']);

        $payload = [
            'title' => 'زیردسته جدید',
            'parent_id' => $root->id,
            'is_active' => 1,
            'variation_attribute_id' => $variation->id,
            'filter_attribute_ids' => [],
        ];

        $this->actingAs($admin)->post('/management/categories', $payload)->assertRedirect();

        $child = Category::where('title', 'زیردسته جدید')->firstOrFail();
        $this->assertSame($root->id, $child->parent_id);

        $this->actingAs($admin)->put('/management/categories/'.$child->id, [
            ...$payload,
            'title' => 'زیردسته ویرایش‌شده',
        ])->assertRedirect();

        $this->assertSame('زیردسته ویرایش‌شده', $child->fresh()->title);
    }

    public function test_category_cannot_become_its_own_parent(): void
    {
        $admin = $this->admin();
        $variation = Attribute::create(['title' => 'مدل']);
        $category = Category::create(['title' => 'دسته', 'parent_id' => 0, 'is_active' => 1]);

        $this->actingAs($admin)->put('/management/categories/'.$category->id, [
            'title' => $category->title,
            'parent_id' => $category->id,
            'is_active' => 1,
            'variation_attribute_id' => $variation->id,
            'filter_attribute_ids' => [],
        ])->assertRedirect();

        $this->assertSame(0, (int) $category->fresh()->parent_id);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $admin = $this->admin();
        $product = $this->makeProduct();

        $this->actingAs($admin)
            ->delete('/management/categories/'.$product->category_id)
            ->assertRedirect();

        $this->assertDatabaseHas('categories', ['id' => $product->category_id]);
    }

    public function test_empty_category_can_be_deleted(): void
    {
        $admin = $this->admin();
        $category = Category::create(['title' => 'دسته خالی', 'parent_id' => 0, 'is_active' => 1]);

        $this->actingAs($admin)
            ->delete('/management/categories/'.$category->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
