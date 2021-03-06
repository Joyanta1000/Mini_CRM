<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required',
                
              ]);

              $loginData = [
            
             'email' => $request->email,
             'password' => $request->password
         ];
            
              if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            else if(!Auth()->attempt($loginData))
        {
             return response(['message'=>'Invalid credentials']);
         }
            else{
                return response(['user'=> Auth()->user(), 'isLoggedIn' => 1]);
            }

        // $loginData = $request->validate([
            
        //     'email' => 'email|required',
        //     'password' => 'required'
        // ]);

        // if(!Auth()->attempt($loginData))
        // {
        //     return response(['message'=>'Invalid credentials']);
        // }

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
