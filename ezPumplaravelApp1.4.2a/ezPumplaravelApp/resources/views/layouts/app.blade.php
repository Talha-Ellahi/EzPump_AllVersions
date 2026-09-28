@use('Illuminate\Support\Facades\Vite')

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/assets/images/favicon-32x32.png" type="image/png" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--plugins-->
    <link href="/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="/assets/css/pace.min.css" rel="stylesheet" />
    <script src="/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="/assets/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <!-- <link href="/assets/css/dashboard.css" rel="stylesheet"> -->
    <link href="/assets/css/new_style.css" rel="stylesheet">
    <link href="/assets/css/icons.css" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
      <!-- Scripts -->
    @vite([
        'resources/js/app.js',
        ])
</head>
<Style>


    .select2-container .select2-selection--single {

        color: #333333;
        border-radius: 5px;
        font-size: 15px;
        border: 1px solid rgba(0, 0, 0, 0.3);
        box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.2);
        display: block;
        user-select: none;
        -webkit-user-select: none;
    }

    .border-danger {
        border-color: #2a2d93 !important;
    }

    .text-danger {
        color: #2a2d93 !important;
    }

    .card-title {
        margin-bottom: 2.5rem;
    }

    .row-cols-xl-4>* {
        flex: 0 0 auto;
        width: 24%;
        margin: 7px 1px 5px 7px;
    }

    .navbar {
        display: contents !important;
    }

    .btn-danger {
        color: #fff;
        background-color: #2a2d93 !important;
        border-color: #2a2d93 !important;
    }

    .logo {
        width: 60px;
        /* Adjust dimensions as needed */
        height: 60px;
        background-repeat: no-repeat;
    }

    .logo-icon {
        width: 55px;
        /* Adjust dimensions as needed */

    }

    .text-bold {
        font-weight: bold;
    }

    th,
    tr {
        border-color: inherit;
        border-style: solid;
        border-width: 0.1px;
    }


    b {
        font-weight: 800;
    }

    .table>:not(caption)>*>* {
        padding: .5rem .2rem;
    }

    @media (min-width: 768px) {
        .row-cols-md-8>* {
            flex: 0 0 auto;
            width: 12.5%;
        }
    }

    .row {
        margin-right: 33px;
        margin-left: 33px;
    }
    .navbar>.container-fluid, .navbar>.container-lg, .navbar>.container-md, .navbar>.container-sm, .navbar>.container-xl, .navbar>.container-xxl {
    display: flex;
    flex-wrap: inherit;
    align-items: center;
    height: 100px;
    justify-content: space-between;
}
</Style>

<body>

@include('layouts.header')

<main id="main" class="py-4 main">
    @yield('content')
</main>

@include('layouts.footer')





    <!-- Bootstrap JS -->
    <script src="/assets/js/jquery.min.js"></script>
    <!-- <script src="/assets/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->
    <script src="/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="/assets/plugins/select2/js/select2.min.js"></script>

     <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace();
    </script>

     <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace();
    </script>

    <script>
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $('.multiple-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>
    <!--app JS-->
{{--<! alarm shift and lock without login page and shift closs-->--}}



          <!-- Include SweetAlert2 -->
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


          @auth
              @if (!request()->is('login') && !request()->is('nozzle'))
          <script>
              let lockBeepInterval = null;
              let lockScreenVisible = false;
              let shiftLockState = false; // Global lock state

              {{--const isAdmin = @json(auth()->user()->role == 0);--}}
              const isAdmin = @json(auth()->user()->role);
              function showReminderScreen(shift) {
                  if (document.querySelector('.luxury-reminder-screen')) return; // Prevent duplicates

                  const minsLeft = Math.abs(shift.minutes_remaining);
                  const reminderHTML = `
                        <div class="luxury-reminder-screen">
                            <div class="reminder-content">
                                <h2>⚠️ Shift Closing Soon</h2>
                                <p>Shift will lock in <strong>${minsLeft} minutes</strong>.</p>
                            </div>
                        </div>
                    `;

                  document.body.insertAdjacentHTML('beforeend', reminderHTML);

                  // Auto-remove reminder after 1 minute
                  setTimeout(() => {
                      const el = document.querySelector('.luxury-reminder-screen');
                      if (el) el.remove();
                  }, 60000);
              }
              function showLockScreen(shift) {
                  if (lockScreenVisible) return;
                  lockScreenVisible = true;

                  const lockHTML = `
            <div class="a1b2c3">
                <div class="d4e5f6"></div>
                <div class="g7h8i9">
                    <div class="j0k1l2">
                        <svg class="m3n4o5" viewBox="0 0 24 24">
                            <path d="M12 2C8.14 2 5 5.14 5 9v1.5c-1.85.97-3 2.9-3 5.1V21h18v-5.4c0-2.2-1.15-4.13-3-5.1V9c0-3.86-3.14-7-7-7zm0 2c2.76 0 5 2.24 5 5v1.5H7V9c0-2.76 2.24-5 5-5z"/>
                        </svg>
                    </div>
                    <h1 class="p6q7r8">Access Locked</h1>
                    <p class="s9t0u1">Your session has ended due to shift expiry.</p>
                    <div class="v2w3x4">Locked</div>
                    <div class="y5z6a7">Please contact administrator to unlock access.</div>
                </div>
            </div>
        `;

                  document.body.insertAdjacentHTML('beforeend', lockHTML);
                  document.body.classList.add('lock-mode');

                  // Start Continuous Beep Loop
                  if (!lockBeepInterval) {
                      lockBeepInterval = setInterval(() => {
                          const softBeep = new Audio('/beep/beep.wav');
                          softBeep.play().catch(e => console.error(e));
                      }, 2000);
                  }

                  disableUserInput();
              }

              function hideLockScreen() {
                  const lockScreenEl = document.querySelector('.a1b2c3');
                  if (lockScreenEl) lockScreenEl.remove();
                  document.body.classList.remove('lock-mode');
                  lockScreenVisible = false;

                  // Stop Beeping
                  if (lockBeepInterval) {
                      clearInterval(lockBeepInterval);
                      lockBeepInterval = null;
                  }

                  enableUserInput();
              }

              function disableUserInput() {
                  document.addEventListener('keydown', blockEvent, true);
                  document.addEventListener('mousedown', blockEvent, true);
                  document.addEventListener('contextmenu', blockEvent, true);
                  document.addEventListener('pointerdown', blockEvent, true);
              }

              function enableUserInput() {
                  document.removeEventListener('keydown', blockEvent, true);
                  document.removeEventListener('mousedown', blockEvent, true);
                  document.removeEventListener('contextmenu', blockEvent, true);
                  document.removeEventListener('pointerdown', blockEvent, true);
              }

              function blockEvent(e) {
                  if (shiftLockState) {
                      e.stopPropagation();
                      e.preventDefault();
                  }
              }

              function checkShiftStatus() {
                  fetch('/api/shift-alerts')
                      .then(res => res.json())
                      .then(shifts => {
                          const shift = shifts.find(s => s.minutes_remaining <= 55);
                          if (shift && isAdmin != 0 && isAdmin != 2) {
                              // New: Bypass Active Check
                              const bypassActiveUntil = shift.bypass_active_until ? new Date(shift.bypass_active_until) : null;
                              const now = new Date();

                              const isBypassActive = bypassActiveUntil && now < bypassActiveUntil;

                              shiftLockState = shift.locked && !isBypassActive; // Lock only if bypass not active

                              if (shiftLockState) {
                                  showLockScreen(shift);
                              } else {
                                  hideLockScreen();

                                  // Reminder Beep Logic
                                  if (shift.minutes_remaining <= 55 && shift.minutes_remaining > 0 && !isBypassActive) {
                                      const softBeep = new Audio('/beep/beep.wav');
                                      softBeep.play().catch(e => console.error(e));
                                      const remainingTime =shift.minutes_remaining;
                                      // console.log('reminder lock');
                                      const shiftReminderCache=@json(\Illuminate\Support\Facades\Cache::put('shift_minutes_remaining',`remainingTime`));
                                      showReminderScreen(shift);
                                  }
                              }

                              // Beep in last 5 minutes if no lock
                              if (shift.should_beep && !shiftLockState) {
                                  const softBeep = new Audio('/beep/beep.wav');
                                  softBeep.play().catch(e => console.error(e));
                                  {{--window.load('@include('index')')--}}
                              }

                          } else {
                              hideLockScreen();
                              shiftLockState = false;
                          }
                      })
                      .catch(console.error);
              }

              setInterval(() => {
                  checkShiftStatus();

                  // DOM Tampering Protection
                  if (shiftLockState && !document.querySelector('.a1b2c3')) {
                      showLockScreen();
                  }
              }, 300000);
          </script>
          <style>
              .lock-mode { overflow: hidden !important; touch-action: none !important; }
              .a1b2c3 {
                  position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                  display: flex; justify-content: center; align-items: center;
                  background: linear-gradient(135deg, #1a1a1a, #000000);
                  z-index: 9999; overflow: hidden; animation: fadeInScreen 0.5s ease-out;
              }
              .d4e5f6 {
                  position: absolute; width: 200%; height: 200%;
                  background: radial-gradient(circle at center, rgba(255, 0, 0, 0.1), transparent 70%);
                  animation: pulseGlow 3s infinite ease-in-out; z-index: 1;
              }
              .g7h8i9 {
                  z-index: 2; background: rgba(30, 30, 30, 0.9);
                  padding: 2.5rem 3rem; border-radius: 20px;
                  box-shadow: 0 0 30px rgba(255, 0, 0, 0.4);
                  text-align: center; color: #fff; max-width: 500px;
                  animation: zoomIn 0.6s ease-out;
              }
              .j0k1l2 {
                  background: rgba(220, 53, 69, 0.2); border-radius: 50%;
                  padding: 1.2rem; margin-bottom: 1.5rem;
                  animation: pulseBorder 2s infinite; display: inline-block;
              }
              .m3n4o5 {
                  width: 60px; height: 60px; fill: #ff4d4d;
                  filter: drop-shadow(0 0 10px rgba(255, 77, 77, 0.6));
              }
              .p6q7r8 {
                  font-size: 2.2rem; margin-bottom: 0.8rem; font-weight: 700;
                  background: linear-gradient(to right, #fff, #ff9999);
                  -webkit-background-clip: text; color: transparent;
              }
              .s9t0u1 {
                  font-size: 1.1rem; color: rgba(255, 255, 255, 0.8);
                  margin-bottom: 1.5rem;
              }
              .v2w3x4 {
                  font-size: 1rem; background: rgba(255, 77, 77, 0.15);
                  border: 1px solid rgba(255, 77, 77, 0.4);
                  border-radius: 20px; padding: 0.5rem 1rem;
                  display: inline-block; margin-bottom: 1rem; color: #fff;
              }
              .y5z6a7 {
                  font-size: 0.95rem; color: rgba(255, 255, 255, 0.7);
              }
              .luxury-reminder-screen {
                  position: fixed; bottom: 20px; right: 20px;
                  background: rgba(255, 193, 7, 0.95); color: #000;
                  padding: 1rem 1.5rem; border-radius: 12px;
                  box-shadow: 0 4px 15px rgba(0,0,0,0.2); z-index: 9999;
                  font-family: 'Inter', sans-serif; animation: slideUp 0.5s ease;
              }
              .reminder-content h2 { font-size: 1.2rem; margin-bottom: 0.5rem; }
              .reminder-content p { font-size: 0.95rem; margin: 0; }
              @keyframes pulseGlow {
                  0%, 100% { transform: scale(1); opacity: 0.8; }
                  50% { transform: scale(1.1); opacity: 1; }
              }
              @keyframes pulseBorder {
                  0%, 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5); }
                  50% { box-shadow: 0 0 0 15px rgba(220, 53, 69, 0); }
              }
              @keyframes fadeInScreen {
                  from { opacity: 0; }
                  to { opacity: 1; }
              }
              @keyframes zoomIn {
                  from { transform: scale(0.9); opacity: 0; }
                  to { transform: scale(1); opacity: 1; }
              }
          </style>
              @endif
          @endauth



          <script src="/assets/js/app.js"></script>
    @yield('scripts')

    @auth
    <script>
        window.user = {
            id: {{ Auth::user()->id }},
            role: {{ Auth::user()->role }}
        };
    </script>
    @endauth
</body>

</html>
