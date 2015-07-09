<?php
/**
 *Send a text only message to a recepient
 *
 *@param object Twilio account object
 *@param string $to Recepient
 *@param string $from Sender message
 *@param string $message Message contents
 *
 *@return void
 */
function sendSMS($account, $to, $from, $content) {
  $account->messages->create(array( 
    'To' => "$to", 
    'From' => "$from", 
    'Body' => "$content",   
  ));
}

/**
 *Returns a first name, and last name associated with a phone number. This requires the address book
 *    plugin
 *
 * @param string $number Number of contact to look up.
 *
 * @return string
 */
function lookupNumber($number) {
  $entry = PluginData::sqlQuery("select * from addressbook_contacts where phone='$number' limit 1");
  if ( count($entry) == 1 ) {
    $name = $entry[0]["first_name"] . " " . $entry[0]["last_name"];
    return $name;
  } else {
    return $number;
  }
}

/**
 * Returns an array of phone numbers associated with the account
 *
 * @param object $account Twilio api object
 *
 * @return array
 */
function getAccountPhoneNumbers($account) {
  $numbers = $account->incoming_phone_numbers->getIterator(0, 50, array());
  foreach ($numbers as $number) {
    $phoneNumbers[] = $number->phone_number;
  }
  return $phoneNumbers;
}

/**
 *Inserts into the database messages present in the api, but missing in sql, and returns the
 *  inserted entry.
 *
 *@param array Message object from twilio api
 *@param string Message sid
 *
 *@return array
 */
function insertMissingMessage($message, $sid) {
  PluginData::sqlQuery("INSERT INTO messages SET created='" . $message->date_created . "', updated='', call_sid='". $message->sid . "', caller='" . $message->from . "', called='" . $message->to . "', type='sms', status='new', content_url='', content_text='" . $message->body . "', notes='', size='', assigned_to='', archived='0', ticket_status='',tenant_id='1'");
  return PluginData::sqlQuery("SELECT * FROM messages WHERE call_sid='$sid'");
}

/**
 * Returns an array containing the selected details of a single message
 *
 * @param array Message object from twilio api
 * @param string sid of message
 * @param string the contact associated with the message
 *
 * @return array
 */
function getMessageArray($message, $sid, $contact) {
  $result = PluginData::sqlQuery("SELECT * FROM messages WHERE call_sid='$sid'");
  
  if ( count($result) == 0 ) {
    $result = insertMissingMessage($message, $sid);
  }
  
  return Array(
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

/**
 * Returns an multidimensional array containing contacts, and messages
 *
 * @param object $account The twilio account object
 * @param int $limit How many messages to retreive from the account (broken)
 * @param array $phoneNumbers Contains phone numbers associated with the account
 * @param string $to Determines how much data is returned, set to empty only the first message of
 *    each contact is returned, set to all, all messages are returned, and when a contact
 *    phone number is specified, only the contacts messages are returned. Phone number is in the
 *    form of +00000000000
 *
 * @return array
 */
function getThreads($account, $limit, $phoneNumbers, $to = "") {
  $messages = $account->messages->getIterator(0, $limit, array());
  
  $threads = array();
  foreach ($messages as $message) {
    if (in_array($message->to, $phoneNumbers)) {
      $contact = $message->from;
    } else {
      $contact = $message->to;
    }
    $sid = $message->sid;
    
    switch ($to) {
      case "":
        if (empty($threads[$contact])) {
          $message_array = getMessageArray($message, $sid, $contact);
        
          if ( $message_array["archived"] == 0 ) {
            $threads[$contact][] = $message_array;
          }
        }
        break;
      case "all":
        $message_array = getMessageArray($message, $sid, $contact);
      
        if ( $message_array["archived"] == 0 ) {
          $threads[$contact][] = $message_array;
        }
        break;
      default:
        if ($contact == $to) {
          $message_array = getMessageArray($message, $sid, $contact);
        
          if ( $message_array["archived"] == 0 ) {
            $threads[$contact][] = $message_array;
          }
        }
    }
    
  }
  return $threads;
}

?>