<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
   public function __construct()
   {
     $this->middleware('auth',['only'=>['edit','update']]);
     $this->middleware('guest',['only'=>['create']]);
   }


    public function create()
    {
    	return view('users.create');
    }

    public function show($id)
    {
      $user = User::findorFail($id);
      return view('users.show',compact('user'));
    }
    public function store(Request $request)
    {
      $this->validate($request,['name'=>'required|max:50','email'=>'required|email|unique:users|max:255','password' => 'required|confirmed|min:6']);

      $user = User::create(['name'=>$request->name,'email'=>$request->email,'password'=>bcrypt($request->password)]);
      Auth::login($user);
      session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
      return redirect()->route('users.show',[$user]);
    }
    public function edit($id)
    {
      $user = User::findorFail($id);
      $this->authorize('update',$user);
      return view('users.edit',compact('user'));
    }

    public function update($id,Request $request)
    {
      $this->validate($request,[
        'name'=>'required|max:50',
        'password'=>'required|confirmed|min:6'
      ]);
      $user = User::findorfail($id);
      $this->authorize('update',$user);
      $user->update([
        'name'=>$request->name,
        'password'=>bcrypt($request->password),
      ]);

      session()->flash('success','information edit success!');
      return redirect()->route('users.show',$id);
    }

     public function index()
     {
       $users = User::paginate(30);
       return view('users.index',compact('users'));
     }

     public function destroy($id)
     {
       $user = User::findOrFail($id);
       $this->authorize('destroy',$user);
       $user->delete();
       session()->flash('success','成功删除用户');
       return back();
     }

}
