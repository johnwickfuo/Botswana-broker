<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #111111;
                font-weight: 400;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .republic {
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .14em;
                text-transform: uppercase;
                color: #009639;
            }

            .title {
                font-size: 36px;
                padding: 20px;
                color: #111111;
            }
        </style>

        <!-- United Arab Emirates brand layer (loaded last to override the theme) -->
        <link rel="stylesheet" href="{{ asset('css/botswana-brand.css') }}">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <p class="republic">United Arab Emirates</p>
                <div class="title">
                    @yield('message')
                </div>
            </div>
        </div>
    </body>
</html>
