@php
setcookie("id", null, time() - 3600, "/");
setcookie("salt", null, time() - 3600, "/");
@if(!empty($_GET['redirect']))
    $redirect=$_GET['redirect'];
    header("Location: /user/login?redirect=$redirect");
 @else
    header("Location: /user/login");
@endif
exit();
@endphp
