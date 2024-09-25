<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\SchoolGroup;
use App\Models\Activity;
use Illuminate\Support\Facades\Hash;

class APIController extends Controller
{
    public function states($country_id)
    {
        $states = State::where('country_id', $country_id)->get();
        return $states;
    }

    public function user($id)
    {
        $user = User::where('id', $id)->with('state', 'country')->first();
        return $user;
    }

    public function users()
    {
        $user = User::where('usertype', 'client')->with('state', 'country')->get();
        return $user;
    }
}