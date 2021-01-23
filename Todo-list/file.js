    console.log("LOADED CONTENT!");
function myFunction() 
{
    var x = document.getElementById("mynav");
    if (x.className === "mynavbar col-md-12 col-sm-12 col-lg-12") {
        x.className += " responsive";
    }
}