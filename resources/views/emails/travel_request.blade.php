<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Perjalanan Dinas</title>
</head>
<body>
    <h3>Halo, {{ $detail['name'] }}</h3>
    <p>Status perjalanan dinas Anda telah diperbarui menjadi: <strong>{{ $detail['status_approve'] }}</strong></p>
    <p>Silakan periksa detailnya di sistem.</p>
    <br>
    <p>Terima kasih,</p>
    <p>Admin</p>
</body>
</html>
