
<!DOCTYPE html>
<head>
    <title>New Order Pusher Test</title>
    <script src="https://js.pusher.com/5.1/pusher.min.js"></script>
    <script>

        var restaurant_id = 2;

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
            cluster: '{{env('PUSHER_APP_CLUSTER')}}',
            forceTLS: true
        });

        var channelPusher = pusher.subscribe('order-channel.'+restaurant_id);
        channelPusher.bind('order-event', function (data) {
            console.log(data);
            alert(JSON.stringify(data));
        });

    </script>
</head>
<body>
<h1>New Order Pusher Test</h1>
<p>
    Try publishing an event to channel <code>order-channel+restaurant_id</code>
    with event name <code>order-event</code>.
    <script>

    </script>
</p>
</body>
