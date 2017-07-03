@extends('layouts.default')
@section('title','登录')
@section('content')
<div class="col-md-offset-2 col-m-8">
  <div class="panel panel-default">
      <div class="panel-heading">
        <h5>登录</h5>
      </div>
      <div class="panel-body">
        @include('shared.errors')

        <form method="post" action="{{route('login')}}">
          {{csrf_field()}}
          <div class="form-group">
            <label for="email">email:</label>
            <input type="text" name="email" class="form-control" value="{{old('email')}}">
          </div>

          <div class="form-group">
            <label for="password"> password:</label>
              <input type="text" name="password" class="form-control" value="{{old('password')}}">
            </div>

            <div class="checkbox">
            <label><input type="checkbox" name="remember"> 记住我</label>
          </div>

            <button type="submit" class="btn btn-primary">Login</button>
          </form>

          <hr>

          <p>还没账号<a href="{{route('signup')}}"> Sign up </a>
      </div>

    </div>
  </div>



@stop
