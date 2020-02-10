<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company ;
use App\Contact ;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $company = Company::orderBy('created_at' , 'desc')->get();
        $company = collect($company)->map(function ($comp) {
            return [
                'id' => $comp->id ,
                'name'=>$comp->name ,
                'contact person'=> $comp->contacts['name'],
                'contact number'=> $comp->contacts['phone']  ,
                'company email'=> $comp->contacts['email'] ,
                'logo'=> $comp->logo ,
                'created_at' => $comp->created_at
            ];
        });
        return $company ;

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
            'name' => 'required|unique:companies,name|max:255|min:3',
            'logo' => 'required',
            ];
    
            $request->validate($valid);
            $company = new Company() ;
            $company->name = $request->name;
            $company->address1 = $request->address1;
            $company->address2 = $request->address2;
            $company->zip_code = $request->zip_code;
            $company->city = $request->city;
            $company->country = $request->country;
            $company->description = $request->description;
    
            if($request->logo){
                $image = $request->logo;  // your base64 encoded
                list($type, $image) = explode(';', $image);
                list(, $image)      = explode(',', $image);
                $data = base64_decode($image);
                $imageName = date("YmdHis"). $request->name . '.jpeg';
                file_put_contents(public_path() . '/' . 'company_logo/' . $imageName, $data);
                $company->logo = $imageName ;
            }
    
            $company->save();
            if($company->id){
                $this->format_contact($request->contacts ,$company);
            }
            return $company ;
    }

    public function format_contact($contacts, $company)
    {
            if(!empty($contacts['id'])){
                $contact = Contact::findorfail($contacts['id']);
            }
            else{
                $contact = new Contact();
            }
            $contact->name = $contacts['name'];
            $contact->phone = $contacts['phone'];
            $contact->email = $contacts['email'];;
            $contact->company_id = $company->id;
            $contact->save();
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
        $company = Company::with('contacts','events')->findorfail($id);
        return $company ;
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
            'name' => 'required|max:255|min:3|unique:companies,name,'.$id,
            'logo' => 'required',
            ];
            $request->validate($valid);
            $company = Company::findorfail($id) ;
            $company->name = $request->name;
            $company->address1 = $request->address1;
            $company->address2 = $request->address2;
            $company->zip_code = $request->zip_code;
            $company->city = $request->city;
            $company->country = $request->country;
            $company->description = $request->description;
    
            try {
                if($request->logo){
                    $image = $request->logo;  // your base64 encoded
                    list($type, $image) = explode(';', $image);
                    list(, $image)      = explode(',', $image);
                    $data = base64_decode($image);
                    $imageName = date("YmdHis"). $request->name . '.jpeg';
                    file_put_contents(public_path() . '/' . 'company_logo/' . $imageName, $data);
                    $company->logo = $imageName ;
                }
                else{
                    return 'wara makasulod' ;
                }
            }catch (\Throwable $th) {
                // return $th ;
            }
            
    
            $company->save();
            if($company->id){
                $this->format_contact($request->contacts ,$company);
            }
            return $company ;
            // return json_encode($this->is_base64($request->logo)) ;
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
        $company = Company::findorfail($id);
        $company->delete();
        return $this->index() ;
    }
}
