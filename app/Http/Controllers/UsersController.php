<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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

class UsersController extends Controller
{

    public function index()
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('UserCreate');
    }

    public function store(Request $request)
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
        ]);

        $user = User::create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => bcrypt(Str::random(20)),
            'role' => 'Admin',
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function edit(User $user)
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
