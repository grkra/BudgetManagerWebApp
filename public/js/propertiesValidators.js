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

    /**
     * Validate delete income category
     */
    $('#formDeleteIncomeCategory').validate({
        rules: {
            oldCategory: {
                required: true,
            }
        },
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

    /**
     * Validate delete expense category
     */
    $('#formDeleteExpenseCategory').validate({
        rules: {
            oldCategory: {
                required: true,
            }
        },
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

    /**
     * Validate delete payment method
     */
    $('#formDeletePaymentMethod').validate({
        rules: {
            oldMethod: {
                required: true,
            }
        },
    });
}

function validateUser() {
    /**
     * Validate change user data
     */
    $('#formChangeUserData').validate({
        rules: {
            name: 'required',
            email: {
                required: true,
                email: true,
                remote: {
                    url: '/account/validate-email',
                    data: {
                        ignore_id: function () {
                            return getUserID();
                        }
                    }
                }
            },
            password: {
                minlength: 6,
                validPassword: true
            },
        },
        messages: {
            email: {
                remote: 'Konto dla tego adresu email już istnieje.'
            }
        }
    });
}

$(document).ready(function () {
    validateIncomes();
    validateExpenses();
    validatePaymentMethods();
    validateUser();
});