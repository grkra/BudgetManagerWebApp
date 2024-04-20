function parseFloatSeparator(string) {
    string = string.replaceAll(" ", "");
    string = string.replaceAll(",", ".");
    return string;
}

const incomes = Array.from($(".income"));
const incomesValues = incomes.map(row => Number(parseFloatSeparator(row.lastElementChild.innerText)));
const incomesLabels = incomes.map(row => row.firstElementChild.innerText);
const incomesTotal = incomesValues.reduce((accumulator, currentValue) => { return accumulator + currentValue }, 0);

const expenses = Array.from($(".expense"));
const expensesValues = expenses.map(row => Number(parseFloatSeparator(row.lastElementChild.innerText)));
const expensesLabels = expenses.map(row => row.firstElementChild.innerText);
const expensesTotal = expensesValues.reduce((accumulator, currentValue) => { return accumulator + currentValue }, 0);

const maxTotal = Math.max(incomesTotal, expensesTotal);

// INCOMES
const dataIncomes = {
    labels: incomesLabels,
    datasets: [
        {
            label: 'Przychody',
            data: incomesValues,
            weight: 40,
        }
    ],
}

const IncomeDataLabels = {
    id: 'IncomeDataLabels',

    beforeDatasetsDraw(chart, args, pluginOptions) {
        const { ctx, data, chartArea: { top, bottom, left, right, width, height } } = chart;

        data.datasets[0].data.forEach((datapoint, index) => {
            chart.getDatasetMeta(0).data[index].y = height / 2 + top;
            chart.getDatasetMeta(0).data[index].x = right - 5;
        })

        ctx.beginPath();
        ctx.moveTo(right, top);
        ctx.lineTo(right, bottom);
        ctx.stroke();

    },

    afterDatasetsDraw(chart, args, pluginOptions) {
        const { ctx, data, chartArea: { top, bottom, left, right, width, height } } = chart;

        const x = chart.getDatasetMeta(0).data[0].x;
        const y = chart.getDatasetMeta(0).data[0].y;

        const outerRadius = chart.getDatasetMeta(0).data[0].outerRadius;

        ctx.save();
        ctx.font = 'bold 14px sans-serif';
        ctx.fillStyle = 'black';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        ctx.translate(x, y);

        const minPartDrawAllData = 0.3
        let smallValuesCounter = 1;

        data.datasets[0].data.forEach((datapoint, index) => {
            const startAngle = chart.getDatasetMeta(0).data[index].startAngle;
            const endAngle = chart.getDatasetMeta(0).data[index].endAngle;

            const xDiagonalLineStart = outerRadius * Math.cos((startAngle + endAngle) / 2);
            const yDiagonalLineStart = outerRadius * Math.sin((startAngle + endAngle) / 2);

            if (datapoint / maxTotal < minPartDrawAllData) {
                const diagonalLineLengthModifier = 0.5 * smallValuesCounter - 0.3;
                smallValuesCounter++

                const xDiagonalLineEnd = xDiagonalLineStart - 25 * diagonalLineLengthModifier;

                const yDiagonalLineEnd = yDiagonalLineStart >= 0 ? yDiagonalLineStart + 25 * diagonalLineLengthModifier : yDiagonalLineStart - 25 * diagonalLineLengthModifier;

                const xhorizontalLineEnd = -25;

                const textWidth = ctx.measureText(chart.data.labels[index]).width;

                const textWidthPosition = -textWidth / 1.9;

                ctx.beginPath();
                ctx.moveTo(xDiagonalLineStart, yDiagonalLineStart);
                ctx.lineTo(xDiagonalLineEnd, yDiagonalLineEnd);
                ctx.lineTo(xDiagonalLineEnd + xhorizontalLineEnd, yDiagonalLineEnd);
                ctx.stroke();

                ctx.fillText(chart.data.labels[index], xDiagonalLineEnd + xhorizontalLineEnd + textWidthPosition, yDiagonalLineEnd);
            } else {
                const x = outerRadius / 2 * Math.cos((startAngle + endAngle) / 2);
                const y = outerRadius / 2 * Math.sin((startAngle + endAngle) / 2);
                ctx.fillText(chart.data.labels[index], x, y);
                ctx.fillText(datapoint.toLocaleString("pl-PL", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                    useGrouping: "always"
                }), x, y + 15);
            }
        });

        ctx.restore();
    }
}

const configIncomes = {
    type: 'pie',
    plugins: [IncomeDataLabels],
    data: dataIncomes,
    options: {
        layout: {
            padding: {
                left: 150,
                right: 0,
                top: 50,
                bottom: 50,
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false },
            title: {
                display: true,
                text: 'PRZYCHODY',
                position: 'top',
                align: 'center',
                color: 'black',
                font: {
                    size: 14,
                    weight: 'bold',
                    family: 'sans-serif',
                }
            },
        },
        aspectRatio: 0.5,
        borderWidth: 1,
        circumference: (ctx) => {
            return incomesTotal / maxTotal * 180;
        },
        rotation: 180,
        radius: 150,
    },
}

// EXPENSES
const dataExpenses = {
    labels: expensesLabels,
    datasets: [
        {
            label: 'Wydatki',
            data: expensesValues,
            weight: 40,
        }
    ],
}

const ExpenseDataLabels = {
    id: 'ExpenseDataLabels',

    beforeDatasetsDraw(chart, args, pluginOptions) {
        const { ctx, data, chartArea: { top, bottom, left, right, width, height } } = chart;

        data.datasets[0].data.forEach((datapoint, index) => {
            chart.getDatasetMeta(0).data[index].y = height / 2 + top;
            chart.getDatasetMeta(0).data[index].x = 5;
        })
    },

    afterDatasetsDraw(chart, args, pluginOptions) {
        const { ctx, data, chartArea: { top, bottom, left, right, width, height } } = chart;

        const x = chart.getDatasetMeta(0).data[0].x;
        const y = chart.getDatasetMeta(0).data[0].y;

        const outerRadius = chart.getDatasetMeta(0).data[0].outerRadius;

        ctx.save();
        ctx.font = 'bold 14px sans-serif';
        ctx.fillStyle = 'black';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        ctx.translate(x, y);

        const minPartDrawAllData = 0.3
        let smallValuesCounter = 1;

        data.datasets[0].data.forEach((datapoint, index) => {
            const startAngle = chart.getDatasetMeta(0).data[index].startAngle;
            const endAngle = chart.getDatasetMeta(0).data[index].endAngle;

            const xDiagonalLineStart = outerRadius * Math.cos((startAngle + endAngle) / 2);
            const yDiagonalLineStart = outerRadius * Math.sin((startAngle + endAngle) / 2);

            if (datapoint / maxTotal < minPartDrawAllData) {
                const diagonalLineLengthModifier = 0.5 * smallValuesCounter - 0.3;
                smallValuesCounter++


                const xDiagonalLineEnd = xDiagonalLineStart + 25 * diagonalLineLengthModifier;

                const yDiagonalLineEnd = yDiagonalLineStart >= 0 ? yDiagonalLineStart + 25 * diagonalLineLengthModifier : yDiagonalLineStart - 25 * diagonalLineLengthModifier;

                const xhorizontalLineEnd = 25;

                const textWidth = ctx.measureText(chart.data.labels[index]).width;

                const textWidthPosition = textWidth / 1.9;

                ctx.beginPath();
                ctx.moveTo(xDiagonalLineStart, yDiagonalLineStart);
                ctx.lineTo(xDiagonalLineEnd, yDiagonalLineEnd);
                ctx.lineTo(xDiagonalLineEnd + xhorizontalLineEnd, yDiagonalLineEnd);
                ctx.stroke();

                ctx.fillText(chart.data.labels[index], xDiagonalLineEnd + xhorizontalLineEnd + textWidthPosition, yDiagonalLineEnd);
            } else {
                const x = outerRadius / 2 * Math.cos((startAngle + endAngle) / 2);
                const y = outerRadius / 2 * Math.sin((startAngle + endAngle) / 2);
                ctx.fillText(chart.data.labels[index], x, y);
                ctx.fillText(datapoint.toLocaleString("pl-PL", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                    useGrouping: "always"
                }), x, y + 15);
            }
        });

        ctx.restore();
    }
}

const configExpenses = {
    type: 'pie',
    plugins: [ExpenseDataLabels],
    data: dataExpenses,
    options: {
        layout: {
            padding: {
                left: 0,
                right: 150,
                top: 50,
                bottom: 50,
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false },
            title: {
                display: true,
                text: 'WYDATKI',
                position: 'top',
                align: 'center',
                color: 'black',
                font: {
                    size: 14,
                    weight: 'bold',
                    family: 'sans-serif'
                }
            },
        },
        aspectRatio: 0.5,
        borderWidth: 1,
        circumference: (ctx) => {
            return expensesTotal / maxTotal * 180;
        },
        radius: 150,
    },
}

// CHARTS
const incomeChart = incomesTotal > 0 && new Chart(
    document.getElementById('incomesChart'), configIncomes);
const expenseChart = expensesTotal > 0 && new Chart(
    document.getElementById('expensesChart'), configExpenses);

// BALANCE
const balance = document.querySelector("#balance");
let initialBalance = 0;
const finalBalance = incomesTotal - expensesTotal;

if (finalBalance >= 0) {
    balance.classList.add("text-success");
} else {
    balance.classList.add("text-danger");
}

const balanceInterval = setInterval(countBalance, 1)
function countBalance() {
    initialBalance = initialBalance + (finalBalance / 50);
    balance.innerText = initialBalance.toLocaleString("pl-PL", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: "always"
    });

    if (Math.abs(initialBalance) >= Math.abs(finalBalance)) {
        clearInterval(balanceInterval);
        balance.innerText = finalBalance.toLocaleString("pl-PL", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            useGrouping: "always"
        });
    }
}