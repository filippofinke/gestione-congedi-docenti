class FinkeLendar {

  constructor(element, labels, hours) {
    this.element = element;
    this.labels = labels;
    this.days = [];
    this.dates = [];
    for (var i = 0; i < labels.length; i++) {
      this.days.push([]);
    }
    this.hours = hours;
    this.selecting = false;
    this.currentSelection = [];

    this.week = "";
  }

  setOnHourClick(callback) {
    this.onHourClick = callback;
  }

  setOnSelected(callback) {
    this.onSelected = callback;
  }

  reset() {
    this.days = [];
    this.dates = [];
    for (var i = 0; i < this.labels.length; i++) {
      this.days.push([]);
    }
    this.selecting = false;
    this.currentSelection = [];
    this.draw();
  }

  isSelected(element) {
    for (var day = 0; day < this.days.length; day++) {
      if (this.days[day].indexOf(element) != -1) {
        return true;
      }
    }
    return false;
  }

  onCalendarPress(event) {
    this.selecting = true;
    this.onCalendarOver(event);
  }

  onCalendarRelease(event) {
    var e = event.target;
    this.selecting = false;
    if (e.dataset.selected == "false") {
      this.onCalendarOver(event);
      if (this.onSelected) {
        this.onSelected(event);
      }
      this.reorder();
      this.render();
    } else if (this.onHourClick) {
      this.onHourClick(event);
    }
  }

  onCalendarOver(event) {
    var e = event.target;
    var day = e.dataset.day;
    if (
      this.selecting
      && !this.isSelected(e)
    ) {
      e.setAttribute("data-selected", "true");
      this.currentSelection.push(e);
      e.style.background = "#defffe";
      this.days[day].push(e);
    }
  }

  reorder() {
    for (var day = 0; day < this.days.length; day++) {
      this.days[day].sort(function (a, b) {
        return Date.parse("1970-01-01 " + a.dataset.start) - Date.parse("1970-01-01 " + b.dataset.start);
      });
    }
  }

  render() {


    for (var day = 0; day < this.days.length; day++) {
      var lastStart = null;
      var lastEnd = null;
      var lastElement = null;
      var elements = 0;
      var dayHours = [];
      for (var i = 0; i < this.days[day].length; i++) {
        dayHours.push(this.days[day][i]);
      }
      for (var i = 0; i < dayHours.length; i++) {
        var element = dayHours[i];
        var start = element.dataset.start;
        var end = element.dataset.end;
        if (lastEnd == start && lastElement.innerText == element.innerText) {
          lastEnd = end;
          lastElement.style.background = "#defffe";
          lastElement.setAttribute("data-end", lastEnd);
          elements += 1;
          lastElement.className = "col-" + elements + " calendar-box";
          element.remove();
          var index = this.days[day].indexOf(element);
          if (index != -1) {
            this.days[day].splice(index, 1);
          }
        } else {
          lastStart = start;
          lastEnd = end;
          lastElement = element;
          elements = 1;
        }
      }
    }
  }

  draw() {
    this.element.innerHTML = "";

    var header = document.createElement("div");
    header.classList = "row mt-2";

    var spacer = document.createElement("div");
    spacer.classList = "calendar-day-spacer col";

    var btn = document.createElement("button");
    btn.classList = "btn btn-outline-danger col-6 float-right";
    btn.innerText = "Cancella";
    btn.onclick = () => { this.reset(); };

    var select = document.createElement("select");
    select.classList = "custom-select col-5";
    select.innerHTML = "<option disabled selected>Settimana</option>";
    select.innerHTML += "<option>A</option>";
    select.innerHTML += "<option>B</option>";
    select.onchange = (event) => { this.week = event.target.value };


    spacer.append(select);
    spacer.append(btn);
    header.append(spacer);

    for (var i = 0; i < this.hours.length; i++) {
      var start = this.hours[i].start;
      var end = this.hours[i].end;
      var allow = this.hours[i].allow;
      var div = document.createElement("div");
      div.classList = "calendar-hour col-1";
      div.innerHTML = "<br>" + start + "<br>" + end;
      if (!allow) {
        div.style.background = "#d4d4d4";
      }
      header.append(div);
    }

    this.element.append(header);

    for (var i = 0; i < this.labels.length; i++) {

      var row = document.createElement("div");
      row.classList = "row";

      var label = document.createElement("div");
      label.classList = "col calendar-day";
      var date = document.createElement("input");
      date.type = "date";
      date.style.fontSize = "10px";
      date.classList = "form-control col-7 float-left";
      date.setAttribute("data-index", i);
      date.addEventListener("change", (event) => {
        this.dates[event.target.dataset.index] = event.target.value;
      });
      label.append(date);
      var text = document.createElement("b");
      text.innerText = this.labels[i];
      label.append(text);
      row.append(label);

      for (var s = 0; s < this.hours.length; s++) {
        var div = document.createElement("div");
        div.classList = "calendar-box col-1";
        if (this.hours[s].allow) {
          div.setAttribute("data-start", this.hours[s].start);
          div.setAttribute("data-end", this.hours[s].end);
          div.setAttribute("data-day", i);
          div.setAttribute("data-selected", false);
          div.onmousedown = (event) => { this.onCalendarPress(event); };
          div.onmouseup = (event) => { this.onCalendarRelease(event); };
          div.onmouseover = (event) => { this.onCalendarOver(event); };
        } else {
          div.style.background = "#d4d4d4";
        }
        row.append(div);
      }
      this.element.append(row);
    }
  }
}