<?php
echo "this is a login page <br />";
//echo dirname(dirname(__FILE__)).WFDS;
echo "<br />";
echo _OPDS;
echo _SERVER_TYPE;
?>

<h1>Login</h1>

<form action="login/run" method="post">
  <label>Login</label><input type="text" name="login" /><br>
  <label>Password</label><input type="password" name="password" /><br>
  <label></label><input type="submit" />
</form>