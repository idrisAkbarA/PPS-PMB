@extends('layouts.base')
@push('styles')
<meta name="description" content="Situs Pendaftaran Pascasarjana UIN SUSKA Riau">
@endpush
@section('content-left')
<div>
    <h4 style="margin-top:0px !important; color: white">Assalamualaikum!</h4>
    <p style="font-weight: bold; color: white">Selamat datang di situs penerimaan mahasiswa baru <br> Pasca Sarjana UIN Suska Riau
    </p>
    <div>
        <a href="/pendaftaran">
            <button style="background: white; color:#12A50A" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                Daftar Sekarang!
            </button>
        </a>
    </div>
</div>
@endsection