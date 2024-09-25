<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\EmailTemplate;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    // protected $redirectTo = RouteServiceProvider::HOME;

    public function redirectTo() {
        $usertype = auth()->user()->usertype; 
        switch ($usertype) {
          case 'admin':
            return RouteServiceProvider::HOMEADMIN;
            break;
          default:
            return '/'; 
          break;
        }
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'country_id' => $data['country_id'],
            // 'city' => $data['city'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'usertype' => 'user',
            'status' => 1,
        ]);
         // Send a simple registration email
        
        // $email_data = EmailTemplate::find(1);
        // $email_content = str_replace('{{name}}', $data['name'], $email_data->content);
        // $email_content = str_replace('{{email}}', $data['email'], $email_content);

        // Mail::send('emails.template', compact('data', 'email_content'), function ($message) use ($data, $email_data) {
        //     $message->to($data['email'], $data['name'])
        //             ->subject($email_data->subject);
        // });

        return $user;
    }
}
