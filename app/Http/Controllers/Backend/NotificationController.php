<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Visitor;
use App\Event;
use App\Notification ;

class NotificationController extends Controller
{
    //
    public function send_notification(Request $request){
        // return $request->notification['bodies'];
        $this->change_notify($request);
        $fcmtoken =[];
        $iostoken=[];
        if( $androidtoken = $request->notification['status'] == 1 ?  Visitor::where('fcmtoken','!=',null)->select('fcmtoken')->get() : Visitor::where('fcmtoken','!=',null)->whereHas('events' ,function($query) use($request){
            $query->where('event_id' , $request->id);
        })->select('fcmtoken')->get()){
            foreach ($androidtoken as $key => $value) 
            {
                $fcmtoken[] = $value->fcmtoken;
            }
            define('AAAAf9lPnKM:APA91bH035-_L3lCbPRbasyUKz7LqRUMe5KEevmgnn843nPY71O4pSnmvO4Y5UuoVLTuVEBPpSbSzF7Ds-tuYSCHLecqhgG1VG_KcNioyQZ7yLz2g95Ide7z3UBXRFIP0ASJYfPgv9dp', 'AAAAf9lPnKM:APA91bH035-_L3lCbPRbasyUKz7LqRUMe5KEevmgnn843nPY71O4pSnmvO4Y5UuoVLTuVEBPpSbSzF7Ds-tuYSCHLecqhgG1VG_KcNioyQZ7yLz2g95Ide7z3UBXRFIP0ASJYfPgv9dp' );
            $msg = array
            (
                'body'  => $request->notification['bodies'],
                'title' => $request->notification['title'],
                'image' => $request->image,
                'subtitle' => $request->id,
                'sound' => 'default',
                'badge' =>    $request->notification['status'] == 1 ? '1' : '2',
                'time' => date('H:i:s')
            );

            $fields = array
            (
                'registration_ids'  => $fcmtoken,
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
        if($appletoken =  $request->notification['status'] == 1 ? Visitor::where('iostoken','!=',null)->select('iostoken')->get() :
            Visitor::where('iostoken','!=',null)->whereHas('events' ,function($query) use($request){
                $query->where('event_id' , $request->id);
            })->select('iostoken')->get()){
            foreach ($appletoken as $key => $value) 
            {
                $iostoken[] = $value->iostoken;
            }
            $url = "https://fcm.googleapis.com/fcm/send";
            $serverKey = 'AAAAf9lPnKM:APA91bH035-_L3lCbPRbasyUKz7LqRUMe5KEevmgnn843nPY71O4pSnmvO4Y5UuoVLTuVEBPpSbSzF7Ds-tuYSCHLecqhgG1VG_KcNioyQZ7yLz2g95Ide7z3UBXRFIP0ASJYfPgv9dp';
            $title = "Event App";
            $body = $request->name;
            $image = $request->image;
            $notification = array('title' =>$title , 'text' => $body, 'sound' => 'default', 'badge' => $request->notification['status'] == 1 ? '1' : '2' , 'image'=> $image , 'subtitle' => $request->id);
            $arrayToSend = array('registration_ids' => $iostoken, 'notification' => $notification,'priority'=>'high');
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
        return $responseData;
    }

    public function change_notify($event) {
        $events = Event::findorfail($event->id);
        $events->notify = 1 ;
        if($events->save()){
            $notice = Notification::findOrNew($event->notification['id']);
            $notice->body = $event->notification['bodies'];
            $notice->title = $event->notification['title'];
            $notice->event_id = $event->id;
            $notice->save();
        }
    }
}
