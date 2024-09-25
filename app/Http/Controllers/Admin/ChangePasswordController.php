<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;

class ChangePasswordController extends Controller
{
    public function change_password(){
        return view('admin.change_password');
    }

    public function update_password(Request $request){
        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        #Match The Old Password
        if(!Hash::check($request->old_password, auth()->user()->password)){
            return back()->with("error", "Old Password Doesn't match!");
        }

        #Update the new Password
        User::where('id', auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        
        // Mail functionality
        // $data = auth()->user();
        // $email_data = EmailTemplate::find(4);
        // $email_content = str_replace('{{name}}', $data->name, $email_data->content);
        // $email_content = str_replace('{{email}}', $data->email, $email_content);

        // Mail::send('emails.template', compact('data', 'email_content'), function ($message) use ($data, $email_data) {
        //     $message->to($data->email, $data->name)
        //             ->subject($email_data->subject);
        // });

        return back()->with("status", "Password changed successfully!");
    }
}