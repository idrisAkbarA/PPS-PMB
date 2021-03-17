@extends('layouts.base')
@push('styles') 
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
@endpush
@push('js')
<script src="{{ mix('js/lupaPassword.js') }}"></script>
@endpush
@section('content-left')
<div>
    {{-- <h4 style="margin-top:0px !important; color: white">Silahkan Login!</h4> --}}
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
<div id="lupa-password-vue-component">
    <v-app>
        <lupa-password></lupa-password>
    </v-app>
</div>
@endsection