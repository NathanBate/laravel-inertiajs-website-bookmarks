<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * @param mixed $user
     * @return bool
     */
    public static function userIsApproved(mixed $user): bool
    {
        $statuses = ['Subscriber', 'Editor', 'Admin'];
        return Self::statusTest($user, $statuses);
    }


    /**
     * @param mixed $user
     * @return bool
     */
    public static function userIsUnapproved(mixed $user): bool
    {
        $statuses = ['Disabled','Denied Approval','Waiting Approval'];
        return Self::statusTest($user, $statuses);
    }

    public static function userIsDeniedorDisabled(mixed $user): bool
    {
        $statuses = ['Disabled','Denied Approval'];
        return Self::statusTest($user, $statuses);
    }

    public static function userIsWaitingApproval(mixed $user): bool
    {
        $statuses = ['Waiting Approval'];
        return Self::statusTest($user, $statuses);
    }

    public static function userIsDeniedApproval(mixed $user): bool {
        $statuses = ['Denied Approval'];
        return Self::statusTest($user, $statuses);
    }

    public static function userIsSuper(mixed $user): bool
    {
        $statuses = ['Super'];
        return Self::statusTest($user, $statuses);
    }

    public static function userIsAdminOnly(mixed $user): bool
    {
        $statuses = ['Admin'];
        return Self::statusTest($user, $statuses);
    }

    public static function userIsAdminOrSuper(mixed $user): bool
    {
        $statuses = ['Admin','Super'];
        return Self::statusTest($user, $statuses);
    }

    private static function statusTest(mixed $user, array $statuses): bool
    {
        if (is_string($user)) {
            if (in_array($user, $statuses)) return true;
        }
        if (is_int($user)) {
            $user = User::where('id', $user)->firstOrFail();
            if (in_array($user->role, $statuses)) return true;
        }
        if ($user instanceof User) {
            if (in_array($user->role, $statuses)) return true;
        }
        return false;
    }
}
