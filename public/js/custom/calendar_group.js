var apiLink = mainLink + '/api/group-events' + '/' + groupID;

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
            // display the date of the events
            $('#events_title').text('Events on 年' + year + '月' + month + '日' + day);

            // display the events on the date
            
            if (data.length === 0)
            {
                $('#group_events').hide();
                $('#events_table').removeClass('table-bordered');
                $('#render_events').html('<p>There are no events on this day. Enjoy free time :)</p>');
            } else {
                $('#group_events').show();
                $('#events_table').addClass('table-bordered');
                // iterate through the data and display all events within the table "group_events"
                $('#render_events').html('');
                for (var i = 0; i < data.length; i++) {
                    var date = new Date(data[i].date);

                    $('#render_events').append('<tr><td>年'+
                        date.getFullYear() + '月' + (date.getMonth() + 1) + '日' + date.getDate() +
                        '</td><td><a href="'+ mainLink + '/groups/' + groupID + '/group-events/' + data[i].id +'">'+
                        data[i].title +
                        '</a></td><td>'+
                        data[i].start_time+
                        '</td></tr>');
                }
            }

            return;
        }
    });
}