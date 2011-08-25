<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
  <title>Ekonomi</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link type="text/css" rel="stylesheet" media="all" href="/CodeIgniter_2.0.2/application/views/css/default.css" />
  <link type="text/css" rel="stylesheet" media="all" href="/CodeIgniter_2.0.2/application/views/css/verification.css" />
  <link type="text/css" rel="stylesheet" media="all" href="/CodeIgniter_2.0.2/application/views/js/jquery-autocomplete/jquery.autocomplete.css" />
  <link type="text/css" rel="stylesheet" media="all" href="/CodeIgniter_2.0.2/application/views/css/impromptu.css" />
  <script type="text/javascript" src="/CodeIgniter_2.0.2/application/views/js/jquery-1.6.min.js"></script>
  <script type="text/javascript" src="/CodeIgniter_2.0.2/application/views/js/jquery-autocomplete/jquery.autocomplete.min.js"></script>
  <script type="text/javascript" src="/CodeIgniter_2.0.2/application/views/js/jquery-impromptu.3.1.min.js"></script>

  <? foreach(BaseSystem::singleton()->GetJs() as $file) { ?>
    <script type="text/javascript" src="/CodeIgniter_2.0.2/application/<?=$file?>"></script>
  <? } ?>

  <? foreach(BaseSystem::singleton()->GetCss() as $file) { ?>
    <link type="text/css" rel="stylesheet" media="all" href="/CodeIgniter_2.0.2/application/<?=$file?>" />
  <? } ?>
</head>
<body>

<div id="container">
  <div id="header">
    <h1>Bokföring</h1>
  </div>

  <div id="menu">
    <h3>Bokföring</h3>
    <ul>
      <li><a href="/CodeIgniter_2.0.2/index.php/accounting/masterledger">Huvudbok</a></li>
      <li><a href="/CodeIgniter_2.0.2/index.php/accounting">Verifikationer</a></li>
      <li><a href="/CodeIgniter_2.0.2/index.php/accounting/accounts">Konton</a></li>
      <li><a href="/CodeIgniter_2.0.2/index.php/accounting/balance">Balansrapport</a></li>
      <li><a href="/CodeIgniter_2.0.2/index.php/accounting/result">Resultatrapport</a></li>
      <li><a href="/CodeIgniter_2.0.2/index.php/invoice">Fakturor</a></li>
      <li><a href="/CodeIgniter_2.0.2/index.php/nordea">Nordea kontoutdrag</a></li>
      <li><a href="/CodeIgniter_2.0.2/index.php/accounting/test">Test</a></li>
    </ul>

    <h3>Övrigt</h3>
    <ul>
      <li><a href="/CodeIgniter_2.0.2/index.php/dashboard">Dashboard</a></li>
      <li><a href="#">Kunder</a></li>
      <li><a href="#">Leverantörer</a></li>
      <li><a href="#">Projekt</a></li>
      <li><a href="#">Tidrapport</a></li>
      <li><a href="#"></a></li>
      <li><a href="#"></a></li>
      <li><a href="#"></a></li>
    </ul>
  </div>

  <div id="main">
    <? if(Message::IsMessages()) { ?>
    <div class="message">
      <?=Message::GetMessages()?>
    </div>
    <? } ?>

    <? if(Message::IsErrors()) { ?>
    <div class="error">
      <?=Message::GetErrors()?>
    </div>
    <? } ?>

