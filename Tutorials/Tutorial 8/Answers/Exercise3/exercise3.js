$(document).ready(() => {
    getItems();

    $("#addItemButton").click(() => {
        let item = {name: $("#itemName").val(), quantity: $("#itemQuantity").val(), price: $("#itemPrice").val()}
        $.post("addItem.php", item, (data) => {
            getItems();
        });
    });


    $("#removeItemButton").click(() => {
        let item = {name: $("#removeItemText").val()}
        $.post("removeItem.php", item, (data) => {
            getItems();
        });
    });

    $("#clearItemsButton").click(() => {
        $.get("clearItems.php", (data) => {
            getItems();
        });
    })
});

getItems = () => {
    $.get("getItems.php", (data) => {
        populateItems(data);
    });
}

populateItems = (data) => {
    $("#items").empty();
    for (let item of data) {
        $("#items").append(`<p>Name: ${item.name}, Quantity: ${item.quantity}, Price per item: $${item.price}, Cost: $${(item.quantity * item.price).toFixed(2)}</p>`);
    }

}
