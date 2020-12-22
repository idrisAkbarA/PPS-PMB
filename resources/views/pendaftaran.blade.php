@extends('layouts.base')
@push('styles')
<meta name="description" content="Pendaftaran mahasiswa pascasarjana UIN SUSKA RIAU">
<link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
@endpush
@section('content-left')
<div>
    <h4 style="margin-top:0px !important; color: white">Silahkan mendaftar!</h4>
    <p style="font-weight: bold; color: white">
    </p>
    <div>

        {{-- <span style="margin-right: 1em; color:white">
            Sudah mendaftar? <a href="#">Login disini</a>
        </span> --}}

    </div>
</div>
@endsection
@section('content-right')
<div class="pendaftaran-wrapper">
    @if ($errors->any())
        {{ $errors }}
    </ul>
    @endif
    <form class="form-wrapper" action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="nama" name="nama">
            <label style="color:black" class="mdl-textfield__label" for="text">Nama Lengkap</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="email" name="email">
            <label style="color:black" class="mdl-textfield__label" for="email">Email</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="password" id="password" name="password">
            <label style="color:black" class="mdl-textfield__label" for="password">Password</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="password" id="password2" name="password2">
            <label style="color:black" class="mdl-textfield__label" for="password2">Konfirmasi Password</label>
        </div>
        <button style="background: #12A50A; color: white"
            type="submit"
            class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
            Daftar
        </button>
        <span style="margin-top: 1em">Masuk <a href="{{ route('login') }}">disini</a> jika sudah mendaftar</span>
        <hr>
        {{-- <span>Daftar dengan metode lain</span>
        <!-- Social login buttons -->
        <div class="social-icon">
            <button type="button"><i class="fa fa-twitter"></i></button>
            <button type="button"><i class="fa fa-facebook"></i></button>
        </div> --}}
    </form>
</div>
@endsection
