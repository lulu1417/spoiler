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
    message.content.id = '{{$user['id']}}'
    message.content.name = '{{$user['name']}}'
    message.content.account = '{{$user['account']}}'
    message.content.api_token = '{{$user['api_token']}}'
    message.content.image = '{{$user['image']}}'
    message.content.email = '{{$user['email']}}'
    message.content.point = '{{$user['point']}}'
    message.content.phone = '{{$user['phone']}}'

    console.log(message)

    window.opener.postMessage(message, '*');
    window.close();

</script>
</body>

</html>
