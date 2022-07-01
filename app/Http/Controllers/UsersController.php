<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
use Illuminate\Support\Facades\Password;
use App\Notifications\ProfileApproved;
use App\Mail\ProfileEmailUpdate;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use phpDocumentor\Reflection\Types\Boolean;


class UsersController extends Controller
{

    public function index(): \Inertia\Response
    {
        $users = User::where('role', '!=', 'Super')
            ->where('id', '!=', Auth::id())
            ->orderBy('created_at','desc')->get();

        return Inertia::render('Users', [
            'user_list' => $users
                ->except("password")
                ->transform(fn ($user) => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'link' => "/user/" . $user->id . "/edit"
                ])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('UserCreate');
    }

    public function store(Request $request): RedirectResponse
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'role' => ['required']
        ]);

        $user = User::create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => bcrypt(Str::random(20)),
            'role' => Request::get('role'),
        ]);

        $status = Password::sendResetLink(
            ['email' => Request::get('email')]
        );

        if ($status == Password::RESET_LINK_SENT) {
            return Redirect::route('users.list')->with('success', 'User created successfully.');
        }

        return Redirect::route('users.list')->with('error', 'User
            created, but there was a problem sending the password reset email.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Inertia\Response
     */
    public function edit(User $user): \Inertia\Response
    {
        return Inertia::render('UserEdit', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function update(User $user): RedirectResponse
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required']
        ]);
        $user->update(Request::only(['first_name', 'last_name', 'email']));

        if (User::userIsUnapproved($user->role)) {
            $user->update(['role' => Request::get('role')]);

            /**
             * Only send the user an email if they have been approved
             */
            if (User::userIsApproved(Request::get('role'))) {
                $user->notify(new ProfileApproved());
            }

            /**
             * Check to see if there are more users to approve. If there are, then
             * route to the next user to be approved.
             */
            $waitingApprovalUsers = User::where('role', 'Waiting Approval')->get();
            if ($waitingApprovalUsers->count() > 0) {
                return redirect('user/' . $waitingApprovalUsers[0]->id . '/edit')
                        ->with('success','User Approved. Here is the next one.');
            } else {
                return redirect()->route('users.list')->with('success', "All pending users have been processed.");
            }
        } else {
            $user->update(['role' => Request::get('role')]);
        }

        return Redirect::back()->with('success', 'User updated.');
    }


    public function profileEdit(User $user)
    {
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
    }


    public function profileVerifyEmail(User $user, $hash)
    {
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
    }


    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function profileUpdate(User $user): RedirectResponse
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
        ]);
        $user->update(Request::only(['first_name', 'last_name']));

        if ($user->email != Request::get('email')) {
            $user->email_to_verify = Request::get('email');
            $user->save();
            Mail::to(Request::get('email'))->send(new ProfileEmailUpdate($user->getKey(), Request::get('email')));
            return Redirect::back()->with('success', 'Profile updated. Check your email for a verification for the new address you entered.');
        }

        return Redirect::back()->with('success', 'Profile updated.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return Redirect::route('users.list')->with('success', 'User deleted.');
    }


    public function profileChangePassword(User $user): RedirectResponse
    {
        Request::validate([
            'currentPassword' => ['required','current_password'],
            'newPassword' => [
                'required',
                'confirmed',
                PasswordRules::min(8)->letters()->mixedCase()->numbers()
            ]
        ]);
        $user->password = Hash::make(Request::get('newPassword'));
        $user->save();
        return Redirect::back()->with('success', 'Password updated.');
    }

    public function sendPasswordResetEmail(User $user): RedirectResponse
    {
        $status = Password::sendResetLink(
            ['email' => $user->email]
        );
        if ($status == Password::RESET_LINK_SENT) {
            return Redirect::back()->with('success', 'Password reset email sent successfully.');
        }
        return Redirect::back()->with('error', 'There was a problem sending the password reset email.');
    }



}
