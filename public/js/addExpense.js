let date = $('#date').val();
let category;

$('input[name="category"]').on("change", async function () {
    category = this.value;
    console.log(category);
})

$('#date').on("change", function () {
    date = this.value;
    console.log(date);
})