<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Penanganan Otomatis Jika Sesi / Token CSRF Kedaluwarsa:
        // Langsung arahkan pengguna ke Landing Page (Beranda) secara mulus tanpa menampilkan layar error 419
        $this->renderable(function (TokenMismatchException $e, $request) {
            return redirect()->route('beranda')->with('sukses', 'Sesi Anda telah diperbarui. Silakan melanjutkan penjelajahan.');
        });
    }
}
