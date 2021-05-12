<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="icon" href="{{ asset('/images/LogoUIN.png') }}">
    @stack('styles')
    <title>Pascasarjana UIN Suska Riau</title>
</head>

<body>
    <div id="app-landing">
        <v-app id="login-vue-component">
            <div ref="spinner" class="loading-container">
                <div class="loading"></div>
            </div>
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
                        <Login v-if="windowWidth <= 600">
                        </Login>
                    </v-container>
                </div>
                <div ref="welcometext" class="right">
                    <v-container fluid fill-height>
                        <Login>
                        </Login>
                    </v-container>
                </div>
            </div>
        </v-app>
    </div>
    <script src="{{ mix('js/mhsLogin.js') }}"></script>
    @stack('js')
</body>

</html>
