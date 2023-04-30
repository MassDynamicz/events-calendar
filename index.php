<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="fullcalendar/fullcalendar.min.css" />
  <script src="fullcalendar/fullcalendar.min.js"></script>
  <style>
    body {
      margin-top: 50px;
      text-align: center;
      font-size: 14px;
      font-family: Helvetica, Arial, Verdana, sans-serif;
    }

    #calendar {
      width: 800px;
      margin: 0 auto;
    }
    .response {
      display: none;
      justify-content: center;
      align-items: center;
      position: fixed;
      bottom: 10vh;
      right: 3vw;
      z-index: 9999;
    }
    .response.active {
      display: flex;
    }
    .success {
      background-color: #27ae60;
      color: #fff;
      padding: 15px 20px;
      box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
    }
  </style>
</head>

<body>
  <h1>Календарь событий</h1>
  <div class="response"></div>
  <div id='calendar'></div>
</body>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    locale: 'ru',
    firstDay: 1,
    headerToolbar: {
      start: 'title', 
      center: '',
      end: 'today prev,next' 
    },
    buttonText: {today: 'Текущая дата'},
    eventSources: [{url: 'api/fetch-event.php'}],
    displayEventTime: false, 
    initialView: 'dayGridMonth',
    eventDisplay: 'block',

    eventDidMount: function(info) {
      // Определяем цвет шрифта в зависимости от яркости фона
      var textColor = isDark(info.event.backgroundColor) ? "#ffffff" : "#000000";
      info.el.style.color = textColor;

      // Изменяем цвет текста внутри элемента события
      var eventTitle = info.el.querySelector('.fc-event-title');
      if (eventTitle) {
        eventTitle.style.color = textColor;
      }
    },
    selectable: true,
    select: function(info) {
      var title = prompt('Название события:');
      if (title) {
        var start = info.startStr;
        var end = info.endStr;
        var color = getRandomColor(); 

        fetch('api/add-event.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'title=' + title + '&start=' + start + '&end=' + end + '&color=' + color
        })
        .then(response => response.text())
        .then(data => {
          displayMessage("Событие добавлено");
          // Перезагрузка событий с сервера
          calendar.refetchEvents();
        });
      }
      calendar.unselect();
    },
    editable: true,
    eventDrop: function(info) {
      var start = info.event.startStr;
      var end = info.event.endStr;
      fetch('api/edit-event.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'title=' + info.event.title + '&start=' + start + '&end=' + end + '&id=' + info.event.id
      })
      .then(response => response.text())
      .then(data => {
        displayMessage("Событие обновлено");
      });
    },
    eventClick: function(info) {
      var deleteMsg = confirm("Вы точно хотите удалить событие?");
      if (deleteMsg) {
        fetch('api/delete-event.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: '&id=' + info.event.id
        })
        .then(response => response.text())
        .then(data => {
          if (parseInt(data) > 0) {
            info.event.remove();
            displayMessage("Событие удалено");
          }
        });
      }
    }
  });
  calendar.render();

  function displayMessage(message) {
    var responseEl = document.querySelector('.response');
    responseEl.classList.add('active');
    responseEl.innerHTML = "<div class='success'>" + message + "</div>";
    setTimeout(function() {
      responseEl.classList.remove('active');
      responseEl.innerHTML = "";
    }, 2000);
  }
});
  
  // Генерация случайного цвета
  function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }
  // Определяем светлый или темный фон
  function isDark(hex) {
    var rgb = parseInt(hex.replace('#', ''), 16);
    var r = (rgb >> 16) & 0xff;
    var g = (rgb >>  8) & 0xff;
    var b = (rgb >>  0) & 0xff;
    var brightness = ((r * 299) + (g * 587) + (b * 114)) / 1000;
    return brightness < 128;
  }

</script>

</html>