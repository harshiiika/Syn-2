<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index() {
        // Get the logged-in admin's profile
        $admin = User::first();
        return view('profile.profile', compact('admin'));
    }

    public function update(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10',
            'alternate' => 'nullable|digits:10',
            'email' => 'required|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get the admin profile (modify based on your auth)
            $admin = User::first();
            
            if (!$admin) {
                $admin = new User();
            }

            // Update basic fields
            $admin->name = $request->name;
            $admin->mobile = $request->mobile;
            $admin->alternate_mobile = $request->alternate;
            $admin->email = $request->email;

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($admin->profile_image && Storage::exists('public/profiles/' . $admin->profile_image)) {
                    Storage::delete('public/profiles/' . $admin->profile_image);
                }

                // Store new image
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/profiles', $imageName);
                $admin->profile_image = $imageName;
            }

            $admin->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'data' => [
                    'name' => $admin->name,
                    'mobile' => $admin->mobile,
                    'alternate' => $admin->alternate_mobile,
                    'email' => $admin->email,
                    'image_url' => $admin->profile_image ? asset('storage/profiles/' . $admin->profile_image) : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }
}