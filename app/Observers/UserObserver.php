<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * در نسخه دمو سرویس ایمیل واقعی وجود ندارد؛ حساب‌های تازه بلافاصله تایید می‌شوند
     * تا کاربر بعد از ثبت‌نام بتواند مسیر تسویه را کامل کند.
     */
    public function creating(User $user): void
    {
        if ($user->email_verified_at === null) {
            $user->email_verified_at = now();
        }
    }
}
