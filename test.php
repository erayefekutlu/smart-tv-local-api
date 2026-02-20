<?php
require_once 'SmartTV.php';

// ============================================================
// BAĞLANTI KURULUMU
// TV'nizin IP adresini girin (router'dan veya TV ağ ayarlarından)
// ============================================================
$tv = new SmartTV('tv_ip', 8080);

echo "=== Smart TV Test Dosyası ===" . PHP_EOL . PHP_EOL;

// ============================================================
// 1. MEVCUT KANAL BİLGİSİ
// ============================================================
echo "--- Mevcut Kanal ---" . PHP_EOL;
$current = $tv->getCurrentChannel();
print_r($current);
// Çıktı örneği: Array ( [no] => 12 [sT] => DTV [src] => Sat )

// ============================================================
// 2. KANAL LİSTESİNİ ÇEK
// ============================================================
echo PHP_EOL . "--- Kanal Listesi (ilk 5 kanal) ---" . PHP_EOL;
$channels = $tv->getChannelList();
if (!isset($channels['error'])) {
    $top5 = array_slice($channels['response'] ?? $channels, 0, 5);
    foreach ($top5 as $ch) {
        $nowPlaying = $ch['now']['n'] ?? 'Bilgi yok';
        echo "#{$ch['no']} {$ch['n']} → Şu an: {$nowPlaying}" . PHP_EOL;
    }
}

// ============================================================
// 3. ZAMANLANMIŞ GÖREVLER
// ============================================================
echo PHP_EOL . "--- Zamanlanmış Görevler ---" . PHP_EOL;
$tasks = $tv->getScheduledTasks();
if (empty($tasks['response'])) {
    echo "Zamanlanmış görev yok." . PHP_EOL;
} else {
    print_r($tasks);
}

// ============================================================
// 4. SES KONTROLLERİ
// ============================================================
echo PHP_EOL . "--- Ses Kontrol ---" . PHP_EOL;

$tv->volumeUp();
echo "Ses +1" . PHP_EOL;
sleep(1);

$tv->volumeUp();
echo "Ses +1" . PHP_EOL;
sleep(1);

$tv->volumeDown();
echo "Ses -1" . PHP_EOL;
sleep(1);

$tv->mute();
echo "Ses açıldı/kapandı" . PHP_EOL;
sleep(1);

$tv->mute(); // tekrar basıp eskiye dön
echo "Ses açıldı/kapandı" . PHP_EOL;

// ============================================================
// 5. KANAL DEĞİŞTİRME
// ============================================================
echo PHP_EOL . "--- Kanal Değiştirme ---" . PHP_EOL;

// Yöntemler:
// a) Kanal zapping
$tv->channelUp();
echo "Kanal +1" . PHP_EOL;
sleep(1);

$tv->channelDown();
echo "Kanal -1" . PHP_EOL;
sleep(1);

// b) Rakam tuşlarıyla doğrudan kanala gitme
$tv->goToChannel(1);
echo "Kanal 1'e gidildi" . PHP_EOL;
sleep(2);

// c) Üç haneli kanal
$tv->goToChannel(271);
echo "Kanal 271'e gidildi" . PHP_EOL;
sleep(2);

// d) Tekil num metodlarıyla (0-9)
$tv->num1();
$tv->num0();
echo "Kanal 10'a gidildi" . PHP_EOL;

// ============================================================
// 6. NAVİGASYON
// ============================================================
echo PHP_EOL . "--- Navigasyon ---" . PHP_EOL;

$tv->menu();
echo "Menü açıldı" . PHP_EOL;
sleep(1);

$tv->navDown();
$tv->navDown();
$tv->navRight();
$tv->ok();
echo "Navigasyon: Aşağı x2 → Sağ → Tamam" . PHP_EOL;
sleep(1);

$tv->back();
echo "Geri" . PHP_EOL;
sleep(1);

$tv->exit();
echo "Çıkış" . PHP_EOL;

// ============================================================
// 7. MENÜ / FONKSİYON TUŞLARI
// ============================================================
echo PHP_EOL . "--- Fonksiyon Tuşları ---" . PHP_EOL;

$tv->guide();
echo "Rehber açıldı" . PHP_EOL;
sleep(2);
$tv->exit();

$tv->info();
echo "Bilgi ekranı açıldı" . PHP_EOL;
sleep(2);
$tv->back();

$tv->tools();
echo "Araçlar menüsü açıldı" . PHP_EOL;
sleep(2);
$tv->exit();

$tv->subtitle();
echo "Altyazı menüsü açıldı" . PHP_EOL;
sleep(2);
$tv->back();

$tv->audio();
echo "Ses dili menüsü açıldı" . PHP_EOL;
sleep(2);
$tv->back();

// ============================================================
// 8. RENK TUŞLARI
// ============================================================
echo PHP_EOL . "--- Renk Tuşları ---" . PHP_EOL;

$tv->colorRed();    echo "Kırmızı" . PHP_EOL;   sleep(1);
$tv->colorGreen();  echo "Yeşil"   . PHP_EOL;   sleep(1);
$tv->colorYellow(); echo "Sarı"    . PHP_EOL;   sleep(1);
$tv->colorBlue();   echo "Mavi"    . PHP_EOL;   sleep(1);
$tv->exit();

// ============================================================
// 9. MEDYA OYNATICI TUŞLARI
// (USB / harici kaynak oynatılırken kullanın)
// ============================================================
echo PHP_EOL . "--- Medya Kontrol ---" . PHP_EOL;

$tv->play();        echo "Oynat"         . PHP_EOL; sleep(2);
$tv->pause();       echo "Duraklat"      . PHP_EOL; sleep(2);
$tv->play();        echo "Devam et"      . PHP_EOL; sleep(2);
$tv->fastForward(); echo "İleri sar"     . PHP_EOL; sleep(3);
$tv->rewind();      echo "Geri sar"      . PHP_EOL; sleep(3);
$tv->nextTrack();   echo "Sonraki"       . PHP_EOL; sleep(2);
$tv->previousTrack(); echo "Önceki"      . PHP_EOL; sleep(2);
$tv->stop();        echo "Durdur"        . PHP_EOL;
$tv->record();      echo "Kayıt başladı" . PHP_EOL; sleep(3);
$tv->stop();        echo "Kayıt durdu"   . PHP_EOL;

// ============================================================
// 10. KLAVİYE GİRİŞİ
// (Netflix / YouTube gibi uygulama arama kutusu açıkken)
// ============================================================
echo PHP_EOL . "--- Klavye Girişi ---" . PHP_EOL;

// Tek karakter
$tv->keyboardInput('A');
echo "Tek harf gönderildi: A" . PHP_EOL;
sleep(1);

// Boşluk
$tv->keyboardInput(' ');
echo "Boşluk gönderildi" . PHP_EOL;
sleep(1);

// Metin olarak gönder
$tv->typeText("Breaking Bad");
echo "Metin gönderildi: 'Breaking Bad'" . PHP_EOL;
sleep(1);

$tv->navDown();
$tv->ok();
echo "Arama sonucuna gidildi" . PHP_EOL;

// ============================================================
// 11. @ INTERNET TUŞU
// ============================================================
echo PHP_EOL . "--- İnternet ---" . PHP_EOL;
$tv->internet();
echo "İnternet / Smart Hub açıldı" . PHP_EOL;
sleep(3);
$tv->exit();

// ============================================================
// 12. TELETEKSTİ AÇ / KAPAT
// ============================================================
echo PHP_EOL . "--- Teletekst ---" . PHP_EOL;
$tv->ttx();
echo "Teletekst açıldı" . PHP_EOL;
sleep(2);
$tv->exit();
echo "Teletekst kapandı" . PHP_EOL;

// ============================================================
// 13. FAVORİLER
// ============================================================
echo PHP_EOL . "--- Favoriler ---" . PHP_EOL;
$tv->fav();
echo "Favoriler listesi açıldı" . PHP_EOL;
sleep(2);
$tv->exit();

// ============================================================
// 14. GÜÇ KAPATMA
// Yalnızca kapatabilir, açmak için fiziksel tuşa basmanız gerekir.
// ============================================================
// echo PHP_EOL . "TV kapatılıyor..." . PHP_EOL;
// $tv->powerOff();

echo PHP_EOL . "=== Test tamamlandı ===" . PHP_EOL;