@extends('layouts.base')
@push('styles')
<meta name="description" content="Situs Pendaftaran Pascasarjana UIN SUSKA Riau">
@endpush
@section('content-left')
<div>
    <h4 style="margin-top:0px !important; color: white">Assalamualaikum!</h4>
    <p style="font-weight: bold; color: white">Selamat datang di situs penerimaan mahasiswa baru <br> Pascasarjana UIN Suska Riau
    </p>
    <div>
        <a href="/pendaftaran">
            <button style="background: white; color:#12A50A" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                Silahkan Register!
            </button>
        </a>
    </div>
</div>
@endsection