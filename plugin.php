<?php
include("functions.php");

$account = OpenVBX::getAccount();
$phoneNumbers = getAccountPhoneNumbers($account);

//detect if the address book is present, if so then we can attempt to lookup contacts
if (count(PluginData::sqlQuery("select * from addressbook_contacts limit 1")) == 1) {
  $address_book = True;
} else {
  $address_book = False;
}

$to = filter_var($_REQUEST['to'], FILTER_SANITIZE_NUMBER_FLOAT);
$from    = filter_var($_REQUEST['from'], FILTER_SANITIZE_NUMBER_FLOAT);
$content = filter_var($_REQUEST['content'], FILTER_SANITIZE_STRING);

// if the to field is set, we're viewing a single thried
if ( $to != "" ) {
  $function = "contact";
  if ( $content != "" && $from != "") {
    sendSMS($account, $to, $from, $content);
  }
}

$limit = 100; // Set a limit of items per page
$threads_per_page = 20; // limit of items to display for page, contacts or messages
$threads_offset = filter_var($_REQUEST['offset'], FILTER_SANITIZE_NUMBER_INT); //offset from begining of messages to display
if ( $threads_offset == "" ) {
  $threads_offset = 0; //default offset value 
}

if ( $function == "contact" ) {
  $allthreads = getThreads($account, $limit, $phoneNumbers, $to);
  $threads = array_slice($allthreads[$to], $threads_offset, $threads_per_page);
  include("view-single-thread.php"); 
} else {
  $allthreads = getThreads($account, $limit, $phoneNumbers);
  $threads = array_slice($allthreads, $threads_offset, $threads_per_page);
  include("view-threads-list.php"); 
}
?>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js"></script>
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" />
