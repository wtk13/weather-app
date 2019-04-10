const $ = require('jquery');

$(document).ready(function() {
    $('.nav-item').removeClass('active');
    $('#nav-history').addClass('active');

    var page = 1;
    getSummary();
    getData(page);
});

function getData(page) {
    $.ajax(
        {
            type: "GET",
            url: document.location.origin + '/list' + '?page=' + page,
            data: "{}",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $('#history tbody').empty();
                $('#pagination').empty();
            },
            success: function (data) {
                console.log(data);
                var trHTML = '';

                $.each(data.items, function (i, item) {
                    trHTML += '<tr>' +
                        '<td>' + item.lat + '</td>' +
                        '<td>' + item.lng + '</td>' +
                        '<td>' + item.name + '</td>' +
                        '<td>' + item.temp + '</td>' +
                        '<td>' + item.pressure + '</td>' +
                        '<td>' + item.humidity + '</td>' +
                        '<td>' + item.temp_min + '</td>' +
                        '<td>' + item.temp_max + '</td>' +
                        '<td>' + item.wind_speed + '</td>' +
                        '<td>' + (item.wind_deg == null ? '-' : item.wind_deg)  + '</td>' +
                        '<td>' + (item.clouds == 0 ? '-' : item.clouds)  + '</td>' +
                        '<td>' + (item.rain_one_h == null ? '-' : item.rain_one_h)  + '</td>' +
                        '<td>' + (item.rain_three_h == null ? '-' : item.rain_three_h)  + '</td>' +
                        '<td>' + (item.snow_one_h == null ? '-' : item.snow_one_h)  + '</td>' +
                        '<td>' + (item.snow_three_h == null ? '-' : item.snow_three_h) + '</td>' +
                        '</tr>';
                });

                $('#history tbody').append(trHTML);

                var pages = '';

                for (var i = 0; i < data.pages; i++) {
                    var active = '';
                    if (data.page === (i + 1)) {
                        active = 'active';
                    }

                    pages += '<li class="page-item ' + active + '"><a class="page-link" href="#" >' + (i + 1) + '</a></li>';
                }

                var pagination = '<ul class="pagination justify-content-center">' + pages + '</ul>';

                $('#pagination').append(pagination);

                $("a.page-link").click(function(e) {
                    getData($.trim($(e.target).text()));
                    return false;
                });
            },
            error: function (msg) {
                console.log(msg);
            }
        });
}

function getSummary() {
    $.ajax(
        {
            type: "GET",
            url: document.location.origin + '/summary',
            data: "{}",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            cache: false,
            success: function (data) {
                $('#place').append(data.mostSearchPlace);
                $('#searches').append(data.howMany);
                $('#max-temp').append(data.tempMax);
                $('#min-temp').append(data.tempMin);
                $('#avg-temp').append(data.tempAvg);
            },
            error: function (msg) {
                console.log(msg);
            }
        });
}

