<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<p>
    <b> Hi {{$full_name}},</b>
    <br>
    <br>
    Thank you for creating an account in {{Config::get('app.name')}}.
    <br>
    <br>
    <br>
    <b style="color:lightgray;font-size: 30px">DISCOVER {{Config::get('app.name')}}</b>
    <br>
    <br>
    Explore the unlimited choices of products provided by our dedicated merchants from around the world.
    <br>
    <br>
    Enjoy your new account.
    <br><br>
    Regards,
    <br>
    {{Config::get('app.name')}} Team

</p>

</body>
</html>