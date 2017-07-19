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
          session()->flash('success','欢迎回来');
          return redirect()->intended(route('users.show',[Auth::user()]));
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
