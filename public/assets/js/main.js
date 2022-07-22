const nightBtn=document.querySelector(".js-night");
const body=document.querySelector(".js-body");


// Is NightMode enabled in localstorage ?,
// If yes, enabled it in the current page
const nightMode  = localStorage.getItem('night-activated')=== 'true';
if (nightMode) {
    body.classList.add("night-activated");
}

nightBtn.addEventListener('click', function (){
    console.log("click");


    if(body.classList.contains("night-activated")){
        body.classList.remove("night-activated");
        localStorage.removeItem('night-activated');

    }else{
        body.classList.add("night-activated");
        localStorage.setItem('night-activated', 'true');
    }
});
