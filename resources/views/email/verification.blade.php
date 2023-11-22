<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify Account</title>
</head>
<body>

<p>
    Hello {{$userInfo['first_name'].' '.$userInfo['last_name'] ?? ''}},<br /><br />
</p>
<p>
    Please visit this link to verify your account.
    <br />
    one-time link: <a href="{{$userInfo['link']}}">click here to verify your account...</a>
</p>

<br><br>
Regards
Bilify Support

</body>
</html>
