<!DOCTYPE html>
<html>
<head>
    <title>Approval Perjalanan Dinas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .status {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Approval Perjalanan Dinas</h2>
        <p>Halo, <strong>{{ $name }}</strong>,</p>
        <p>Perjalanan dinas untuk proyek <strong>{{ $project }}</strong> telah mengalami perubahan status <strong>{{ $statuskirim }}</strong>.</p>
        <p>Status saat ini: <span class="status">{{ $status }}</span></p>
        <p>Persetujuan dilakukan oleh: <strong>{{ $jabatan }}</p>
        <p>Silakan periksa sistem untuk informasi lebih lanjut.</p>
        <hr>
        <p class="footer">Email ini dikirim secara otomatis oleh sistem. Harap tidak membalas email ini.</p>
    </div>
</body>
</html>
