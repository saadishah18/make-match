<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
{{--    <title>TOP</title>--}}
    <meta name="description" content="">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <style>
        html,
        body {
            color: #808080;
            background: #eee;
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            text-align: center;
            font: 400 16px/24px Gotham, "Helvetica Neue", Helvetica, Arial, "sans-serif";
        }

        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        main {
            width: 600px;
            margin: 0 auto;
            padding: 100px;
            text-align: center;
            overflow: hidden;
            background: #fff url(./border-img.png) no-repeat;
            background-size: cover;
        }

        h2 {
            color: black;
            font-size: 60px;
            text-align: center;
            padding-bottom: 20px;
        }

        p {
            color: #909191;
            font-size: 25px;
            text-align: center;
        }

        h3 {
            color: #E16AA4;
            font-size: 30px;
            text-align: center;
        }

        footer {
            margin-top: 24;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: center;
        }

        h6 {
            font-size: 25px;
            color: black;
            text-align: center;
            text-align: center;
            width: 12rem;
            padding: 8px 0;
            margin-bottom: 10px;
        }

        h6 span {
            font-size: 25px;
            color: black;
            border-bottom: 1px solid #DBDBDB;
            text-align: center;
            font-weight: 400;
            display: block;
            text-align: center;
            width: 12rem;
            padding: 8px 0;
            margin-bottom: 10px;
        }
    </style>
</head>

<body width="100%" style="margin: 0; background: #eee;">
<main
    style="width: 1200px; margin: 0 auto; padding: 100px; overflow: hidden; background: #fff url(./border-img.png) no-repeat; background-size: cover;"
    class="wrapper">
    <div
        style="padding: 200px 160px; text-align: center; background: url(./certificate-bg-img.png) no-repeat; overflow: hidden; background-size: cover; position: relative;">
        <div
            style="padding: 20; display:flex; justify-content: center; flex-direction: column; align-items: center; text-align: center;">
{{--            <figure style="width: 55px; height: 50px; position: relative; overflow: hidden; "><img src="{{url('/')}}/assets/images/certificate-logo.png" alt="logo" style="display: block; width: 100%; height: 100%;"/></figure>--}}
            <h2 style="font-size: 50px; font-weight: 700; text-align: center; color: black; margin-top: 20px; border-bottom: 1px solid #DBDBDB; padding-bottom: 20px;">
                Talaq Certificate </h2>
        </div>
        <div
            style="display:flex; justify-content:center; text-align: center; align-items: center; flex-direction: column;">
            <p style="color:#909191; font-weight:400; text-align:center; text-align: center; width:30rem; margin-top: 24px; font-size: 18px;">
                Talaq Was held between </p>
            <h3 style="font-size: 30px; font-weight: 600; text-align: center; margin-top: 40px; color: #E16AA4; font-weight: 500;">  {{ $talaq_data['requester']}}
                & {{ $talaq_data['bride']}}
            </h3>
        </div>
        <p style="color:#909191; font-weight: 400; text-align:center; margin-top: 24; text-align: center; font-size: 18px;">
            Talaq Counter is: <b>{{$talaq_data['talaq_counter']}}</b></p>
        <p style="color:#909191; font-weight: 400; text-align:center; margin-top: 24; text-align: center; font-size: 18px;">
            @php
                if($talaq_data['talaq_counter'] == 1){
                    $talaq_date = $talaq_data['first_talaq_date'];
                }else if($talaq_data['talaq_counter'] == 2){
                    $talaq_date =  $talaq_data['second_talaq_date'];
                }else{
                     $talaq_date =  $talaq_data['third_talaq_date'];
                }
            @endphp

            Talaq held on date {{ \Carbon\Carbon::parse($talaq_date)->toFormattedDateString() }}
        </p>
        <footer
            style="margin-top: 24; display:flex; justify-content: space-between; align-items: center; text-align: center;">
            <h6 style="font-size: 16px; color: black; text-align: center; width:12rem; padding: 8px 0; margin-bottom: 10px;">
                <span
                    style="font-size: 16px; color: black; border-bottom: 1px solid #DBDBDB; text-align: center; font-weight: 400; display: block; width:12rem; padding: 8px 0; margin-bottom: 10px;"> Niakh App Admin </span>
                Admin Signature </h6>
            <h6 style="font-size: 16px; color: black; text-align: center; width:12rem; padding: 8px 0; margin-bottom: 10px;">
                @php
                    $nikah = \App\Models\Nikah::find($talaq_data['nikah_id']);
                @endphp
                <span style="font-size: 16px; color: black; border-bottom: 1px solid #DBDBDB; text-align: center; font-weight: 400; display: block; width:12rem; padding: 8px 0; margin-bottom: 10px;">
                    {{$nikah->assignedImam ? fullName($nikah->assignedImam->first_name,$nikah->assignedImam->last_name) : 'N/A'}}
                </span>
                Imam Signature
            </h6>
            <h6 style="font-size: 16px; color: black; text-align: center; width:12rem; padding: 8px 0; margin-bottom: 10px;">
                <span style="font-size: 16px; color: black; border-bottom: 1px solid #DBDBDB; text-align: center; font-weight: 400; display: block; width:12rem; padding: 8px 0; margin-bottom: 10px;">{{$talaq_data['requester']}}</span>
                Groom Signature
            </h6>
        </footer>
    </div>
</main>
</body>

</html>
