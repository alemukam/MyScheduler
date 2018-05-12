var apiLink = mainLink + '/api/user-events/' + id;

function getEvents(day, month, year) {
    $.ajax({
        url: apiLink,
        type: 'GET',
        data: {
            'day': day,
            'month': month,
            'year': year
        }, 
        success: function(data) {
            // language attribute
            var noEvents_u = '', noEvents_g = '', header = '';
            switch (document.documentElement.lang) {
                case 'jp':
                    header = 'イベントの';
                    noEvents_u = 'この日は個人的な出来事はありません。 自由時間を楽しむ :)';
                    noEvents_g = 'この日にグループイベントはありません。 自由時間を楽しむ :)';
                    break;
                // English is default
                case 'en':
                default:
                    header = 'Events on ';
                    noEvents_u = 'You have no personal events on this day. Enjoy free time :)';
                    noEvents_g = 'You have no group events on this day. Enjoy free time :)';
            }


            // display the date of the events
            $('#events_title').text(header + '年' + year + '月' + month + '日' + day);

            // display personal events
            if (data.user.length == 0) {
                $('#u_events').hide();
                $('#u_events_table').removeClass('table-bordered');
                $('#u_events_render').html('<p>'+ noEvents_u +'</p>');

            } else {
                $('#u_events').show();
                $('#u_events_table').addClass('table-bordered');
                // iterate through the data and display all events within the table "group_events"
                $('#u_events_render').html('');
                for (var i = 0; i < data.user.length; i++) {
                    var date = new Date(data.user[i].date);

                    $('#u_events_render').append('<tr><td>年'+
                        date.getFullYear() + '月' + (date.getMonth() + 1) + '日' + date.getDate() +
                        '</td><td><a href="'+ mainLink + '/user-events/' + data.user[i].id +'">' +
                        data.user[i].title +
                        '</a></td><td>' +
                        data.user[i].start_time +
                        '</td></tr>');
                }
            }

            // display group events
            if (data.group.length == 0) {
                $('#g_events').hide();
                $('#g_events_table').removeClass('table-bordered');
                $('#g_events_render').html('<p>'+ noEvents_g +'</p>');
                
            } else {
                $('#g_events').show();
                $('#g_events_table').addClass('table-bordered');
                // iterate through the data and display all events within the table "group_events"
                $('#g_events_render').html('');
                for (var i = 0; i < data.group.length; i++) {
                    var date = new Date(data.group[i].date);

                    $('#g_events_render').append('<tr><td>年'+
                        date.getFullYear() + '月' + (date.getMonth() + 1) + '日' + date.getDate() +
                        '</td><td><a href="'+ mainLink + '/groups/' + data.group[i].group_id + '/group-events/' + data.group[i].id +'">'+
                        data.group[i].title +
                        '</a></td><td><a href="' + mainLink + '/groups/' + data.group[i].id + '">' +
                        data.group[i].group_name +
                        '</a></td><td>' +
                        data.group[i].start_time +
                        '</td></tr>');
                }
            }
        }
    });
}