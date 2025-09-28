<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Reset Password</title></head>
<body>
    <p>Hi <?= esc($name) ?>,</p>
    <p>Kami terima permintaan untuk reset password. Sila klik link di bawah untuk set password baru (link sah 60 minit):</p>
    <p><a href="<?= $link ?>"><?= $link ?></a></p>
    <p>Jika bukan awak, abaikan email ini.</p>
    <p>Terima kasih.</p>
</body>
</html>
