<?php
// app/Http/Controllers/BinController.php

namespace App\Http\Controllers;

use App\Models\Bin;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class BinController extends Controller
{
    public function index()
    {
        $bins     = Bin::orderBy('fill_level', 'desc')->get();
        $fullBins = $bins->whereIn('status', ['full', 'overflow']);
        return view('bins.index', compact('bins', 'fullBins'));
    }

    public function update(Request $request, Bin $bin)
    {
        $validated = $request->validate([
            'fill_level' => 'required|integer|min:0|max:100',
        ]);

        $bin->fill_level = $validated['fill_level'];
        $bin->recalcStatus();

        // Alert admins if newly full
        if (in_array($bin->status, ['full', 'overflow']) && ! $bin->alert_sent) {
            $bin->alert_sent = true;
            $bin->save();

            foreach (User::admins()->active()->get() as $admin) {
                Notification::send(
                    $admin->id,
                    'Bin overflow alert',
                    "{$bin->name} ({$bin->area}) is {$bin->fill_level}% full — immediate collection needed.",
                    'bin'
                );
            }
        } elseif ($bin->status === 'low') {
            $bin->alert_sent = false;
            $bin->save();
        }

        return back()->with('success', 'Bin level updated.');
    }

    public function markCollected(Bin $bin)
    {
        $bin->fill_level = 0;
        $bin->status     = 'low';
        $bin->alert_sent = false;
        $bin->last_collected_at = now();
        $bin->save();

        return back()->with('success', "Bin '{$bin->name}' marked as collected.");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                 => 'required|string|max:100',
            'area'                 => 'required|string|max:100',
            'location_description' => 'nullable|string',
            'bin_type'             => 'required|in:general,dry,wet,plastic,ewaste,hazardous',
            'latitude'             => 'nullable|numeric',
            'longitude'            => 'nullable|numeric',
        ]);
        Bin::create($validated);
        return back()->with('success', 'Bin added successfully.');
    }
}
