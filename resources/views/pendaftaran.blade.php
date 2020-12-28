@extends('layouts.base')
@push('styles')
<meta name="description" content="Pendaftaran mahasiswa pascasarjana UIN SUSKA RIAU">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
@endpush
@push('js')
<script src="{{ mix('js/pendaftaran.js') }}"></script>
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
<div id="pendaftaran-vue-component">
    <v-app>
        <Pendaftaran
        url-pendaftaran="{{route('register')}}"
        ></Pendaftaran>
    </v-app>
</div>
@endsection
