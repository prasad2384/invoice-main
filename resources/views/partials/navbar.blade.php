        <style>
          .logo-image{
            height:75px
          }
        </style>
        {{--
        <nav class="navbar navbar-expand-lg bg-primary">
          <div class="container-fluid ps-5 pe-5">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent1"> 
              <ul class="navbar-nav mb-2 mb-lg-0 justify-content-center mx-auto me-auto">
                
              </ul>
                @if (Route::has('login'))
                  @auth
                    @if(auth()->user()->usertype == 'admin')
                      <a class="btn btn-primary" href="{{ url('/admin/dashboard') }}">My Account</a>
                      @else
                      <a class="btn btn-primary" href="{{ url('/user/dashboard') }}">My Account</a>
                    @endif
                    @else
                      <a class="btn btn-light" href="{{ route('login') }}">SING IN / UP</a>
                  @endauth
                @endif
            </div>
          </div>
        </nav>
        --}}
        