<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $address = Address::where('user_id', Auth::user()->id)->get();
        return $address;
    }

    public function addAddress(Request $request)
    {
        $request->validate([
            'firstname' => 'required|max:191',
            'lastname' => 'required|max:191',
            'phone' => 'required|max:191',
            'address' => 'required|max:191',
            'city' => 'required|max:191',
            'zipcode' => 'required|max:191',
        ]);

        $address = new Address;
        $address->user_id = Auth::user()->id;
        $address->firstname = $request->firstname;
        $address->lastname = $request->lastname;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->city = $request->city;
        $address->zipcode = $request->zipcode;
        $address->save();

        return $address;
    }

    public function update(Request $request, $id)
    {
        $address = Address::findOrFail($id);
        $request->validate([
            'firstname' => 'required|max:191',
            'lastname' => 'required|max:191',
            'phone' => 'required|max:191',
            'address' => 'required|max:191',
            'city' => 'required|max:191',
            'zipcode' => 'required|max:191',
        ]);
        $address->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
        ]);
        $address->save();
    }

    public function destroy($id)
    {
        return Address::findOrFail($id)->delete();
    }
}
