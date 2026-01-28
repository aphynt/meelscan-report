<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/png" href="{{ asset('logo/icon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Food Order | Meelcount</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f7fa; /* polos & clean */
        }

        .order-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 20px;
            padding: 32px 28px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            text-align: center;
            margin-bottom: 26px;
        }

        .header img {
            width: 160px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
            color: #1f2937;
        }

        .header p {
            margin-top: 6px;
            font-size: 13px;
            color: #6b7280;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            outline: none;
            transition: border 0.2s, box-shadow 0.2s;
        }

        .form-group select:focus,
        .form-group input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .submit-btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 14px;
            background: #2563eb;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s, box-shadow 0.1s;
        }

        .submit-btn:hover {
            background: #1e40af;
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .footer-text {
            margin-top: 22px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }

        @media (max-width: 480px) {
            .order-card {
                padding: 26px 22px;
            }
        }
    </style>
</head>

<body>

    <div class="order-card">
        @include('sweetalert2')

        <div class="header">
            <img src="{{ asset('logo/logo-full.png') }}" alt="Meelcount">
            <h2>Food Order</h2>
            <p>Please select your meal and room</p>
        </div>

        <form method="POST" action="{{ route('orders.create') }}">
            @csrf
            <div class="form-group">
                <label for="meal_type">Meal Type</label>
                <select id="meal_type" name="meal_type" required>
                    <option value="">-- Select Meal Type --</option>
                    <option value="breakfast">Breakfast</option>
                    <option value="lunch">Lunch</option>
                    <option value="dinner">Dinner</option>
                </select>
            </div>
            <div class="form-group">
                <label for="order_date">Order Date</label>
                <input
                    type="date"
                    id="order_date"
                    name="order_date"
                    value="{{ now()->toDateString() }}"
                    min="{{ now()->toDateString() }}"
                    max="{{ now()->addDay()->toDateString() }}"
                    required
                />
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" required/>
            </div>
            <!-- Room -->
            <div class="form-group">
                <label for="room">Room</label>
                <input type="text" id="room" name="room" placeholder="Example: E10"/>
            </div>

            <div class="form-group">
                <label for="remarks_order">Remarks</label>
                <input type="text" id="remarks_order" name="remarks_order" placeholder="Opsional" required />
            </div>

            <button type="submit" class="submit-btn">
                Submit Order
            </button>
        </form>

        <div class="footer-text">
            © 2026 Meelcount · Food Consumption System
        </div>
        <div class="footer-text"> <a href="{{ route('logout') }}">Logout</a> </div>

    </div>

</body>
</html>
