<?php
class Earthquake {
    private $url = "http://www.koeri.boun.edu.tr/scripts/lst3.asp";

    public function getEarthquakes() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) return [];

        $response = mb_convert_encoding($response, 'UTF-8', 'ISO-8859-9');
        
        // <pre> etiketleri arasındaki tabloyu al
        preg_match('/<pre>(.*?)<\/pre>/s', $response, $matches);
        if (!isset($matches[1])) return [];

        $lines = explode("\n", trim($matches[1]));
        $results = [];

        foreach ($lines as $index => $line) {
            if ($index < 6 || empty(trim($line))) continue;

            // Çoklu boşluklara göre parçala
            $parca = preg_split('/\s+/', trim($line));
            
            if (count($parca) >= 8) {
                // Yer bilgisi 8. indexten sonrasıdır
                $konum = implode(" ", array_slice($parca, 8));
                $konum = preg_replace('/\s+(İlksel|REVIZE.*)$/', '', $konum);

                $results[] = [
                    "tarih_saat" => $parca[0] . " " . $parca[1],
                    "enlem"      => $parca[2],
                    "boylam"     => $parca[3],
                    "derinlik"   => $parca[4],
                    "buyukluk"   => $parca[6],
                    "yer"        => $konum
                ];
            }
        }
        return $results;
    }
}