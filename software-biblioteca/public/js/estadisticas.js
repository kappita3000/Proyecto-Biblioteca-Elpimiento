document.addEventListener('DOMContentLoaded', function() {
    // Libros Más Prestados
    var ctxLibrosMasPrestados = document.getElementById('librosMasPrestadosChart').getContext('2d');
    var librosMasPrestadosLabels = window.librosMasPrestadosLabels || [];
    var librosMasPrestadosData = window.librosMasPrestadosData || [];

    var librosMasPrestadosChart = new Chart(ctxLibrosMasPrestados, {
        type: 'bar',
        data: {
            labels: librosMasPrestadosLabels,
            datasets: [{
                label: 'Veces Prestado',
                data: librosMasPrestadosData,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45,
                        callback: function(value, index, values) {
                            let label = librosMasPrestadosLabels[index];
                            return label.length > 15 ? label.substr(0, 15) + '...' : label;
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    max: Math.max(...librosMasPrestadosData) + 1
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });

    // Préstamos por Mes
    var ctxPrestamosPorMes = document.getElementById('prestamosPorMesChart').getContext('2d');
    var prestamosPorMesLabels = window.prestamosPorMesLabels || [];
    var prestamosPorMesData = window.prestamosPorMesData || [];
    var prestamosPorMesChart = new Chart(ctxPrestamosPorMes, {
        type: 'line',
        data: {
            labels: prestamosPorMesLabels,
            datasets: [{
                label: 'Préstamos',
                data: prestamosPorMesData,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false,
            }]
        }
    });

    // Tasa de Devoluciones
    var ctxTasaDeDevoluciones = document.getElementById('tasaDeDevolucionesChart').getContext('2d');
    var tasaDeDevolucionesData = window.tasaDeDevolucionesData || [0, 0];
    var tasaDeDevolucionesChart = new Chart(ctxTasaDeDevoluciones, {
        type: 'pie',
        data: {
            labels: ['Devuelto', 'No Devuelto'],
            datasets: [{
                data: tasaDeDevolucionesData,
                backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'],
            }]
        }
    });

    // Distribución de Préstamos por Género
    var ctxPrestamosPorGenero = document.getElementById('prestamosPorGeneroChart').getContext('2d');
    var prestamosPorGeneroLabels = window.prestamosPorGeneroLabels || [];
    var prestamosPorGeneroData = window.prestamosPorGeneroData || [];
    var prestamosPorGeneroChart = new Chart(ctxPrestamosPorGenero, {
        type: 'bar',
        data: {
            labels: prestamosPorGeneroLabels,
            datasets: [{
                label: 'Préstamos',
                data: prestamosPorGeneroData,
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
            }]
        }
    });

    // Préstamos por Usuarios Registrados vs No Registrados
    var ctxPrestamosPorTipoUsuario = document.getElementById('prestamosPorTipoUsuarioChart').getContext('2d');
    var prestamosPorTipoUsuarioData = window.prestamosPorTipoUsuarioData || [0, 0];
    var prestamosPorTipoUsuarioChart = new Chart(ctxPrestamosPorTipoUsuario, {
        type: 'bar',
        data: {
            labels: ['Registrados', 'No Registrados'],
            datasets: [{
                label: 'Préstamos',
                data: prestamosPorTipoUsuarioData,
                backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)'],
            }]
        }
    });

    // Préstamos por Día de la Semana
    var ctxPrestamosPorDia = document.getElementById('prestamosPorDiaChart').getContext('2d');
    var prestamosPorDiaLabels = window.prestamosPorDiaLabels || [];
    var prestamosPorDiaData = window.prestamosPorDiaData || [];
    var prestamosPorDiaChart = new Chart(ctxPrestamosPorDia, {
        type: 'bar',
        data: {
            labels: prestamosPorDiaLabels,
            datasets: [{
                label: 'Préstamos',
                data: prestamosPorDiaData,
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...prestamosPorDiaData) + 5
                }
            }
        }
    });
});
