@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card border-0 bg-light px-4 py-2">
                <div class="card-body">
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control border-0" type="email" name="email" autocomplete="new-email" placeholder="Tu nombre...">
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input class="form-control border-0" type="password" name="password" id="password" autocomplete="new-password" placeholder="Tu contraseña">
                        </div>
                        <button class="btn btn-primary btn-block" id="login-btn">Login</button>
    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection