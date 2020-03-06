<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/5.1/pusher.min.js"></script>
    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
            cluster: process.env.MIX_PUSHER_APP_CLUSTER,
            forceTLS: true
        });

        var channel = pusher.subscribe('my-channel');
        console.log(data);
        // channel.bind('my-event', function(data) {
        //     alert(JSON.stringify(data));
        // });
    </script>
</head>
<body>
<h1>Pusher Test</h1>
<p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
</p>
</body>

