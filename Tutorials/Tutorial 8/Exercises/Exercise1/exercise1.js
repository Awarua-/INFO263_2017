document.addEventListener("DOMContentLoaded", (event) => {

    document.getElementById("go").addEventListener("click", (event) => {

    });
});

let populate_content = (content) => {
    document.getElementById("content").innerHTML = content.target.response;
};
