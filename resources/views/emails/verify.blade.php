<!DOCTYPE html>
<html>
<head>
    <title>Verify Email</title>
</head>
<body>
    <h2>Welcome to our app!</h2>
 <p>Click the link below to verify your email:</p>
<a href="{{ route('verify.email', $token) }}">Verify Email</a>

</html>

