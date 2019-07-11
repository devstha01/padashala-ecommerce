<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>{{__('email.Password Recovery')}}</h2>
<div>
    <img src="{{asset('image/gghl-logo.png')}}" style="height: 50px" alt="logo">
</div>
<p>
    <b> {{__('email.Hi')}} ,</b>
    <br>
    {{__("email.You're receiving this email because you requested a password reset for your Golden Gate (hk) Account")}}
    . {{__("email.If you did not request this change, you can safely ignore this email")}}.
    <br>
    {{__('email.To choose a new password and complete your request, please follow the link below:')}}
    <br>
    <a style="color: dodgerblue" href="{{$link}}">{{$link}}</a>
    <br>

    {{__("email.If it is not clickable, please copy and paste the URL into your browser's address bar")}}.
    <br><br>
    {{__('email.Regards')}},
    <br>
    Goldengate (hk) Team
</p>

</body>
</html>