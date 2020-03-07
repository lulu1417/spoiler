<!DOCTYPE html>
<html>
<head>
    <title>Facebook Login Success</title>
    <meta charset="UTF-8">
</head>
<body>
<script>
    var message = new Object();
    message.status = 'success';
    message.content = '{{$user}}'

    window.opener.postMessage(message, '*');
    window.close();

</script>
</body>

</html>
