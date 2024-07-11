document.addEventListener('DOMContentLoaded', function() {
    var trendSalesCanvas = document.getElementById('trendSalesChart');
    var salesChartCanvas = document.getElementById('salesChart');
    
    var ctxTrendSales = trendSalesCanvas.getContext('2d');
    var ctxSalesChart = salesChartCanvas.getContext('2d');

    var chartDataElement = document.getElementById('chartData');
    var chartData = JSON.parse(chartDataElement.textContent);

    var trendSalesChart = new Chart(ctxTrendSales, {
        type: 'doughnut',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Top 10 Trend Sales'
                }
            }
        }
    });

    var salesChart = new Chart(ctxSalesChart, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Top 10 Trend Sales'
                }
            }
        }
    });
});
