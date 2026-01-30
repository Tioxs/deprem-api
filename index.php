<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *"); // API'nin her yerden erişilmesini sağlar

require_once __DIR__ . '/source/deprem.php';
require_once __DIR__ . '/source/Cache.php';

$cachePath = __DIR__ . '/data/cache.json';
$data = __DIR__ . '/data';

if (!is_dir($data)) {
    mkdir($data, 0777, true);
}

try {
    $cache = new Cache($cachePath, 60);

    if ($cache->isValid()) {
        $data = $cache->read();
        $source = "cache";
    } else {
        $api = new Earthquake();
        $data = $api->getEarthquakes();
        if (!empty($data)) {
            $cache->write($data);
        }
        $source = "live";
    }
    // --- GELİŞTİRME VE FİLTRELEME BÖLÜMÜ BURASI ---
   
    // 1. Şehir Filtresi (Örn: ?sehir=antalya)
     if (isset($_GET['sehir'])) {
    // Gelen şehir ismini küçük harfe çevir ve temizle
         $sehir = mb_strtolower(trim($_GET['sehir']), 'UTF-8');
         $data = array_filter($data, function($d) use ($sehir) {
        // Kandilli verisindeki 'yer' alanını küçük harfe çevirip kontrol et
            $yer = mb_strtolower($d['yer'], 'UTF-8');
             return str_contains($yer, $sehir);
        });
    }

    // 1. Minimum Büyüklük Filtresi (Örn: ?min=3.0)
    if (isset($_GET['min'])) {
        $min = (float)$_GET['min'];
        $data = array_filter($data, function($d) use ($min) {
            return (float)$d['buyukluk'] >= $min;
        });
    }

    // 2. Yer/Konum Filtresi (Örn: ?yer=marmara)
    if (isset($_GET['yer'])) {
        $search = mb_strtolower($_GET['yer'], 'UTF-8');
        $data = array_filter($data, function($d) use ($search) {
            return str_contains(mb_strtolower($d['yer'], 'UTF-8'), $search);
        });
    }

    // 3. Limit Filtresi (Örn: ?limit=5)
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : count($data);
    
    // Filtrelemeden sonra indeksleri sıfırla ve limiti uygula
    $data = array_slice(array_values($data), 0, $limit);

    // --- FİLTRELEME BİTİŞ ---

    echo json_encode([
        "status" => "success",
        "info" => [
            "source" => $source,
            "count"  => count($data),
            "date"   => date("Y-m-d H:i:s")
        ],
        "result" => $data
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(100);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}