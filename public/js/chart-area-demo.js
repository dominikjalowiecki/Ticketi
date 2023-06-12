// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily =
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#292b2c";

const revenueChartTypeSelect = document.querySelector(
    "#revenueChartTypeSelect"
);

// Area Chart Example
var ctxRevenue = document.getElementById("myAreaChart");
var myLineChart;

revenueChartTypeSelect.addEventListener("change", async function (e) {
    // myLineChart.destroy();
    // generateRevenueChart();

    try {
        var res = await fetchRevenueStats();
    } catch (error) {
        error.message; // 'An error has occurred: 404'
        return;
    }

    const { dates, income } = res;

    const maxIncome = Math.max.apply(null, income);

    myLineChart.data.labels = dates;
    myLineChart.data.datasets[0].data = income;

    // myLineChart.options.scales.xAxes[0].ticks.maxTicksLimit = dates.length;
    myLineChart.options.scales.yAxes[0].ticks.max =
        250 + 250 * Math.floor(maxIncome / 250);

    // myLineChart.options.scales.xAxes.ticks.maxTicksLimit

    // myLineChart.data.datasets[0].data[2] = 50; // Would update the first dataset's value of 'March' to be 50
    myLineChart.update(); // Calling update now animates the position of March from 90 to 50.
});

async function fetchRevenueStats() {
    const urlRevenue =
        ctxRevenue.dataset.url + "/" + revenueChartTypeSelect.value;

    const response = await fetch(urlRevenue, {
        headers: {
            Accept: "application/json",
        },
    });

    if (!response.ok) {
        const message = `An error has occured: ${response.status}`;
        throw new Error(message);
    }

    const revenue = await response.json();

    // return revenue;
    return {
        dates: revenue.map((revenue) => revenue.date),
        income: revenue.map((revenue) => revenue.income),
    };
}

async function generateRevenueChart() {
    try {
        var res = await fetchRevenueStats();
    } catch (error) {
        error.message; // 'An error has occurred: 404'
        return;
    }

    const { dates, income } = res;
    // const dates = revenue.map((revenue) => revenue.date);
    // const income = revenue.map((revenue) => revenue.income);

    // names[names.length - 1] = "Total orders";

    // const values = categories.map((category) =>
    //     parseInt(category.orders_count)
    // );

    const maxIncome = Math.max.apply(null, income);

    myLineChart = new Chart(ctxRevenue, {
        type: "line",
        data: {
            labels: dates,
            datasets: [
                {
                    label: "Revenue",
                    lineTension: 0,
                    backgroundColor: "rgba(2,117,216,0.2)",
                    borderColor: "rgba(2,117,216,1)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(2,117,216,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(2,117,216,1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: income,
                },
            ],
        },
        options: {
            scales: {
                xAxes: [
                    {
                        time: {
                            unit: "date",
                        },
                        gridLines: {
                            display: false,
                        },
                        ticks: {
                            maxTicksLimit: 31,
                            // maxTicksLimit: dates.length,
                        },
                    },
                ],
                yAxes: [
                    {
                        ticks: {
                            min: 0,
                            max: 250 + 250 * Math.floor(maxIncome / 250),
                            maxTicksLimit: 5,
                            callback: function (value, index, values) {
                                return value.toLocaleString("en-US", {
                                    style: "currency",
                                    currency: "EUR",
                                });
                            },
                        },
                        gridLines: {
                            color: "rgba(0, 0, 0, .125)",
                        },
                    },
                ],
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var label =
                            data.datasets[tooltipItem.datasetIndex].label || "";

                        if (label) {
                            label += ": ";
                        }

                        label += tooltipItem.yLabel.toLocaleString("en-US", {
                            style: "currency",
                            currency: "EUR",
                        });

                        return label;
                    },
                },
            },
            legend: {
                display: false,
            },
        },
    });
}

generateRevenueChart();
