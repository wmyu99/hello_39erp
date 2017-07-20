<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\User;
use Auth;
use Mail;
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
      $this->sendEmailConfirmationTo($user);
      session()->flash('success', '验证邮件已经发送，请您检查邮箱。~');
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


     protected function sendEmailConfirmationTo($user)
     {
       $view ='emails.confirm';
       $data = compact('user');
       $from = 'aufree@estgroupe.com';
       $name = 'Aufree';
       $to = $user->email;
       $subject = "感谢注册 Sample 应用！ 请确认你的邮箱";

       Mail::send($view,$data,function($message) use ($from,$name,$to,$subject) {
         $message->from($from,$name)->to($to)->subject($subject);
       });
     }

     public function confirmEmail($token)
     {
       $user = User::where('activation_token',$token)->firstOrFail();
       $user->activated = true;
       $user->activation_token = null;
       $user->save();

       Auth::login($user);
       session()->flash('success','恭喜，激活成功');
       return redirect()->route('users.show',[$user]);
     }

}
