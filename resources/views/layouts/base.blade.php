<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <link rel="stylesheet" href="{{asset('css/nonSPA.css')}}">
  @stack('styles')
  <title>Pascasarjana UIN Suska Riau</title>
</head>

<body>
  <div class="parent">

    {{-- left side --}}
    <div class="{{($is_landing_page ?? '') == 1? 'left' : 'left-no-shadow'}}">
      <div class="left-menu">
        <div class="logo-uin">
          <img src="{{asset('images/LogoUIN.png')}}" alt="logo uin suska" height="40px">
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
            <h6>
              Blog
            </h6>
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
      <div class="{{($is_login ?? '') == 1? 'left-image' : 'left-content'}}">
        @yield('content-left')
      </div>
    </div>

    {{-- right side --}}
    <div class="right">
      @yield('content-right')
    </div>

  </div>
  <script src="{{ mix('js/nonSPA.js') }}"></script>
  @stack('js')
</body>

</html>