var songs = [
    {
        "cover": "https://i.scdn.co/image/ab67616d0000b273b6d4566db0d12894a1a3b7a2",
        "title": "Undisclosed Desires",
        "artist": "Muse",
        "album": "The Resistance",
        "duration": "3:56",
        "url": "https://open.spotify.com/track/3K4HG9evC7dg3N0R9cYqk4"
    },
    {
        "cover": "https://i.scdn.co/image/ab67616d0000485129b3f99acf4ee06bfa44fa54",
        "title": "September Song",
        "artist": "Anges Obel",
        "album": "Aventine",
        "duration": "3:15",
        "url": "https://open.spotify.com/track/2EuqgpA1cTC95AQUgCcZHk"
    },
    {
        "cover": "https://i.scdn.co/image/ab67616d000048516142f1d46f6d8b804382fb25",
        "title": "Familiar",
        "artist": "Agnes Obel",
        "album": "Citizen of Glass",
        "duration": "3:50",
        "url": "https://open.spotify.com/track/2EWnKuspetOzgfBtmaNZvJ"
    },
    {
        "cover": "https://i.scdn.co/image/ab67616d00004851a807936f0595920c2fdf2130",
        "title": "Lost Day",
        "artist": "Other Lives",
        "album": "For Their Love",
        "duration": "4:02",
        "url": "https://open.spotify.com/track/50XHvARYO6Sz0bxvOOD6oE"
    },
    {
        "cover": "https://i.scdn.co/image/ab67616d00004851aaeb5c9fb6131977995b7f0e",
        "title": "Sweden",
        "artist": "C418",
        "album": "",
        "duration": "",
        "url": "https://open.spotify.com/track/"
    }
]

$(function () {
    $('.btn-delete').click(function () {
        if (!confirm('Are you sure you want to delete this?')) {
            return false;
        }
    });
    $('#sidebarToggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-toggle');
    });

    if ($('#consumptionChart').length) {
        var consumptionDatasets = [
            {
                label: 'Electric',
                data: [Math.floor(Math.random() * 500) + Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100],
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 2,
                fill: false,
                pointRadius: 0,
                pointHoverRadius: 0,
                pointHitRadius: 0,
                pointBorderWidth: 0,
                pointStyle: 'rectRounded'
            }, {
                label: 'Water',
                data: [Math.floor(Math.random() * 500) + Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: false,
                pointRadius: 0,
                pointHoverRadius: 0,
                pointHitRadius: 0,
                pointBorderWidth: 0,
                pointStyle: 'rectRounded'
            }, {
                label: 'Gas',
                data: [Math.floor(Math.random() * 500) + Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100, Math.floor(Math.random() * 500) + 100],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: false,
                pointRadius: 0,
                pointHoverRadius: 0,
                pointHitRadius: 0,
                pointBorderWidth: 0,
                pointStyle: 'rectRounded'
            }
        ];
        var consumptionChartCanvas = document.getElementById('consumptionChart').getContext('2d');
        var consumptionChart = new Chart(consumptionChartCanvas, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: consumptionDatasets
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
        $(window).resize(function () {
            if ($('#consumptionChart').length) {
                consumptionChart.resize();
            }
        });
    }

    if ($('#temperatureChart').length) {
        var temperatureChartCanvas = document.getElementById('temperatureChart').getContext('2d');
        var temperatureChart = new Chart(temperatureChartCanvas, {
            type: 'line',
            data: {
                labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                datasets: [
                    {
                        label: 'Temperature',
                        data: [
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30)
                        ],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: false,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        pointHitRadius: 0,
                        pointBorderWidth: 0,
                        pointStyle: 'rectRounded'
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
        $(window).resize(function () {
            if ($('#temperatureChart').length) {
                temperatureChart.resize();
            }
        });
    }

    if ($('#humidityChart').length) {
        var humidityChartCanvas = document.getElementById('humidityChart').getContext('2d');
        var humidityChart = new Chart(humidityChartCanvas, {
            type: 'line',
            data: {
                labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                datasets: [
                    {
                        label: 'Humidity',
                        data: [
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
                            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30)
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: false,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        pointHitRadius: 0,
                        pointBorderWidth: 0,
                        pointStyle: 'rectRounded'
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
        $(window).resize(function () {
            if ($('#humidityChart').length) {
                humidityChart.resize();
            }
        });
    }

    if ($('.security-cam').length) {
        var securityCams = $('.security-cam').data('cams'),
            securityCam = 0;
        if (securityCams.length > 0) {
            setInterval(function () {
                securityCam++;
                if (securityCam >= securityCams.length) {
                    securityCam = 0;
                }
                $('.security-cam > img').attr('src', securityCams[securityCam].src);
                $('.security-cam span.text-dark').text(securityCams[securityCam].name);
            }, 5000);
        }
    }

    //  Consumption Tab
    $('.card.consumption .nav-item a').on('click', function (e) {
        e.preventDefault();
        $('.card.consumption .nav-item a').removeClass('active').removeClass('btn-sh').addClass('text-dark').removeClass('text-white');
        $(this).addClass('active').addClass('btn-sh').removeClass('text-dark').addClass('text-white');
        var chartTabLabel = $(this).data('consumption');
        var chartTabData = consumptionDatasets.filter(function (dataset) {
            if (chartTabLabel == 'all') {
                return true;
            }
            return dataset.label == chartTabLabel;
        });
        consumptionChart.data.datasets = chartTabData;
        consumptionChart.update();
    });

    // Speaker
    if ($('.speaker').length) {
        $('.speaker .btn-play').click(function (e) {
            e.preventDefault();
            $(this).find('i').toggleClass('fa-play-circle fa-pause-circle');
        });
        $('.speaker .btn-mute').click(function (e) {
            e.preventDefault();
            $(this).find('i').toggleClass('fa-volume-up fa-volume-mute');
            if ($('.volume-range').val() != 0) {
                $('.volume-range').data('val', $('.volume-range').val());
            }
            if ($(this).find('i').hasClass('fa-volume-mute')) {
                $('.volume-range').val(0);
            } else {
                $('.volume-range').val($('.volume-range').data('val'));
            }
        });

        $('.speaker .btn-next, .speaker .btn-prev').click(function (e) {
            e.preventDefault();
            var randomSong = songs[Math.floor(Math.random() * songs.length)];
            $('.speaker .song-title').text(randomSong.title);
            $('.speaker .song-artist').text(randomSong.artist);
            $('.speaker .song-album').text(randomSong.album);
            $('.speaker .song-cover').attr('src', randomSong.cover);

        });
        $('.speaker .btn-random, .speaker .btn-toggle').click(function (e) {
            e.preventDefault();
            $(this).toggleClass('btn-sh');
        });

        // Every 3 min change song if playing.
        setInterval(function () {
            if ($('.speaker .btn-play i').hasClass('fa-pause-circle')) {
                $('.speaker .btn-next').trigger('click');
            }
        }, 180000);
    }

    if ($('.doors-control').length) {
        $('.doors-control ul li .badge').click(function (e) {
            e.preventDefault();
            $(this).toggleClass('bg-success bg-danger');
            $(this).find('i').toggleClass('fa-lock-open fa-lock');
            if ($(this).hasClass('bg-success')) {
                $(this).find('span').text('Locked');
            } else {
                $(this).find('span').text('Unlocked');
            }
        });
    }

});