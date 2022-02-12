<?php
function login(){
    if(isset($_SESSION['id']) AND isset($_SESSION['ime']) AND isset($_SESSION['email']) AND isset($_SESSION['status']) )
        return true;
    else
        return false;
}
function odjaviKorisnika(){
    session_unset();
    session_destroy();
}

function prikaziPodatke(){
    echo "<div>";
    echo "Prijavljeni ste kao <b>{$_SESSION['ime']}</b> (<b>{$_SESSION['email']}</b>)<br>";
    echo "<a href='index.php'>Početna</a> | ";
    if($_SESSION['status']=='admin') echo "<a href='admin.php'>Stranica administracije</a> | ";
    echo "<a href='index.php?odjava'>Odjava</a>";
    echo "</div>";
}
?>