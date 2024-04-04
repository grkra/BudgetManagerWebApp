const customDateModal = new bootstrap.Modal($("#customDateModal"));
const periodSelect = document.querySelector("#select");

for (let i = 0; i < periodSelect.length; i++) {
    const date = new Date();
    let month, year, startDate, endDate;
    switch (i) {
        case 0:
            month = date.getMonth();
            year = date.getFullYear();
            startDate = new Date(year, month, 2).
                toISOString().substring(0, 10);
            endDate = new Date(year, month + 1, 1).
                toISOString().substring(0, 10);
            periodSelect[i].value = `thisMonth|${startDate}|${endDate}|${periodSelect[i].innerText}`;
            break;
        case 1:
            month = date.getMonth() - 1;
            year = date.getFullYear();
            startDate = new Date(year, month, 2).
                toISOString().substring(0, 10);
            endDate = new Date(year, month + 1, 1).
                toISOString().substring(0, 10);
            periodSelect[i].value = `lastMonth|${startDate}|${endDate}|${periodSelect[i].innerText}`;
            break;
        case 2:
            year = date.getFullYear();
            periodSelect[i].value = `thisYear|${year}-01-01|${year}-12-31|${periodSelect[i].innerText}`;
            break;
        case 3:
            periodSelect[i].value = "custom|1990-01-01|1990-01-01|${periodSelect[i].innerText}";
            break;
        default:
            break;
    }
}

$("#select").on("change", function () {
    const selectedValue = this.value;
    // if (selectedValue === "custom") {
    //     customDateModal.show();
    // };
    this.form.submit();
})




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