<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Visitor ;
use Auth ;
use Illuminate\Support\Facades\Hash;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $visitors = Visitor::orderBy('created_at' , 'desc')->get();
        $visitors = collect($visitors)->map(function ($visit) {
            return [
               'id' => $visit->id ,
               'name'=>$visit->name ,
               'contact'=>$visit->phone ,
               'email'=>$visit->email,
               'created_at'=> $visit->created_at
           ];
       });
        return $visitors ;
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
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
        $valid =  [
            'name' => 'required|max:255|min:3',
            'email'=> 'required|email',
            'phone'=> 'required',
            'username'=> 'required|unique:visitors,username|max:255|min:3',
            'password'=> 'required',
        ];

        $request->validate($valid);

        $visitor = new Visitor();
        $visitor->name = $request->name;
        $visitor->phone = $request->phone;
        $visitor->username = $request->username;
        $visitor->email = $request->email;
        $visitor->password = Hash::make($request->password);
        $visitor->save();
        return $visitor;
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
        $visitor = Visitor::findorfail($id);
        return $visitor;
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
        $valid =  [
            'name' => 'required|max:255|min:3',
            'email'=> 'required|email',
            'phone'=> 'required',
            'username'=> 'required|unique:visitors,username,'.$id,
            'new_password'=> 'nullable',
        ];

        $request->validate($valid);

        $visitor =Visitor::findorfail($id);
        $visitor->name = $request->name;
        $visitor->phone = $request->phone;
        $visitor->username = $request->username;
        $visitor->email = $request->email;
        if(!empty($request->new_password)) {
            $visitor->password = Hash::make($request->new_password);
        }
        $visitor->save();
        return $visitor;
    
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
        $visitor = Visitor::findorfail($id);
        $visitor->delete();
        return $this->index();
    }
}
