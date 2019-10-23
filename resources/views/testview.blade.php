<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
      </script>

      <script>
         function getMessage() {
            $.ajax({
              headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              type: 'POST',
              url: '/getmsg',
              dataType: 'json',
              success:function(response) {
                var currencyRates = response.data;
                var html = '<ul>';
                for (var i=0; i < currencyRates.length; i++) {
                  html += '<li><p>Quote: ' +
                  currencyRates[i]["quote"] +
                  '</p><p>Rate: ' +
                  currencyRates[i]["rate"] +
                  '</p></li>';
                }
                html += '</ul>';

                $("#msg").html(html);
              }
            });
         }
      </script>

        <title>Test View</title>
    </head>
    <body>
      <div id = 'msg'>Get currency rates from DB:
        <?php
          echo Form::button('Request data',['onClick'=>'getMessage()']);
        ?>
      </div>
    </body>
</html>
