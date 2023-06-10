var songs = [],
    consumptionChart,
    consumptionDatasets = [];

// functions
function usageData() {
    fetch('api.php?action=getUsagesData')
        .then(response => response.json())
        .then(data => {
            $('.electricity-usage').html(data.electricity);
            $('.water-usage').html(data.water);
            $('.gas-usage').html(data.gas);
            if ($('.indoor-temperature span.text').length) {
                $('.indoor-temperature span.text').html(data.temperature + ' °C');
                $('.indoor-temperature div.progress-bar').css('width', (data.temperature) + '%');
            }
            if ($('.indoor-humidity span.text').length) {
                $('.indoor-humidity span.text').html(data.humidity + ' %');
                $('.indoor-humidity div.progress-bar').css('width', data.humidity + '%');
            }
        });
}

function deviceStatus(deviceID, status) {
    var formData = new FormData();
    formData.append('id', deviceID);
    if (status === '0') {
        status = false;
    }
    formData.append('status', status ? 1 : 0);
    fetch('api.php?action=setDeviceStatus', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            usageData();
        });
}

function setConfig(name, config, value) {
    var formData = new FormData();
    formData.append('name', name);
    formData.append('config', config);
    formData.append('value', value);
    fetch('api.php?action=setConfig', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            usageData();
        });
}

function setRoomData(roomID, config, value) {
    var formData = new FormData();
    formData.append('id', roomID);
    formData.append('config', config);
    formData.append('value', value);
    fetch('api.php?action=setRoomData', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
        });
}

function setDeviceData(deviceID, config, value) {
    var formData = new FormData();
    formData.append('id', deviceID);
    formData.append('config', config);
    formData.append('value', value);
    fetch('api.php?action=setDeviceData', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
        });
}

if ($('.electricity-usage').length) {
    setInterval(usageData, 5000);
}

$(function () {
    $('.btn-delete').click(function () {
        if (!confirm('Are you sure you want to delete this?')) {
            return false;
        }
    });
    $('#sidebarToggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-toggle');
        if (window.innerWidth > 768) {
            document.cookie = 'sidebar-toggle=' + ($('body').hasClass('sidebar-toggle') ? 1 : 0);
        } else {
            document.cookie = 'sidebar-toggle=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        }
    });

    if ($('#consumptionChart').length) {
        var consumptionPeriod = typeof $('#consumptionChart').data('period') !== 'undefined' ? $('#consumptionChart').data('period') : 'year';
        fetch('api.php?action=getConsumptionData&period=' + consumptionPeriod)
            .then(response => response.json())
            .then(data => {
                window.consumptionDatasets = data.data;
                var consumptionChartCanvas = document.getElementById('consumptionChart').getContext('2d');
                consumptionChart = new Chart(consumptionChartCanvas, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: window.consumptionDatasets
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
    }

    if ($('.security-cam[data-cams]').length) {
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
        fetch('api.php?action=getSongs')
            .then(response => response.json())
            .then(data => {
                window.songs = data.data;
            });
        $('.speaker .btn-play').click(function (e) {
            e.preventDefault();
            var deviceID = $(this).data('id');
            $(this).find('i').toggleClass('fa-play-circle fa-pause-circle');
            setDeviceData(deviceID, 'status', ($(this).find('i').hasClass('fa-pause-circle') ? '1' : '0'));
        });
        $('.speaker .btn-mute').click(function (e) {
            e.preventDefault();
            $(this).find('i').toggleClass('fa-volume-up fa-volume-mute');
            if ($('.volume-range').val() != 0) {
                $('.volume-range').data('val', $('.volume-range').val());
            }
            if ($(this).find('i').hasClass('fa-volume-mute')) {
                $('.volume-range').val(0).change();
            } else {
                $('.volume-range').val($('.volume-range').data('val')).change();
            }
        });
        $('.speaker .volume-range').on('change', function () {
            var deviceID = $(this).data('id');
            setDeviceData(deviceID, 'volume', $(this).val());
            if ($(this).val() == 0) {
                $('.speaker .btn-mute i').removeClass('fa-volume-up').addClass('fa-volume-mute');
            } else {
                $('.speaker .btn-mute i').removeClass('fa-volume-mute').addClass('fa-volume-up');
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
            var deviceID = $(this).data('device');
            $(this).toggleClass('bg-secondary btn-sh');
            $(this).find('i').toggleClass('fa-lock-open fa-lock');
            if ($(this).hasClass('bg-secondary')) {
                $(this).find('span').text('Locked');
            } else {
                $(this).find('span').text('Unlocked');
            }
            setConfig(deviceID, 'status', $(this).hasClass('bg-secondary') ? '1' : '0');
        });
    }

    $('.outdoor-lock .btn').click(function (e) {
        e.preventDefault();
        $(this).toggleClass('btn-sh btn-secondary');
        $(this).closest('.outdoor-lock').find('i').toggleClass('fa-lock-open fa-lock');
        if (!$(this).hasClass('btn-sh')) {
            $(this).closest('.outdoor-lock').find('span.lock-text').text('Locked');
            setConfig('outdoor_lock', 'status', '1');
        } else {
            $(this).closest('.outdoor-lock').find('span.lock-text').text('Unlocked');
            setConfig('outdoor_lock', 'status', '0');
        }
    });

});