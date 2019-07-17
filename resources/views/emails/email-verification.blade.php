<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2> Email Verification</h2>
<p>
    <b> Hi {{$link['name']}},</b>
    <br>
    You're receiving this email because you created an account on {{Config::get('app.name')}} and requires email verification.
    If you did not request this change, you can safely ignore this email.
    <br>
    To complete your email verification, please follow the link below:
    <br>
    <a style="color: dodgerblue" href="{{$link['url']}}">{{$link['url']}}</a>
    <br>

    If it is not clickable, please copy and paste the URL into your browser's address bar.
    <br><br>
    Regards ,
    <br>
    {{Config::get('app.name')}} Team
</p>

</body>
</html>