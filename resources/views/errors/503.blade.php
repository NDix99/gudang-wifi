<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situs Sedang Maintenance</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #2563eb, #1e3a8a);
            color: #fff;
            text-align: center;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            max-width: 500px;
            backdrop-filter: blur(8px);
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #facc15;
        }
        p {
            margin-bottom: 1rem;
            font-size: 1rem;
            color: #f3f4f6;
        }
        .illustration {
            margin: 1.5rem 0;
        }
        .illustration img {
            max-width: 180px;
            animation: float 3s ease-in-out infinite;
        }
        .loader {
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid #facc15;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 1rem auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="illustration">
            <img src="https://cdn-icons-png.flaticon.com/512/483/483361.png" alt="Router WiFi">
        </div>
        <h1>⚡ Gudang WiFi Sedang Maintenance</h1>
        <p>Kami sedang melakukan Maintenace aplikasi.</p>
        <p>Aplikasi akan kembali normal dalam beberapa saat.</p>
        <div class="loader"></div>
    </div>
</body>
</html>
