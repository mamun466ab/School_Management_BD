<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;
session_start();

class SuperAdminController extends Controller
{
    public function __construct(){
        $superAdminId = Session::get('superAdminId');
    }


    public function index()
    {
        $superAdminId = Session::get('superAdminId');
        if($superAdminId != Null){
            return Redirect::to('/super-dashboard/')->send();
        }
        return view('admin.login');
    }

    public function super_dashboard()
    {
        $superAdminId = Session::get('superAdminId');
        if($superAdminId == Null){
            return Redirect::to('/super/')->send();
        }
        $superAdmin = DB::table('super_admin')->get();
        
        $index_content = view('admin.index_page_content');
        return view('admin.index')->with('page_content', $index_content);
    }

    public function superAdminLogin(Request $request)
    {
        $username = $request->username;
        $password = md5($request->password);

        $superAdminQuery = DB::table('super_admin')
                        ->where('username', $username)
                        ->where('password', $password)
                        ->first();
        if($superAdminQuery){
            Session::put('SuperAdminName', $superAdminQuery->name);
            Session::put('superAdminId', $superAdminQuery->id);

            return Redirect::to('/super-dashboard/');
        }else{
            Session::put('exception', 'User email and password not match!!');
            return Redirect::to('/super/');
        }
    }


    public function location()
    {
        $index_content = view('admin.location');
        return view('admin.index')->with('page_content', $index_content);
    }
    
    public function division($id){
        $country_id = $id;
        $division = DB::table('division')->where('country_id', $country_id)->get();
        echo '<option value="">Select Country</option>';
        foreach ($division as $dvn):
            echo '<option value="' . $dvn->id . '">' . $dvn->division_name . '</option>';
        endforeach;
    }
    
    public function district($id){
        $divesion_id = $id;
        $district = DB::table('district')->where('division_id', $divesion_id)->get();
        echo '<option value="">Select District</option>';
        foreach ($district as $dist):
            echo '<option value="' . $dist->id . '">'.$dist->district_name . '</option>';
        endforeach;
    }

    public function country_create(Request $request)
    {
        $country = $request->country;


        $create_country = DB::table('country')->insert([
                    'country_name' => $country
                ]);
        if($create_country){
            Session::put('message', 'Country added successfully');
            return Redirect::to('/location');
        }else{
            Session::put('message', 'Country not added!');
        }
    }

    public function division_create(Request $request){
        $division_name = $request->division_name;
        $country_id = $request->country_id;


        $create = DB::table('division')->insert([
                    'division_name' => $division_name,
                    'country_id' => $country_id,
                ]);
        if($create){
            Session::put('message', 'Division added successfully');
            return Redirect::to('/location');
        }else{
            Session::put('message', 'Division not added!');
        }
    }

    public function district_create(Request $request){
        $district_name = $request->district_name;
        $division_id = $request->division_id;


        $create = DB::table('district')->insert([
                    'district_name' => $district_name,
                    'division_id' => $division_id,
                ]);
        if($create){
            Session::put('message', 'District added successfully');
            return Redirect::to('/location');
        }else{
            Session::put('message', 'District not added!');
        }
    }

    public function selectAjax(Request $request){
        if($request->ajax()){
            $states = DB::table('district')->where('division_id',$request->division_id)->all();
            $data = view('ajax-select',compact('states'))->render();
            return response()->json(['options'=>$data]);
        }
    }

    public function thana_create(Request $request){
         
        $validator = Validator::make($request->all(), [
            'thana_name' => 'unique:thana,thana_name',
        ],[            
            'thana_name.unique' => 'This name already exists.',
        ]);
        
        if($validator->passes()){
            return response()->json(['success' => '!!! This name successfully added. !!!']);
        }else{            
            return response()->json(['errors' => $validator->errors()]);
        }

            $thana_name = $request->thana_name;
            $district_id = $request->district_id;



            $create = DB::table('thana')->insert([
                        'thana_name' => $thana_name,
                        'district_id' => $district_id,
                    ]);
            if($create){
                Session::put('message', 'Thana added successfully');
                return Redirect::to('/location');
            }else{
                Session::put('message', 'Thana not added!');
            }
    }


    public function class_routine()
    {
        $days = DB::table('days')
                ->orderby('id', 'asc')
                ->get();
        $index_content = view('admin.class_routine')
        ->with('Days', $days);

        return view('admin.index')
        ->with('page_content', $index_content);
    }


    public function logoutSuper()
    {
        Session::put('SuperAdminName', null);
        Session::put('superAdminId', null);
        Session::put('message', 'You are successfully logout');
        return Redirect::to('/super/');
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
    }
}
