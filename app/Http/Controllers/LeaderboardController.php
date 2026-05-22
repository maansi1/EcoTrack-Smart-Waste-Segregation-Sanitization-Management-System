<?php
// app/Http/Controllers/LeaderboardController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        $topUsers  = User::users()->active()->orderByDesc('points')->limit(20)->get();
        $myRank    = Auth::user()->leaderboard_rank;
        $myPoints  = Auth::user()->points;
        return view('leaderboard.index', compact('topUsers', 'myRank', 'myPoints'));
    }
}
