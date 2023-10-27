<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <meta httpEquiv="Content-Security-Policy" content="default-src 'self' data: gap: https://ssl.gstatic.com 'unsafe-eval'; style-src 'self' 'unsafe-inline'; media-src *;**script-src 'self' http://onlineerp.solution.quebec 'unsafe-inline' 'unsafe-eval';** "/> -->

    <title inertia>{{ config('app.name', 'Nikah App') }}</title>
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">

    <meta name="description" content="MyNikahNow is a unique and innovative app designed specifically for Muslim couples who want to plan and undertake their Nikah in a simple, fast, and hassle-free way. Our mission is to provide a fully halal-compliant alternative to civil marriage, ensuring that the sacred tradition of Nikah is accessible to all." />

    <meta property="og:type" content="website" />

    <meta property="og:url" content="https://mynikahnow.co.uk/" />

    <meta property="og:title" content="MyNikahNow" />

    <meta property="og:description" content="MyNikahNow is a unique and innovative app designed specifically for Muslim couples who want to plan and undertake their Nikah in a simple, fast, and hassle-free way. Our mission is to provide a fully halal-compliant alternative to civil marriage, ensuring that the sacred tradition of Nikah is accessible to all." />

    <meta property="og:image" content="{{asset('assets/images/meta-image.png')}}" />

    <meta property="twitter:card" content="summary_large_image" />

    <meta property="twitter:url" content="https://mynikahnow.co.uk/" />

    <meta property="twitter:title" content="MyNikahNow" />

    <meta property="twitter:description" content="MyNikahNow is a unique and innovative app designed specifically for Muslim couples who want to plan and undertake their Nikah in a simple, fast, and hassle-free way. Our mission is to provide a fully halal-compliant alternative to civil marriage, ensuring that the sacred tradition of Nikah is accessible to all." />

    <meta property="twitter:image" content="{{asset('assets/images/meta-image.png')}}" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="{{asset('assets/css/responsive.css')}}" rel="styleshee">

    <!-- Mailchimp style -->
    <link href="//cdn-images.mailchimp.com/embedcode/classic-061523.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        #mc_embed_signup {
            background: #fff;
            clear: left;
            font: 14px Helvetica, Arial, sans-serif;
            width: 600px;
        }

        /* Add your own Mailchimp form style overrides in your site stylesheet or in this style block.
            We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
    </style>

    <!-- Scripts -->
    @routes
    @viteReactRefresh
    @vite('resources/js/app.jsx')
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>