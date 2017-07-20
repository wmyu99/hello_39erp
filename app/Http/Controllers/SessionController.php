<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest',['only'=>['create']]);
    }

    public function create()
    {
      return view('sessions.create');
    }

    public function store(Request $request)
    {
          $this->validate($request,['email'=>'required|email|max:255',
                                    'password'=>'required'
        ]);

        $credentials = ['email'=>$request->email,
                        'password'=>$request->password
        ];
        if (Auth::attempt($credentials,$request->has('remember'))){
              if(Auth::user()->activated){
                  session()->flash('success','欢迎回来');
                  return redirect()->intended(route('users.show',[Auth::user()]));
               }
               else{
                 Auth::logout();
                 session()->flash('warning','您的账号未激活，请检查邮箱中的注册邮件进行激活。');
                 return redirect()->intended(route('users.show',[Auth::user()]));
                 return redirect('/');
               }
           }
        else{
            session()->flash('danger','邮箱密码不匹配');
            return redirect()->back();
            }

    }


    public function destory()
    {
      Auth::logout();
      session()->flash('success','Logout!');
      return redirect('login');
    }
}
