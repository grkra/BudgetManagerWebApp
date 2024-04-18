function validateIncomes() {
    /**
     * Validate add income category
     */
    $('#formAddIncomeCategory').validate({
        rules: {
            category: {
                required: true,
                remote: '/properties/validate-income-category'
            }
        },
        messages: {
            category: {
                remote: 'Taka kategoria już istnieje.'
            }
        }
    });

    /**
     * Validate change income category
     */
    $('#formChangeIncomeCategory').validate({
        rules: {
            category: {
                required: true,
                remote: '/properties/validate-income-category'
            },
            oldCategory: {
                required: true,
            }
        },
        messages: {
            category: {
                remote: 'Taka kategoria już istnieje.'
            }
        }
    });
}

function validateExpenses() {
    /**
     * Validate add expense category
     */
    $('#formAddExpenseCategory').validate({
        rules: {
            category: {
                required: true,
                remote: '/properties/validate-expense-category'
            }
        },
        messages: {
            category: {
                remote: 'Taka kategoria już istnieje.'
            }
        }
    });

    /**
     * Validate change income category
     */
    $('#formChangeExpenseCategory').validate({
        rules: {
            category: {
                required: true,
                remote: '/properties/validate-expense-category'
            },
            oldCategory: {
                required: true,
            }
        },
        messages: {
            category: {
                remote: 'Taka kategoria już istnieje.'
            }
        }
    });
}

function validatePaymentMethods() {
    /**
     * Validate add payment method
     */
    $('#formAddPaymentMethod').validate({
        rules: {
            method: {
                required: true,
                remote: '/properties/validate-payment-method'
            }
        },
        messages: {
            method: {
                remote: 'Taka metoda już istnieje.'
            }
        }
    });

    /**
     * Validate change payment method
     */
    $('#formChangePaymentMethod').validate({
        rules: {
            method: {
                required: true,
                remote: '/properties/validate-payment-method'
            },
            oldMethod: {
                required: true,
            }
        },
        messages: {
            method: {
                remote: 'Taka kategoria już istnieje.'
            }
        }
    });
}

function validateUser() {
}

$(document).ready(function () {
    validateIncomes();
    validateExpenses();
    validatePaymentMethods();
    validateUser();
});

