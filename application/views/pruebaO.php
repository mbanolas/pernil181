
    <style>
      

      body_
      {
        max-width: 500px;
        margin: 2em auto;
        padding: 0 0.5em;
        font-size: 20px;
      }

      h1
      {
        text-align: center;
      }

      .hidden_
      {
        display: none;
      }

      #custom-notification_
      {
        margin-top: 2em;
      }

      label_
      {
        display: block;
      }

      input[name="title"],
      textarea
      {
        width: 100%;
      }

      input[name="title"]
      {
        height: 2em;
      }

      textarea
      {
        height: 5em;
      }

      .buttons-wrapper
      {
        text-align: center;
      }

      .button-demo
      {
        padding: 0.5em;
        margin: 1em;
      }

      #log
      {
        height: 200px;
        width: 100%;
        overflow-y: scroll;
        border: 1px solid #333333;
        line-height: 1.3em;
      }

      .author
      {
        display: block;
        margin-top: 3em;
      }
    </style>
  
 
    
    <script>
     if (!('Notification' in window)) {
        document.getElementById('wn-unsupported').classList.remove('hidden');
        document.getElementById('button-wn-show-preset').setAttribute('disabled', 'disabled');
        document.getElementById('button-wn-show-custom').setAttribute('disabled', 'disabled');
      }
      else {
        var log = document.getElementById('log');
        var notificationEvents = ['onclick', 'onshow', 'onerror', 'onclose'];

        function notifyUser(event) {
          var title;
          var options;

          event.preventDefault();

          if (event.target.id === 'button-wn-show-preset') {
            title = 'Email received';
            options = {
              body: 'You have a total of 3 unread emails',
              tag: 'preset',
              icon: 'http://www.audero.it/favicon.ico'
            };
          } else {
            title = document.getElementById('title').value;
            options = {
              body: document.getElementById('body').value,
              tag: 'custom'
            };
          }

          Notification.requestPermission(function() {
            var notification = new Notification(title, options);

            notificationEvents.forEach(function(eventName) {
              notification[eventName] = function(event) {
                log.innerHTML = 'Event "' + event.type + '" triggered for notification "' + notification.tag + '"<br />' + log.innerHTML;
              };
            });
          });
        }

        document.getElementById('button-wn-show-preset').addEventListener('click', notifyUser);
        document.getElementById('button-wn-show-custom').addEventListener('click', notifyUser);
        document.getElementById('clear-log').addEventListener('click', function() {
          log.innerHTML = '';
        });
      }
    </script>
  