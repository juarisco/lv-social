@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            @include('partials.validation-errors')
            <div class="card border-0 bg-light px-4 py-2">
                <form action="{{ route('register') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Username</label>
                            <input class="form-control border-0" type="text" name="name" autocomplete="new-name" placeholder="Tu nombre de usuario...">
                        </div>
                        <div class="form-group">
                            <label for="first_name">Nombre</label>
                            <input class="form-control border-0" type="text" name="first_name" autocomplete="new-first_name" placeholder="Tu primer nombre...">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Apellido</label>
                            <input class="form-control border-0" type="text" name="last_name" autocomplete="new-last_name" placeholder="Tu apellido...">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control border-0" type="email" name="email" autocomplete="new-email" placeholder="Tu nombre...">
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input class="form-control border-0" type="password" name="password" id="password" autocomplete="new-password" placeholder="Tu contraseña">
                        </div>
                        <div class="form-group">
                            <label for="password">Repite la contraseña</label>
                            <input class="form-control border-0" type="password" name="password_confirmation" id="password" autocomplete="new-password" placeholder="Repite tu contraseña">
                        </div>
                        <button class="btn btn-primary btn-block" dusk="register-btn">Registro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection