import time
import pprint
from SmartTV import SmartTV

# ============================================================
# BAĞLANTI KURULUMU
# TV'nizin IP adresini girin (router'dan veya TV ağ ayarlarından)
# ============================================================
tv = SmartTV("tv_ip", 8080)

print("=== Smart TV Test Dosyası ===\n")

# ============================================================
# 1. MEVCUT KANAL BİLGİSİ
# ============================================================
print("--- Mevcut Kanal ---")
current = tv.get_current_channel()
pprint.pprint(current)
# Çıktı örneği: {'no': 12, 'sT': 'DTV', 'src': 'Sat'}

# ============================================================
# 2. KANAL LİSTESİNİ ÇEK
# ============================================================
print("\n--- Kanal Listesi (ilk 5 kanal) ---")
channels = tv.get_channel_list()
if "error" not in channels:
    data = channels.get("response", channels)
    if isinstance(data, list):
        for ch in data[:5]:
            now_playing = ch.get("now", {}).get("n", "Bilgi yok")
            print(f"#{ch['no']} {ch.get('n', '?')} → Şu an: {now_playing}")

# ============================================================
# 3. ZAMANLANMIŞ GÖREVLER
# ============================================================
print("\n--- Zamanlanmış Görevler ---")
tasks = tv.get_scheduled_tasks()
response = tasks.get("response", [])
if not response:
    print("Zamanlanmış görev yok.")
else:
    pprint.pprint(tasks)

# ============================================================
# 4. SES KONTROLLERİ
# ============================================================
print("\n--- Ses Kontrol ---")
tv.volume_up();   print("Ses +1");         time.sleep(1)
tv.volume_up();   print("Ses +1");         time.sleep(1)
tv.volume_down(); print("Ses -1");         time.sleep(1)
tv.mute();        print("Sessiz açık/kapalı"); time.sleep(1)
tv.mute();        print("Sessiz açık/kapalı")

# ============================================================
# 5. KANAL DEĞİŞTİRME
# ============================================================
print("\n--- Kanal Değiştirme ---")

# a) Zapping
tv.channel_up();   print("Kanal +1"); time.sleep(1)
tv.channel_down(); print("Kanal -1"); time.sleep(1)

# b) go_to_channel() ile doğrudan git
tv.go_to_channel(1);   print("Kanal 1'e gidildi (TRT1 HD)");          time.sleep(2)
tv.go_to_channel(271); print("Kanal 271'e gidildi (Discovery)");       time.sleep(2)

# c) Tekil num metodlarıyla
tv.num1(); tv.num0(); print("Kanal 10'a gidildi (CNN TÜRK HD)")

# d) num(n) yardımcı metodu
tv.num(5); print("5 rakamı gönderildi")

# ============================================================
# 6. NAVİGASYON
# ============================================================
print("\n--- Navigasyon ---")
tv.menu();      print("Menü açıldı");              time.sleep(1)
tv.nav_down();  tv.nav_down();  tv.nav_right()
tv.ok();        print("Aşağı x2 → Sağ → Tamam");  time.sleep(1)
tv.back();      print("Geri");                     time.sleep(1)
tv.exit();      print("Çıkış")

# ============================================================
# 7. MENÜ / FONKSİYON TUŞLARI
# ============================================================
print("\n--- Fonksiyon Tuşları ---")
tv.guide();    print("Rehber açıldı");           time.sleep(2); tv.exit()
tv.info();     print("Bilgi ekranı açıldı");     time.sleep(2); tv.back()
tv.tools();    print("Araçlar menüsü açıldı");   time.sleep(2); tv.exit()
tv.subtitle(); print("Altyazı menüsü açıldı");   time.sleep(2); tv.back()
tv.audio();    print("Ses dili menüsü açıldı");  time.sleep(2); tv.back()
tv.source();   print("Kaynak menüsü açıldı");    time.sleep(2); tv.exit()

# ============================================================
# 8. RENK TUŞLARI
# ============================================================
print("\n--- Renk Tuşları ---")
tv.color_red();    print("Kırmızı"); time.sleep(1)
tv.color_green();  print("Yeşil");   time.sleep(1)
tv.color_yellow(); print("Sarı");    time.sleep(1)
tv.color_blue();   print("Mavi");    time.sleep(1)
tv.exit()

# ============================================================
# 9. MEDYA OYNATICI TUŞLARI
# (USB / harici kaynak oynatılırken kullanın)
# ============================================================
print("\n--- Medya Kontrol ---")
tv.play();           print("Oynat");         time.sleep(2)
tv.pause();          print("Duraklat");      time.sleep(2)
tv.play();           print("Devam et");      time.sleep(2)
tv.fast_forward();   print("İleri sar");     time.sleep(3)
tv.rewind();         print("Geri sar");      time.sleep(3)
tv.next_track();     print("Sonraki");       time.sleep(2)
tv.previous_track(); print("Önceki");        time.sleep(2)
tv.stop();           print("Durdur")
tv.record();         print("Kayıt başladı"); time.sleep(3)
tv.stop();           print("Kayıt durdu")

# ============================================================
# 10. KLAVİYE GİRİŞİ
# (Netflix / YouTube gibi uygulama arama kutusu açıkken)
# ============================================================
print("\n--- Klavye Girişi ---")
tv.keyboard_input("A"); print("Tek harf gönderildi: A"); time.sleep(1)
tv.keyboard_input(" "); print("Boşluk gönderildi");      time.sleep(1)

tv.type_text("Breaking Bad")
print("Metin gönderildi: 'Breaking Bad'");                time.sleep(1)

tv.nav_down(); tv.ok(); print("Arama sonucuna gidildi")

# ============================================================
# 11. İNTERNET TUŞU (Smart Hub / Browser)
# ============================================================
print("\n--- İnternet ---")
tv.internet(); print("İnternet / Smart Hub açıldı"); time.sleep(3)
tv.exit()

# ============================================================
# 12. TELETEKSTİ AÇ / KAPAT
# ============================================================
print("\n--- Teletekst ---")
tv.ttx();  print("Teletekst açıldı"); time.sleep(2)
tv.exit(); print("Teletekst kapandı")

# ============================================================
# 13. FAVORİLER
# ============================================================
print("\n--- Favoriler ---")
tv.fav();  print("Favoriler listesi açıldı"); time.sleep(2)
tv.exit()

# ============================================================
# 14. GÜÇ KAPATMA
# Yalnızca kapatabilir, açmak için fiziksel tuşa basmanız gerekir.
# ============================================================
# print("\nTV kapatılıyor...")
# tv.power_off()

# ============================================================
# CONTEXT MANAGER ile kullanım örneği
# ============================================================
# with SmartTV("tv_ip") as tv:
#     tv.volume_up()
#     tv.go_to_channel(1)

tv.disconnect()
print("\n=== Test tamamlandı ===")