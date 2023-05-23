<?php

namespace App\Http\Controllers;


use App\Models\Nurses;
use Illuminate\Http\Request;

class NurseController extends Controller
{

    
    public function index()
    {
        echo "Nurses\n";
        // Retrieve all doctors
        $nurses = Nurses::all();

        return view('nurses', ['nurses' => $nurses]);
    }

    public function createNurse(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'department' => 'required',
        ]);

        $nurse = Nurses::create($request->only('name', 'email' , 'department'));
        
        $nurses = Nurses::orderBy('id', 'asc')->get();
        return redirect('/nurses')->with('success', 'Nurse created successfully.');
    }

    public function edit(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'department' => 'required',
        ]);

        $nurse = Nurses::findOrFail($id);
        $nurse->edit($request->only('name', 'email' , 'department'));

        $nurses = Nurses::orderBy('id', 'asc')->get();
        return redirect('/nurses')->with('success', 'Nurse updated successfully.');
    }

    public function destroy(int $id)
    {
        // Delete a specific nurse
        $nurse = Nurses::findOrFail($id);
        $nurse->delete();

        $nurses = Nurses::orderBy('id', 'asc')->get();

        return redirect('/nurses')->with('success', 'NUrses deleted successfully.');
    }
}
