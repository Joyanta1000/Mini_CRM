<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Companies;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = DB::table('companies')
                ->select('companies.*')
                ->paginate(10);

                return response()->json([
                    'data' => $companies ?  $companies : null,
                    'message' => $companies ? 'Successfully Retrieved' : 'Error',
                  ], 201);

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
      $validator = Validator::make($request->all(), [
            
        'name' => 'required|string',
        'email' => 'required|string|email|unique:companies,email',
            'avatar' => 'required',
            'website' => 'required|string|unique:companies,website',
            
          ]);

          
        
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        else{
        $Company = new Companies();

    
    $Company->name =  $request->name;
    $Company->email =  $request->email;
    
        if($request->file('avatar'))
        {
                $random = Str::random(40);
                $destination_path = 'public';
                $logos_file = $request->file('avatar');
                $logos_files_name = $random . $logos_file->getClientOriginalName();
                $path = $request->file('avatar')->storeAs($destination_path, $logos_files_name);
                $Company->logo = $logos_files_name;
        }

    $Company->website =  $request->website;

    $Company->save();

    return response()->json([
      'data' => $Company ?  $Company : null,
      'message' => $Company ? 'Successfully Inserted' : 'Error',
    ], 201);
        }
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
   $Companies = DB::table('companies')
        ->where('companies.id', '=', "$id")
        ->select('companies.*')
        ->get();

    return response()->json([
      'data' => $Companies ? $Companies : null,
      'message' =>  $Companies?'Successfully Received' : 'Error',
    ], 200);
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
      $validator = Validator::make($request->all(), [
            
        'name' => 'required|string',
            'avatar' => 'required',

            
          ]);

          
        
          if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        else{
        $Company = Companies::find($id);

    
    $Company->name =  $request->name;
    $Company->email =  $request->email;
    
        if($request->file('avatar'))
        {
                $random = Str::random(40);
                $destination_path = 'public';
                $logos_file = $request->file('avatar');
                $logos_files_name = $random . $logos_file->getClientOriginalName();
                $path = $request->file('avatar')->storeAs($destination_path, $logos_files_name);
                $Company->logo = $logos_files_name;
        }

    $Company->website =  $request->website;

    $Company->save();

    return response()->json([
      'data' => $Company ?  $Company : null,
      'message' => $Company ? 'Successfully Updated' : 'Error',
    ], 201);
  }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
  {
    $delete = Companies::find($id)->delete();

        if($delete)
        {
            return response()->json([
                    'data' => $delete ? $delete : null,
                    'message' => $delete ? 'Successfully Deleted' : 'Error',
                ], 201);
        }
  }
}
