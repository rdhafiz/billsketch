<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Code</title>
</head>
<body>

<p>
    Hello {{$userInfo['first_name'].' '.$userInfo['last_name'] ?? ''}},<br /><br />
</p>
<p>
    Here is your password reset code.
</p>
<h1>{{$userInfo['reset_code']}}</h1>

<br><br>
Regards
<br>
Bilify Support

</body>
</html>
