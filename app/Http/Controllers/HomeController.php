<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function edit_profile()
    {
        $data = User::where('id', auth()->user()->id)->first();
        $countries = Country::all();
        return view('user.profile', compact('data', 'countries'));
    }

    public function update_profile(Request $request)
    {
        $data = User::where('id', auth()->user()->id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'city' => $request->city,
            'address' => $request->address,
        ]);
        return redirect()->back()->with('message', 'Profile updated successfully');
    }
}
