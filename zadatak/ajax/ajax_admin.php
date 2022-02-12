<?php
session_start();
require_once("../klase/classBaza.php");
require_once("../klase/classLog.php");
$db=new Baza();
if(!$db->connect())
{
    echo "Baza trenutno nije dostupna!";
    exit();
}
$funkcija=$_GET['funkcija'];

if($funkcija=='odobri')
{
    //radi samo za prvog (gornjeg) u listi termina na admin stranici, nije dobro! (razlog je: isti nazivi za dugmad, isti naziv za hidden input)
    //probaj da uradis bez dugmad (<a href=...>Odobri</>)
    if(isset($_POST['idTermina'])){
        $id=$_POST['idTermina'];
        $upit="UPDATE termini SET odobren=1 WHERE id=$id";
        $rez=$db->query($upit);
        $upit1="SELECT * FROM termini WHERE id=$id";
        $rez1=$db->query($upit1);
        $red=$db->fetch_object($rez1);
        Log::upisiLog("../logovi/odobreni.txt", "Odobren termin za korisnika: {$red->ime}, datuma: {$red->datum}, vakcina: {$red->idVakcine}.");
        echo "1";
    }
}

if($funkcija=="obrisi")
{
    $id=$_POST['nekiId'];
    $upit="DELETE FROM termini WHERE id=$id";
    $db->query($upit);
    if($db->affected_rows()==1)
        echo "Uspešno izbrisan termin.";  
    else
        echo "Greška prilikom brisanja termina:<br>".$db->error();
}

if($funkcija=="log")
{
    $fajl=$_POST['fajl'];
    if(file_exists("../logovi/".$fajl))
    {
        $odgovor=file_get_contents("../logovi/".$fajl);
        $odgovor=str_replace("\r\n", "<br>", $odgovor);
        echo $odgovor;
    }
    else
        echo "Fajl ne postoji!!!!";
}
?>