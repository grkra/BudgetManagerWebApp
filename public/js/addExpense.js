let newExpense;
let date = $('#date').val();
let category;

let categoryLimit;
let expensesSum;
let balance;

$('input[name="value"]').on("change", function () {
    newExpense = this.value.length === 0 ? undefined : this.value;

    if (date.length != 0 && category != undefined && newExpense != undefined) {
        balance = categoryLimit - expensesSum - newExpense;
    }

    if (date.length != 0 && category != undefined && newExpense != undefined) {
        $("#category-balance span:first-child")
            .text(
                new Intl.NumberFormat('pl-PL',
                    {
                        style: 'decimal',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                ).format(Number(balance)));
        if (balance < 0) {
            $("#expenses-balance").removeClass("bg-success-subtle");
            $("#category-balance").removeClass("text-success fw-bold");
            $("#expenses-balance").addClass("bg-danger-subtle");
            $("#category-balance").addClass("text-danger fw-bold");
        } else {
            $("#expenses-balance").removeClass("bg-danger-subtle");
            $("#category-balance").removeClass("text-danger fw-bold");
            $("#expenses-balance").addClass("bg-success-subtle");
            $("#category-balance").addClass("text-success fw-bold");
        }
        $("#category-balance").show();
    } else {
        $("#category-balance").hide();
        $("#category-balance span:first-child")
            .text("");
    }
});

$('#date').on("change", async function () {
    date = this.value;

    if (date.length != 0 && category != undefined) {
        expensesSum = await getExpenses(category, date);
    }

    if (date.length != 0 && category != undefined && newExpense != undefined) {
        balance = categoryLimit - expensesSum - newExpense;
    }

    if (date.length != 0 && category != undefined) {
        $("#category-expenses span:first-child")
            .text(
                new Intl.NumberFormat('pl-PL',
                    {
                        style: 'decimal',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                ).format(Number(expensesSum)));

        $("#category-expenses").show();
    } else {
        $("#category-expenses").hide();
        $("#category-expenses span:first-child")
            .text("");
    }

    if (date.length != 0 && category != undefined && newExpense != undefined) {
        $("#category-balance span:first-child")
            .text(
                new Intl.NumberFormat('pl-PL',
                    {
                        style: 'decimal',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                ).format(Number(balance)));

        $("#category-balance").show();
    } else {
        $("#category-balance").hide();
        $("#category-balance span:first-child")
            .text("");
    }
})

$('input[name="category"]').on("change", async function () {
    category = this.value;
    categoryLimit = await getLimit(category);

    if (date.length != 0 && category != undefined) {
        expensesSum = await getExpenses(category, date);
    }

    if (date.length != 0 && category != undefined && newExpense != undefined) {
        balance = categoryLimit - expensesSum - newExpense;
    }

    if (categoryLimit === null) {
        $("#expenses-balance").hide();
        $("#category-no-limit").show();
        $(".category-limit span:first-child").text("");
    } else {
        $("#category-no-limit").hide();
        $("#category-limit span:first-child").text(
            new Intl.NumberFormat('pl-PL',
                {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            ).format(Number(categoryLimit)));
        $("#expenses-balance").show();
    }

    if (date.length != 0 && category != undefined) {
        $("#category-expenses span:first-child")
            .text(
                new Intl.NumberFormat('pl-PL',
                    {
                        style: 'decimal',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                ).format(Number(expensesSum)));

        $("#category-expenses").show();
    } else {
        $("#category-expenses").hide();
        $("#category-expenses span:first-child")
            .text("");
    }

    if (date.length != 0 && category != undefined && newExpense != undefined) {
        $("#category-balance span:first-child")
            .text(
                new Intl.NumberFormat('pl-PL',
                    {
                        style: 'decimal',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                ).format(Number(balance)));

        $("#category-balance").show();
    } else {
        $("#category-balance").hide();
        $("#category-balance span:first-child")
            .text("");
    }
})

async function getLimit(selectedCategory) {
    try {
        const response = await fetch(`/api/limit/${selectedCategory}`);
        const data = await response.json();
        const categoryLimit = data.category_limit;
        return categoryLimit;
    } catch (error) {
        console.log("Error: " + error);
    }
}

async function getExpenses(selectedCategory, selectedDate) {
    try {
        const response = await fetch(`/api/expenses/${selectedCategory}/${selectedDate}`);
        const data = await response.json();
        const expensesSum = data.sum;
        return expensesSum;
    } catch (error) {
        console.log("Error: " + error);
    }
}