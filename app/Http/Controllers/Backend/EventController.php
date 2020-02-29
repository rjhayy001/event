<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Event ;
use App\Program;
use App\Visitor;
use App\Presentation;
use App\Company ;
use Image ;

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
        $events = Event::orderBy('created_at' , 'desc')->get()->map->format();
        return $events ;
    }
    public function upcoming()
    {
        //
        $events = Event::orderBy('fromdate')->get()->map->format();
        return $events ;
    }

    public function search($value= '') {
        $events = Event::FindEvent($value)->orderBy('name')->get()->map->format();
        $companies = Company::FindCompany($value)->orderBy('name')->get()->map->format();
        $visitors = Visitor::FindVisitor($value)->orderBy('name')->get()->map->format();
        $data = array_merge($events->toArray(), $companies->toArray(),$visitors->toArray());
        return $data ;
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
        
    //    return $request ;
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
        if($request['fullmap']) {
            $image = $request['fullmap'];  // your base64 encoded
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $data = base64_decode($image);
            $imageName = date("YmdHis"). '_fullmap' . '.jpeg';
            \Image::make($image)->crop(1230, 900, 25, 25)->save(public_path('/event_images/') . $imageName);
            $event->fullmap = $imageName ;
        }
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
                'x' => $value['x'] , 'y' => $value['y'] , 'description' => $value['description'] , 'highlight' =>  $value['highlight'] == true ? 1 : 0  ];
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
        $finalevent = [
            'id' => $event->id,
            'name' => $event->name ,
            'place' => $event->place ,
            'dates' => [$event->fromdate , $event->todate],
            'description' =>$event->description,
            'from' => $event->fromdate,
            'to' => $event->todate ,
            'logo' => $event->logo,
            'image' => $event->image,
            'presentation_image' => $event->presentation_image,
            'programme_image' => $event->programme_image,
            'tarifs_image' => $event->tarifs_image,
            'plan_image' => $event->plan_image,
            'restaurant_image' => $event->restaurant_image,
            'highlights_image' => $event->highlights_image,
            'map' => $event->map,
            'fullmap' => $event->fullmap,
            'notify' => $event->notify , 
            'prices' => collect($event->categories)->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->person ,
                    'price' => $category->pivot->price
                ];
            }),
            'programs' => collect($event->programs)->map(function ($program) {
                return [
                    'id' => $program->id,
                    'name' => $program->name ,
                    'details' => $program->details,
                    'time' => $program->time,
                    'highlight' => $program->highlight == 1 ? true : false,
                    'model' => false
                ];
            }),
            'companies' => collect($event->companies)->map(function ($comp) {
                return [
                    'id' => $comp->id,
                    'name' => $comp->name ,
                    'paidprice' => $comp->pivot->paidprice,
                    'x' => $comp->pivot->x,
                    'y' => $comp->pivot->y,
                    'description' => $comp->pivot->description,
                    'highlight' => $comp->pivot->highlight,
                    'is_restaurant' => $comp->pivot->is_restaurant,
                    'model' => false,
                    'logo' => $comp->logo,
                ];
            }),
            'presentation' => [
                'id' => $event->presentation->id,
                'image' => $event->presentation->image,
                'details' => $event->presentation->details,
            ],
            'notification' => [
                'id' => $event->notification['id'] ? $event->notification['id'] : null ,
                'title' => $event->notification['title'] ? $event->notification['title'] : $event->name, 
                'status' => 1 ,
                'bodies' => $event->notification['body'] ? $event->notification['body'] : 'this is notification' ,
            ]
        ];
        $program_highlight  = $event->programs()->where('highlight' , '1')->get();
        $company_highlight  = $event->companies()->where('highlight' , '1')->get();
        return compact('finalevent','program_highlight','company_highlight') ;
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
        // return $request ;
        $valid =  [
            'name' => 'required|max:255|min:3|unique:events,name,'.$id,
        ];
        $request->validate($valid);
        $from = null; 
        $to = null;
        if($request->dates) {
            $from = $request->dates[0] < $request->dates[1] ? $request->dates[0] : $request->dates[1];
            $to = $request->dates[0] < $request->dates[1] ? $request->dates[1] : $request->dates[0];
        }
        $event = Event::findorfail($id);
        $event->name = $request->name ;
        $event->place = $request->place ;
        $event->fromdate = $from ;
        $event->todate = $to ;
        $event->description = $request->description ;
        if($request->presentation_image != $event->presentation_image){
            $event->presentation_image = upload_image($request->presentation_image , 'event_images/' , 'presentation_image') ;
        }
        if($request->highlights_image != $event->highlights_image){
            $event->highlights_image =  upload_image($request->highlights_image , 'event_images/' , 'highlights_image') ;
        }
        if($request->restaurant_image != $event->restaurant_image){
            $event->restaurant_image =  upload_image($request->restaurant_image , 'event_images/' , 'restaurant_image') ;
        }
        if($request->programme_image != $event->programme_image){
            $event->programme_image =  upload_image($request->programme_image , 'event_images/' , 'programme_image') ;
        }
        if($request->tarifs_image != $event->tarifs_image){
            $event->tarifs_image =  upload_image($request->tarifs_image , 'event_images/' , 'tarifs_image') ;
        }
        if($request->plan_image != $event->plan_image){
            $event->plan_image =  upload_image($request->plan_image , 'event_images/' , 'plan_image') ;
        }
        if($request->image != $event->image){
            $event->image =  upload_image($request->image , 'event_images/' , 'image') ;
        }
        if($request->logo != $event->logo){
            $event->logo =  upload_image($request->logo , 'event_images/' , 'logo') ;
        }
        if($request->map != $event->map){
            $event->map = upload_image($request->map , 'event_images/' , 'map') ;
        }
        if($request['fullmap']) {
            try {
                $image = $request['fullmap'];  // your base64 encoded
                list($type, $image) = explode(';', $image);
                list(, $image)      = explode(',', $image);
                $data = base64_decode($image);
                $imageName = date("YmdHis"). '_fullmap' . '.jpeg';
                \Image::make($image)->crop(1230, 900, 25, 25)->save(public_path('/event_images/') . $imageName);
                $event->fullmap = $imageName ;
            } catch (\Throwable $th) {
                // return $th ;
            }
        }
        $event->save();
        if($event->id) {
            $this->format_pricing($request->prices , $event);
            $this->format_Program($request->programs , $event);
            $this->format_presentation($request->presentation , $event);
            $this->format_company($request->companies , $event);

        }

        return $event ;
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
