let newExpense;
let date = $('#date').val();
let category;

let categoryLimit;
let expensesSum;
let balance;

$('input[name="value"]').on("change", function () {
    newExpense = this.value.length === 0 ? undefined : this.value;

    updateBalance();
});

$('input[name=date]').on("change", async function () {
    date = this.value;

    await updateExpensesSum();
    updateBalance();
})

$('input[name="category"]').on("change", async function () {
    category = this.value;

    await updateCategoryLimit();
    await updateExpensesSum();
    updateBalance();
})

async function updateCategoryLimit() {
    categoryLimit = await getLimit(category);
    showCategoryLimit();
}

async function updateExpensesSum() {
    if (date.length != 0 && category != undefined) {
        expensesSum = await getExpenses(category, date);
    }
    showExpensesSum();
}

function updateBalance() {
    if (date.length != 0 && category != undefined && newExpense != undefined) {
        balance = categoryLimit - expensesSum - newExpense;
    }
    showBalance();
}

function showCategoryLimit() {
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
}

function showExpensesSum() {
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
}

function showBalance() {
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
}