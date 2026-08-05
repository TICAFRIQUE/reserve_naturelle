<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    public function index(){
        $zones = Zone::orderBy('nom')->paginate(15);
        return view('admin.zones.index', compact('zones'));
    }

    public function create(){
        return view('admin.zones.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'tarif' => 'required|numeric|min:0',
            'est_expedition' => 'required|boolean',
        ]);

        DB::transaction(function () use ($validated) {
            if ($validated['est_expedition']) {
                Zone::where('est_expedition', true)->update(['est_expedition' => false]);
            }
            Zone::create($validated);
        });

        return redirect()->route('admin.zones.index')->with('success', 'Zone créée avec succès.');
    }

    public function edit(Zone $zone){
        return view('admin.zones.edit', compact('zone'));
    }

    public function update(Request $request, Zone $zone){
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'tarif' => 'required|numeric|min:0',
            'est_expedition' => 'required|boolean',
        ]);

        DB::transaction(function () use ($validated, $zone) {
            if ($validated['est_expedition']) {
                Zone::where('est_expedition', true)->where('id', '!=', $zone->id)->update(['est_expedition' => false]);
            }
            $zone->update($validated);
        });

        return redirect()->route('admin.zones.index')->with('success', 'Zone mise à jour avec succès.');
    }

    public function destroy(Zone $zone){
        $zone->delete();
        return back()->with('success', 'Zone supprimée avec succès.');
    }
}