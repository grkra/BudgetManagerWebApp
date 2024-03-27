let showDetails = false;

$(".income, .expense").on("mouseenter", function () {
    changeColor(this.firstElementChild.lastElementChild);

})
$(".income, .expense").on("mouseleave", function () {
    changeColor(this.firstElementChild.lastElementChild);
})

$(".income, .expense").on("click", function () {
    showHideDetails(this.getAttribute("id"));
    this.firstElementChild.lastElementChild.classList.toggle("rotated");
})

function changeColor(arrow) {
    arrow.classList.toggle("red");
}

function showHideDetails(category) {
    document.querySelector("#" + category + "-details").classList.toggle("d-none");
}