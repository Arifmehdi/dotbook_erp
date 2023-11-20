//=========================== dotbook new calculator start ===========================
var input = document.getElementById("dtbCalcResult");
var td = document.querySelectorAll(".dtb-calc-box td");
var length = td.length;
var lastInputIsSymbol = false; // Initialize a flag to track the last input type

td.forEach(index => {
    index.addEventListener("click", () => {
        var num = index.innerHTML;
        if (isSymbol(num) && lastInputIsSymbol) {
            return; // Return early if the last input was a symbol
        }

        if (num == "=") {
            var value = input.value;
            if (isSymbol(value[value.length - 1])) {
                value = value.slice(0, -1); // Remove the last symbol if it's an operator
            }
            var sum = eval(value);
            input.value = sum;
        } else if (num == "C") {
            input.value = "";
        } else if (num == "CE") {
            var value = input.value;
            value = value.substr(0, value.length - 1);
            input.value = value;
        } else {
            input.value += num;
        }

        lastInputIsSymbol = isSymbol(num); // Update the flag based on the current input
    });
});

document.addEventListener("keydown", function (e) {
    if (e.key === "=" || e.key === "Enter") {
        var value = input.value;
        if (isSymbol(value[value.length - 1])) {
            value = value.slice(0, -1); // Remove the last symbol if it's an operator
        }
        var sum = eval(value);
        input.value = sum;
    } else if (e.key === "Escape") {
        input.value = "";
    } else if (
        (e.key >= "0" && e.key <= "9") ||
        e.key === "+" ||
        e.key === "-" ||
        e.key === "*" ||
        e.key === "/"
    ) {
        if (isSymbol(e.key) && lastInputIsSymbol) {
            return; // Return early if the last input was a symbol
        }
        input.value += e.key;
        lastInputIsSymbol = isSymbol(e.key); // Update the flag based on the current input
    } else if (e.key === "Backspace") {
        var value = input.value;
        value = value.substr(0, value.length - 1);
        input.value = value;
    } else {
        return false;
    }
});

function isSymbol(input) {
    var symbols = ["+", "-", "*", "/"]; // Define an array of symbols
    return symbols.includes(input); // Check if the input is a symbol
}
//=========================== dotbook new calculator end ===========================