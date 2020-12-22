@extends('layouts.base')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
@endpush
@push('js')
<script src="{{ mix('js/mhsLogin.js') }}"></script>
@endpush
@section('content-left')
<div>
    <h4 style="margin-top:0px !important; color: white">Silahkan Login!</h4>
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
<div id="login-vue-component">
    <v-app>
        <Login></Login>
    </v-app>
</div>
@endsection