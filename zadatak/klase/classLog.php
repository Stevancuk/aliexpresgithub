<?php
class Log{
    public static function upisiLog($imeDatoteke, $stringZaUpis){
        $stringZaUpis=date("d.m.Y H:i:s", time())." - $stringZaUpis\r\n";
        $f=fopen($imeDatoteke, "a");
        fwrite($f, $stringZaUpis);
        fclose($f);
    }
}
?>