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
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
use Illuminate\Support\Facades\Password;
use App\Notifications\ProfileApproved;
use App\Mail\ProfileEmailUpdate;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class UsersController extends Controller
{

    public function index(): \Inertia\Response
    {
        $users = User::where('role', '!=', 'Super')->orderBy('created_at','desc')->get();

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

        if ($user->role == 'Waiting Approval') {
            $user->update(['role' => Request::get('role')]);
            $user->notify(new ProfileApproved());
        }

        return Redirect::back()->with('success', 'User updated.');
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


}
