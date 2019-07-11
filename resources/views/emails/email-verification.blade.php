<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2> Email Verification</h2>
<div>
    <img src="{{asset('image/gghl-logo.png')}}" style="height: 50px" alt="logo">
</div>
<p>
    <b> Hi ,</b>
    <br>
    You're receiving this email because you created an account on Golden Gate (hk) and requires email verification.
    If you did not request this change, you can safely ignore this email.
    <br>
    To complete your email verification, please follow the link below:
    <br>
    <a style="color: dodgerblue" href="{{$link}}">{{$link}}</a>
    <br>

    If it is not clickable, please copy and paste the URL into your browser's address bar.
    <br><br>
    Regards ,
    <br>
    {{env('APP_NAME')}} Team
</p>

</body>
</html>