<html>
<head></head>
<body style="background: black; color: white">
<b>Hi {{ $username }}</b> <br />
You recently requested to reset your password for your ThisHabbo account. Use the link below to change your password. <br />
<br />
{{ URL::to('/') }}/auth/change/password?code={{ $code }}
<br />
<br />
If you did not request a password reset, please ignore this email. This password reset is only valid for the next 30 minutes.
<br />
<br />
Thanks,
<br />
ThisHabbo
</body>
</html>