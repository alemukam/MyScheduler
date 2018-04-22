<?php

namespace App\Classes;

class Calendar
{
    private $month; // 1 - 12 -> Number for each month
    private $year; // 0 - 9999 -> a number that represents a year
    private $days_of_week; // array - stores names of each day of the weak
    private $num_days; // 1 - 28, 29, 30, 31 -> number of a specific day in a month
    private $date_info; // information about the first day of the month (['wday'] is needed from the array)
    private $day_of_week; // 0 - 6 -> number of a specific day in a week
    private $prevMonth; // last date of the previous month
    private $btnEvents = ['prev' => 'prevMonth()', 'next' => 'nextMonth()'];

    public function __construct() { }

    private function setPrevMonth() {
        $prevYear;
        // minimum date is (1,1) month is 1, year is 1 - no previous date
        if ($this -> month == 1 && $this -> year == 1) { $this ->prevMonth = -1; return; }

        // previous month of January is December of the previous year
        if ($this -> month == 1) { $prevMonth = 12; $prevYear = $this -> year - 1; }
        else { $prevMonth = $this -> month - 1; $prevYear = $this -> year; }
        $this -> prevMonth = cal_days_in_month(CAL_GREGORIAN, $prevMonth, $prevYear);
    }

    // Constructor - get all the information about the month, year and the first day of the month
    public function build($month, $year, $days_of_week = array('月', '火', '水', '木', '金', '土', '日'))
    {
            $this -> month = $month;
            $this -> year = $year;
            $this -> days_of_week = $days_of_week;
            // Get a number of days in the month based in the Gregorian style, current month and year
            $this -> num_days = cal_days_in_month(CAL_GREGORIAN, $this -> month, $this -> year);
            $this -> setPrevMonth(); // get the last date of the previous month
            // Get information about the first day of the month
            $this -> date_info = getdate(strtotime('first day of', mktime(0, 0, 0, $this -> month, 1, $this -> year)));
            // Move Sunday to the last position, other days back by one position
            // getdate() returns [wday]: Sunday: 0, Monday: 1, Tuesday: 2, ..., Saturtday: 6
            switch ($this -> date_info['wday'])
            {
                case 0:
                    $this -> day_of_week = 6;
                    break;
                default:
                    $this -> day_of_week = $this -> date_info['wday'] - 1;
            }
    }

    // Destructor
    public function __destruct() {
        unset($month, $year, $days_of_week, $num_days, $date_info, $day_of_week, $prevMonth);
        unset($btnEvents);
    }

    // Display the calendar
    public function show() {
        $output = '<table class="table table-bordered calendar"><thead>';
        //$output .= '<thead><tr><td colspan="1">aa</td><td colspan="5">aa</td><td colspan="1">aa</td></tr>';
        // calendar will be displayed as an HTML <table>, this is the opening tag
        $output .= '<tr><td colspan="1" id="btn_month_back" class="btn-info" onclick="'. $this -> btnEvents['prev'] .'">&#8810;</td>';
        $output .= '<td colspan="5" class="month_display">年'. $this -> year .' 月'. intval($this -> month) .'</td>';
        $output .= '<td colspan="1" id="btn_month_next" class="btn-info" onclick="'. $this -> btnEvents['next'] .'">&#8811;</td></tr>';
        //$output .= '<caption>'. $this -> date_info['month'] .' '. $this -> year .'</caption>'; // prints month and year as the first line of the calendar (full width of the table)
        $output .= '<tr>'; // opening of the row wichi will display weekdays

        // append all weekdays as separate columns to the second row
        foreach ($this -> days_of_week as $weekday)
            $output .= '<th class="header">'. $weekday .'</th>';
        $output .= '</tr></thead><tr>'; // close the "weekdays" row and begin the next one

        // create a colspan for as many days as the "distance" of the first day of the month from the first day of the week
        // if the first day of the month is not the first day of the week it will not be printed at the beginning of the week
        if ($this -> day_of_week > 0)
        {
            $prevDates = '';
            // dates are created from lates to newest, so newest dates must be before the latest
            for($i = $this -> day_of_week; $i > 0; $i--, $this -> prevMonth--)
                $prevDates = '<td class="day day-outside" onclick="'. $this -> btnEvents['prev'] .')">'. $this -> prevMonth . '</td>' . $prevDates;

            $output .= $prevDates;
            unset($prevDates);
        }

        $current_day = 1; // set the date of the first day of the month

        // print all dates
        while($current_day <= $this -> num_days) {
            // when the week is finished restard the week
            if ($this -> day_of_week === 7) {
                $this -> day_of_week = 0; // start at the beginning of the week
                $output .= '</tr><tr>'; // close the current row and start a new one
                // if the last day of the month is the last day of the week ($this -> day_of_week === 6) the if statement will not be executed because the while loop will finish before the if statement
            }

            $output .= '<td class="day day-current'. (($this -> day_of_week === 6) ? ' holiday' : '') .'" onclick="getEvents('.
            $current_day .', '. $this -> month .', '. $this -> year .')">'. $current_day . '</td>'; // print the current day

            // go to the next date and next day of the week
            $current_day++;
            $this -> day_of_week++;
        }

        // finish the month with the same colspan, $this -> day_of_week will be incremented to '7' if the last day of the month is the last day of the week (so the actual number of rendered days is returned from the while loop)
        if ($this -> day_of_week !== 7) {
            $remaining_days = 7 - $this -> day_of_week; // calculate the width of the <span>
            // the last year when PHP can get the current date is 2037
            if ($this -> year > 2036 && $this -> month == 12) $output .= '<td colspan=">'. $remaining_days .'></td>'; // colspan - no dates are ahead
            else
            {
                for($i = 1; $remaining_days > 0; $remaining_days--, $i++, $this -> day_of_week++)
                    $output .= '<td class="day day-outside'. (($this -> day_of_week === 6) ? ' holiday' : '').
                    '" onclick="'. $this -> btnEvents['next'] .'">'. $i .'</td>';
            }
            
        }

        $output .= '</tr></table>'; // close the current row and the whole table
        echo $output; // render the calendar table
    }
}