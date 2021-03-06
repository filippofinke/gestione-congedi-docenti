/**
 * FinkeLendar.js
 * Classe utilizzata per la generazione e rappresentazione del calendario lato client.
 * 
 * @author Filippo Finke
 */
class FinkeLendar {

  /**
   * Metodo costruttore per il calendario.
   * 
   * @param element Elemento nel quale renderizzare il calendario.
   * @param labels I giorni da mostrare nel calendario.
   * @param hours Le ore disponibili alla selezione.
   */
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

  /**
   * Metodo utilizzato per impostare la settimana del calendario 'A' o 'B'. 
   *
   * @param week La settimana del calendario.
   */
  setWeek(week) {
    this.week = week;
    this.select.value = week;
  }

  /**
   * Metodo utilizzsato per impostare il callback quando si preme un blocco nel calendario.
   * 
   * @param callback Funzione da chiamare al click di un blocco nel calendario.
   */
  setOnHourClick(callback) {
    this.onHourClick = callback;
  }

  /**
   * Metodo utilizzsato per impostare il callback quando si seleziona un blocco nel calendario.
   * 
   * @param callback Funzione da chiamare al select di un blocco nel calendario.
   */
  setOnSelected(callback) {
    this.onSelected = callback;
  }

  /**
   * Metodo utilizzato per resettare il calendario.
   */
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

  /**
   * Metodo utilizzato per controllare se un elemento è gia stato selezionato.
   * 
   * @param element Il blocco da controllare.
   */
  isSelected(element) {
    for (var day = 0; day < this.days.length; day++) {
      if (this.days[day].indexOf(element) != -1) {
        return true;
      }
    }
    return false;
  }

  /**
   * Metodo chiamato quando si preme un blocco nel calendario.
   * 
   * @param event Evento del mouse.
   */
  onCalendarPress(event) {
    this.selecting = true;
    this.onCalendarHover(event);
  }

  /**
   * Metodo chiamato quando si rilascia il mouse su un blocco del calendario.
   * 
   * @param event Evento del mouse.
   */
  onCalendarRelease(event) {
    var e = event.target;
    this.selecting = false;
    if (e.dataset.selected == "false") {
      this.onCalendarHover(event);
      if (this.onSelected) {
        this.onSelected(event);
      }
      this.reorder();
      this.render();
    } else if (this.onHourClick) {
      this.onHourClick(event);
    }
  }

  /**
   * Metodo chiamato quando si passa con il mouse su un blocco del calendario.
   * 
   * @param event Evento del mouse.
   */
  onCalendarHover(event, bypass = false) {
    var e = event.target;
    var day = e.dataset.day;
    if (
      (this.selecting
        && !this.isSelected(e)) || bypass
    ) {
      e.setAttribute("data-selected", "true");
      this.currentSelection.push(e);
      e.style.background = "#defffe";
      this.days[day].push(e);
    }
  }

  /**
   * Metodo utilizzato per riordinare la selezione dei blocchi nel calendario.
   */
  reorder() {
    for (var day = 0; day < this.days.length; day++) {
      this.days[day].sort(function (a, b) {
        return Date.parse("1970-01-01 " + a.dataset.start) - Date.parse("1970-01-01 " + b.dataset.start);
      });
    }
  }

  /**
   * Metodo utilizzato per verificare ed unire i blocchi del calendario.
   */
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

  /**
   * Metodo per ricavare il lunedì della settimana corrente.
   */
  getMonday() {
    var d = new Date();
    var day = d.getDay(),
      diff = d.getDate() - day + (day == 0 ? -6 : 1);
    return new Date(d.setDate(diff));
  }

  /**
   * Metodo utilizzato per stampare il calendario nel contenitore.
   */
  draw() {
    this.element.innerHTML = "";

    var header = document.createElement("div");
    header.classList = "row mt-2";

    var spacer = document.createElement("div");
    spacer.classList = "calendar-day-spacer col";

    var btn = document.createElement("button");
    btn.classList = "btn btn-danger col-4 float-left mr-3";
    btn.innerText = "X";
    btn.onclick = () => {
      if (confirm("Sei sicuro di voler cancellare le date selezionate?")) {
        this.reset();
      }
    };

    this.select = document.createElement("select");
    this.select.classList = "custom-select col-6";
    this.select.innerHTML = "<option disabled selected>Settimana</option>";
    this.select.innerHTML += "<option>A</option>";
    this.select.innerHTML += "<option>B</option>";
    this.select.onchange = (event) => { this.week = event.target.value };


    spacer.append(btn);
    spacer.append(this.select);
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

    var monday = this.getMonday();
    var today = new Date();
    const zeroPad = (num, places) => String(num).padStart(places, '0')

    for (var i = 0; i < this.labels.length; i++) {

      var row = document.createElement("div");
      row.classList = "row";

      var label = document.createElement("div");
      label.classList = "col calendar-day";
      var date = document.createElement("input");
      date.type = "date";
      date.step = "7";
      var min = new Date(monday.getTime() + 86400000 * i);
      if (today.getTime() > min.getTime()) {
        min.setTime(min.getTime() + 86400000 * 7);
      }
      date.min = min.getFullYear() + "-" + zeroPad((min.getMonth() + 1), 2) + "-" + zeroPad(min.getDate(), 2);
      date.classList = "form-control col-7 float-left ml-3";
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
          div.onmouseover = (event) => { this.onCalendarHover(event); };
        } else {
          div.style.background = "#d4d4d4";
        }
        row.append(div);
      }
      this.element.append(row);
    }
  }
}

