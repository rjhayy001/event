<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Visitor ;
use App\Http\Resources\VisitorResource;
use App\Http\Resources\Event as EventResource ;
use Illuminate\Support\Facades\Hash;
use Image;
use Auth ;

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
        $visitors = Visitor::all();
        return VisitorResource::collection($visitors);
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
        $visitor=Visitor::with('events')->findorfail($id);
        return $visitor;
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
        $validatedData = $request->validate([
            'name' => 'required|unique:visitors,name',
            'phone' => 'required',
            'email' => 'required|email|unique:visitors,email',
            'username' => 'required|unique:visitors,username',
            'password' => 'required|confirmed',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,gif,svg'
        ]);

        if($validatedData)
        {
        $visitor = new Visitor($request->id);
        $visitor->name = $request->input('name');
        $visitor->phone = $request->input('phone');
        $visitor->username = $request->input('username');

        if($request->file('profile_pic')){
            $file = $request->file('profile_pic');
            $extension = time(). '.' .$file->getClientOriginalExtension();
            $visitor->image = $extension;
            $file->move('image',$extension);
        }

        $visitor->email = $request->input('email');
        $visitor->password = Hash::make($request->input('password'));
        $visitor->save();
        $accessToken = $visitor->createToken('authtoken')->accessToken ;
            return response(['user' => new VisitorResource($visitor) , 'access_token' => $accessToken]);
        }
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
        $visitor = Visitor::find($id) ;
        $visitor->delete();
        return "deleted ! " ;
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:visitors,email',
            'username' => 'required|unique:visitors,username',
            'password' => 'required|confirmed',
            // 'profile_pic' => 'nullable|image|mimes:jpeg,png,gif,svg'
        ]);

        if($validatedData)
        {
        $visitor = new Visitor();
        $visitor->name = $request->input('name');
        $visitor->phone = $request->input('phone');
        $visitor->username = $request->input('username');

        if($request->profile_pic){
            $image = $request->profile_pic;  // your base64 encoded
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $data = base64_decode($image);
            $imageName = date("YmdHis"). '.' . 'jpeg';
            file_put_contents(public_path() . '/' . 'image/' . $imageName, $data);

            $visitor->image = $imageName ;
        }

        $visitor->email = $request->input('email');
        $visitor->password = Hash::make($request->input('password'));
        $visitor->save();
        $accessToken = $visitor->createToken('authtoken')->accessToken ;
        return response(['user' => new VisitorResource($visitor) , 'access_token' => $accessToken]);
        }
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        if($loginData){
             $visitor = Visitor::where('username',$request->username)->first();
            if ( ($visitor != null) && Hash::check($request->password, $visitor->password) ){
                $accessToken = $visitor->createToken('authToken')->accessToken;
                return response(['user' => new VisitorResource($visitor) , 'accessToken' => $accessToken ]);
            }
            return response(['message'=> 'invalid credentials']);
        }
    }

    public function change_password(Request $request){
        $validatedData = $request->validate([
            'id' => 'required',
            'current_password' => 'required',
            'new_password' => 'confirmed|required',
        ]);

        $visitor = Visitor::findorfail($request->id);
        
        if (Hash::check($request->current_password, $visitor->password)){
            $visitor->password = Hash::make($request->input('new_password'));

            $visitor->save();
            return response([ 'user' => new VisitorResource($visitor) ]);
        }
            return response(['message'=> 'password Incorrect']);
    }
    public function event_visitor(Request $request ,$id)
    {
        $visitor = Visitor::find($id);
        $eventid = $request->input('event_id');
        // $data[$visitor->id] = ['event_id'=>$eventid ];
        $visitor->events()->attach($eventid);
        return 'event saved';
    }
    public function remove_event_visitor(Request $request , $id)
    {
        $visitor = Visitor::find($id);
        $eventid = $request->input('event_id');
        $visitor->events()->detach($eventid);
        return 'event removed';
    }
    public function update_profile(Request $request){

        $validatedData = $request->validate([
            'id' => 'required',
            // 'profile_pic' => 'nullable|image|mimes:jpeg,png,gif,svg'
        ]);

        if($validatedData)
        {
            $visitor = Visitor::findorfail(Auth::user()->id);

            if($request->profile_pic){
                $image = $request->profile_pic;  // your base64 encoded
                list($type, $image) = explode(';', $image);
                list(, $image)      = explode(',', $image);
                $data = base64_decode($image);
                $imageName = date("YmdHis") . $request->id . '.' . 'jpeg';
                file_put_contents(public_path() . '/' . 'images/' . $imageName, $data);

                $visitor->image = $imageName ;
            }

            $visitor->save();

            return response(['user' => new VisitorResource($visitor)]);
        }
    }
    public function logout(Request $request)
    {
        $visitor = Visitor::find($request->id);

        Auth::$visitor->AauthAcessToken()->delete();
        return "logout successfully !";
    }

    public function favorites($id)
    {
        $visitor = Visitor::findorfail(Auth::user()->id);
        $visitor->events_fav()->attach($id);
        return response('added to favorites', 200);
    }

    public function get_favorites()
    {
        $favorites = Visitor::findorfail(Auth::user()->id);
        return EventResource::collection($favorites->events_fav);        
    }

    public function remove_favorites($id)
    {
        $visitor = Visitor::findorfail(Auth::user()->id);
        $visitor->events_fav()->detach($id);
        return response('removed to favorites', 200);
    }
}
