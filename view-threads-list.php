  <div class="vbx-content-menu vbx-content-menu-top">
<!--    <ul class="inbox-menu vbx-menu-items-left">
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
    </ul>--><!-- .vbx-menu-items -->
    <div class="pagination">
      <?php if (sizeof($allthreads) > $threads_per_page): ?>
        <?php foreach(range(0, sizeof($allthreads), $threads_per_page) as $offset): ?>
          <?php if ($offset == $threads_offset): ?>
            <span class="current"><?php echo ($offset / $threads_per_page + 1);?></span>
          <?php else: ?>
            <span class="num"><a href="<?php echo site_url("p/messages?offset=" . $offset) ?>"><?php echo ($offset / $threads_per_page + 1);?></a></span>
          <?php endif ?>
        <?php endforeach; ?>
        <span class="next"><a href="<?php echo site_url("p/messages?offset=" . ($threads_offset + $threads_per_page)) ?>">Next</a></span>
        <span class="last"><a href="<?php echo site_url("p/messages?offset=" . (sizeof($allthreads) - $threads_per_page)) ?>">»</a></span>
      <?php else: ?>
        <span class="current">1</span>
        <span class="next"><a href="<?php echo site_url("p/messages?offset=0") ?>">Next</a></span>
        <span class="last"><a href="<?php echo site_url("p/messages?offset=0") ?>">»</a></span>
      <?php endif ?>
    </div>	
  </div><!-- .vbx-content-menu -->
  <table border="0" class="vbx-items-grid">
    <tbody>
      <?php foreach($threads as $message): ?>
      <tr rel="<?php echo $message[0]['id'] ?>" class="message-row sms-type <?php echo ($message[0]['status'])? 'unread' : 'read'?>">
        <td class="message-caller">
          <a href="<?php echo site_url("messages/details/{$message[0]['id']}")?>" class="quick-call-button">
            <span class="replace"><?php echo $message[0]['contact'] ?></span>
          </a>
          <a href="<?php echo site_url("messages/details/{$message[0]['id']}")?>" class="quick-sms-button">
            <span class="replace"><?php echo $message[0]['contact'] ?></span>
          </a>
          <div id="quick-call-popup-<?php echo $message[0]['id'] ?>" class="quick-call-popup hide">
            <a href="" class="close action toggler"><span class="replace">close</span></a>
            <p class="call-to-phone"><?php echo $message[0]['contact'] ?></p>
            <ul class="caller-id-phone">
              <li>
                <a href="<?php echo site_url("messages/details/{$message[0]['id']}/callback") ?>" class="call">
                  Call
                  <span class="to hide"><?php echo $message[0]['contact'] ?></span>
                  <span class="callerid hide"><?php echo $phoneNumbers[0] ?></span>
                  <span class="from hide"><?php echo isset($user_numbers[0])? $user_numbers[0]->value : '' ?></span>
                </a>
              </li>
            </ul>
          </div>
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
            <a href="<?php echo site_url("p/messages/?to=" . urlencode($message[0]['contact']))?>"  style="text-decoration:none" >
              <?php
              if ($address_book) {
                echo lookupNumber($message[0]['contact']);
              } else {
                echo $message[0]['contact'];
              }
              ?>
            </a>
          </span>
        </td>
        <td class="message-content">
            <span class="transcript"><?php echo $message[0]['body'] ?></span>
        </td>
        <td>
          <?php
            if ($message[0]['num_media'] > 0) {
            foreach($message[0]['media'] as $media) {
            echo "<a href='https://api.twilio.com" . $media->uri . "' style='text-decoration:none' data-lightbox='" . $message[0]['id'] . "'>&#x1f4f7;</a>";
            //echo "     \     " . $media->content_type . "     \     ";
            }
            }
          ?>
        </td>
        <td class="message-timestamp">
          <div class="unformatted-absolute-timestamp hide">
            <?php echo strtotime($message[0]['created']) ?>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table><!-- .vbx-items-grid -->    
  <div class="vbx-content-menu vbx-content-menu-bottom">
<!--    <ul class="inbox-menu vbx-menu-items">
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
    </ul>--><!-- .vbx-menu-items -->
    <div class="pagination">
      <?php if (sizeof($allthreads) > $threads_per_page): ?>
        <?php foreach(range(0, sizeof($allthreads), $threads_per_page) as $offset): ?>
          <?php if ($offset == $threads_offset): ?>
            <span class="current"><?php echo ($offset / $threads_per_page + 1);?></span>
          <?php else: ?>
            <span class="num"><a href="<?php echo site_url("p/messages?offset=" . $offset) ?>"><?php echo ($offset / $threads_per_page + 1);?></a></span>
          <?php endif ?>
        <?php endforeach; ?>
        <span class="next"><a href="<?php echo site_url("p/messages?offset=" . ($threads_offset + $threads_per_page)) ?>">Next</a></span>
        <span class="last"><a href="<?php echo site_url("p/messages?offset=" . (sizeof($allthreads) - $threads_per_page)) ?>">»</a></span>
      <?php else: ?>
        <span class="current">1</span>
        <span class="next"><a href="<?php echo site_url("p/messages?offset=0") ?>">Next</a></span>
        <span class="last"><a href="<?php echo site_url("p/messages?offset=0") ?>">»</a></span>
      <?php endif ?>
    </div>	
  </div><!-- .vbx-content-menu -->