<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Category;
use App\Models\Hobby;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with(['hobbies'])->select(['id', 'name', 'contact_number', 'category', 'profile_picture'])->get();
            // dd($data);
            return Datatables::of($data)
                ->addColumn('hobbies', function($row) {
                    // Check if hobbies relationship is loaded and not empty
                    if ($row->relationLoaded('hobbies')) {
                        // Access the hobbies relationship and pluck the names
                        return $row->hobbies->pluck('name')->implode(', ');
                    } else {
                        return 'No hobbies';
                    }
                })
                ->addColumn('select', function($row) {
                    return '<input type="checkbox" class="selectBox" data-id="' . $row->id . '">';
                })
                ->addColumn('actions', function($row) {
                    $editBtn = '<button class="btn btn-primary editBtn" data-id="' . $row->id . '">Edit</button>';
                    $deleteBtn = '<button class="btn btn-danger deleteBtn" data-id="' . $row->id . '">Delete</button>';
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['select', 'actions'])
                ->make(true);
        }

        $categories = Category::all();
        $hobbies = Hobby::all();

        return view('form', compact('categories', 'hobbies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'contact_number' => 'required',
            'hobbies' => 'required|array',
            'hobbies.*' => 'exists:hobbies,id', // Validate that each hobby exists in the hobbies table
            'category' => 'required',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $profilePicture = time().'.'.$request->profile_picture->extension();
        $request->profile_picture->move(public_path('images'), $profilePicture);

        $formData = new User;
        $formData->name = $request->name;
        $formData->contact_number = $request->contact_number;
        $formData->category = $request->category;
        $formData->profile_picture = $profilePicture;
        $formData->save();

        // Sync the hobbies with the user
        $formData->hobbies()->sync($request->hobbies);

        return response()->json(['success' => 'Form submitted successfully!']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'contact_number' => 'required',
            'hobbies' => 'required|array',
            'hobbies.*' => 'exists:hobbies,id', // Validate that each hobby exists in the hobbies table
            'category' => 'required',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $formData = User::findOrFail($id);

        if ($request->hasFile('profile_picture')) {
            $profilePicture = time().'.'.$request->profile_picture->extension();
            $request->profile_picture->move(public_path('images'), $profilePicture);
            $formData->profile_picture = $profilePicture;
        }

        $formData->update($data);

        // Sync the hobbies with the user
        $formData->hobbies()->sync($request->hobbies);

        return response()->json(['success' => 'Data updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['success' => 'Data deleted successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        User::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => 'Data deleted successfully.']);
    }

    public function getHobbies($id)
    {
        // Find the user by ID
        $user = User::find($id);

        // Check if user exists
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Get the user's hobbies
        $hobbies = $user->hobbies;

        // Return the hobbies as a JSON response
        return response()->json(['hobbies' => $hobbies]);
    }

    public function getAllHobbies(){
        $hobbies = Hobby::all();
        return response()->json(['hobbies' => $hobbies]);
    }
}

