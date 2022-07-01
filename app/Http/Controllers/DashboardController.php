<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $waitingApprovalUsers = User::select('id','first_name', 'last_name','email')
            ->where('role','Waiting Approval')->get();

        $waitingApprovalUsers = $waitingApprovalUsers->transform(fn ($user) => [
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'link' => "/user/" . $user->id . "/edit"
        ]);

        return Inertia::render('Dashboard',[
            'usersWaitingApproval' =>
                ((Auth::user()->role == 'Super') || (Auth::user()->role) == 'Admin')
                    ? $waitingApprovalUsers : [],
        ]);
    }
}
