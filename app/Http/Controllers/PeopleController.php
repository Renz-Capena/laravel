<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\People;
use DataTables;

class PeopleController extends Controller
{
    // create
    public function create(Request $request){
        
        People::create([
            'email' => $request->email,
            'password' => $request->password
        ]);

        return "success";
    }

    // Read
    public function read(){

        $data = People::all();

        return DataTables::of($data)
        ->addColumn('action',function($row){
            return "<button class='btn btn-primary btn-sm' data-id=".$row->id." id='editbtn'>edit</button><button class='btn btn-danger btn-sm ms-2'data-id=".$row->id." id='deleteBtn'>delete</button>";
        })
        ->make(true);
    }

    // delete
    public function delete(Request $request){
        
        $id = $request->id;

        People::find($id)->delete();
        
    }

    // edit data
    public function updateData(Request $request){
        
        $id = $request->id;

        $data = People::find($id)->first();

        return $data;
    
    }

    // update
    public function update(Request $request){


        $id = $request->id;
        $email = $request->emailEdit;
        $pass = $request->passwordEdit;

        People::find($id)->update([
            'email' => $email,
            'password' => $pass
        ]);

        // return $request->all();

    }   
}
