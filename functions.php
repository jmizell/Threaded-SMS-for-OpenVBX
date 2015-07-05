<?php
function sendSMS($account, $to, $from, $content) {
  $account->messages->create(array( 
    'To' => "$to", 
    'From' => "$from", 
    'Body' => "$content",   
  ));
}

function lookupNumber($number) {
  $entry = PluginData::sqlQuery("select * from addressbook_contacts where phone='$number' limit 1");
  if ( count($entry) == 1 ) {
    $name = $entry[0]["first_name"] . " " . $entry[0]["last_name"];
    return $name;
  } else {
    return $number;
  }
}

function getAccountPhoneNumbers($account) {
  $numbers = $account->incoming_phone_numbers->getIterator(0, 50, array());
  foreach ($numbers as $number) {
    $phoneNumbers[] = $number->phone_number;
  }
  return $phoneNumbers;
}


function getMessageThreads($account, $limit, $phoneNumbers) {
  $messages = $account->messages->getIterator(0, $limit, array());
  $timezone = PluginData::get("timezone",'UM8');
  
  $threads = array();
  foreach ($messages as $message) {
    if (in_array($message->to, $phoneNumbers)) {
      $contact = $message->from;
    } else {
      $contact = $message->to;
    }
    $sid = $message->sid;
    $result = PluginData::sqlQuery("SELECT * FROM messages WHERE call_sid='$sid'");
    
    if ( count($result) == 0 ) {
      PluginData::sqlQuery("INSERT INTO messages SET created='" . $message->date_created . "', updated='', call_sid='". $message->sid . "', caller='" . $message->from . "', called='" . $message->to . "', type='sms', status='new', content_url='', content_text='" . $message->body . "', notes='', size='', assigned_to='', archived='0', ticket_status='',tenant_id='1'");
      $result = PluginData::sqlQuery("SELECT * FROM messages WHERE call_sid='$sid'");
    }
  
    if ( $result[0]["archived"] == 0 ) {
      $threads[$contact][] = Array(
        'id' => $result[0]["id"],
        'sid' => $sid,
        'contact' => $contact,
        'num_media' => $message->num_media,
        'media' => $message->media,
        'caller' => $message->from,
        'created' => $message->date_created,
        'status' => $result[0]["status"],
        'archived' => $result[0]["archived"],
        'body' => $message->body
      );
    }
  }
  return $threads;
}
?>