
<!DOCTYPE html>
<head>
    <title>New Food Pusher Test</title>
    <script src="https://js.pusher.com/5.1/pusher.min.js"></script>
{{--    <script src="{{ asset('/js/app.js') }}"></script>--}}
    <script>
        var restaurant_id = 2;
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('{{env('MIX_PUSHER_APP_KEY')}}', {
            cluster: '{{env('MIX_PUSHER_APP_CLUSTER')}}',
            forceTLS: true
        });

        var channel = pusher.subscribe('food-channel.'+restaurant_id);
        channel.bind('food-event', function(data) {
            console.log('Pusher');
            alert(JSON.stringify(data));
        });

        // var channelEcho = window.Echo.channel('food-channel');
        // channelEcho.listen('.food-event', function (data) {
        //     console.log('Echo');
        //     console.log(data);
        // });

    </script>
</head>
<body>
<h1>New Food Pusher Test</h1>
<p>
    Try publishing an event to channel <code>food-channel+restaurant_id</code>
    with event name <code>food-event</code>.
    <script>

    </script>
</p>
</body>
