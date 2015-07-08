  <div class="vbx-content-menu vbx-content-menu-top">
    <a href="<?php echo site_url("p/messages"); ?>" class="back-link">« Back to Inbox</a>
    <ul class="inbox-menu vbx-menu-items-right">
      <li class="menu-item">
        <a href="<?php echo site_url("p/messages/?to=" . urlencode($to)); ?>" class="link-button"><span>Reload</span></a>
      </li>
    </ul>
  </div><!-- .vbx-content-menu -->
  <table border="0" class="vbx-items-grid">
    <tbody>
      <tr>
        <td colspan="4">
           <form action="" method="POST" class="vbx-form" style="margin:8px;">
             <textarea rows="2" cols="150" name="content" style="width:100%;"></textarea>
             <input type="text"  name="to" value="<?php echo $to; ?>" style="float:left; margin: 5px 0 0 0;" readonly="readonly" />
             <select name="from" style="float:left; margin:5px; ">
               <?php foreach($phoneNumbers as $number): ?>
                 <option value="<?php echo $number; ?>"><?php echo $number; ?></option>
               <?php endforeach ?>
             </select>
             <button class="normal-button" type="submit" style="float:right;">
               <span>Send</span>
             </button>
             <button class="normal-button" type="reset" style="float:right; margin-right: 5px;">
               <span>Reset</span>
             </button>
           </form>
           <div style="clear:both;"></div>
         </td>
       </tr>
    </tbody>
  </table><!-- .vbx-items-grid -->
  
  <div class="vbx-content-menu vbx-content-menu-top">
    <ul class="inbox-menu vbx-menu-items-left">
      <li class="menu-item">
        <a href="" class="dropdown-select-button link-button"><span>Select</span></a>
        <ul class="hide">
          <li><a class="select select-all" href="">Select All</a></li>
          <li><a class="select select-none" href="">Select None</a></li>
          <li><a class="select select-read" href="">Select Read</a></li>
          <li><a class="select select-unread" href="">Select Unread</a></li>
        </ul>
      </li>
      <li class="menu-item">
        <a href="" class="delete-button link-button"><span>Delete</span></a>
      </li>
    </ul><!-- .vbx-menu-items -->
    <div class="pagination">
      <?php if (sizeof($allthreads[$to]) > $threads_per_page): ?>
        <?php foreach(range(0, sizeof($allthreads[$to]), $threads_per_page) as $offset): ?>
          <?php if ($offset == $threads_offset): ?>
            <span class="current"><?php echo ($offset / $threads_per_page + 1);?></span>
          <?php else: ?>
            <span class="num"><a href="<?php echo site_url("p/messages?offset=" . $offset . "&to=" . urlencode($to)) ?>"><?php echo ($offset / $threads_per_page + 1);?></a></span>
          <?php endif ?>
        <?php endforeach; ?>
        <span class="next"><a href="<?php echo site_url("p/messages?offset=" . ($threads_offset + $threads_per_page) . "&to=" . urlencode($to)) ?>">Next</a></span>
        <span class="last"><a href="<?php echo site_url("p/messages?offset=" . (sizeof($allthreads) - $threads_per_page) . "&to=" . urlencode($to)) ?>">»</a></span>
      <?php else: ?>
        <span class="current">1</span>
        <span class="next"><a href="<?php echo site_url("p/messages?offset=0&to=" . urlencode($to)) ?>">Next</a></span>
        <span class="last"><a href="<?php echo site_url("p/messages?offset=0&to=" . urlencode($to)) ?>">»</a></span>
      <?php endif ?>
    </div>	
  </div><!-- .vbx-content-menu -->
  
    <table border="0" class="vbx-items-grid">
    <tbody>
      <?php foreach($threads as $message): ?>
      <tr rel="<?php echo $message['id'] ?>" class="message-row sms-type <?php echo ($message['status'])? 'unread' : 'read'?>">
        <td class="message-select">
          <div style="padding: 6px">
            <input type="checkbox" name="message[id][]" value="<?php echo $message['id'] ?>" />
          </div>
        </td>
        <td class="message-caller message-details-link">
          <a href="<?php echo site_url("messages/details/{$message[0]['id']}")?>" class="quick-sms-button">
            <span class="replace"><?php echo $message[0]['contact'] ?></span>
          </a>
          <div id="quick-sms-popup-<?php echo $message[0]['id'] ?>" class="quick-sms-popup hide">
            <a href="" class="close action sms-toggler"><span class="replace">close</span></a>
            <input class="sms-message" type="text" name="content" />
            <span class="count">160</span>
            <button class="send-button" rel="<?php echo $message[0]['id'] ?>"><span>Send</span></button>
            <img class="sending-sms-loader hide" src="<?php echo asset_url('assets/i/ajax-loader.gif')?>" alt="..." />
            <p class="sms-to-phone hide"><?php echo $message[0]['contact'] ?></p>
            <p class="from-phone hide"><?php echo $phoneNumbers[0] ?></p>
          </div>
          <span class="phone-number">
              <?php
              if ($address_book) {
                echo lookupNumber($message['caller']);
              } else {
                echo $message['caller'];
              }
              ?>
          </span>
        </td>
        <td class="message-content message-details-link" style="overflow: visible; white-space: normal">
            <?php echo $message['body'] ?>
        </td>
        <td>
          <?php
            if ($message['num_media'] > 0) {
            foreach($message['media'] as $media) {
            echo "<a href='https://api.twilio.com" . $media->uri . "' style='text-decoration:none' data-lightbox='" . $message['id'] . "'>&#x1f4f7;</a>";
            //echo "     \     " . $media->content_type . "     \     ";
            }
            }
          ?>
        </td>
        <td class="message-timestamp message-details-link">
          <div class="unformatted-absolute-timestamp hide">
            <?php echo strtotime($message['created']) ?>
          </div>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table><!-- .vbx-items-grid -->
  
  <div class="vbx-content-menu vbx-content-menu-bottom">
    <ul class="inbox-menu vbx-menu-items-left">
      <li class="menu-item">
        <a href="" class="dropdown-select-button link-button"><span>Select</span></a>
        <ul class="hide">
          <li><a class="select select-all" href="">Select All</a></li>
          <li><a class="select select-none" href="">Select None</a></li>
          <li><a class="select select-read" href="">Select Read</a></li>
          <li><a class="select select-unread" href="">Select Unread</a></li>
        </ul>
      </li>
      <li class="menu-item">
        <a href="" class="delete-button link-button"><span>Delete</span></a>
      </li>
    </ul><!-- .vbx-menu-items -->
    <div class="pagination">
      <?php if (sizeof($allthreads[$to]) > $threads_per_page): ?>
        <?php foreach(range(0, sizeof($allthreads[$to]), $threads_per_page) as $offset): ?>
          <?php if ($offset == $threads_offset): ?>
            <span class="current"><?php echo ($offset / $threads_per_page + 1);?></span>
          <?php else: ?>
            <span class="num"><a href="<?php echo site_url("p/messages?offset=" . $offset . "&to=" . urlencode($to)) ?>"><?php echo ($offset / $threads_per_page + 1);?></a></span>
          <?php endif ?>
        <?php endforeach; ?>
        <span class="next"><a href="<?php echo site_url("p/messages?offset=" . ($threads_offset + $threads_per_page) . "&to=" . urlencode($to)) ?>">Next</a></span>
        <span class="last"><a href="<?php echo site_url("p/messages?offset=" . (sizeof($allthreads) - $threads_per_page) . "&to=" . urlencode($to)) ?>">»</a></span>
      <?php else: ?>
        <span class="current">1</span>
        <span class="next"><a href="<?php echo site_url("p/messages?offset=0&to=" . urlencode($to)) ?>">Next</a></span>
        <span class="last"><a href="<?php echo site_url("p/messages?offset=0&to=" . urlencode($to)) ?>">»</a></span>
      <?php endif ?>
    </div>	
  </div><!-- .vbx-content-menu -->