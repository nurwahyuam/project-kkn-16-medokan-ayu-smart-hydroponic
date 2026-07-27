const canvas = document.getElementById("tempChart");

if (canvas) {

    const ctx = canvas.getContext("2d");

    new Chart(ctx, {

        type:'line',
    data:data,
    options:{
        responsive:true,
        maintainAspectRatio:false

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    display: false

                }

            }

        }

    });

}