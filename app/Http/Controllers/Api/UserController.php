<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * اطلاعات کاربر احراز هویت‌شده (روت پیش‌فرض API).
     */
    public function show(Request $request)
    {
        return $request->user();
    }
}
