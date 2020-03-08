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
    message.id = '{{$user['id']}}'
    message.name = '{{$user['name']}}'
    message.account = '{{$user['account']}}'
    message.api_token = '{{$user['api_token']}}'
    message.image = '{{$user['image']}}'
    message.email = '{{$user['email']}}'
    message.point = '{{$user['point']}}'
    message.phone = '{{$user['phone']}}'

    console.log(message)

    window.opener.postMessage(message, '*');
    window.close();

</script>
</body>

</html>
