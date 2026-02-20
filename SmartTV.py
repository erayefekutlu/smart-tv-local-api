"""
smart_tv.py - Smart TV WebSocket Kontrol Kütüphanesi
Gereksinim: pip install websocket-client
"""

import json
import ssl
import websocket


class SmartTV:
    def __init__(self, ip: str, port: int = 8080):
        self.ip = ip
        self.port = port
        self._ws = None
        self._command_id = 1

    # =========================================================
    # BAĞLANTI
    # =========================================================

    def connect(self):
        if self._ws is not None:
            return

        ssl_opts = {
            "cert_reqs": ssl.CERT_NONE,
            "check_hostname": False,
        }

        headers = [
            f"Origin: https://{self.ip}:{self.port}",
            "Sec-WebSocket-Protocol: ArWebsocket",
            "User-Agent: okhttp/3.12.1",
            "Accept-Encoding: gzip",
        ]

        try:
            self._ws = websocket.WebSocket(sslopt=ssl_opts)
            self._ws.connect(
                f"wss://{self.ip}:{self.port}/",
                header=headers,
                timeout=5,
            )
        except Exception as e:
            self._ws = None
            raise ConnectionError(f"TV Bağlantı Hatası: {e}")

    def disconnect(self):
        if self._ws is not None:
            try:
                self._ws.close()
            except Exception:
                pass
            self._ws = None

    def __enter__(self):
        self.connect()
        return self

    def __exit__(self, *args):
        self.disconnect()

    def __del__(self):
        self.disconnect()

    # =========================================================
    # KOMUT GÖNDERİCİ
    # =========================================================

    def _send_command(self, command: str, arguments: dict = None) -> dict:
        self.connect()

        if arguments is None:
            arguments = {}

        payload = {
            "commandId": self._command_id,
            "command": command,
            "arguments": arguments,
        }
        self._command_id += 1

        try:
            self._ws.send(json.dumps(payload))
            response = self._ws.recv()
            return json.loads(response)
        except Exception as e:
            return {"error": str(e)}

    def _send_rc_key(self, key: str) -> dict:
        return self._send_command("on-rc-input", {"key": key})

    # =========================================================
    # SES KONTROLLERİ
    # =========================================================

    def volume_up(self):    return self._send_rc_key("RC_VOLUME_UP")
    def volume_down(self):  return self._send_rc_key("RC_VOLUME_DOWN")
    def mute(self):         return self._send_rc_key("RC_MUTE")

    # =========================================================
    # KANAL KONTROLLERİ
    # =========================================================

    def channel_up(self):   return self._send_rc_key("RC_CHANNEL_UP")
    def channel_down(self): return self._send_rc_key("RC_CHANNEL_DOWN")

    # =========================================================
    # NAVİGASYON TUŞLARI
    # =========================================================

    def nav_up(self):    return self._send_rc_key("RC_NAV_UP")
    def nav_down(self):  return self._send_rc_key("RC_NAV_DOWN")
    def nav_left(self):  return self._send_rc_key("RC_NAV_LEFT")
    def nav_right(self): return self._send_rc_key("RC_NAV_RIGHT")
    def ok(self):        return self._send_rc_key("RC_OK")
    def back(self):      return self._send_rc_key("RC_BACK")
    def exit(self):      return self._send_rc_key("RC_EXIT")

    # =========================================================
    # MENÜ TUŞLARI
    # =========================================================

    def menu(self):     return self._send_rc_key("RC_MENU")
    def guide(self):    return self._send_rc_key("RC_GUIDE")
    def info(self):     return self._send_rc_key("RC_INFO")
    def tools(self):    return self._send_rc_key("RC_TOOLS")
    def internet(self): return self._send_rc_key("RC_INTERNET")
    def fav(self):      return self._send_rc_key("RC_FAV")
    def ttx(self):      return self._send_rc_key("RC_TTX")
    def audio(self):    return self._send_rc_key("RC_AUDIO")
    def subtitle(self): return self._send_rc_key("RC_SUBTITLE")
    def source(self):   return self._send_rc_key("RC_SOURCE")

    # =========================================================
    # RAKAM TUŞLARI (0-9)
    # =========================================================

    def num(self, n: int) -> dict:
        if not 0 <= n <= 9:
            raise ValueError("Geçerli rakam: 0-9")
        return self._send_rc_key(f"RC_NUM_{n}")

    def num0(self): return self._send_rc_key("RC_NUM_0")
    def num1(self): return self._send_rc_key("RC_NUM_1")
    def num2(self): return self._send_rc_key("RC_NUM_2")
    def num3(self): return self._send_rc_key("RC_NUM_3")
    def num4(self): return self._send_rc_key("RC_NUM_4")
    def num5(self): return self._send_rc_key("RC_NUM_5")
    def num6(self): return self._send_rc_key("RC_NUM_6")
    def num7(self): return self._send_rc_key("RC_NUM_7")
    def num8(self): return self._send_rc_key("RC_NUM_8")
    def num9(self): return self._send_rc_key("RC_NUM_9")

    # =========================================================
    # RENK TUŞLARI
    # =========================================================

    def color_red(self):    return self._send_rc_key("RC_COLOR_RED")
    def color_green(self):  return self._send_rc_key("RC_COLOR_GREEN")
    def color_yellow(self): return self._send_rc_key("RC_COLOR_YELLOW")
    def color_blue(self):   return self._send_rc_key("RC_COLOR_BLUE")

    # =========================================================
    # MEDYA / OYNATICI TUŞLARI
    # =========================================================

    def record(self):         return self._send_rc_key("RC_RECORD")
    def play(self):           return self._send_rc_key("RC_PLAYER_PLAY")
    def pause(self):          return self._send_rc_key("RC_PLAYER_PAUSE")
    def stop(self):           return self._send_rc_key("RC_PLAYER_STOP")
    def previous_track(self): return self._send_rc_key("RC_PLAYER_PREVIOUS")
    def next_track(self):     return self._send_rc_key("RC_PLAYER_NEXT")
    def rewind(self):         return self._send_rc_key("RC_PLAYER_FR")
    def fast_forward(self):   return self._send_rc_key("RC_PLAYER_FF")

    # =========================================================
    # GÜÇ
    # =========================================================

    def power_off(self): return self._send_rc_key("RC_POWER")

    # =========================================================
    # KLAVİYE GİRİŞİ
    # =========================================================

    def keyboard_input(self, key: str) -> dict:
        """Tek karakter veya boşluk gönderir."""
        return self._send_command("on-keyboard-input", {"key": key})

    def type_text(self, text: str) -> list:
        """Metni klavye üzerinden karakter karakter gönderir."""
        return [self.keyboard_input(char) for char in text]

    # =========================================================
    # BİLGİ SORGULAMA
    # =========================================================

    def get_current_channel(self) -> dict:
        """
        O anda izlenen kanalı döndürür.
        Yanıt: {"no": 12, "sT": "DTV", "src": "Sat"}
        """
        return self._send_command("get-current-channel")

    def get_channel_list(self) -> dict:
        """
        Tüm kanal listesini döndürür.
        Her kanal: no, sT, src, n (isim), now (şimdiki program), next (sonraki)
        """
        return self._send_command("get-channel-list")

    def get_scheduled_tasks(self) -> dict:
        """Zamanlanmış görevleri (kayıtlar vb.) döndürür."""
        return self._send_command("get-scheduled-tasks")

    # =========================================================
    # YARDIMCI: Kanal numarasına doğrudan geç
    # =========================================================

    def go_to_channel(self, channel_no: int) -> list:
        """Rakamları sırayla RC_NUM_x olarak göndererek kanala geçer."""
        return [self._send_rc_key(f"RC_NUM_{d}") for d in str(int(channel_no))]