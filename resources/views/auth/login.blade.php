@extends('layouts.app_login')

@section('content')

<div class="login-box-body">
    <p class="login-box-msg">Login</p>

    <form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
        
        <div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }} has-feedback">
          <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
          @if ($errors->has('email'))
              <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
              </span>
          @endif
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
          <input type="password" class="form-control" placeholder="Password"  name="password" required>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          @if ($errors->has('password'))
              <span class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
              </span>
          @endif
        </div>

        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn btn-success btn-block btn-flat">Login</button>
          </div>
          <a class="btn btn-link" href="{{ route('password.request') }}">
              {{ __('Password dimenticata ?') }}
          </a>
          <!-- /.col -->
        </div>
    </form>
    

    <!-- /.social-auth-links -->
    {{-- <a href="{{ route('password.request') }}">Forgot Your Password?</a><br> --}}
  
 </div>
  <!-- /.login-box-body -->


@endsection


@section('script_footer')
    {{-- <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-green',
          radioClass: 'iradio_square-green',
          increaseArea: '20%' /* optional */
        });
      });
    </script> --}}
@endsection
