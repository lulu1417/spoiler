<!DOCTYPE html>
<html>
<head>
    <title>Facebook Login Failed</title>
    <meta charset="UTF-8">
</head>
<body>
<script>
    var message = new Object();
    message.status = 'fail';
    message.content = 'login failed.'

    window.opener.postMessage(message, '*');
    window.close();

</script>
</body>

</html>

