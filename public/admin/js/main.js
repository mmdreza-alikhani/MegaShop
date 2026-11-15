function rtl() {
    const body = document.body;
    body.classList.toggle("rtl");
}

window.addEventListener("load", function () {
    const preloader = document.getElementById("preloader");

    if (preloader) {
        preloader.classList.add("fade-out");
    }
});

function dark() {
    const body = document.body;
    body.classList.toggle("dark");

    // Save the theme preference in local storage
    if (body.classList.contains("dark")) {
        localStorage.setItem("theme", "dark");
    } else {
        localStorage.setItem("theme", "light");
    }
}

// Apply the saved theme when the page loads
document.addEventListener("DOMContentLoaded", function () {
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark");
    }
});

$(document).ready(function () {
    $("ul.a-collapse").click(function () {
        if ($(this).hasClass("short")) {
            $(".a-collapse").addClass("short");
            $(this).toggleClass("short");
            $(".side-item-container").addClass("hide animated");
            $("div.side-item-container", this).toggleClass("hide animated");
        } else {
            $(this).toggleClass("short");
            $("div.side-item-container", this).toggleClass("hide animated");
        }
    });
});

/**
 * نمودارهای داشبورد فقط در صفحه‌ای که canvas آن‌ها وجود دارد و کتابخانه Chart.js
 * بارگذاری شده باشد رسم می‌شوند؛ در بقیه صفحات نباید خطای جاوااسکریپت بدهند.
 */
function renderDashboardCharts() {
    if (typeof Chart === "undefined") {
        return;
    }

    const polarCanvas = document.getElementById("myChart2");

    if (polarCanvas) {
        new Chart(polarCanvas, {
            type: "polarArea",
            data: {
                labels: ["Red", "Green", "Yellow", "Grey", "Blue"],
                datasets: [{
                    label: "# of Votes",
                    data: [6, 8, 5, 2, 3],
                    backgroundColor: [
                        "#ff6384",
                        "#4bc0c0",
                        "#ffcd56",
                        "#c9cbcf",
                        "#36a2eb",
                    ]
                }]
            },
            options: {}
        });
    }

    const doughnutCanvas = document.getElementById("myChart4");

    if (doughnutCanvas) {
        new Chart(doughnutCanvas, {
            type: "doughnut",
            data: {
                labels: ["Red", "Green", "Yellow", "Grey", "Blue"],
                datasets: [{
                    label: "# of Votes",
                    data: [6, 8, 5, 2, 3],
                    backgroundColor: [
                        "#ff6384",
                        "#4bc0c0",
                        "#ffcd56",
                        "#c9cbcf",
                        "#36a2eb",
                    ]
                }]
            },
            options: {}
        });
    }

    const mixedCanvas = document.getElementById("myChart5");

    if (mixedCanvas) {
        new Chart(mixedCanvas, {
            type: "bar",
            data: {
                labels: ["Red", "Green", "Yellow", "Grey", "Blue", "Red", "Green", "Yellow", "Grey", "Blue"],
                datasets: [{
                    label: "Bar Dataset",
                    data: [6, 8, 5, 2, 3, 10, 20, 30, 40, 50],
                    backgroundColor: [
                        "#ff6384",
                        "#4bc0c0",
                        "#ffcd56",
                        "#c9cbcf",
                        "#36a2eb",
                        "#ff6384",
                        "#4bc0c0",
                        "#ffcd56",
                        "#c9cbcf",
                        "#36a2eb",
                    ]
                }, {
                    label: "Line Dataset",
                    data: [8, 12, 6, 3, 5, 12, 20, 30, 44, 60],
                    type: "line"
                }],
            },
            options: {}
        });
    }
}

document.addEventListener("DOMContentLoaded", renderDashboardCharts);
