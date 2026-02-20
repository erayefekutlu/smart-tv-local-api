# Smart TV Local API Client (PHP & Python) 📺

<div align="center">

![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Python Version](https://img.shields.io/badge/Python-3.7%2B-3776AB?style=for-the-badge&logo=python&logoColor=white)
![License: MIT](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)
[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-brightgreen.svg?style=for-the-badge)](https://github.com/erayefekutlu/smart-tv-local-api/graphs/commit-activity)
[![GitHub stars](https://img.shields.io/github/stars/erayefekutlu/smart-tv-local-api?style=for-the-badge&color=yellow)](https://github.com/erayefekutlu/smart-tv-local-api/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/erayefekutlu/smart-tv-local-api?style=for-the-badge&color=orange)](https://github.com/erayefekutlu/smart-tv-local-api/network)

🌍 **Choose Language / Dil Seçimi:** <br>
[🇬🇧 English Documentation](#-english-documentation) | [🇹🇷 Türkçe Dokümantasyon](#-türkçe-dokümantasyon)

</div>

---

# 🇬🇧 English Documentation

An unofficial, object-oriented API client (available in both **PHP** and **Python**) for controlling and interacting with compatible smart TVs over the local network (LAN) via Secure WebSockets (WSS).

This project was developed to enable **interoperability** between proprietary smart TV ecosystems and third-party home automation platforms (like Home Assistant, OpenHAB, or custom dashboards) without relying on external cloud services or mobile applications.

## ⚠️ Disclaimer
**This project is an independent research effort aimed solely at enabling interoperability between a legally purchased smart TV device and third-party home automation systems.**
* No proprietary source code, firmware, APK/IPA files, certificates, or confidential information belonging to the manufacturer are included, utilized, or distributed in this repository.
* All code in this repository has been written from scratch based on observed network communication patterns (packet analysis) within a privately owned local network.
* This project is not affiliated with, endorsed by, or associated with any smart TV manufacturer or brand. 
* The author assumes no responsibility for any misuse of this software. Use at your own risk.

## 🔍 Protocol Analysis & How It Works
The official mobile applications communicate with the TV via a local API using **Secure WebSockets (WSS)** on port `8080`. 
1. **Custom Subprotocol:** The TV requires a specific header to accept connections: `Sec-WebSocket-Protocol: ArWebsocket`.
2. **Legacy TLS:** The TV's internal server utilizes an older TLS configuration with a self-signed certificate. To establish a connection, the OpenSSL security level is explicitly lowered (`@SECLEVEL=0` in PHP, `ssl.CERT_NONE` in Python).
3. **JSON Payloads:** Commands are sent/received as clear-text JSON payloads containing a `commandId`, the `command` type, and `arguments`.

## 🚀 Installation

### For PHP
1. Install the required WebSocket dependency via Composer:
```bash
composer require textalk/websocket
```
2. Include `SmartTV.php` in your project.

### For Python
1. Install the required WebSocket dependency via pip:
```bash
pip install websocket-client
```
2. Import the `SmartTV` class from `SmartTV.py`.

---

## 📚 Documentation & Examples

### 1. Basic Setup & Fetching Data
You can fetch live data from the TV, such as the current active channel and the full Electronic Program Guide (EPG).

**PHP:**
```php
require_once 'SmartTV.php';
$tv = new SmartTV('tv_ip');

$currentChannel = $tv->getCurrentChannel();
print_r($currentChannel); // e.g. ['no' => 12, 'sT' => 'DTV', 'src' => 'Sat']
```

**Python:**
```python
from SmartTV import SmartTV
tv = SmartTV("tv_ip")

current_channel = tv.get_current_channel()
print(current_channel) # e.g. {'no': 12, 'sT': 'DTV', 'src': 'Sat'}
```

### 2. Audio & Channel Controls
**PHP:**
```php
$tv->volumeUp();
$tv->mute();
$tv->goToChannel(271); // Automatically sends exact digits
```

**Python:**
```python
tv.volume_up()
tv.mute()
tv.go_to_channel(271)
```

### 3. Smart Features & Typing
Navigate through menus and type text directly into search bars (like YouTube or Netflix) on the TV.

**PHP:**
```php
$tv->menu();
$tv->navDown();
$tv->ok();

$tv->typeText("Breaking Bad");
$tv->ok();
```

**Python:**
```python
tv.menu()
tv.nav_down()
tv.ok()

tv.type_text("Breaking Bad")
tv.ok()
```

### 4. Context Manager (Python Only)
You can use Python's `with` statement for automatic connection and cleanup:
```python
with SmartTV("tv_ip") as tv:
    tv.volume_up()
    tv.go_to_channel(5)
```

---
<br>

# 🇹🇷 Türkçe Dokümantasyon

Uyumlu akıllı televizyonları yerel ağ (LAN) üzerinden Güvenli WebSocket (WSS) protokolü ile kontrol etmek için geliştirilmiş, gayri resmi ve nesne yönelimli (**PHP** ve **Python** destekli) bir API istemcisidir.

Bu proje, kapalı ekosistem akıllı televizyonların, harici bulut servislerine veya mobil uygulamalara bağlı kalmaksızın; Home Assistant, OpenHAB veya özel kontrol panelleri gibi üçüncü parti ev otomasyon sistemleriyle **birlikte çalışabilirliğini (interoperability)** sağlamak amacıyla geliştirilmiştir.

## ⚠️ Yasal Uyarı (Disclaimer)
**Bu proje, mülkiyeti yazara ait olan bir akıllı televizyonun bağımsız ev otomasyon sistemleriyle birlikte çalışabilirliğini sağlamak amacıyla yapılmış bağımsız bir araştırma ve geliştirme çalışmasıdır.**
* Bu depoda, üreticiye ait herhangi bir tescilli kaynak kodu, donanım yazılımı (firmware), APK/IPA dosyası, sertifika veya gizli bilgi bulunmamaktadır ve dağıtılmamaktadır.
* Depodaki tüm kodlar, yazarın kendi özel yerel ağındaki ağ iletişim kalıplarının (paket analizi) incelenmesiyle sıfırdan yazılmıştır.
* Bu projenin herhangi bir akıllı televizyon üreticisi veya markasıyla hiçbir resmi bağlantısı, onayı veya sponsorluğu bulunmamaktadır.
* Yazılımın kullanımından doğabilecek sorunlardan veya cihazınıza gelebilecek zararlardan kullanıcı sorumludur.

## 🔍 Protokol Analizi ve Çalışma Mantığı
Resmi mobil uygulamalar, televizyonla `8080` portu üzerinden **Secure WebSockets (WSS)** kullanarak yerel bir API aracılığıyla haberleşir.
1. **Özel Alt-Protokol:** Televizyon bağlantıyı kabul etmek için `Sec-WebSocket-Protocol: ArWebsocket` başlığını (header) zorunlu kılar.
2. **Eski TLS Sürümü:** TV'nin iç sunucusu, kendinden imzalı (self-signed) bir sertifika ile eski bir TLS yapılandırması kullanır. Bağlanabilmek için OpenSSL güvenlik seviyesi bilinçli olarak düşürülmüştür (PHP'de `@SECLEVEL=0`, Python'da `ssl.CERT_NONE`).
3. **JSON Paketleri:** Komutlar; `commandId`, `command` türü ve `arguments` içeren açık metin JSON formatında iletilir.

## 🚀 Kurulum

### PHP İçin
1. Gerekli WebSocket kütüphanesini Composer ile kurun:
```bash
composer require textalk/websocket
```
2. Projenize `SmartTV.php` sınıfını dahil edin.

### Python İçin
1. Gerekli WebSocket kütüphanesini pip ile kurun:
```bash
pip install websocket-client
```
2. Projenize `SmartTV.py` modülünü dahil edin.

---

## 📚 Dokümantasyon ve Örnekler

### 1. Temel Kurulum ve Veri Çekme
Televizyondan anlık olarak izlenen kanalı veya tam kanal listesini (EPG) çekebilirsiniz.

**PHP:**
```php
require_once 'SmartTV.php';
$tv = new SmartTV('tv_ip');

$currentChannel = $tv->getCurrentChannel();
print_r($currentChannel); 
```

**Python:**
```python
from SmartTV import SmartTV
tv = SmartTV("tv_ip")

current_channel = tv.get_current_channel()
print(current_channel)
```

### 2. Ses ve Kanal Kontrolleri
**PHP:**
```php
$tv->volumeUp();
$tv->mute();
$tv->goToChannel(271); // Rakamları otomatik hesaplar ve gönderir
```

**Python:**
```python
tv.volume_up()
tv.mute()
tv.go_to_channel(271)
```

### 3. Akıllı TV Özellikleri ve Klavye Kullanımı
Menülerde gezinebilir ve TV'deki arama kutularına (YouTube, Netflix vb.) doğrudan metin gönderebilirsiniz.

**PHP:**
```php
$tv->menu();
$tv->navDown();
$tv->ok();

$tv->typeText("Breaking Bad");
$tv->ok();
```

**Python:**
```python
tv.menu()
tv.nav_down()
tv.ok()

tv.type_text("Breaking Bad")
tv.ok()
```

### 4. Context Manager (Yalnızca Python)
Python tarafında bağlantıyı otomatik açıp kapatmak için `with` bloğunu kullanabilirsiniz:
```python
with SmartTV("tv_ip") as tv:
    tv.volume_up()
    tv.go_to_channel(5)
```

---

## 🤝 Contributing / Katkıda Bulunma
Pull request'ler kabul edilmektedir. Büyük değişiklikler için lütfen önce neyi değiştirmek istediğinizi tartışmak adına bir "Issue" açın.

## 📝 License / Lisans
This project is licensed under the [MIT License](LICENSE).
