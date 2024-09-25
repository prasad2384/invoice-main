<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users=User::where('usertype','=','client')->paginate(10);
        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $states=State::all();
        $countries = Country::all();
        return view('admin.users.create',compact('states','countries'));
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
        $validation = Validator::make($request->all(),[
            'email' => 'required|email|unique:users,email',
        ]);
        if($validation->fails()){
            return redirect()->back()->withErrors($validation)->withInput();
        }
        $firstname =$request->get('firstname');
        $lastname =$request->get('lastname');
        $email =$request->get('email');
        $phone =$request->get('phone');
        $address =$request->get('address');
        $postal_code =$request->get('postal_code');
        $password =$request->get('password');
        $country =$request->get('country');
        $state =$request->get('state');

        $user=User::create([
            'name'=> $firstname . ' ' . $lastname,
            'email'=>$email,
            'phone'=>$phone,
            'address'=>$address,
            'postal_code'=>$postal_code,
            'password'=>Hash::make($password),
            'country_id'=>$country,
            'state_id'=>$state,
            'usertype'=>'client',
        ]);
        return redirect()->route('users.index')->with(['message'=> 'User Created Successfully']);
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
        $data=User::find($id);
        $nameParts = explode(' ', $data->name, 2);
        $firstname = $nameParts[0];
        $lastname = isset($nameParts[1]) ? $nameParts[1] : '';
        $countries = Country::all();

        return view('admin.users.edit',compact('data','countries','firstname','lastname'));

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
        //
        $validation=Validator::make($request->all(),[
            'email'=>[
                'required',
                'string',
                'max:255',
                'email',
                Rule::unique('users')->ignore($id)
            ]
            ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }
        $user = User::find($id);
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $address = $request->get('address');
        $postal_code = $request->get('postal_code');
        $country = $request->get('country');
        $state = $request->get('state');
        $data=[
            'name' => $firstname . ' ' . $lastname,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'postal_code' => $postal_code,
            'country_id' => $country,
            'state_id' => $state,
        ];
        $user->update($data);
        return redirect()->route('users.index')->with(['message' => 'User Updated Successfully']);

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
        $data=User::find($id);
        $data->delete();
        return redirect()->route('users.index')->with(['message'=>'User Deleted Successfully ']);
    }
}
