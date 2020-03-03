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
        $visitors = Visitor::orderBy('created_at' , 'desc')->get()->map->format();
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

    public function activate_controller(Request $request) {
        $visitor = Visitor::findorfail($request->id);
        $visitor->active = $request->active ;

        if($visitor->save() && $visitor->fcmtoken != null ) {
            $this->send_deactivate_notification_android($visitor);
        }
        if($visitor->save() && $visitor->iostoken != null ){
            $this->send_deactivate_notification_ios($visitor);
        }
        return $this->index() ;
    }

    public function send_deactivate_notification_android($visitor) {
        $tokens[] = $visitor->fcmtoken ;
        define('AAAAf9lPnKM:APA91bH035-_L3lCbPRbasyUKz7LqRUMe5KEevmgnn843nPY71O4pSnmvO4Y5UuoVLTuVEBPpSbSzF7Ds-tuYSCHLecqhgG1VG_KcNioyQZ7yLz2g95Ide7z3UBXRFIP0ASJYfPgv9dp', 'AAAAf9lPnKM:APA91bH035-_L3lCbPRbasyUKz7LqRUMe5KEevmgnn843nPY71O4pSnmvO4Y5UuoVLTuVEBPpSbSzF7Ds-tuYSCHLecqhgG1VG_KcNioyQZ7yLz2g95Ide7z3UBXRFIP0ASJYfPgv9dp' );
        $msg = array
        (
            'body'  => $visitor->active == 0 ? 'youre account has been deactivated !' : 'youre account has been activated !',
            'title' =>$visitor->active == 0 ? 'Deactivation Notice' : 'Activation Notice',
            'sound' => 'default',
            'badge' =>  '3' ,
            'time' => date('H:i:s')
        );

        $fields = array
        (
            'registration_ids'  => $tokens,
            'notification'  => $msg,
            'data' => $msg
        );

        $headers = array
        (
        'Authorization: key=' . 'AAAAf9lPnKM:APA91bH035-_L3lCbPRbasyUKz7LqRUMe5KEevmgnn843nPY71O4pSnmvO4Y5UuoVLTuVEBPpSbSzF7Ds-tuYSCHLecqhgG1VG_KcNioyQZ7yLz2g95Ide7z3UBXRFIP0ASJYfPgv9dp',
        'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        $result = json_decode($result,true);
        $responseData['android'] =[ "result" =>$result ];
        curl_close( $ch );
    }
    public function send_deactivate_notification_ios($visitor) {
        $tokens[] = $visitor->iostoken ;
        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = 'AAAAf9lPnKM:APA91bH035-_L3lCbPRbasyUKz7LqRUMe5KEevmgnn843nPY71O4pSnmvO4Y5UuoVLTuVEBPpSbSzF7Ds-tuYSCHLecqhgG1VG_KcNioyQZ7yLz2g95Ide7z3UBXRFIP0ASJYfPgv9dp';
        $title = $visitor->active == 0 ? 'Deactivation Notice' : 'Activation Notice';
        $body = $visitor->active == 0 ? 'youre account has been deactivated !' : 'youre account has been activated !';
        $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => '3' );
        $arrayToSend = array('registration_ids' => $tokens, 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Send the request
        $result = curl_exec($ch);
        if ($result === FALSE) 
        {
                die('FCM Send Error: ' . curl_error($ch));
        }
        $result = json_decode($result,true);
        $responseData['ios'] =[
            "result" =>$result
        ];
        curl_close($ch);
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
        $visitor = Visitor::with('events')->findorfail($id);
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
