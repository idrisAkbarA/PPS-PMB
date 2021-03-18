<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Home">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="icon" href="{{ asset('/images/LogoUIN.png') }}">
    @stack('styles')
    <title>Pascasarjana UIN Suska Riau</title>
</head>

<body>
    <div id="app-landing">
        <v-app id="app">
            <div class="i-container">
                <div class="left">
                    <div class="left-menu">
                        <div class="logo-uin">
                            <img src="{{ asset('images/LogoUIN.png') }}" alt="logo uin suska" height="40px">
                        </div>

                        <div class="menu-link">
                            <div>
                                <a href="/">
                                    <h6>
                                        Home
                                    </h6>
                                </a>
                            </div>
                            <div>
                                <a href="/login">
                                    <h6>
                                        Login
                                    </h6>
                                </a>
                            </div>
                            <div>
                                <h6>
                                    Tentang
                                </h6>
                            </div>
                            <div>
                                <a href="/pendaftaran">
                                    <h6>
                                        Pendaftaran
                                    </h6>
                                </a>
                            </div>
                        </div>
                    </div>
                    <v-container fluid fill-height>

                        <v-layout align-center justify-center>
                            <v-flex class="mt-n16" xs12 sm12 md6 align-center>
                                <h1 class="margin:0 text-white">Assalamualaikum!</h1>
                                <p class="text-white">Selamat datang di situs penerimaan mahasiswa baru
                                    Pascasarjana UIN Suska Riau </p>
                                <p class="text-white"></p>
                                <v-btn href="/pendaftaran">Silahkan register</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </div>
                <div class="right" style="background-color:#066AFE">
                    <v-container fluid fill-height>
                        <v-layout align-center justify-center>
                            <v-flex xs12 sm12 md6>


                            </v-flex>
                        </v-layout>
                    </v-container>
                </div>
            </div>
        </v-app>
    </div>
    <script src="{{ mix('js/landing.js') }}"></script>
    @stack('js')
</body>

</html>
