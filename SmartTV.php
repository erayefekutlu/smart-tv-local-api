<?php
require 'vendor/autoload.php';
use WebSocket\Client;

class SmartTV
{
    private $ip;
    private $port;
    private $client = null;
    private $commandId = 1;

    public function __construct($ip, $port = 8080)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    private function connect()
    {
        if ($this->client !== null)
            return;

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
                'crypto_method' => STREAM_CRYPTO_METHOD_ANY_CLIENT,
                'ciphers' => 'DEFAULT:@SECLEVEL=0'
            ]
        ]);

        try {
            $this->client = new Client("wss://{$this->ip}:{$this->port}/", [
                'context' => $context,
                'timeout' => 5,
                'headers' => [
                    'Origin' => "https://{$this->ip}:{$this->port}",
                    'Sec-WebSocket-Protocol' => 'ArWebsocket',
                    'User-Agent' => 'okhttp/3.12.1',
                    'Accept-Encoding' => 'gzip'
                ]
            ]);
        } catch (\Exception $e) {
            throw new \Exception("TV Bağlantı Hatası: " . $e->getMessage());
        }
    }

    private function sendCommand($command, $arguments = [])
    {
        $this->connect();

        $payload = [
            "commandId" => $this->commandId++,
            "command" => $command,
            "arguments" => $arguments
        ];

        try {
            $this->client->text(json_encode($payload));
            $response = $this->client->receive();
            return json_decode($response, true);
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    private function sendRcKey($key)
    {
        return $this->sendCommand("on-rc-input", ["key" => $key]);
    }

    // =========================================================
    // SES KONTROLLERİ
    // =========================================================

    public function volumeUp()
    {
        return $this->sendRcKey("RC_VOLUME_UP");
    }
    public function volumeDown()
    {
        return $this->sendRcKey("RC_VOLUME_DOWN");
    }
    public function mute()
    {
        return $this->sendRcKey("RC_MUTE");
    }

    // =========================================================
    // KANAL KONTROLLERİ
    // =========================================================

    public function channelUp()
    {
        return $this->sendRcKey("RC_CHANNEL_UP");
    }
    public function channelDown()
    {
        return $this->sendRcKey("RC_CHANNEL_DOWN");
    }

    // =========================================================
    // NAVİGASYON TUŞLARI
    // =========================================================

    public function navUp()
    {
        return $this->sendRcKey("RC_NAV_UP");
    }
    public function navDown()
    {
        return $this->sendRcKey("RC_NAV_DOWN");
    }
    public function navLeft()
    {
        return $this->sendRcKey("RC_NAV_LEFT");
    }
    public function navRight()
    {
        return $this->sendRcKey("RC_NAV_RIGHT");
    }
    public function ok()
    {
        return $this->sendRcKey("RC_OK");
    }
    public function back()
    {
        return $this->sendRcKey("RC_BACK");
    }
    public function exit()
    {
        return $this->sendRcKey("RC_EXIT");
    }

    // =========================================================
    // MENÜ TUŞLARI
    // =========================================================

    public function menu()
    {
        return $this->sendRcKey("RC_MENU");
    }
    public function guide()
    {
        return $this->sendRcKey("RC_GUIDE");
    }
    public function info()
    {
        return $this->sendRcKey("RC_INFO");
    }
    public function tools()
    {
        return $this->sendRcKey("RC_TOOLS");
    }
    public function internet()
    {
        return $this->sendRcKey("RC_INTERNET");
    }
    public function fav()
    {
        return $this->sendRcKey("RC_FAV");
    }
    public function ttx()
    {
        return $this->sendRcKey("RC_TTX");
    }   // Teletekst
    public function audio()
    {
        return $this->sendRcKey("RC_AUDIO");
    }
    public function subtitle()
    {
        return $this->sendRcKey("RC_SUBTITLE");
    }
    public function source()
    {
        return $this->sendRcKey("RC_SOURCE");
    }

    // =========================================================
    // RAKAM TUŞLARI (0-9)
    // =========================================================

    public function num($n)
    {
        $n = (int) $n;
        if ($n < 0 || $n > 9)
            throw new \InvalidArgumentException("Geçerli rakam: 0-9");
        return $this->sendRcKey("RC_NUM_{$n}");
    }

    public function num0()
    {
        return $this->sendRcKey("RC_NUM_0");
    }
    public function num1()
    {
        return $this->sendRcKey("RC_NUM_1");
    }
    public function num2()
    {
        return $this->sendRcKey("RC_NUM_2");
    }
    public function num3()
    {
        return $this->sendRcKey("RC_NUM_3");
    }
    public function num4()
    {
        return $this->sendRcKey("RC_NUM_4");
    }
    public function num5()
    {
        return $this->sendRcKey("RC_NUM_5");
    }
    public function num6()
    {
        return $this->sendRcKey("RC_NUM_6");
    }
    public function num7()
    {
        return $this->sendRcKey("RC_NUM_7");
    }
    public function num8()
    {
        return $this->sendRcKey("RC_NUM_8");
    }
    public function num9()
    {
        return $this->sendRcKey("RC_NUM_9");
    }

    // =========================================================
    // RENK TUŞLARI
    // =========================================================

    public function colorRed()
    {
        return $this->sendRcKey("RC_COLOR_RED");
    }
    public function colorGreen()
    {
        return $this->sendRcKey("RC_COLOR_GREEN");
    }
    public function colorYellow()
    {
        return $this->sendRcKey("RC_COLOR_YELLOW");
    }
    public function colorBlue()
    {
        return $this->sendRcKey("RC_COLOR_BLUE");
    }

    // =========================================================
    // MEDYA / OYNATICI TUŞLARI
    // =========================================================

    public function record()
    {
        return $this->sendRcKey("RC_RECORD");
    }
    public function play()
    {
        return $this->sendRcKey("RC_PLAYER_PLAY");
    }
    public function pause()
    {
        return $this->sendRcKey("RC_PLAYER_PAUSE");
    }
    public function stop()
    {
        return $this->sendRcKey("RC_PLAYER_STOP");
    }
    public function previousTrack()
    {
        return $this->sendRcKey("RC_PLAYER_PREVIOUS");
    }
    public function nextTrack()
    {
        return $this->sendRcKey("RC_PLAYER_NEXT");
    }
    public function rewind()
    {
        return $this->sendRcKey("RC_PLAYER_FR");
    }   // Fast Rewind
    public function fastForward()
    {
        return $this->sendRcKey("RC_PLAYER_FF");
    }   // Fast Forward

    // =========================================================
    // GÜÇ
    // =========================================================

    public function powerOff()
    {
        return $this->sendRcKey("RC_POWER");
    }

    // =========================================================
    // KLAVİYE GİRİŞİ
    // Tek karakter veya boşluk gönderir.
    // =========================================================

    public function keyboardInput($key)
    {
        return $this->sendCommand("on-keyboard-input", ["key" => $key]);
    }

    /**
     * Bir metni klavye üzerinden karakter karakter gönderir.
     * Örnek: $tv->typeText("klav metni");
     */
    public function typeText($text)
    {
        $results = [];
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($chars as $char) {
            $results[] = $this->keyboardInput($char);
        }
        return $results;
    }

    // =========================================================
    // BİLGİ SORGULAMA
    // =========================================================

    /**
     * O anda izlenen kanalı döndürür.
     * Yanıt örneği: {"no":12,"sT":"DTV","src":"Sat"}
     */
    public function getCurrentChannel()
    {
        return $this->sendCommand("get-current-channel", (object) []);
    }

    /**
     * Tüm kanal listesini döndürür.
     * Her kanal: no, sT, src, n (isim), now (şimdiki program), next (sonraki program)
     */
    public function getChannelList()
    {
        return $this->sendCommand("get-channel-list", (object) []);
    }

    /**
     * Zamanlanmış görevleri (kayıtlar vb.) döndürür.
     */
    public function getScheduledTasks()
    {
        return $this->sendCommand("get-scheduled-tasks", (object) []);
    }

    // =========================================================
    // YARDIMCI: Kanal numarasına doğrudan geç
    // Rakamları sırayla RC_NUM_x olarak gönderir.
    // =========================================================

    public function goToChannel($channelNo)
    {
        $digits = str_split((string) (int) $channelNo);
        $results = [];
        foreach ($digits as $digit) {
            $results[] = $this->sendRcKey("RC_NUM_{$digit}");
        }
        return $results;
    }

    // =========================================================
    // SOKETE TEMİZ KAPAT
    // =========================================================

    public function __destruct()
    {
        if ($this->client !== null) {
            $this->client->close();
        }
    }
}