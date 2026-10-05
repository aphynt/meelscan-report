<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('logo/icon.png') }}">
    <title>Food Order | {{ config('app.name') }}</title>
    <link href="{{ asset('admin/dist') }}/assets/css/app.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/dist') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/dist') }}/assets/css/meelcount-modern.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    @include('sweetalert2')

    <main class="mc-order-shell">
        <header class="mc-order-top">
            <img src="{{ asset('logo/logo-full.png') }}" alt="SIMS Jaya Kaltim">
            <div class="d-flex align-items-center gap-2">
                <span class="d-none d-sm-inline text-muted" style="font-size:11px">{{ auth()->user()->name }}</span>
                <a href="{{ route('logout') }}" class="btn btn-light btn-sm"><i class="mdi mdi-logout"></i> Logout</a>
            </div>
        </header>

        <div class="mc-order-main">
            <section class="mc-order-copy">
                <div class="eyebrow">Employee Meal Order</div>
                <h1>Order your meal in a few simple steps.</h1>
                <p>Select the meal type, date, quantity, and add any relevant note. Your order will be recorded directly in the meal attendance system.</p>

                <div class="mc-order-points">
                    <div class="mc-order-point"><span class="icon"><i class="mdi mdi-calendar-check-outline"></i></span><span>Choose today or the next available order date.</span></div>
                    <div class="mc-order-point"><span class="icon"><i class="mdi mdi-silverware-fork-knife"></i></span><span>Select breakfast, lunch, or dinner.</span></div>
                    <div class="mc-order-point"><span class="icon"><i class="mdi mdi-check-circle-outline"></i></span><span>Review the details before submitting your order.</span></div>
                </div>
            </section>

            <section class="mc-order-card">
                <div class="mc-order-card-head">
                    <h2>Food Order</h2>
                    <p>Complete the information below to submit your meal request.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 px-3" style="font-size:12px;border-radius:10px">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('orders.create') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="meal_type" class="form-label">Meal Type</label>
                        <div class="mc-input-wrap">
                            <span class="mc-input-icon mdi mdi-silverware-fork-knife"></span>
                            <select id="meal_type" name="meal_type" class="form-select" required>
                                <option value="">Select meal type</option>
                                <option value="breakfast">Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="dinner">Dinner</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-7">
                            <label for="order_date" class="form-label">Order Date</label>
                            <div class="mc-input-wrap">
                                <span class="mc-input-icon mdi mdi-calendar-blank-outline"></span>
                                <input type="date" id="order_date" name="order_date" class="form-control" value="{{ now()->toDateString() }}" min="{{ now()->toDateString() }}" max="{{ now()->addDay()->toDateString() }}" required>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="room" class="form-label">Room</label>
                        <div class="mc-input-wrap">
                            <span class="mc-input-icon mdi mdi-door-outline"></span>
                            <input type="text" id="room" name="room" class="form-control" placeholder="Example: E10">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="remarks_order" class="form-label">Remarks</label>
                        <div class="mc-input-wrap">
                            <span class="mc-input-icon mdi mdi-note-text-outline"></span>
                            <input type="text" id="remarks_order" name="remarks_order" class="form-control" placeholder="Add a short note" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4" style="min-height:45px">
                        <i class="mdi mdi-send-outline"></i> Submit Order
                    </button>
                </form>
            </section>
        </div>

        <footer class="mc-order-footer">© 2026 {{ config('app.name') }} · Food Consumption System</footer>
    </main>
</body>
</html>
