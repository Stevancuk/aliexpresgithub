$(document).ready(function(){
    $("#dugmeZaPrijavu").click(function(){
        let korime=$("#korime").val();
        let lozinka=$("#lozinka").val();
        if(korime=="" || lozinka=="")
        {
            $("#odgovor").html("Svi podaci su obavezni");
            return false;
        }
        $.post("ajax/ajax_index.php?funkcija=prijava", {korime: korime, lozinka:lozinka}, function(response){
            if(response=="1") document.location.assign("index.php");
            else $("#odgovor").html(response);
        })
    });

    $("#dugme1").click(function(){
        let podaci=$("#podaci").val();
        let vakcine=$("#vakcine").val();
        let datum=$("#datum").val();
        let komentar=$("#komentar").val();
        if(podaci=="" || vakcine=="" || datum=="")
        {
            $("#odgovor1").html("Svi podaci su obavezni...!");
            return false;
        }
        
        $.post("ajax/ajax_index.php?funkcija=prijaviTermin", {podaci: podaci, vakcine: vakcine, datum: datum, komentar: komentar}, function(response){
            if(response=="1")
            {
                document.location.assign("index.php");
                alert("Uspesno snimljeni podaci.");
            }
            else $("#odgovor1").html(response);
        })
    });
})
