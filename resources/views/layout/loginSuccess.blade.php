<?php
use App\Http\Controllers\FBController;
?>
    <!DOCTYPE html>
<html>
<head>
    <title>Facebook Login success</title>
    <meta charset="UTF-8">
</head>
<body>
<script>
    window.opener.postMessage({{$token}});
</script>
</body>

</html>