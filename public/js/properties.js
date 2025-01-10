let selectedPaymentCategoryIndex;

$("#change_expense_category_select").on("change", function () {
    const selectedPaymentMethod = this.value;

    if (selectedPaymentMethod === "") {
        selectedPaymentCategoryIndex = -1;
        hideCategoryLimit();
    } else {
        selectedPaymentCategoryIndex = paymentCategoriesLimits.findIndex(element => element.category === selectedPaymentMethod);

        const categoryLimit = paymentCategoriesLimits[selectedPaymentCategoryIndex].limit;
        showCategoryLimit(categoryLimit);
    }
})

function showCategoryLimit(limit) {
    if (limit === "") {
        $(".category-limit").hide();
        $(".category-no-limit").show();
        $("#change-category-limit-button").show();
        $(".category-limit span:first-child").text("");
    } else {
        $(".category-no-limit").hide();
        $(".category-limit span:first-child").text(
            new Intl.NumberFormat('pl-PL',
                {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            ).format(Number(limit)));
        $(".category-limit").show();
        $("#change-category-limit-button").show();
    }
}

function hideCategoryLimit() {
    $(".category-limit").hide();
    $(".category-no-limit").hide();
    $("#change-category-limit-button").hide();
    $(".category-limit span:first-child").text("");
}

$("#formChangeExpenseCategoryModal").on("submit", async function (event) {
    event.preventDefault();
    const selectedCategory = paymentCategoriesLimits[selectedPaymentCategoryIndex].category;
    const newLimit = this.elements[0].value;

    if ($("#formChangeExpenseCategoryModal").valid()) {
        $("#editCategoryEditLimitModal").modal('hide');

        const data = await sendLimit(selectedCategory, newLimit);

        $("#change-limit-errors ul").empty();

        if (data.success) {
            $("#change-limit-errors").hide();
            $("#change_expense_category_limit_input").val("");

            const newLimit = await getLimit(selectedCategory);

            updateLimitInformation(newLimit);
        } else {
            data.errors.map(element => { $("#change-limit-errors ul").append(`<li><label>${element}</label></li>`) });
            $("#change-limit-errors").show();
        }
    }
})

function updateLimitInformation(newLimit) {
    paymentCategoriesLimits[selectedPaymentCategoryIndex].limit = newLimit;

    $(".category-limit").addClass("text-primary fw-bold");
    showCategoryLimit(newLimit);
    setTimeout(() => { $(".category-limit").removeClass("text-primary fw-bold") }, 5000);
}

