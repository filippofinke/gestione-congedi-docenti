class FinkeLendar {

  constructor(element, labels, hours) {
    this.element = element;
    this.labels = labels;
    this.days = [];
    for (var i = 0; i < labels.length; i++) {
      this.days.push([]);
    }
    this.hours = hours;
    this.selecting = false;
    this.currentSelection = [];
  }

  reset() {
    this.days = [];
    for (var i = 0; i < labels.length; i++) {
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
    if (e.innerHTML == "") {
      this.onCalendarOver(event);
      var room = prompt("Testo di prova: ", "");
      for (var i = 0; i < this.currentSelection.length; i++) {
        var element = this.currentSelection[i];
        element.innerText = room;
      }
      this.reorder();
      this.render();
    } else {
      this.onCalendarClick(e);
    }
    this.currentSelection = [];
  }

  onCalendarClick(element) {
    var edit = prompt("Modifica: ");
    element.innerHTML = edit;
    this.reorder();
    this.render();
  }

  onCalendarOver(event) {
    var e = event.target;
    var day = e.dataset.day;
    if (
      this.selecting
      && !this.isSelected(e)
    ) {
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
      for (var i = 0; i < this.days[day].length; i++) {
        var element = this.days[day][i];
        var start = element.dataset.start;
        var end = element.dataset.end;
        if (lastEnd == start && lastElement.innerText == element.innerText) {
          lastEnd = end;
          lastElement.style.background = "#defffe";
          elements += 1;
          lastElement.className = "col-" + elements + " calendar-box";
          element.remove();
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
    btn.classList = "btn btn-outline-danger float-right";
    btn.innerText = "Cancella";
    btn.onclick = () => { this.reset(); };

    spacer.append(btn);
    header.append(spacer);

    for (var i = 0; i < hours.length; i++) {
      var start = hours[i].start;
      var end = hours[i].end;
      var allow = hours[i].allow;
      var div = document.createElement("div");
      div.classList = "calendar-hour col-1";
      div.innerHTML = start + "<br>" + end;
      if (!allow) {
        div.style.background = "#d4d4d4";
      }
      header.append(div);
    }

    this.element.append(header);

    for (var i = 0; i < labels.length; i++) {

      var row = document.createElement("div");
      row.classList = "row";

      var label = document.createElement("div");
      label.classList = "col calendar-day";
      label.innerHTML = "<b>" + labels[i] + "</b>";

      row.append(label);

      for (var s = 0; s < hours.length; s++) {
        var div = document.createElement("div");
        div.classList = "calendar-box col-1";
        if (hours[s].allow) {
          div.setAttribute("data-start", hours[s].start);
          div.setAttribute("data-end", hours[s].end);
          div.setAttribute("data-day", i);
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