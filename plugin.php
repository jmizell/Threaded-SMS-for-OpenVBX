<?php
include("functions.php");

$account = OpenVBX::getAccount();
$phoneNumbers = getAccountPhoneNumbers($account);

if (count(PluginData::sqlQuery("select * from addressbook_contacts limit 1")) == 1) {
  $address_book = True;
} else {
  $address_book = False;
}

$to = filter_var($_REQUEST['to'], FILTER_SANITIZE_NUMBER_FLOAT);
$from    = filter_var($_REQUEST['from'], FILTER_SANITIZE_NUMBER_FLOAT);
$content = filter_var($_REQUEST['content'], FILTER_SANITIZE_STRING);

if ( $to != "" ) {
  $function = "contact";
  if ( $content != "" && $from != "") {
    sendSMS($account, $to, $from, $content);
  }
}

// Set a limit of items per page
$limit = 100;
$threads = getMessageThreads($account, $limit, $phoneNumbers);

if ( $function == "contact" ) {
  include("view-single-thread.php"); 
} else {
  include("view-threads-list.php"); 
}
?>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js"></script>
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" />
