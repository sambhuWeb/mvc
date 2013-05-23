<html>
  <head>
    <title>Use Header variable</title>    
    <link href=<?= $this->cssFilesLink; ?> rel="stylesheet">
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

    <div id="content">