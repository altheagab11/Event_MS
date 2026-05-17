<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>EMS Digital Event Pass — {{ $passData['pass_code'] }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('digital-pass.partials.styles')
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #030914;
            padding: 40px 20px;
            color: #ffffff;
        }

        .pass-wrapper {
            display: flex;
            flex-direction: column;
            gap: 28px;
            align-items: center;
            max-width: 760px;
            margin: 0 auto;
        }

        .card-label {
            margin-top: 14px;
            font-size: 14px;
            letter-spacing: 4px;
            color: #b9c6d8;
            font-weight: 700;
            text-align: center;
        }

        .card-group {
            width: 100%;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .event-card {
                box-shadow: none;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <section class="pass-wrapper">
        <section class="card-group">
            @include('digital-pass.partials.front')
            <p class="card-label">FRONT</p>
        </section>

        <section class="card-group">
            @include('digital-pass.partials.back')
            <p class="card-label">BACK</p>
        </section>
    </section>
</body>

</html>
