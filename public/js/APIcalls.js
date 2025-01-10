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

async function sendLimit(selectedCategory, newLimit) {
    try {
        const response = await fetch('/api/set-limit', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ oldCategory: selectedCategory, limit: newLimit })
        });

        const data = await response.json();

        return data;
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