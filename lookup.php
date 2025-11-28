<?php
// ===============================
// Verifica se o IP foi enviado
// ===============================
if (!isset($_GET['ip']) || empty(trim($_GET['ip']))) {
    die("<strong>Erro:</strong> Nenhum IP informado.");
}

$ip = trim($_GET['ip']);

// ===============================
// Validação do IP
// ===============================
if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    die("<strong>Erro:</strong> IP inválido.");
}

// ===============================
// API KEY direta no código (a pedido)
// ===============================
$api_key = "EsOf64y+PVCkMt0a05R9YQ==ma10rYSMEG8nNLdL";

// ===============================
// Consulta API Ninjas
// ===============================
$url = "https://api.api-ninjas.com/v1/iplookup?address=" . urlencode($ip);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "X-Api-Key: $api_key",
    "Accept: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("Erro na requisição: " . curl_error($ch));
}

curl_close($ch);

$data = json_decode($response, true);

// ===============================
// Valida resposta
// ===============================
if (!$data || !is_array($data)) {
    die("<strong>Erro:</strong> Não foi possível interpretar a resposta da API.");
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Resultado - IP Lookup</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 40px;
        }

        .container {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            width: 600px;
            margin: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .back-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 12px;
            background: #007bff;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
        }

        .back-btn:hover {
            background: #0056b3;
        }
    </style>

</head>
<body>

<div class="container">
    <h2>Resultado para IP: <strong><?php echo htmlspecialchars($ip); ?></strong></h2>

    <table>
        <?php foreach ($data as $key => $value): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($key); ?></strong></td>
                <td><?php echo is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : htmlspecialchars($value); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a class="back-btn" href="index.php">Voltar</a>
</div>

</body>
</html>
