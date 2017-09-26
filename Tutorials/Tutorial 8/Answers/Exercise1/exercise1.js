document.addEventListener("DOMContentLoaded", (event) => {

    document.getElementById("go").addEventListener("click", (event) => {
        let req = new XMLHttpRequest();
        req.addEventListener("load", populateContent);
        req.open("GET", "https://api.at.govt.nz/v2/public/realtime/", true);
        req.setRequestHeader("Ocp-Apim-Subscription-Key", "862db80e8ba6459ab8be38d7459404a2");
        req.send();
    });
});

let populateContent = (content) => {
    document.getElementById("content").innerHTML = content.target.response;
};
