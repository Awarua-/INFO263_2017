let person = {
    firstName: "Dion",
    lastName: "Woolley",
    address: "1 Ilam road",
    age: 22,
    degree: "Software Engineering"
}

let printPerson = function(person) {
    console.log(`${person.firstName} ${person.lastName} is ${person.age} of age, lives at ${person.address} and studies ${person.degree}`);
}

printPerson();
