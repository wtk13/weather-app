require('../css/app.css');
require('./../../node_modules/bootstrap/dist/css/bootstrap.min.css');

const $ = require('jquery');
require('bootstrap');



$(document).ready(function() {
    $('.nav-item').removeClass('active');
    $('#nav-map').addClass('active');

    map.addListener('click', function(e) {
        var lat = e.latLng.lat();
        var lng = e.latLng.lng();

        addLocation(lat, lng);
    });

    $('#myModal').on('hidden.bs.modal', function (e) {
        $('.elem').text('');
    })
});


function addLocation(lat, lng) {
    $('.spinner-border').removeClass('spinner');

    $('#myModal').modal();

    $.ajax(
        {
            type: "POST",
            url: document.location.origin + '/add',
            data: JSON.stringify({
                lat: lat,
                lng: lng
            }),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            cache: false,
            success: function (data) {
                $('.spinner-border').addClass('spinner');

                $('#lat').text(data.lat);
                $('#lng').text(data.lng);
                $('#name').text(data.name);
                $('#temp').text(data.temp);
                $('#pressure').text(data.pressure);
                $('#humidity').text(data.humidity);
                $('#temp-min').text(data.tempMin);
                $('#temp-max').text(data.tempMax);
                $('#wind-speed').text(data.windSpeed);
                $('#wind-deg').text(data.windDeg);
                $('#clouds').text(data.clouds);
                $('#rain-1').text(data.rainOneH);
                $('#rain-3').text(data.rainThreeH);
                $('#snow-1').text(data.snowOneH);
                $('#snow-3').text(data.snowThreeH);

            },
            error: function (msg) {
                console.log(msg);
            }
        });
}
