var songs = [],
    consumptionChart,
    consumptionDatasets = [],
    temperatureChart,
    humidityChart;

// functions
function usageData() {
    if ($('.electricity-usage').length === 0 && $('.water-usage').length === 0 && $('.gas-usage').length === 0 && $('.indoor-temperature').length === 0 && $('.indoor-humidity').length === 0) {
        return false;
    }
    fetch('api.php?action=getUsagesData')
        .then(response => response.json())
        .then(data => {
            $('.electricity-usage').html(data.electricity);
            $('.water-usage').html(data.water);
            $('.gas-usage').html(data.gas);
            if ($('.indoor-temperature span.text').length) {
                $('.indoor-temperature span.text').html(data.temperature + '&deg;');
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
    setInterval(usageData, 4000);
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
                        animation: {
                            duration: 100,
                            easing: 'linear'
                        },
                        legend: {
                            display: false
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        transitions: {
                            show: {
                                animations: {
                                    x: {
                                        from: 0
                                    },
                                    y: {
                                        from: 0
                                    }
                                }
                            },
                            hide: {
                                animations: {
                                    x: {
                                        to: 0
                                    },
                                    y: {
                                        to: 0
                                    }
                                }
                            }
                        }
                    }
                });
                setInterval(function () {
                }, 4000);
            });
    }

    if ($('#temperatureChart').length) {
        var temperatureChartCanvas = document.getElementById('temperatureChart').getContext('2d');
        var temperatureLabels = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        var temperatureData = [
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30)
        ];
        var temperatureDataAttr = $('#temperatureChart').closest('.temperature[data-chart]');
        if (temperatureDataAttr.length) {
            temperatureLabels = temperatureDataAttr.data('chart');
            temperatureData = temperatureDataAttr.data('chart');
        }
        window.temperatureChart = new Chart(temperatureChartCanvas, {
            type: 'line',
            data: {
                labels: temperatureLabels,
                datasets: [
                    {
                        label: 'Temperature',
                        data: temperatureData,
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
                animation: {
                    duration: 100,
                    easing: 'linear'
                },
                legend: {
                    display: false
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false
                    }
                }
            }
        });
    }

    if ($('#humidityChart').length) {
        var humidityChartCanvas = document.getElementById('humidityChart').getContext('2d');
        var humidityLabels = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        var humidityData = [
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30),
            Math.floor(Math.random() * 18) + Math.floor(Math.random() * 30)
        ];
        var humidityDataAttr = $('#humidityChart').closest('.humidity[data-chart]');
        if (humidityDataAttr.length) {
            humidityData = humidityDataAttr.data('chart');
            humidityLabels = humidityDataAttr.data('chart');
        }
        window.humidityChart = new Chart(humidityChartCanvas, {
            type: 'line',
            data: {
                labels: humidityLabels,
                datasets: [
                    {
                        label: 'Humidity',
                        data: humidityData,
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
                animation: {
                    duration: 100,
                    easing: 'linear'
                },
                legend: {
                    display: false
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false
                    }
                }
            }
        });
    }

    if ($('#temperatureChart').length || $('#humidityChart').length) {
        var roomID = (typeof $('.temperature[data-room]').data('room') !== 'undefined') ? $('.temperature[data-room]').data('room') : $('.humidity[data-room]').data('room');
        setInterval(function () {
            $.post('api.php?action=roomSensorData', { id: roomID }, function (data) {
                if (typeof data.sensors.temperature !== 'undefined') {
                    temperatureChart.data.datasets[0].data = data.sensors.temperature;
                    temperatureChart.update();
                    $('.temperature .fs-1').html(data.temperature + '&deg;');
                }
                if (typeof data.sensors.humidity !== 'undefined') {
                    humidityChart.data.datasets[0].data = data.sensors.humidity;
                    humidityChart.update();
                    $('.humidity .fs-1').html(data.humidity + '%');
                }
            }, 'json');
        }, 2000);
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
            }, 4000);
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
            if (randomSong.title == $('.speaker .song-title').text()) {
                randomSong = songs[Math.floor(Math.random() * songs.length)];
            }
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

    $('#addRoomModal form').submit(function (e) {
        e.preventDefault();
        $.post('api.php?action=addRoom', $(this).serialize(), function (data) {
            if (data.status == 'success') {
                $('#addRoomModal').modal('hide');
                if (location.href.indexOf('producer.php') > -1 || location.href.indexOf('producer_devices.php') > -1) {
                    location.href = 'producer_devices.php' + ($('#roomConsumer').length ? '?user=' + $('#roomConsumer').val() : '');
                } else if (location.href.indexOf('producer_rooms.php') > -1) {
                    location.href = 'producer_rooms.php' + ($('#roomConsumer').length ? '?user=' + $('#roomConsumer').val() : '');
                } else {
                    if (typeof data.id != 'undefined') {
                        location.href = 'room.php?id=' + data.id;
                    } else {
                        location.reload();
                    }
                }
            }
        }, 'json');
    });

    $('.room-device .on-off-btn').click(function (e) {
        e.preventDefault();
        var deviceID = $(this).data('id');
        var deviceDataStatus = !$(this).data('status');
        $(this).closest('.room-device').find('span.text-muted').text(deviceDataStatus ? 'On' : 'Off');
        $(this).data('status', deviceDataStatus);
        deviceStatus(deviceID, deviceDataStatus);
    });

    $('body').on('click', '.delete-device[data-id]', function (e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to delete this device?')) {
            return false;
        }
        var deviceID = $(this).data('id'),
            deviceEl = $(this).closest('.room-device');
        $.post('api.php?action=deleteDevice', {
            id: deviceID
        }, function (data) {
            if (data.status == 'success') {
                location.reload();
            }
        }, 'json');
    });

    $('.add-device-btn').click(function (e) {
        e.preventDefault();
        var roomID = $(this).data('room');
        $('#addDeviceModal').modal('show');
        $('#deviceRoom').val(roomID);
    });

    $('#addDeviceModal form').submit(function (e) {
        e.preventDefault();
        $.post('api.php?action=addDevice', $(this).serialize(), function (data) {
            if (data.status == 'success') {
                $('#addDeviceModal').modal('hide');
                if (location.href.indexOf('producer.php') > -1) {
                    location.href = 'producer_devices.php';
                } else {
                    location.reload();
                }
            }
        }, 'json');
    });

    $('.edit-room-btn[data-id]').click(function (e) {
        e.preventDefault();
        var roomID = $(this).data('id');
        $.post('api.php?action=getRoom', {
            id: roomID
        }, function (data) {
            if (data.status == 'success') {
                $('#editRoomModal').modal('show');
                $('#editRoomModal form').attr('data-id', roomID);
                $('#editRoomModal form #editRoomId').val(roomID);
                $('#editRoomModal form #editRoomName').val(data.data.name);
                $('#editRoomModal form .delete-room').attr('data-id', roomID);
                if (typeof data.data.data.temperature != 'undefined' && parseInt(data.data.data.temperature) > 0) {
                    $('#editRoomModal form #editRoomTemperature').attr('checked', true).val(data.data.data.temperature);
                } else {
                    $('#editRoomModal form #editRoomTemperature').attr('checked', false);
                }
                if (typeof data.data.data.temperature_status != 'undefined' && data.data.data.temperature_status == 0) {
                    $('#editRoomModal form #editRoomTemperature').attr('checked', false);
                }

                if (typeof data.data.data.humidity != 'undefined' && parseInt(data.data.data.humidity) > 0) {
                    $('#editRoomModal form #editRoomHumidity').attr('checked', true).val(data.data.data.humidity);
                } else {
                    $('#editRoomModal form #editRoomHumidity').attr('checked', false);
                }
                if (typeof data.data.data.humidity_status != 'undefined' && data.data.data.humidity_status == 0) {
                    $('#editRoomModal form #editRoomHumidity').attr('checked', false);
                }

                $('#editRoomModal form #editRoomFireCo').attr('checked', false);
                if (typeof data.data.data.fireco_status != 'undefined' && data.data.data.fireco_status != 0) {
                    $('#editRoomModal form #editRoomFireCo').attr('checked', true);
                }
            }
        }, 'json');
    });

    $('#editRoomModal form').submit(function (e) {
        e.preventDefault();
        $.post('api.php?action=editRoom', $(this).serialize(), function (data) {
            if (data.status == 'success') {
                $('#editRoomModal').modal('hide');
                location.reload();
            }
        }, 'json');
    });

    $('.delete-room[data-id]').click(function (e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to delete this room?')) {
            return false;
        }
        var roomID = $(this).data('id'),
            roomEl = $(this).closest('.room-area');
        $.post('api.php?action=deleteRoom', {
            id: roomID
        }, function (data) {
            if (data.status == 'success') {
                console.log(roomEl);
                if (roomEl.length > 0) {
                    roomEl.remove();
                } else {
                    location.reload();
                }
            }
        }, 'json');
    });

    $('.edit-lamp-btn[data-id]').click(function (e) {
        e.preventDefault();
        var lampID = $(this).data('id');
        $.post('api.php?action=getLamp', {
            id: lampID
        }, function (data) {
            if (data.status == 'success') {
                $('#editLampModal').modal('show');
                $('#editLampModal form').attr('data-id', lampID);
                $('#editLampModal form .delete-lamp-btn').attr('data-id', lampID);
                $('#editLampModal form #editLampId').val(lampID);
                $('#editLampModal form #editLampName').val(data.device.name);
                if (typeof data.device.data.color != 'undefined') {
                    $('#editLampModal form #editLampColor').val(data.device.data.color).trigger('change');
                }
                if (typeof data.device.data.brightness != 'undefined') {
                    $('#editLampModal form #editLampBrightness').val(data.device.data.brightness);
                }
            }
        }, 'json');
    });

    $('#editLampModal form').submit(function (e) {
        e.preventDefault();
        $.post('api.php?action=editLamp', $(this).serialize(), function (data) {
            if (data.status == 'success') {
                $('#editLampModal').modal('hide');
                location.reload();
            }
        }, 'json');
    });

    $('body').on('click', '.delete-lamp-btn[data-id]', function (e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to delete this lamp?')) {
            return false;
        }
        var lampID = $(this).data('id');
        $.post('api.php?action=deleteLamp', {
            id: lampID
        }, function (data) {
            if (data.status == 'success') {
                location.reload();
            }
        }, 'json');
    });


    $('#addLampModal form').submit(function (e) {
        e.preventDefault();
        $.post('api.php?action=addLamp', $(this).serialize(), function (data) {
            if (data.status == 'success') {
                $('#addLampModal').modal('hide');
                location.reload();
            }
        }, 'json');
    });

    $('.temperature[data-temperature] .increase, .temperature[data-temperature] .decrease').click(function (e) {
        e.preventDefault();
        var tempEl = $(this).closest('.temperature');
        var temperature = parseInt(tempEl.data('temperature'));
        if ($(this).hasClass('increase')) {
            temperature++;
        } else {
            temperature--;
        }
        if (temperature < 18) {
            temperature = 18;
        } else if (temperature > 30) {
            temperature = 30;
        }
        tempEl.data('temperature', temperature);
        tempEl.find('span.set-text').html(temperature + '&deg;');
        setRoomData(tempEl.data('room'), 'temperature', temperature);
    });

    $('.humidity[data-humidity] .increase, .humidity[data-humidity] .decrease').click(function (e) {
        e.preventDefault();
        var humEl = $(this).closest('.humidity');
        var humidity = parseInt(humEl.data('humidity'));
        if ($(this).hasClass('increase')) {
            humidity++;
        } else {
            humidity--;
        }
        if (humidity < 30) {
            humidity = 30;
        } else if (humidity > 80) {
            humidity = 80;
        }
        humEl.data('humidity', humidity);
        humEl.find('span.set-text').html(humidity + '%');
        setRoomData(humEl.data('room'), 'humidity', humidity);
    });

    $('.room-wifi button[data-id]').click(function (e) {
        e.preventDefault();
        var deviceID = $(this).data('id'),
            deviceEl = $(this).closest('.room-wifi'),
            deviceConnected = $(this).data('status');
        deviceStatus(deviceID, deviceConnected == '1' ? '0' : '1');
        if (deviceConnected == '1') {
            $(this).data('status', '0');
            deviceEl.find('.text-muted').html('Disconnected');
        } else {
            $(this).data('status', '1');
            deviceEl.find('.text-muted').html('<i class="fa fa-spinner fa-spin"></i> Connecting...');
            setTimeout(function () {
                deviceEl.find('.text-muted').html('Connected');
            }, 1000);
        }
    });

    $('.shade-control .increase, .shade-control .decrease').click(function (e) {
        e.preventDefault();
        var deviceEl = $(this).closest('.shade-control'),
            shade = parseInt(deviceEl.data('shade'));
        if ($(this).hasClass('increase')) {
            shade++;
        } else {
            shade--;
        }
        if (shade < 0) {
            shade = 0;
        } else if (shade > 100) {
            shade = 100;
        }
        deviceEl.data('shade', shade);
        deviceEl.find('.form-range').val(shade).change();
    });

    $('.shade-control .form-range').on('input', function (e) {
        e.preventDefault();
        var deviceEl = $(this).closest('.shade-control'),
            shade = parseInt($(this).val());
        deviceEl.data('shade', shade);
        deviceEl.find('span.fs-1').html(shade + '%');
    });

    $('.shade-control .form-range').on('change', function (e) {
        e.preventDefault();
        var deviceEl = $(this).closest('.shade-control'),
            deviceID = deviceEl.data('id'),
            shade = parseInt($(this).val());
        setDeviceData(deviceID, 'shade', shade);
        deviceEl.data('shade', shade);
        deviceEl.find('span.fs-1').html(shade + '%');
    });

    $('.tv-control .on-off-btn').click(function (e) {
        e.preventDefault();
        var deviceEl = $(this).closest('.tv-control'),
            deviceID = deviceEl.data('id'),
            deviceConnected = $(this).data('status');
        deviceStatus(deviceID, deviceConnected == '1' ? '0' : '1');
        if (deviceConnected == '1') {
            $(this).data('status', '0');
            deviceEl.find('.card-body.on').addClass('d-none');
            deviceEl.find('.card-body.off').removeClass('d-none');
        } else {
            $(this).data('status', '1');
            deviceEl.find('.card-body.off').addClass('d-none');
            deviceEl.find('.card-body.on').removeClass('d-none');
        }
    });

    $('.tv-control .increase, .tv-control .decrease').click(function (e) {
        e.preventDefault();
        var deviceEl = $(this).closest('.tv-control'),
            deviceID = deviceEl.data('id'),
            channel = parseInt(deviceEl.data('channel'));
        if ($(this).hasClass('increase')) {
            channel++;
        } else {
            channel--;
        }
        if (channel < 1) {
            channel = 1;
        } else if (channel > 100) {
            channel = 100;
        }
        deviceEl.data('channel', channel);
        setDeviceData(deviceID, 'channel', channel);
        deviceEl.find('span.channel-name').text(channel);
    });

    $('.tv-control .form-range').on('change', function (e) {
        e.preventDefault();
        var deviceEl = $(this).closest('.tv-control'),
            deviceID = deviceEl.data('id'),
            volume = parseInt($(this).val());
        setDeviceData(deviceID, 'volume', volume);
        deviceEl.data('volume', volume);
    });

    $('.static-cam button[data-cam="prev"], .static-cam button[data-cam="next"]').click(function (e) {
        e.preventDefault();
        var camEl = $('.static-cam .dropdown-menu .dropdown-item.active').parent();
        if ($(this).data('cam') == 'prev') {
            if (camEl.prev().length) {
                camEl.prev().find('a').click();
            } else {
                $('.static-cam .dropdown-menu li:last-child .dropdown-item').click();
            }
        } else {
            if (camEl.next().length) {
                camEl.next().find('a').click();
            } else {
                $('.static-cam .dropdown-menu li:first-child a.dropdown-item').click();
            }
        }
    });

    $('.static-cam .dropdown-menu .dropdown-item').click(function (e) {
        e.preventDefault();
        $('.static-cam .dropdown-menu .dropdown-item').removeClass('active').removeClass('btn-sh');
        $(this).addClass('active').addClass('btn-sh');
        $('.static-cam > img').attr('src', $(this).data('src'));
        $('.static-cam .cam-name span.name').text($(this).text());
        $('.static-cam').data('id', $(this).data('id'));
    });

    $('.static-cam .add-cam-btn').click(function (e) {
        e.preventDefault();
        $('#addCamModal').modal('show');
    });

    $('body').on('submit', '#addCamModal form', function (e) {
        e.preventDefault();
        $.post('api.php?action=addDevice', $(this).serialize(), function (data) {
            if (data.status == 'success') {
                $('#addCamModal').modal('hide');
                location.reload();
            }
        }, 'json');
    });

    $('.static-cam .edit-cam-btn').click(function (e) {
        e.preventDefault();
        var camID = $('.static-cam').data('id');
        $.post('api.php?action=getDevice', {
            id: camID
        }, function (data) {
            if (data.status == 'success') {
                $('#editCamModal').modal('show');
                $('#editCamModal #editCamId').val(camID);
                $('#editCamModal .delete-cam-btn').data('id', camID);
                $('#editCamModal #editCamName').val(data.device.name);
                $('#editCamModal #editCamStatus').val(data.device.status);
            }
        }, 'json');
    });

    $('body').on('submit', '#editCamModal form', function (e) {
        e.preventDefault();
        $.post('api.php?action=editDevice', $(this).serialize(), function (data) {
            if (data.status == 'success') {
                $('#editCamModal').modal('hide');
                location.reload();
            }
        }, 'json');
    });

    $('body').on('click', '#editCamModal .delete-cam-btn', function (e) {
        e.preventDefault();
        var camID = $('.static-cam').data('id');
        $.post('api.php?action=deleteDevice', {
            id: camID
        }, function (data) {
            if (data.status == 'success') {
                $('#editCamModal').modal('hide');
                location.reload();
            }
        }, 'json');
    });

    $('.refrigerator[data-id] .btn-mode').click(function (e) {
        e.preventDefault();
        var deviceEl = $(this).closest('.refrigerator'),
            deviceID = deviceEl.data('id'),
            mode = $(this).data('mode'),
            temperature = $(this).data('temperature');
        deviceEl.find('.btn-mode').removeClass('btn-sh').addClass('btn-light');
        $(this).addClass('btn-sh').removeClass('btn-light');
        deviceEl.find('.set-text').html(temperature + '&deg;');
        setDeviceData(deviceID, 'mode', mode);
    });

    $('.air-conditioner[data-id] .btn-mode').click(function (e) {
        e.preventDefault();
        var deviceEl = $(this).closest('.air-conditioner'),
            deviceID = deviceEl.data('id'),
            mode = $(this).data('mode');
        deviceEl.find('.btn-mode').removeClass('btn-sh').addClass('btn-light');
        $(this).addClass('btn-sh').removeClass('btn-light');
        setDeviceData(deviceID, 'mode', mode);
    });

    if ($('.send-data-btn').length) {
        var sendSensorData = function () {
            if (!$('.sensor-toggler input').prop('checked')) {
                return;
            }
            var all_rooms = $('.send-data-btn').data('rooms'),
                user_id = $('.send-data-btn').data('id');
            $.post('producer_api.php?action=sendData', {
                user_id: user_id,
                type: 'sensor'
            }, function (data) { }, 'json');
        };
        $('.send-data-btn').click(function (e) {
            e.preventDefault();
            $.post('producer_api.php?action=getRoomsDataForm', {
                id: $(this).data('id')
            }, function (data) {
                if (data.status == 'success') {
                    $('#sendDataModal .modal-body').html(data.data);
                    $('#sendDataModal').modal('show');
                }
            });
        });
        $('body').on('submit', '#sendDataModal form', function (e) {
            e.preventDefault();
            $.post('producer_api.php?action=sendData', $(this).serialize(), function (data) {
                if (data.status == 'success') {
                    $('#sendDataModal').modal('hide');
                    location.reload();
                }
            }, 'json');
        });
        $('.sensor-toggler input').change(function (e) {
            e.preventDefault();
            if ($(this).prop('checked')) {
                document.cookie = 'sensor-disabled=0; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            } else {
                document.cookie = 'sensor-disabled=1';
            }
        });

        setInterval(sendSensorData, 4000);
    }

    $('.edit-device[data-id], .edit-device-producer[data-id]').click(function (e) {
        e.preventDefault();
        var deviceID = $(this).data('id');
        $.post('api.php?action=getDeviceDataForm', {
            id: deviceID
        }, function (data) {
            if (data.status == 'success') {
                $('#editDeviceDataModal .modal-body').html(data.data);
                $('#editDeviceDataModal .modal-header h5').text(data.name);
                $('#editDeviceDataModal #editDeviceId').val(deviceID);
                $('#editDeviceDataModal .delete-device').data('id', deviceID);
                $('#editDeviceDataModal').modal('show');
            }
        }, 'json');
    });

    $('body').on('submit', '#editDeviceDataModal form', function (e) {
        e.preventDefault();
        $.post('api.php?action=setDeviceDataMultiple', $(this).serialize(), function (data) {
            if (data.status == 'success') {
                $('#editDeviceDataModal').modal('hide');
                location.reload();
            }
        }, 'json');
    });

    $('.edit-consumer[data-id]').click(function (e) {
        e.preventDefault();
        var consumerID = $(this).data('id');
        $.post('producer_api.php?action=getConsumer', {
            id: consumerID
        }, function (data) {
            if (data.status == 'success') {
                $('#editConsumerModal #editConsumerId').val(consumerID);
                $('#editConsumerModal #editConsumerName').val(data.consumer.name);
                $('#editConsumerModal #editConsumerEmail').val(data.consumer.email);
                $('#editConsumerModal #editConsumerUsername').val(data.consumer.username);
                $('#editConsumerModal').modal('show');
            }
        }, 'json');
    });

    $('.producer-devices .temperature .set-text').click(function (e) {
        e.preventDefault();
        var tempEl = $(this).closest('.temperature'),
            temperature = tempEl.data('temperature');
        $(this).toggleClass('d-none');
        tempEl.find('.set-input').toggleClass('d-none').val(temperature);
    });

    $('.producer-devices .temperature .set-input').keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();
            var tempEl = $(this).closest('.temperature'),
                temperature = $(this).val();

            if (!$.isNumeric(temperature) || temperature < 18 || temperature > 30) {
                alert('Temperature must be between 18 and 30');
                return;
            }
            tempEl.find('.set-input').toggleClass('d-none');
            tempEl.find('.set-text').toggleClass('d-none');
            tempEl.data('temperature', temperature);
            tempEl.find('span.set-text').html(temperature + '&deg;');
            setRoomData(tempEl.data('room'), 'temperature', temperature);
        }
    });

    $('.producer-devices .humidity .set-text').click(function (e) {
        e.preventDefault();
        var tempEl = $(this).closest('.humidity'),
            humidity = tempEl.data('humidity');
        $(this).toggleClass('d-none');
        tempEl.find('.set-input').toggleClass('d-none').val(humidity);
    });

    $('.producer-devices .humidity .set-input').keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();
            var tempEl = $(this).closest('.humidity'),
                humidity = $(this).val();
            if (!$.isNumeric(humidity) || humidity < 30 || humidity > 80) {
                alert('Humidity must be between 30 and 80');
                return;
            }
            tempEl.find('.set-input').toggleClass('d-none');
            tempEl.find('.set-text').toggleClass('d-none');
            tempEl.data('humidity', humidity);
            tempEl.find('span.set-text').html(humidity + '%');
            setRoomData(tempEl.data('room'), 'humidity', humidity);
        }
    });

});