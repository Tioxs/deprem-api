# deprem-api
✨ Özellikler

OOP Mimari: Temiz, sürdürülebilir ve geliştirilebilir sınıf yapısı.<br>
Akıllı Önbellek (Caching): 60 saniyelik önbellek mekanizması ile sunucu yükünü azaltır ve yanıt süresini milisaniyelere indirir.<br>
Gelişmiş Filtreleme: Şehir, minimum büyüklük ve sonuç limiti gibi dinamik sorgu parametreleri desteği.<br>
Hata Yönetimi: Veri kaynağına ulaşılamadığında veya yazma izinleri hatalı olduğunda güvenli hata mesajları döner.<br>
CORS Desteği: Frontend projelerinde (React, Vue, vb.) doğrudan kullanım imkanı<br><br><br>

🛠️ Kurulum
Projeyi yerel makinenize klonlayın:
```bash
git clone https://github.com/Tioxs/deprem-api.git
``` 
`data/` klasörüne yazma izni (CHMOD 777) verin:
```bash
chmod -R 777 data/
```
PHP yerel sunucuyu başlat:
```bash
php -S localhost:8000
```

📖 API Kullanımı
Sorgu Parametreler:
```table
Parametre	   Tip	         Açıklama                                               	Örnek
sehir	       string	     Belirli bir şehir veya bölgeye göre filtreleme yapar.	    ?sehir=izmir
min	           float	     Belirtilen değer ve üzerindeki büyüklükleri getirir.	    ?min=4.0
limit      	   int	         Dönecek olan maksimum sonuç sayısını belirler.	            ?limit=5
sort	       string	     mag değeri verilirse en büyük depremi en üste alır.	    ?sort=mag
```

Örnek Request;
```bash
GET http://localhost:8000/index.php?sehir=antalya&min=3.0&limit=3
```
Örnek Response;
```json
{
  "status": "success",
  "info": {
    "source": "cache",
    "count": 3,
    "max_mag": 4.1,
    "date": "2026-01-30 18:30:00"
  },
  "result": [
    {
      "tarih_saat": "2026.01.30 18:05:12",
      "enlem": "36.4237",
      "boylam": "30.1428",
      "derinlik": "10.2",
      "buyukluk": "4.1",
      "yer": "ANTALYA ACIKLARI (AKDENIZ)"
    }
  ]
}
```

İletişim;
[Bu adresten benle iletişim kurabilirsiniz](https://t.me/tisikoz) 

📄 Lisans
Bu proje MIT Lisansı ile lisanslanmıştır.
