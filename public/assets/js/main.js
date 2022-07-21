const nightBtn=document.querySelector(".js-night");
const body=document.querySelector(".js-body");

console.log("hello there");

nightBtn.addEventListener('click', function (){
    console.log("click");

    if(body.classList.contains("night-activated")){
        body.classList.remove("night-activated");
    }else{
        body.classList.add("night-activated");
    }
});