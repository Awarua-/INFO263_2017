document.addEventListener("DOMContentLoaded", (event) => {
    getItems();

    document.getElementById("addItemButton").addEventListener("click", (event) => {
        let item = `name=${document.getElementById("itemName").value}&quantity=${document.getElementById("itemQuantity").value}&price=${document.getElementById("itemPrice").value}`;
        let req = new XMLHttpRequest();

    });

    document.getElementById("removeItemButton").addEventListener("click", (event) => {
        let item = `name=${document.getElementById("removeItemText").value}`;

    });

    document.getElementById("clearItemsButton").addEventListener("click", (event) => {

    });
});

getItems = () => {
    let req = new XMLHttpRequest();
    req.addEventListener("load", populateItems)
    req.open("GET", "getItems.php");
    req.send();
}

populateItems = (event) => {
    data = JSON.parse(event.target.response);
    let items = document.getElementById("items");
    let html = ""
    for (let item of data) {
        html += `<p>Name: ${item.name}, Quantity: ${item.quantity}, Price per item: $${item.price}, Cost: $${(item.quantity * item.price).toFixed(2)}</p>`;
    }
    items.innerHTML = html;
}
