function toggleUnlock(element){
    element.classList.toggle("unlocked")
}

function increaseRoomTemp(){
    var tempElement = document.getElementById("room-temp-value");
    tempElement.innerHTML = parseInt(tempElement.innerText)+1;
}

function decreaseRoomTemp(){
    var tempElement = document.getElementById("room-temp-value");
    tempElement.innerHTML = parseInt(tempElement.innerText)-1;
}

function increaseAirConTemp(){
    var tempElement = document.getElementById("air-con-temp");
    tempElement.innerHTML = parseInt(tempElement.innerText)+1;
}

function decreaseAirConTemp(){
    var tempElement = document.getElementById("air-con-temp");
    tempElement.innerHTML = parseInt(tempElement.innerText)-1;
}

function increaseHumidity(){
    var tempElement = document.getElementById("humidity-value");
    tempElement.innerHTML = parseInt(tempElement.innerText)+1;
}

function decreaseHumidity(){
    var tempElement = document.getElementById("humidity-value");
    tempElement.innerHTML = parseInt(tempElement.innerText)-1;
}


