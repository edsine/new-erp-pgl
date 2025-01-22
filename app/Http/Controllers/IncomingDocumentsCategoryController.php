<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentsCategoryRequest;
use App\Http\Requests\UpdateDocumentsCategoryRequest;
use App\Models\IncomingDocumentsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Shared\Models\Department;
use Illuminate\Support\Facades\Auth;

class IncomingDocumentsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    
    public function index()
    {
        if (Auth()->user()->hasRole('super-admin') || (Auth::user()->level && Auth::user()->level->id == 20) || (Auth::user()->level && Auth::user()->level->id == 18)) {
        $incoming_documents_categories = IncomingDocumentsCategory::orderBy('id' ,'desc')->get();
    } else {
        return redirect()->back()->with('error', 'Permission denied for file indexing access');
    }

        return view('incoming_documents_categories.index', compact('incoming_documents_categories'));
    }

    public function generateFileNo(Request $request) {
        $last_no = IncomingDocumentsCategory::where('department_id', $request->input('department_id'))->latest()->first();
    
        if ($last_no && is_numeric($last_no['name'])) {
            do {
                $fileNumber = $last_no['name'] + 1;
                $file_exists = IncomingDocumentsCategory::where('name', $fileNumber)->exists();
            } while ($file_exists);
        } else {
            $fileNumber = '10001';
        }
    
        return response()->json(['data' => ['name' => $fileNumber]]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth()->user()->hasRole('super-admin')) {
            $departments = Department::get();
        } else {
            $departments = Department::where('id', Auth()->user()->staff->department_id)->get();
        }
        
         return view('incoming_documents_categories.create', compact('departments'));
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentsCategoryRequest $request)
{
    try {
        //if (Auth()->user()->hasRole('super-admin') || Auth()->user()->hasRole('MANAGING DIRECTOR') || Auth()->user()->hasRole('SECRETARY')) {
            $validated = $request->validated();
             /* if (Auth()->user()->hasRole('super-admin')) {
             $validated['department_id'] = $request->input('department_id');
             } else {
             $validated['department_id'] = Auth()->user()->staff->department_id ?? 0;
             } */
             $incoming_documents_category = IncomingDocumentsCategory::create($validated);
             return redirect()->route('incoming_documents_category.index')->with('success', 'File added successfully!');
        //} else {
            //$departments = Department::where('id', Auth()->user()->staff->department->id)->get();
          //  return redirect()->back()->with('error', 'Permission denied for document audit trail access');
        //}
        
    } catch (\Throwable $e) {
        // Log the error or handle it as needed
        return redirect()->back()->withErrors(['error' => 'Failed to add File. Please try again.']);
    }
}



    /**
     * Display the specified resource.
     */
    public function show(IncomingDocumentsCategory $incoming_documents_category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomingDocumentsCategory $incoming_documents_category)
    {
        if (Auth()->user()->hasRole('super-admin')) {
            $departments = Department::get();
        } else {
            $departments = Department::where('id', Auth()->user()->staff->department_id)->get();
        }
        return view('incoming_documents_categories.edit', compact(['incoming_documents_category', 'departments']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentsCategoryRequest $request, IncomingDocumentsCategory $incoming_documents_category)
    {
        $validated = $request->validated();
        /* if (Auth()->user()->hasRole('super-admin')) {
            $validated['department_id'] = $request->input('department_id');
            } else {
            $validated['department_id'] = Auth()->user()->staff->department_id ?? 0;
            } */
        $incoming_documents_category->update($validated);
        return redirect()->route('incoming_documents_category.index')->with('success', 'File udpated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomingDocumentsCategory $incoming_documents_category)
    {
        if ($incoming_documents_category->delete())
            return redirect()->back()->with('success', 'File deleted successfully!');
        return redirect()->back()->with('error', 'File could not be deleted!');
    }

    public function ajaxDestroy($id)
{
    // Logic to delete the document category with the given ID
    $documentCategory = IncomingDocumentsCategory::findOrFail($id);
    $documentCategory->delete();
    return response()->json(['success' => true]);
}

    
}
