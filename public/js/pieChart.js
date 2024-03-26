const ctx = document.getElementById('balanceChart');

const incomes = Array.from($(".income"));
const incomesValues = incomes.map(row => Number(row.lastElementChild.innerText));
const incomesLabels = incomes.map(row => row.firstElementChild.innerText);
const incomesTotal = incomesValues.reduce((accumulator, currentValue) => accumulator + currentValue);

const expenses = Array.from($(".expense"));
const expensesValues = expenses.map(row => Number(row.lastElementChild.innerText));
const expensesLabels = expenses.map(row => row.firstElementChild.innerText);
const expensesTotal = expensesValues.reduce((accumulator, currentValue) => accumulator + currentValue)

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
        })

        ctx.beginPath();
        ctx.moveTo(right, top);
        ctx.lineTo(right, bottom);
        ctx.stroke();

    },

    afterDatasetsDraw(chart, args, pluginOptions) {
        const { ctx, data, chartArea: { top, bottom, left, right, width, height } } = chart;

        // console.log(chart.getDatasetMeta(0).data[0]);

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

            if (datapoint / expensesTotal < minPartDrawAllData) {
                const diagonalLineLengthModifier = 0.5 * smallValuesCounter - 0.3;
                smallValuesCounter++

                // kierunek linii skośnej (lewo/prawo)
                // const xLine = x >= halfWidth ? x + 25 : x - 25; 
                const xDiagonalLineEnd = xDiagonalLineStart - 25 * diagonalLineLengthModifier;

                // kierunek linii skośnej (góra/dół)
                // const yLine = yPos >= halfHeight ? yPos + 25 : yPos - 25;
                const yDiagonalLineEnd = yDiagonalLineStart >= 0 ? yDiagonalLineStart + 25 * diagonalLineLengthModifier : yDiagonalLineStart - 25 * diagonalLineLengthModifier;

                // kierunek linii poziomej (lewo/prawo)
                // const extraLine = x >= halfWidth ? 25 : -25; 
                const xhorizontalLineEnd = -25;

                // szerokość tekstu
                // const textWidth = ctx.measureText(datapoint.toLocaleString()).width;
                const textWidth = ctx.measureText(chart.data.labels[index]).width;

                // kierunek odsunięcia etykiety od linii poziomej (lewo/prawo)
                // const textWidthPosition = x >= halfWidth ? textWidth : -textWidth;
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
                ctx.fillText(datapoint.toLocaleString(), x, y + 15);
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

            if (datapoint / expensesTotal < minPartDrawAllData) {
                const diagonalLineLengthModifier = 0.5 * smallValuesCounter - 0.3;
                smallValuesCounter++

                // kierunek linii skośnej (lewo/prawo)
                // const xLine = x >= halfWidth ? x + 25 : x - 25; 
                const xDiagonalLineEnd = xDiagonalLineStart + 25 * diagonalLineLengthModifier;

                // kierunek linii skośnej (góra/dół)
                // const yLine = yPos >= halfHeight ? yPos + 25 : yPos - 25;
                const yDiagonalLineEnd = yDiagonalLineStart >= 0 ? yDiagonalLineStart + 25 * diagonalLineLengthModifier : yDiagonalLineStart - 25 * diagonalLineLengthModifier;

                // kierunek linii poziomej (lewo/prawo)
                // const extraLine = x >= halfWidth ? 25 : -25; 
                const xhorizontalLineEnd = 25;

                // szerokość tekstu
                // const textWidth = ctx.measureText(datapoint.toLocaleString()).width;
                const textWidth = ctx.measureText(chart.data.labels[index]).width;

                // kierunek odsunięcia etykiety od linii poziomej (lewo/prawo)
                // const textWidthPosition = x >= halfWidth ? textWidth : -textWidth;
                const textWidthPosition = textWidth / 1.9;

                ctx.beginPath();
                ctx.moveTo(xDiagonalLineStart, yDiagonalLineStart);
                ctx.lineTo(xDiagonalLineEnd, yDiagonalLineEnd);
                ctx.lineTo(xDiagonalLineEnd + xhorizontalLineEnd, yDiagonalLineEnd);
                ctx.stroke();

                // ctx.fillText(datapoint.toLocaleString(), xLine + extraLine + textWidthPosition, yLine);      //WYŚWIETLA WARTOŚĆ

                ctx.fillText(chart.data.labels[index], xDiagonalLineEnd + xhorizontalLineEnd + textWidthPosition, yDiagonalLineEnd);
            } else {
                const x = outerRadius / 2 * Math.cos((startAngle + endAngle) / 2);
                const y = outerRadius / 2 * Math.sin((startAngle + endAngle) / 2);
                ctx.fillText(chart.data.labels[index], x, y);
                ctx.fillText(datapoint.toLocaleString(), x, y + 15);
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
        rotation: 0,
    },
}

// CHARTS
const incomeChart = new Chart(
    document.getElementById('incomesChart'), configIncomes);
const expenseChart = new Chart(
    document.getElementById('expensesChart'), configExpenses);