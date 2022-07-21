const nightBtn=document.querySelector(".js-night");
const body=document.querySelector(".js-body");

nightBtn.addEventListener('click', function (){

    if(body.classList.contains("night-activated")){
        body.classList.remove("night-activated");
    }else{
        body.classList.add("night-activated");
    }
});