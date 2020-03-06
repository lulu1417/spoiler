<!DOCTYPE html>
<html>
<head>
    <title>Facebook Login success</title>
    <meta charset="UTF-8">
</head>
<body>
<script>

    // function loginSuccess (){
    //
        window.opener.postMessage('{{$token}}', '*');
        window.close();
        // setTimeout(window.close(),1000)

    // }
    // loginSuccess()

</script>
</body>

</html>
