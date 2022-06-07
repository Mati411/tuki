@extends('layouts.login')

@section('content')
<div class="modal-dialog" style="margin-bottom:0">
    <div class="modal-content">
        <div class="panel-heading text-center">
            <h3>Acceso restringido</h3>
        </div>
        <div class="panel-body">
            <form role="form" method="POST" action="{{ route('login') }}">
                @csrf
                <fieldset>
                    <div class="form-group @error('email') has-error @enderror">
                        <input class="form-control" name="email" value="{{ old('email') }}" name="email" type="email"value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="help-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group @error('password') has-error @enderror">
                        <input class="form-control" name="password" type="password" required autocomplete="current-password">
                        @error('password')
                            <span class="help-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="checkbox">
                        <label>
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Recordarme
                        </label>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-primary">Iniciar sesión</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
@endsection
