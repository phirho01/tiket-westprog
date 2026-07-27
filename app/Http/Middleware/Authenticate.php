<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            session()->flash('gagal', 'Silakan masuk (login) terlebih dahulu untuk melihat informasi wisata lebih lanjut, penilaian ulasan, dan memesan tiket.');
            return route('masuk');
        }
        return null;
    }
}
