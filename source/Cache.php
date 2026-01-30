<?php
class Cache {
    private $cacheFile;
    private $cacheTime;

    public function __construct($fileName, $seconds = 60) {
        $this->cacheFile = $fileName;
        $this->cacheTime = $seconds;
    }

    public function isValid() {
        // Dosya varsa ve süresi dolmadıysa true döner
        if (file_exists($this->cacheFile)) {
            return (time() - filemtime($this->cacheFile)) < $this->cacheTime;
        }
        return false;
    }

    public function read() {
        $content = file_get_contents($this->cacheFile);
        return json_decode($content, true);
    }

    public function write($data) {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        // data klasörünün yazılabilir olduğundan emin oluyoruz
        return file_put_contents($this->cacheFile, $jsonData);
    }
}