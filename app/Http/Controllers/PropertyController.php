<?php
// app/Http/Controllers/PropertyController.php

namespace App\Http\Controllers;

use App\Imports\PropertiesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Middleware\AdminAuthenticate;
use App\Models\Properties;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    public function index()
    {
        $query = Properties::orderBy('id', 'desc');

        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('Code', 'like', "%{$search}%")
                  ->orWhere('Property', 'like', "%{$search}%")
                  ->orWhere('City', 'like', "%{$search}%");
            });
        }

        $properties = $query->paginate(15);
        return view('property.index', compact('properties'));
    }

    public function create()
    {
        return view('property.create');
    }

    public function store()
    {
        $data = request()->validate([
            'Code'     => 'required|unique:properties,Code',
            'Property' => 'required',
            'Address'  => 'required',
            'City'     => 'required',
            'Zip'      => 'required',
            'State'    => 'required',
            'units'    => 'required|integer',
        ]);

        Properties::create($data);

        return redirect()->route('properties.index')->with('success', 'Property created successfully.');
    }

    public function edit($id)
    {
        $property = Properties::findOrFail($id);
        return view('property.edit', compact('property'));
    }

    public function update($id)
    {
        $property = Properties::findOrFail($id);
        $data = request()->validate([
            'Code'     => 'required|unique:properties,Code,' . $id,
            'Property' => 'required',
            'Address'  => 'required',
            'City'     => 'required',
            'Zip'      => 'required',
            'State'    => 'required',
            'units'    => 'required|integer',
        ]);

        $property->update($data);

        return redirect()->route('properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy($id)
    {
        $property = Properties::findOrFail($id);
        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Property deleted successfully.');
    }
    
    public function importPropertiesForm()
    {
        return view('property.import');
    }

    public function importProperties()
    {
        request()->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = request()->file('file');

        ini_set('memory_limit', '512M');
        set_time_limit(300);

        Excel::import(new PropertiesImport, $file);

        return redirect()->route('properties.index')->with('success', 'Properties imported successfully.');
    }
}