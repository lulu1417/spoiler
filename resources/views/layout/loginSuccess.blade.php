<!DOCTYPE html>
<html>
<head>
    <title>Facebook Login success</title>
    <meta charset="UTF-8">
</head>
<body>
<script>
    token = '{{$token}}';
    function loginSuccess (token){

        window.opener.postMessage('token', '*');
        setTimeout(window.close(),1000)

    }
    loginSuccess(token)

</script>
</body>

</html>
