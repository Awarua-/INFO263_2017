var divBy10ButNot3 = function(value) {
    if (value % 10 === 0 && value % 3 !== 0) {
        return true;
    }
    return false;
}

console.log(divBy10ButNot3(20));
console.log(divBy10ButNot3(30));
