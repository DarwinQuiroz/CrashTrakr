@extends('layouts.auth')

@section('title', 'Confirma tu Cuenta')

@section('auth-content')
    <p class="mt-5 text-lg">Tu cuenta fue creada con éxito. Ahora solo debes confirmarla, revisa tu e-mail.</p>

    <form action="{{ route('verification.send') }}" method="POST">
        @csrf
        <input type="submit" value="Reenviar Correo de Verificación" class="bg-amber-500 w-full text-center mt-5 px-5 py-2 uppercase font-bold cursor-pointer">
    </form>
@endsection