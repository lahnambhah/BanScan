<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>

<body style="font-family: Arial; background:#f5f5f5; padding:40px;">

    <div style="max-width:600px; margin:auto; background:white; padding:30px; border-radius:10px;">
        
        <h2>Reset Your Password</h2>

        <p>Hello 👋,</p>

        <p>You requested to reset your password.</p>

        <p>Click the button below to set a new password:</p>

        <div style="text-align:center; margin:30px 0;">
            <a href="{{ $url }}" 
               style="background:black; color:white; padding:12px 20px; text-decoration:none; border-radius:6px;">
               Reset Password
            </a>
        </div>

        <p>If you did not request this, ignore this email.</p>

        <hr>

        <p style="font-size:12px; color:gray;">
            © {{ date('Y') }} BanScan
        </p>

    </div>

</body>
</html>