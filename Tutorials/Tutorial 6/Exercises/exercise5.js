var isInList = function(item, list) {
    for (var thing of list) {
        if (thing === item) {
            return true;
        }
    }
    return false;
}

console.log(isInList("Dog", ["Cat", "Rabbit", "Dog"]));
console.log(isInList("Bird", ["Cat", "Rabbit", "Dog"]));


var addUp = function(values) {
    var result = 0,
        index = 0;

    while (index < values.length) {
        result += values[index];
        index++;
    }

    return result;
}

console.log(addUp([1, 2, 3, 4, 5]));
