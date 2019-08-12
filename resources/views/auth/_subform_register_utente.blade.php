

<div class="form-group has-feedback">
     <label for="email">Email</label>
    <input id="email" type="email" placeholder="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" @if ($utente->exists) value="{{ old('email') != '' ? old('email') : $utente->email }}" @else value="{{ old('email')}}" @endif" required>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    {{-- @if ($errors->has('email'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif --}}
</div>

{{-- <div class="form-group has-feedback">        
    <label for="username">Username</label>
    <input id="username" type="text" placeholder="username"  class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" @if ($utente->exists) value="{{ old('username') != '' ? old('username') : $utente->username }}" @else value="{{ old('username')}}" @endif" required autofocus>
    <span class="glyphicon glyphicon-text-size form-control-feedback"></span>
</div> --}}

@if ($utente->exists)
    <div class="box-header">
      Lascia vuoti questi campi se NON vuoi MODIFICARE la password
    </div>
@endif

<div class="form-group has-feedback">
    <label for="password">Password</label>
    <input id="password" type="password"  placeholder="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" @if (!$utente->exists) required @endif>
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
</div>

<div class="form-group has-feedback">
    <label for="password-confirm">Conferma password</label>
    <input id="password-confirm" type="password" placeholder="conferma password"  class="form-control" name="password_confirmation" @if (!$utente->exists) required @endif>
    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
</div>