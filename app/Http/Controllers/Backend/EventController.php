<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Event ;
use App\Program;
use App\Presentation;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $events = Event::orderBy('created_at' , 'desc')->get();
        $events = collect($events)->map(function ($event) {
            return [
                'id' => $event->id ,
                'name'=>$event->name   ,
                'from'=> $event->fromdate ?  date('d F Y', strtotime($event->fromdate)) : 'no given date' ,
                'to'=> $event->todate ? date('d F Y', strtotime($event->todate)) : 'no given date',
                'start' => $event->fromdate,
                'end' => $event->todate,
                'company_count'=> $event->companies->count() ,
                'visitor_count' => $event->visitors->count()
            ];
        });
        return $events ;
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
            'name' => 'required|unique:events,name|max:255|min:3',
        ];
        $request->validate($valid);
        $from = null; 
        $to = null;
        if($request->dates) {
            $from = $request->dates[0] < $request->dates[1] ? $request->dates[0] : $request->dates[1];
            $to = $request->dates[0] < $request->dates[1] ? $request->dates[1] : $request->dates[0];
        }
        $event = new Event();
        $event->name = $request->name ;
        $event->place = $request->place ;
        $event->fromdate = $from ;
        $event->todate = $to ;
        $event->presentation_image = upload_image($request->presentation_image , 'event_images/' , 'presentation_image') ;
        $event->highlights_image =  upload_image($request->highlights_image , 'event_images/' , 'highlights_image') ;
        $event->restaurant_image =  upload_image($request->restaurant_image , 'event_images/' , 'restaurant_image') ;
        $event->programme_image =  upload_image($request->programme_image , 'event_images/' , 'programme_image') ;
        $event->tarifs_image =  upload_image($request->tarifs_image , 'event_images/' , 'tarifs_image') ;
        $event->plan_image =  upload_image($request->plan_image , 'event_images/' , 'plan_image') ;
        $event->image =  upload_image($request->image , 'event_images/' , 'image') ;
        $event->logo =  upload_image($request->logo , 'event_images/' , 'logo') ;
        $event->map =  upload_image($request->map , 'event_images/' , 'map') ;
        $event->description = $request->description ;
        $event->save() ;
        if($event->id) {
            $this->format_Program($request->programs , $event);
            $this->format_presentation($request->presentation , $event);
            $this->format_pricing($request->prices , $event);
            $this->format_company($request->companies , $event);
            
        }
        return $event ;
    }
    public function format_pricing($prices , $event)
    {

        $data = [];
        if(count($prices))
        {
            foreach ($prices as $key => $value) {
                if($value['price'] != '') {
                    $data[$value['id']] = ['price' => $value['price']];
                }
            }
        }

        $event->categories()->sync($data);
    }

    public function format_company($companies ,$event) {
        $data = [];
        if(count($companies)) {
            foreach ($companies as $key => $value) {
                $data[$value['id']] = ['paidprice' => $value['paidprice'] , 'is_restaurant' => $value['is_restaurant'] == true ? 1 : 0 ,
                'lat' => $value['lat'] , 'lng' => $value['lang'] , 'description' => $value['description'] , 'highlight' =>  $value['highlight'] == true ? 1 : 0  ];
            }
        }
        $event->companies()->sync($data);
    }

    public function format_Program($programs, $event)
    {
        $data = [];

        foreach ($programs as $key => $value) {
            if(!empty($value['name'])) {
                if(empty($value['id'])){
                    $program = new Program();
                }
                else{
                    $program = Program::find($value['id']);
                }
                $program->highlight =$value['highlight'] == true ? 1 : 0 ;
                $program->name = $value['name'];
                $program->time = $value['time'];
                $program->details = $value['details'];
                $program->event_id = $event->id;
                $program->save();
            }
        }
        return $data;
    }

    public function format_presentation($presentations , $event){
        if(empty($presentations['id'])){
            $presentation = new Presentation();
        }
        else{
            $presentation = Presentation::findorfail($presentations['id']);
        }
        $presentation->details = $presentations['details'];
        $presentation->event_id = $event->id;
        try {
            $presentation->image =  upload_image($presentations['image'], 'event_images/' , 'present_image');
        } catch (\Throwable $th) {
            //throw $th;
        }
        $presentation->save();
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
        $event = Event::with('companies','categories','programs','presentation')->findorfail($id);
        $program_highlight  = $event->programs()->where('highlight' , '1')->get();
        $company_highlight  = $event->companies()->where('highlight' , '1')->get();
        return compact('event','program_highlight','company_highlight') ;
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
        $event = Event::findorfail($id);
        $event->delete();
        return $this->index();
    }
}
