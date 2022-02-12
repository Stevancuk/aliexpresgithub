$(document).ready(function(){
    $("#odobri").click(function(){
        let id=$("#idTermina").val();
        $.post("ajax/ajax_admin.php?funkcija=odobri", {idTermina: id}, function(response){
            if(response=="1")
            {
                document.location.assign("admin.php");
                alert("Uspesno snimljeni podaci.");
            }
            else $("#odgovor1").html(response);
        })
    });

    $("#obrisi").click(function(){
        let id=$("#idTermina").val();
        if(id=="0")
        {
            $("#odgovor1").html("Niste izabrali termin za brisanje");
            return false;
        }
        if(!confirm("Da li ste sigurni da želite da izbrišete termin?")) return false;
        
        $.post("ajax/ajax_admin.php?funkcija=obrisi", {nekiId: id}, function(response){
            if(response=="Uspešno izbrisan termin.")
            {
                document.location.assign("admin.php");
                alert(response);
                ocistiTermin();
            }
            else $("#odgovor1").html(response); 
        })
    });

    $("#log").change(function(){
        let fajl=$(this).val();
        if(fajl=="0")
        {
            $("#divlogovi").html("");
            return false;
        }
        $.post("ajax/ajax_admin.php?funkcija=log",{fajl:fajl}, function(response){
            $("#divlogovi").html(response);
        })
    })
})

function ocistiTermin(){
    $("#termin").val("");
}