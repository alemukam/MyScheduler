function Calendar(month, year) {
	this.month = month;
	this.year = year;
	this.dayOfWeek = null;
	this.daysOfWeek = ['月', '火', '水', '木', '金', '土', '日'];
	this.numberOfDays;
	this.element = null;


	this.build = function() {
		// 0. Get a week day of the first day of the month
		// if day (3rd argument) is not "0" than months start from 0
		this.dayOfWeek = new Date(this.year, this.month - 1, 1).getDay(); // Sunday is 0, Monday is 1, and so on
		this.dayOfWeek = (this.dayOfWeek == 0 ? 6 : this.dayOfWeek - 1);

		// 1. create the table HTML element - container for the calendar
		var calendar = document.createElement('table');
		calendar.classList.add('table');
		calendar.classList.add('table-bordered');
		calendar.classList.add('calendar');

		// 2. Create header - name of the month and names of weekdays
		var wholeHeader = document.createElement('thead');
		// 2.1. name of the month
		// 2.1.1 create both buttons (button prev should be rendered first)
		var headerMonth = document.createElement('tr');
		var btnPrev = createBtn('btn_month_back', 'prevMonth('+ this.month +', '+ this.year +')', '&#8810;');
		var btnNext = createBtn('btn_month_next', 'nextMonth('+ this.month +', '+ this.year +')', '&#8811;');
		headerMonth.appendChild(btnPrev);

		// 2.1.2 create a display of the month and render it right after the "Prev Month" button
		var monthDisplay = document.createElement('td');
		monthDisplay.setAttribute('colspan', 5);
		monthDisplay.classList.add('month_display');
		var monthDisplayText = document.createTextNode('年' + this.year + ' 月' + this.month);
		monthDisplay.appendChild(monthDisplayText);
		headerMonth.appendChild(monthDisplay);

		// 2.1.3. render the "Next Month" button right after the month display
		headerMonth.appendChild(btnNext);
		wholeHeader.appendChild(headerMonth);

		// 2.1.4. Function which creates buttons (next and prev)
		function createBtn(id, onclickFunc, value) {
			var btn = document.createElement('td');
			btn.setAttribute('colspan', 1);
			btn.setAttribute('id', id);
			btn.classList.add('btn-info');
			btn.setAttribute('onclick', onclickFunc);
			btn.innerHTML = value;

			return btn;
		}

		// 2.2. names of week days
		var weekdays = document.createElement('tr');
		for(var i = 0; i < this.daysOfWeek.length; i++) {
			var elem = document.createElement('td');
			elem.classList.add('header');
			var elemText = document.createTextNode(this.daysOfWeek[i]);
			elem.appendChild(elemText);
			weekdays.appendChild(elem);
		}
		wholeHeader.appendChild(weekdays);

		// 2.3. end of the header of the calendar
		calendar.appendChild(wholeHeader);

		// 3. render dates
		// 3.1. render dates of the previous month if the first day of the current month is not Monday
		var tableBody = document.createElement('tbody');
		var weekRow = document.createElement('tr');
		// 3.1. render dates of the previous month (1 - 12 in JS)
		if (this.dayOfWeek > 0) {
			// if a day (3rd argument) is zero, months start with 1
			var prevMonth = new Date(this.year, this.month - 1, 0).getDate();
			for(var i = this.dayOfWeek; i > 0; i--, prevMonth--) {
				var elem = document.createElement('td');
				elem.classList.add('day');
				elem.classList.add('day-outside');
				elem.setAttribute('onclick', 'prevMonth('+ this.month +', '+ this.year +')');
				var elemText = document.createTextNode(prevMonth);
				elem.appendChild(elemText);
				weekRow.insertBefore(elem, weekRow.childNodes[0]);
			}
			prevMonth = null;
		}

		// 3.2. render dates of the current month
		// (date + 1) - allow iteration if the last day of the month is Sunday,
		// otherwise the loop will not be run and the last week will not be rendered
		this.numberOfDays = new Date(this.year, this.month, 0).getDate() + 1;
		for(var i = 1; i <= this.numberOfDays; i++, this.dayOfWeek++) {
			if (this.dayOfWeek === 7) {
				tableBody.appendChild(weekRow);
				if (i == this.numberOfDays) break;

				this.dayOfWeek = 0; // reset to Monday
				weekRow = document.createElement('tr');
			}

			if (i == this.numberOfDays) break;
				
			var elem = document.createElement('td');
			elem.classList.add('day');
			elem.classList.add('day-current');
			if (this.dayOfWeek === 6) elem.classList.add('holiday');
			elem.setAttribute('onclick', 'getEvents('+ i +', '+ this.month +', '+ this.year +')');
			var elemText = document.createTextNode(i);
			elem.appendChild(elemText);
			weekRow.appendChild(elem);
		}

		// 3.3. render the last week of the month
		if (weekRow.childNodes.length !== 7) {
			this.dayOfWeek = 7 - this.dayOfWeek; // count the remaining days
			for(var i = 1; this.dayOfWeek !== 0; i++, this.dayOfWeek--) {
				var elem = document.createElement('td');
				elem.classList.add('day');
				elem.classList.add('day-outside');
				if (this.dayOfWeek == 1) elem.classList.add('holiday');
				elem.setAttribute('onclick', 'nextMonth('+ this.month +', '+ this.year +')');
				var elemText = document.createTextNode(i);
				elem.appendChild(elemText);
				weekRow.appendChild(elem);
			}

			tableBody.appendChild(weekRow);
		}
			

		calendar.appendChild(tableBody);
		this.element = calendar;
	}

	this.render = function(id) {
		if (this.element == null) return;
		var appendElement = document.getElementById(id);
		appendElement.innerHTML = '';
		appendElement.appendChild(this.element);
	}
}