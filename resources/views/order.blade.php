<!DOCTYPE html>
<head>
    <title>New Order Pusher Test</title>
    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="https://js.pusher.com/5.1/pusher.min.js"></script>
    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('{{env('MIX_PUSHER_APP_KEY')}}', {
            cluster: '{{env('MIX_PUSHER_APP_CLUSTER')}}',
            forceTLS: true
        });

        var channelPusher = pusher.subscribe('order-channel');
        channelPusher.bind('order-event', function (data) {
            console.log('Js');
            console.log(data);
        });


        var channelEcho = window.Echo.channel('order-channel');
        channelEcho.listen('.order-event', function (data) {
            console.log('Echo');
            console.log(data);
        });


    </script>
</head>
<body>
<h1>New Order Pusher Test</h1>
<p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
    <script>

    </script>
</p>
</body>
