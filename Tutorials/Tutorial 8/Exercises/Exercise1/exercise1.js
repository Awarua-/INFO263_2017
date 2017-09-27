document.addEventListener("DOMContentLoaded", (event) => {

    document.getElementById("go").addEventListener("click", (event) => {
        var req = new XMLHttpRequest();
    });
});

let populateContent = (content) => {
    document.getElementById("content").innerHTML = content.target.response;
};
