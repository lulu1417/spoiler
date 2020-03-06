<!DOCTYPE html>
<html>
<head>
    <title>Facebook Login success</title>
    <meta charset="UTF-8">
</head>
<body>
<script>
    window.opener.postMessage('{{$token}}', '*');
    window.close();
</script>
</body>

</html>
