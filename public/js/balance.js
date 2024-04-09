const periodSelect = document.querySelector("#select");
const originallySelectedPeriod = periodSelect.selectedIndex;

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
    }
}

$("#select").on("change", function (event) {
    const selectedIndex = this.selectedIndex;
    if (selectedIndex === 3) {
        new bootstrap.Modal($("#customDateModal")).show();
    } else {
        this.form.submit();
    }
})

$("#modalSaveButton").on("click", function (event) {
    const startDate = this.form.elements[0].value;
    const endDate = this.form.elements[1].value;

    periodSelect[3].value = `custom|${startDate}|${endDate}|${periodSelect[3].innerText}`;
    periodSelect.form.submit();
})

$("#customDateModal").on("hidden.bs.modal", function (event) {
    periodSelect.selectedIndex = originallySelectedPeriod;
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