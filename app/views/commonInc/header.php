<html>
  <head>
    <title>Use Header variable</title>
    <link href="/loader/css/bootstrap|bootstrap-responsive|default|testcss/" rel="stylesheet" type="text/css" />
    <!--link href="<?= _CURRENT_DOMAIN . DS ?>public/bootstrap/bscss/bootstrap.min.css" rel="stylesheet">
    <link href="<?= _CURRENT_DOMAIN . DS ?>public/bootstrap/bscss/bootstrap-responsive.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= _CURRENT_DOMAIN . DS ?>public/css/default.css" /-->
  </head>
  <body>  
    <div id="header">
      Header
      <br />
      <a href="<?= _CURRENT_DOMAIN . DS ?>index">Index</a>
      <a href="<?= _CURRENT_DOMAIN . DS ?>help">Help</a>
      <?php if (Session::get('loggedIn') == true):?>
        <a href="<?= _CURRENT_DOMAIN . DS ?>dashboard/logout">Logout</a>
      <?php else: ?>
        <a href="<?= _CURRENT_DOMAIN . DS ?>login">Login</a>
      <?php endif; ?>
    </div>

    <div Id="content">