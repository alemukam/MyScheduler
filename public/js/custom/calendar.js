// 1. 
var myCalendar = null;

function prevMonth(month, year) {
    // get next month, if the current month is January - go to the next year
    if (month == 1) { month = 12; year--; }
    else month--;
    
    renderCalendar(month, year);
}
function nextMonth(month, year) {
    // get next month, if the current month is December - go to the next year
    if (month == 12) { month = 1; year++; }
    else month++;

    renderCalendar(month, year);
}
function getEvents(day, month, year) {
    console.log(day + '.' + month + '.' + year);
}

function renderCalendar(month, year) {
    myCalendar = null;

    myCalendar = new Calendar(month, year);
    myCalendar.build();
    myCalendar.render('render_calendar');

    if (month == 1 && year == 0) {
        var btnPrev = document.getElementById('btn_month_back');
        btnPrev.removeAttribute('onclick');
        btnPrev.classList.remove('btn-info');
        btnPrev.classList.add('btn-inactive');
    }

    var currentDateElement = document.querySelectorAll('.day-current')[currentDay - 1];
    if (month == currentMonth && year == currentYear) currentDateElement.classList.add('day-today');
    else currentDateElement.classList.remove('day-today');
}