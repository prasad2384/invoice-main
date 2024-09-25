<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Setting::paginate(5);
        return view('admin.settings.index',compact('data'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data=Setting::find($id);
        return view('admin.settings.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the existing setting record by its ID
        $setting = Setting::find($id);

        // Check if the setting exists
        if (!$setting) {
            return redirect()->back()->with('error', 'Settings not found.');
        }

        // Validate incoming request
       

        // Handle logo upload if provided
        if ($request->hasFile('update_logo')) {
            // Delete the old logo if exists
            if ($setting->update_logo && file_exists(public_path('images/' . $setting->update_logo))) {
                unlink(public_path('images/' . $setting->update_logo));
            }

            // Store new logo and get the filename
            $logo = $request->file('update_logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('images'), $logoName);

            // Update the setting's logo field
            $setting->update_logo = $logoName;
        }

        // Update the rest of the settings fields
        $setting->company_name = $request->input('company_name');
        $setting->trade_name = $request->input('trade_name');
        $setting->gst_number = $request->input('gst_number');
        $setting->lut = $request->input('lut');
        $setting->company_address = $request->input('company_address');

        // Save updated settings
        $setting->save();

        // Redirect back with success message
        return redirect()->route('settings.index')->with(['message' => 'Settings Updated Successfully']);

        // return redirect()->back()->with('success', 'Settings updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
