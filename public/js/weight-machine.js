const SCALE_API_URL = window.SCALE_API;
console.log(SCALE_API_URL);

async function getWeight() {
    return await fetch(SCALE_API_URL, {
        mode: 'cors'
    })
        .then(data => data.json())
        .then(final => final.data);
}

function addWeightMachine(button, inputField) {
    const mButton = document.getElementById(button);
    mButton.element = document.getElementById(inputField);
    mButton.addEventListener('click', insertVehicleWeight, false);
}

function insertVehicleWeight(e) {
    e.preventDefault();
    let target = e.currentTarget.element;
    $.ajax({
        url: SCALE_API_URL,
        type: 'GET',
        crossDomain: true,
        success: function ({ data }) {
            target.value = data;
        }
    });
}

function insertVehicleWeightDynamic(e) {
    e.preventDefault();
    let target = e.currentTarget.element;
    setInterval(() => {
        $.ajax({
            url: SCALE_API_URL,
            type: 'GET',
            crossDomain: true,
            success: function ({ data }) {
                target.value = data;
            }
        });
    }, 1000);
}
