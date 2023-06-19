// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily =
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#292b2c";

var ctxCategories = document.getElementById("categoriesChart");
const urlCategories = ctxCategories.dataset.url;

async function fetchCategoriesStats() {
    const response = await fetch(urlCategories, {
        headers: {
            Accept: "application/json",
        },
    });

    if (!response.ok) {
        const message = `An error has occured: ${response.status}`;
        throw new Error(message);
    }

    return response.json();
}

fetchCategoriesStats()
    .then((categories) => {
        const names = categories.map((category) => category.category_name);

        names[names.length - 1] = "Total";

        const values = categories.map((category) =>
            parseInt(category.orders_count)
        );

        const maxValue = Math.max.apply(null, values);

        var myLineChart = new Chart(ctxCategories, {
            type: "bar",
            data: {
                labels: names,
                datasets: [
                    {
                        label: "Tickets sold",
                        backgroundColor: "rgba(2,117,216,1)",
                        borderColor: "rgba(2,117,216,1)",
                        data: values,
                    },
                ],
            },
            options: {
                scales: {
                    xAxes: [
                        {
                            gridLines: {
                                display: true,
                            },
                            ticks: {
                                maxTicksLimit: 7,
                            },
                        },
                    ],
                    yAxes: [
                        {
                            ticks: {
                                min: 0,
                                max: 50 + 50 * Math.floor(maxValue / 50),
                                maxTicksLimit: 5,
                            },
                            gridLines: {
                                display: true,
                            },
                        },
                    ],
                },
                legend: {
                    display: true,
                },
            },
        });
    })
    .catch((error) => {
        error.message;
    });
