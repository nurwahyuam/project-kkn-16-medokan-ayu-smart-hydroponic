const canvas = document.getElementById("tempChart");

if (canvas) {

    const ctx = canvas.getContext("2d");

    new Chart(ctx, {
        type: "line",

        data: {
            labels: ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00"],

            datasets: [{
                label: "Temperature",

                data: [26, 27, 28, 27, 29, 28],

                borderColor: "#58C472",

                backgroundColor: "rgba(88,196,114,.15)",

                fill: true,

                tension: .4
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }

    });

}