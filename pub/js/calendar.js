const DANI = [
  "ponedeljak",
  "utorak",
  "sreda",
  "četvrtak",
  "petak",
  "subota",
  "nedelja"
];

const MESECI = [
  "januar",
  "februar",
  "mart",
  "april",
  "maj",
  "jun",
  "jul",
  "avgust",
  "septembar",
  "oktobar",
  "novembar",
  "decembar"
];

class CSCalendar {
  constructor(options) {
    this.options = options;
    this.display = document.getElementById("display");
    // this.elements = {
    //   days: this.getFirstElementInsideIdByClassName("days"),
    //   week: this.getFirstElementInsideIdByClassName("week"),
    //   month: this.getFirstElementInsideIdByClassName("month"),
    //   year: this.getFirstElementInsideIdByClassName("current-year"),
    //   prevYear: this.getFirstElementInsideIdByClassName("prev-year"),
    //   nextYear: this.getFirstElementInsideIdByClassName("next-year")
    // };
    this.elements = {};
    this.date = +new Date();
    this.options.maxDays = 35;
    this.init();
  }

  init() {
    if (!this.options.id) return false;
    this.calendarElement = document.getElementById(this.options.id);
    this.createAll();
    this.eventsTrigger();
    this.drawAll();
  }

  createAll() {
    this.createYearSlider();
    this.createMonthSelect();
    this.createWeekdays();
    this.createDays();
  }

  createYearSlider() {
    const changeYearWrapper = document.createElement("div");
    changeYearWrapper.classList.add("text-right");
    const slider = document.createElement("div");
    slider.classList.add("change-year");
    const prev = document.createElement("i");
    prev.classList.add("fas", "fa-caret-left", "prev-year", "pointer");
    this.elements.prevYear = prev;
    const year = document.createElement("span");
    year.classList.add("current-year");
    this.elements.year = year;
    const next = document.createElement("i");
    next.classList.add("fas", "fa-caret-right", "next-year", "pointer");
    this.elements.nextYear = next;
    slider.appendChild(prev);
    slider.appendChild(year);
    slider.appendChild(next);
    changeYearWrapper.appendChild(slider);
    this.calendarElement.appendChild(changeYearWrapper);
  }

  createMonthSelect() {
    const table = document.createElement("table");
    table.classList.add("table", "table-borderless", "table-sm");
    const tbody = document.createElement("tbody");
    const tr = document.createElement("tr");
    tr.classList.add("month");
    this.elements.month = tr;
    tbody.appendChild(tr);
    table.appendChild(tbody);
    this.calendarElement.appendChild(table);
  }

  createWeekdays() {
    const table = document.createElement("table");
    table.classList.add("table", "table-bordered", "table-sm", "m-0");
    const tbody = document.createElement("tbody");
    const tr = document.createElement("tr");
    tr.classList.add("week");
    this.elements.week = tr;
    tbody.appendChild(tr);
    table.appendChild(tbody);
    this.calendarElement.appendChild(table);
  }

  createDays() {
    const table = document.createElement("table");
    table.classList.add("table", "table-bordered");
    const tbody = document.createElement("tbody");
    tbody.classList.add("days");
    this.elements.days = tbody;
    table.appendChild(tbody);
    this.calendarElement.appendChild(table);
  }

  eventsTrigger() {
    this.elements.prevYear.addEventListener("click", e => {
      let calendar = this.getCalendar();
      this.updateTime(calendar.pYear);
      this.drawAll();
    });

    this.elements.nextYear.addEventListener("click", e => {
      let calendar = this.getCalendar();
      this.updateTime(calendar.nYear);
      this.drawAll();
    });

    this.elements.month.addEventListener("click", e => {
      let calendar = this.getCalendar();
      let month = e.srcElement.getAttribute("data-month");
      if (!month || calendar.active.month == month) return false;

      let newMonth = new Date(calendar.active.tm).setMonth(month);
      this.updateTime(newMonth);
      this.drawAll();
    });

    this.elements.days.addEventListener("click", e => {
      let element = e.srcElement;
      let day = element.getAttribute("data-day");
      let month = element.getAttribute("data-month");
      let year = element.getAttribute("data-year");
      if (!day) return false;
      let strDate = `${Number(month) + 1}/${day}/${year}`;
      this.updateTime(strDate);
      this.drawAll();
    });
  }

  drawAll() {
    this.drawWeekDays();
    this.drawMonths();
    this.drawDays();
    this.drawYear();
  }

  drawYear() {
    let calendar = this.getCalendar();
    this.elements.year.innerHTML = calendar.active.year;
  }

  drawMonths() {
    let calendar = this.getCalendar();
    let monthTemplate = "";
    MESECI.forEach((month, idx) => {
      monthTemplate += `<td class="text-center pointer${
        idx === calendar.active.month ? " active" : ""
      }" data-month="${idx}">${month.slice(0, 3)}</td>`;
    });
    this.elements.month.innerHTML = monthTemplate;
  }

  drawWeekDays() {
    let weekTemplate = "";
    DANI.forEach((week, idx) => {
      weekTemplate += `<td class="${idx > 4 ? "weekend" : ""}">${week.slice(
        0,
        3
      )}</td>`;
    });
    this.elements.week.innerHTML = weekTemplate;
  }

  drawDays() {
    let calendar = this.getCalendar();

    let numForRange =
      calendar.active.startWeek === 0 ? 6 : calendar.active.startWeek - 1;
    let latestDaysInPrevMonth = this.range(numForRange)
      .map((day, idx) => {
        return {
          dayNumber: this.countOfDaysInMonth(calendar.pMonth) - idx,
          month: new Date(calendar.pMonth).getMonth(),
          year: new Date(calendar.pMonth).getFullYear(),
          currentMonth: false
        };
      })
      .reverse();

    let daysInActiveMonth = this.range(calendar.active.days).map((day, idx) => {
      let dayNumber = idx + 1;
      let today = new Date();
      return {
        dayNumber,
        today:
          today.getDate() === dayNumber &&
          today.getFullYear() === calendar.active.year &&
          today.getMonth() === calendar.active.month,
        month: calendar.active.month,
        year: calendar.active.year,
        selected: calendar.active.day === dayNumber,
        currentMonth: true
      };
    });

    if (latestDaysInPrevMonth.length + daysInActiveMonth.length > 35) {
      this.options.maxDays = 42;
    } else {
      this.options.maxDays = 35;
    }

    let countOfDays =
      this.options.maxDays -
      (latestDaysInPrevMonth.length + daysInActiveMonth.length);

    let daysInNextMonth = this.range(countOfDays).map((day, idx) => {
      return {
        dayNumber: idx + 1,
        month: new Date(calendar.nMonth).getMonth(),
        year: new Date(calendar.nMonth).getFullYear(),
        currentMonth: false
      };
    });

    let days = [
      ...latestDaysInPrevMonth,
      ...daysInActiveMonth,
      ...daysInNextMonth
    ];

    let daysTemplate = "";
    days.forEach((day, idx) => {
      if (idx % 7 === 0) {
        daysTemplate += `<tr>`;
      }
      daysTemplate += `<td class="${day.currentMonth ? "" : "another-month"}${
        day.today ? " active-day " : ""
      }${day.selected ? "selected-day" : ""}${
        day.hasEvent ? " event-day" : ""
      }" data-day="${day.dayNumber}" data-month="${day.month}" data-year="${
        day.year
      }">${day.dayNumber}</td>`;
      if (idx % 7 === 6) {
        daysTemplate += `</tr>`;
      }
    });

    this.elements.days.innerHTML = daysTemplate;
  }

  getCalendar() {
    let time = new Date(this.date);
    return {
      active: {
        days: this.countOfDaysInMonth(time), // koliko mesec ima dana
        startWeek: this.getStartedDayOfWeekByTime(time), // pocetni dan meseca (0-nedelja)
        day: time.getDate(), // dan u mesecu
        week: time.getDay(), // dan u nedelji (0-nedelja)
        month: time.getMonth(), // mesec u godini -1 (0-based)
        year: time.getFullYear(), // godina
        formatted: this.getFormattedDate(time), // formatirani datum
        formattedFull: this.getFormattedDate(time, true), // formatirani datum mesec slovima
        tm: +time // unix timestamp
      },
      pMonth: new Date(time.getFullYear(), time.getMonth() - 1, 1), // prethodni mesec
      nMonth: new Date(time.getFullYear(), time.getMonth() + 1, 1), // naredni mesec
      pYear: new Date(new Date(time).getFullYear() - 1, 0, 1), // prethodna godina
      nYear: new Date(new Date(time).getFullYear() + 1, 0, 1) // naredna godina
    };
  }

  countOfDaysInMonth(time) {
    let date = this.getMonthAndYear(time);
    return new Date(date.year, date.month + 1, 0).getDate();
  }

  getStartedDayOfWeekByTime(time) {
    let date = this.getMonthAndYear(time);
    return new Date(date.year, date.month, 1).getDay();
  }

  getMonthAndYear(time) {
    let date = new Date(time);
    return {
      year: date.getFullYear(),
      month: date.getMonth()
    };
  }

  getFormattedDate(date, month = false) {
    let mesec = ("0" + (date.getMonth() + 1)).slice(-2) + ".";
    if (month) {
      mesec = " " + MESECI[date.getMonth()] + " ";
    }
    const datum =
      ("0" + date.getDate()).slice(-2) + "." + mesec + date.getFullYear();
    return datum;
  }

  updateTime(time) {
    this.date = +new Date(time);
  }

  range(number) {
    return new Array(number).fill().map((e, i) => i);
  }

  getFirstElementInsideIdByClassName(className) {
    return document
      .getElementById(this.options.id)
      .getElementsByClassName(className)[0];
  }
}
