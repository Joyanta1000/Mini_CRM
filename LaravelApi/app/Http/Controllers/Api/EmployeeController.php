<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Companies;
use App\Models\Employees;
use DB;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = DB::table('employees')
                ->join('companies','employees.company_id','companies.id')
                ->select('employees.*', 'companies.name')
                ->paginate(10);

                return response()->json([
                    'data' => $employees ?  $employees : null,
                    'message' => $employees ? 'Successfully Retrieved' : 'Error',
                  ], 201);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function company_info()
    {
        $companies = DB::table('companies')
                ->select('companies.*')
                ->get();

                return response()->json([
                    'data' => $companies ?  $companies : null,
                    'message' => $companies ? 'Successfully Retrieved' : 'Error',
                  ], 201);

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
            
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'company_id' => 'required|string',
            'email' => 'required|string|email|unique:employees,email',
            'phone' => 'required',
                
              ]);
    
              
            
              if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
        else{
        $Employee = new Employees();

    $Employee->first_name =  $request->first_name;
    $Employee->last_name =  $request->last_name;
    $Employee->company_id =  $request->company_id;
    $Employee->email =  $request->email;
    $Employee->phone =  $request->phone;

    $Employee->save();

    return response()->json([
      'data' => $Employee ?  $Employee : null,
      'message' => $Employee ? 'Successfully Inserted' : 'Error',
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
   $Employees = DB::table('employees')
        ->where('employees.id', '=', "$id")
        ->select('employees.*')
        ->get();

    return response()->json([
      'data' => $Employees ? $Employees : null,
      'message' =>  $Employees?'Successfully Received' : 'Error',
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
            
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'company_id' => 'required|string',
            
            'phone' => 'required',
                
              ]);
    
              
            
              if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
        else{
        $Employee = Employees::find($id);

        $Employee->first_name =  $request->first_name;
        $Employee->last_name =  $request->last_name;
        $Employee->company_id =  $request->company_id;
        $Employee->email =  $request->email;
        $Employee->phone =  $request->phone;

    $Employee->save();

    return response()->json([
      'data' => $Employee ?  $Employee : null,
      'message' => $Employee ? 'Successfully Updated' : 'Error',
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
    $delete = Employees::find($id)->delete();

        if($delete)
        {
            return response()->json([
                    'data' => $delete ? $delete : null,
                    'message' => $delete ? 'Successfully Deleted' : 'Error',
                ], 201);
        }
  }
}
