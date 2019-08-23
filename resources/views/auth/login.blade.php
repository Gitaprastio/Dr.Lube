@extends('layouts.front')

@section('content')

<div class="container">

  <!-- Outer Row -->
  <div class="row justify-content-center">

    <div class="col-xl-10 col-lg-12 col-md-9">

      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
          <!-- Nested Row within Card Body -->
          <div class="row">
            <div class="col-lg-6 d-none d-lg-block bg-login-image">
            </div>
            <div class="col-lg-6">
              <div class="p-5">
                <div class="text-center">
                  <ul class="tulisan_login">
                  <li><img src="../assets/image/dr_lube-01.png" class="logo_samping"></li>
                  <!-- <li><h1 class="h4 text-gray-900 mb-4">Dr. Lube</h1></li> -->
                  </ul>
                </div>
                <form method="POST" action="{{ route('login') }}" class="user">
                  @csrf
                  <div class="form-group">
                    <input id="email" type="email" class="form-control form-control-user  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address" autofocus>
                    @error('email')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                    @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <div class="custom-control custom-checkbox small">
                      <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                      <label class="custom-control-label" for="remember">
                          {{ __('Remember Me') }}
                      </label>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary btn-user btn-block">
                      {{ __('Login') }}
                  </button>

                  <hr>
                </form>
                <hr>
                <div class="text-center">
                  @if (Route::has('password.request'))
                    <a class="small" href="{{ route('password.request') }}">
                      {{ __('Forgot Your Password?') }}
                    </a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

</div>

@endsection
