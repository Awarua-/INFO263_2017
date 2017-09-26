document.addEventListener("DOMContentLoaded", (event) => {
    getItems();

    document.getElementById("addItemButton").addEventListener("click", (event) => {
        let item = `name=${document.getElementById("itemName").value}&quantity=${document.getElementById("itemQuantity").value}&price=${document.getElementById("itemPrice").value}`;
        let req = new XMLHttpRequest();
        req.addEventListener("load", getItems);
        req.open("POST", "addItem.php", true);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(item);
    });

    document.getElementById("removeItemButton").addEventListener("click", (event) => {
        let item = `name=${document.getElementById("removeItemText").value}`;
        let req = new XMLHttpRequest();
        req.addEventListener("load", getItems);
        req.open("POST", "removeItem.php", true);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(item);
    });

    document.getElementById("clearItemsButton").addEventListener("click", (event) => {
        let req = new XMLHttpRequest();
        req.addEventListener("load", getItems);
        req.open("GET", "clearItems.php", true);
        req.send();
    });
});

getItems = () => {
    let req = new XMLHttpRequest();
    req.addEventListener("load", populate_items)
    req.open("GET", "getItems.php");
    req.send();
}

populate_items = (event) => {
    data = JSON.parse(event.target.response);
    let items = document.getElementById("items");
    let html = ""
    for (let item of data) {
        html += `<p>Name: ${item.name}, Quantity: ${item.quantity}, Price per item: $${item.price}, Cost: $${(item.quantity * item.price).toFixed(2)}</p>`;
    }
    items.innerHTML = html;
}
