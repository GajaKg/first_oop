$(document).ready(function(){

for(var i in svoOsoblje){
    $("#pozicija").append("<option value='"+svoOsoblje[i]+"'>"+firstLetterUp(svoOsoblje[i])+"</option>");
}
    
 
});

/** ------------------------------- json data --------------------------- **/
function getServiceData(method, url, bool){
    
    try {

        var xml, result;

        xml = new XMLHttpRequest();

        xml.onreadystatechange = function(){
            if(xml.readyState === 4 && xml.status === 200){
                result = JSON.parse(xml.response);
            }
        }

        xml.open(method, url, bool);
        xml.send();

        return result; 
    
    }
    catch(err){
        console.log(err);
    }

}

var employees = getServiceData("GET", "json/employee_json.php", false);
console.log(employees);

var info = getServiceData("GET", "json/info_json.php", false);
console.log(info);
/**** -----------------------end json data--------------------------------- **/




// new contract form validation
function validate_contract(){
    var procenat = $("#procenat").val();
   
    if (procenat == "" || procenat == undefined || procenat < 0 || procenat > 130){
        error_message("Procenat mora biti u rasponu od 0 do 130.");
        alert("Polje stepen mora biti u rasponu od 1 do 7");
        return false;
    }
}

// new employee form validation
function validate_employee(){
    
    var ime = document.getElementById("first_name").value;
    var prezime = document.getElementById("last_name").value;
    var stepen = document.getElementById("stepen").value;
    
    //if (ime.validity.valueMissing){
    if (ime == "" || ime.length < 3){
        error_message("Unesite polje 'Ime', mora imati minimum tri slova.");
        return false;
    }
    
    //if (prezime.validity.valueMissing){
    if (prezime == "" || prezime.length < 3){
        error_message("Unesite polje 'prezime', mora imati minimum tri slova.");
        return false;
    }
    
    //if (stepen.validity.rangeOverflow || stepen.validity.rangeUnderflow){
    if (stepen == "" || stepen == undefined || stepen < 1 || stepen > 7){
        error_message("Polje stepen mora biti u rasponu od 1 do 7");
        return false;
    }
    
    return true;
    
}




// ----------- helpers
function firstLetterUp(word){
    return word[0].toUpperCase() + word.slice(1);
}

function error_message(msg){
    var div = document.getElementById("validation");
        div.innerHTML = "";
    
    var alertDiv = document.createElement("div");
        alertDiv.setAttribute("class", "alert alert-danger");
        alertDiv.innerHTML = msg;
    
    div.appendChild(alertDiv);
}

// position for new contract input
var vanNastavno = ["direktor", "sekretar", "psiholog", "pedagog", "andragoski asistent", "pedagoski asistent", "bibiliotekar", "administrativni radnik", "rukovodioc racunovodstva"];

var nastavno = ["matematika", "srpski", "engleski", "istorija", "fizika", "geografija", "biologija", "hemija", "fizicko", "nemacki", "tio"];

var radnici = ["pomocni radnik", "domar/lozac", "kuvar", "servirka"];

var svoOsoblje = vanNastavno.concat(nastavno, radnici); //console.log(svoOsoblje);