<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\CoverLetterController;
use App\Http\Controllers\PhonePeController;
use App\Http\Controllers\SessionBookingController;
use App\Http\Controllers\MasterControlController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Master Control (Superadmin) Routes
Route::prefix('mastercontrol')->group(function () {
    Route::get('/login', [MasterControlController::class, 'showLogin'])->name('mastercontrol.login');
    Route::post('/login', [MasterControlController::class, 'login'])->name('mastercontrol.login.submit');
    
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [MasterControlController::class, 'dashboard'])->name('mastercontrol.dashboard');
        Route::get('/dashboard', [MasterControlController::class, 'dashboard']);
        Route::post('/logout', [MasterControlController::class, 'logout'])->name('mastercontrol.logout');

        // User Management
        Route::get('/users', [MasterControlController::class, 'users'])->name('mastercontrol.users');
        Route::get('/users/{user}', [MasterControlController::class, 'showUser'])->name('mastercontrol.users.show');
        Route::post('/users/{user}/toggle-status', [MasterControlController::class, 'toggleUserStatus'])->name('mastercontrol.users.toggle-status');
        Route::delete('/users/{user}', [MasterControlController::class, 'deleteUser'])->name('mastercontrol.users.delete');

        // Bookings
        Route::get('/bookings', [MasterControlController::class, 'bookings'])->name('mastercontrol.bookings');

        // Transactions
        Route::get('/transactions', [MasterControlController::class, 'transactions'])->name('mastercontrol.transactions');

        // REST API for Users
        Route::get('/api/users', [MasterControlController::class, 'apiUsers'])->name('mastercontrol.api.users');
    });
});

Route::get('/', function () {
    if (auth()->check()) {
        return view('dashboard');
    }
    return view('index');
})->name('home');

// PhonePe payment routes (kept public so unauthenticated users can pay and PhonePe can hit the callback)
// Route to show the payment button/form
Route::get('/pay', [PhonePeController::class, 'showPaymentForm'])->name('payment.form');

// Route to initiate the payment
Route::post('/pay', [PhonePeController::class, 'initiatePayment'])->name('payment.initiate');

// Route for the callback from PhonePe (must be accessible publicly)
Route::match(['get', 'post'], '/payment/callback', [PhonePeController::class, 'handleCallback'])->name('payment.callback');
Route::get('/resume', function () {
    return view('resume');
});
Route::get('/services', function () {
    return view('services');
});
Route::get('/coverletter', function () {
    return view('coverletter');
});
Route::get('/portfolio', function () {
    return view('portfolio');
});
Route::get('/career', function () {
    return view('career');
});
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});
Route::get('/refund-policy', function () {
    return view('refund-policy');
});
Route::get('/terms', function () {
    return view('terms');
});
Route::get('/faq', function () {
    return view('faq');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/contact', function () {
    return view('contact');
});

Route::get('/phpinfo', function () {
    phpinfo();
});


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/cover-letter-template', [CoverLetterController::class, 'show'])->name('cover-letter-template.show');
    Route::post('/cover-letter-template', [CoverLetterController::class, 'store'])->name('cover-letter-template.store');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile.show');
    Route::post('/profile/update', [CoverLetterController::class, 'updateProfile'])->name('profile.update');

    Route::get('/resume-build', [PricingController::class, 'show'])->name('resume-build');
    Route::get('/resume-template', [ResumeController::class, 'show'])->name('resume-template');
    Route::get('/portfolio-template', function () {
        return view('portfolio-template');
    })->name('portfolio-template');
    Route::get('/career-template', function () {
        return view('career-template');
    })->name('career-template');
    Route::post('/resume-template', [ResumeController::class, 'store'])->name('resume-template.store');
    Route::get('/resume/preview/{template}', [ResumeController::class, 'preview'])->name('resume.preview');
    Route::get('/resume/fullscreen-preview/{template}', [ResumeController::class, 'fullscreenPreview'])->name('resume.fullscreen.preview');
    // Route::post('/payment/initiate', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');
    // Route::get('/payment/status/{merchantOrderId}', [PaymentController::class, 'paymentStatus'])->name('payment.status');
    // Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
    // 1-on-1 Session Routes
    Route::get('/sessions', [SessionBookingController::class, 'index'])->name('session-booking.index');
    Route::get('/book-session/{sessionType}', [SessionBookingController::class, 'create'])->name('session-booking.create');
    Route::post('/book-session', [SessionBookingController::class, 'store'])->name('session-booking.store');
    Route::get('/book-session/payment/{booking}', [SessionBookingController::class, 'payment'])->name('session-booking.payment');

    // Placeholder Routes
    Route::get('/coming-soon', function () {
        return view('placeholders.coming-soon');
    })->name('coming-soon');
    Route::get('/contact-whatsapp', function () {
        return view('placeholders.whatsapp-redirect');
    })->name('whatsapp-redirect');

});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->user_type === 'admin') {
            return view('admin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
});
