<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

use Inertia\Inertia;
use App\Http\Controllers\UsersController;


/**
 * Guest Middleware
 */
Route::middleware('guest')->group(function () {

    /**
     * Login
     */
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);


    /**
     * Register
     */
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);


    /**
     * Forgot Password
     */
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');


    /**
     * Reset Password
     */
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');


    /**
     * Verify Email address for existing user
     */
    Route::get('verify-profile-email/{user}/{hash}', function (User $user, $hash) {

        //dd($hash . "\n" . sha1($user->email_to_verify) );

        if (hash_equals($hash,
            sha1($user->email_to_verify))) {
            $user->email = $user->email_to_verify;
            $user->email_verified_at = now();
            $user->email_to_verify = null;
            $user->save();
            return Inertia::render('UserProfileEmailVerified', [
                    'message' => "Thanks for signing up and verifying your email! You may close this tab."
                ]);
        } else {
            return Inertia::render('UserProfileEmailVerified', [
                'message' => "There was a problem and your email was not verified. Your original email is still in affect."
            ]);
        }

    })->middleware(['signed','throttle:6,1'])->name('verification.profile.email.verify');

});


/**
 * Auth Middleware
 */
Route::middleware('auth')->group(function () {

    /**
     * Verify Email address for new user
     */
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1']) // 6 attempts allowed and only a minute of time
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1') // 6 attempts allowed and only a minute of time
        ->name('verification.send');

    /**
     *  Confirm Password
     */
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);


    /**
     * Waiting Approval
     */
    Route::get('waiting-approval', function () {
        return Inertia::render('Auth/WaitingApproval');
    })->name("waiting.approval");

    Route::match(['post','get'],'logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

});


/**
 * Auth + Verified Email + Profile Approved Middleware
 */
Route::middleware(['auth','verified','profile.approved'])->group(function () {

    /**
     * Dashboard
     */
    Route::get('/', function () {
        return Inertia::render('Dashboard',[
            'waitingApproval' =>
                ((Auth::user()->role == 'Super') || (Auth::user()->role) == 'Admin')
                ? User::where('role','Waiting Approval')->get() : [],
        ]);
    })->name("dashboard");

});


/**
 * Auth + Verified Email + Profile Approved + Only Current User Profile Middleware
 */
Route::middleware(['auth','verified','profile.approved','edit.user.profile.only'])->group(function () {

    Route::get('profile/{user}/edit', function (User $user) {
        return Inertia::render('UserProfile', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
                'profile' => true,
            ],
        ]);
    })
        ->name('profile.edit');

    Route::post('profile/{user}/update', [UsersController::class, 'profileUpdate'])
        ->name('profile.update');

    Route::post('profile/{user}/change-password', [UsersController::class, 'profileChangePassword'])
        ->name('profile.change.password');

});


/**
 * Auth + Email Verified + Profile Approved + Admin Role Middleware
 */
Route::middleware(['auth','verified','profile.approved','admin.role'])->group(function () {

    /**
     * Users
     */
    Route::get('users', [UsersController::class, 'index'])
        ->name('users.list');

    Route::get('users/create', [UsersController::class, 'create'])
        ->name('users.create');

    Route::post('users', [UsersController::class, 'store'])
        ->name('users.store');

    Route::get('user/{user}/edit', [UsersController::class, 'edit'])
        ->name('user.edit');

    Route::post('user/{user}/update', [UsersController::class, 'update'])
        ->name('user.update');

    Route::delete('users/{user}', [UsersController::class, 'destroy'])
        ->name('users.destroy');

});
