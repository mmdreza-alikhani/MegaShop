<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileAvatarTest extends ShopTestCase
{
    private function fakeImage(string $name = 'avatar.png', int $width = 400, int $height = 400): UploadedFile
    {
        return UploadedFile::fake()->image($name, $width, $height);
    }

    public function test_user_can_upload_a_cropped_avatar(): void
    {
        Storage::fake('public');
        $user = $this->makeUser();

        $response = $this->actingAs($user)->postJson(route('home.profile.avatar'), [
            'avatar' => $this->fakeImage(),
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $user->refresh();

        $this->assertNotSame('avatar.png', $user->avatar);
        Storage::disk('public')->assertExists(config('upload.user_avatar_path') . '/' . $user->avatar);
    }

    public function test_avatar_larger_than_the_global_limit_is_rejected(): void
    {
        Storage::fake('public');
        $user = $this->makeUser();

        $limitKb = (int) config('upload.max_file_kb');
        $file = UploadedFile::fake()->image('big.jpg', 1200, 1200)->size($limitKb + 120);

        $this->actingAs($user)
            ->postJson(route('home.profile.avatar'), ['avatar' => $file])
            ->assertStatus(422)
            ->assertJsonValidationErrors('avatar');
    }

    public function test_avatar_rejects_non_image_files(): void
    {
        Storage::fake('public');
        $user = $this->makeUser();

        $file = UploadedFile::fake()->create('document.pdf', 120, 'application/pdf');

        $this->actingAs($user)
            ->postJson(route('home.profile.avatar'), ['avatar' => $file])
            ->assertStatus(422)
            ->assertJsonValidationErrors('avatar');
    }

    public function test_guest_cannot_upload_an_avatar(): void
    {
        $response = $this->postJson(route('home.profile.avatar'), [
            'avatar' => $this->fakeImage(),
        ]);

        $response->assertStatus(401);
        $this->assertGuest();
    }

    public function test_upload_limit_is_five_hundred_kilobytes(): void
    {
        $this->assertSame(500, (int) config('upload.max_file_kb'));
    }
}
