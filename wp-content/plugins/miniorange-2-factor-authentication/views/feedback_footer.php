<?php 
echo'   <div class="mo_otp_footer"> 
  <div class="mo-2fa-mail-button">
  <img id= "mo_wpns_support_layout_tour" src="'.dirname(plugin_dir_url(__FILE__)).'/includes/images/mo_support_icon.png" class="show_support_form"  onclick="openForm()">
  </div>
  <button type="button" class="mo-2fa-help-button-text" onclick="openForm()"">Hello there!<br>Need Help? Drop us an Email</button>
  </div>';
?>


<div id="feedback_form_bg" > 
<div class="mo2f-chat-popup" id="myForm" style="display:none; width: 100%;padding: 1%; padding-top: 50%;background-color: rgba(0,0,0,0.61);">
  
  <div id ='mo_wpns_support_layout_tour_open' style="background-color: white;min-height: 370px;width: 45%; text-align: right;float: right;border-radius: 8px;">
    <div style="min-height: 500px;background-image: linear-gradient(to bottom right, #dffffd, #8feeea);width: 43%;float: left;padding: 10px; border-bottom-left-radius: 8px;border-top-left-radius: 8px;">
          <center>
            <?php
            echo '
              <img src="'.dirname(plugin_dir_url(__FILE__)).'/includes/images/minirange-logo.png" style="width: 46%;">';
            ?>
        <h1 style="    font-family: auto;">Contact Information</h1>
      </center><br>
      <div style="text-align: left;padding: 3%;">
        <table>
            <tr>
            <td>
              <span class="dashicons dashicons-email"></span>
            </td>
            <td><h3>2fasupport@xecurify.com</h3>
            </td>
            </tr>
            <tr>
            <td>
              <span class="dashicons dashicons-email"></span>
            </td>
            <td><h3>info@xecurify.com</h3>
            </td>
            </tr>
            <!-- <tr>
            <td>
              <span class="dashicons dashicons-phone"></span>
            </td>
            <td><h3>+91 9876543210</h3>
            </td>
            </tr> -->
            <tr>
            <td>
              <span class="dashicons dashicons-admin-site-alt3"></span>
            </td>
            <td><h3><a href="https://miniorange.com/" target="_blank"> www.miniorange.com</a></h3>
            </td>
            </tr>
                    
        </table><br>
      
      </div>
  </div>
  <div class="mo2f-form-container">
      <span class="mo2f_rating_close" onclick="closeForm()">×</span>
    <h1 style="text-align: center;    font-family: auto;">Contact Us</h1>

        <form name="f" method="post" action="" id="mo_wpns_query_form_close">
    <div style="width: 100%;">
                   

                   <div id="low_rating" style="display: block; width: 100%;">
                    <div style=" float: left;">
    
                        <br>
                        <?php
                        echo '
                        <table class="mo_wpns_settings_table">
                        <tr><td>
                        <span class="mo_support_input_label">Your Email</span>
                          <input type="email" class="mo_wpns_table_textbox" id="query_email" name="query_email" style="border: none;border-bottom: 1px solid;" value="'.$email.'" placeholder="Enter your email" required />
                          </td>
                        </tr>
                        <tr><td>
                        <span class="mo_support_input_label">Your Phone Number</span>
                          <input type="text" class="mo_wpns_table_textbox" name="query_phone" id="query_phone" style="border: none;border-bottom: 1px solid;" value="'.$phone.'" placeholder="Enter your phone"/>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <textarea id="query" name="query" class="mo_wpns_settings_textarea" style="resize: vertical;width:100%;border: 1px solid;" cols="52" rows="7" onkeyup="mo_wpns_valid(this)" onblur="mo_wpns_valid(this)" onkeypress="mo_wpns_valid(this)" placeholder="Write your query here"></textarea>
                          </td>
                        </tr>
                      </table>
                        ';
                        ?>
                    
                        <div id="send_button" style="display: block; text-align: center;">
                        <input type="button" name="miniorange_skip_feedback"
                               class="mo_wpns_button mo_wpns_button1" value="Send" onclick="document.getElementById('mo_wpns_query_form_close').submit();"/>
                    </div>
                   
                    </div>
                    
                    </div>

    </div>
                <input type="hidden" name="option" value="mo_wpns_send_query"/>
          
    </form>
  </div>

</div>
</div>
</div>

<script>

function openForm() {
  document.getElementById("myForm").style.display = "block";
  document.getElementById("feedback_form_bg").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
  document.getElementById("feedback_form_bg").style.display = "none";

}
</script>