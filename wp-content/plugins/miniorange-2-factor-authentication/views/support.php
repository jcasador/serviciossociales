<?php
global $mo2f_dirName;
require_once $mo2f_dirName . DIRECTORY_SEPARATOR.'includes'. DIRECTORY_SEPARATOR.'lib'. DIRECTORY_SEPARATOR.'mo-2fa-options-enum.php';

echo'	
                <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<div class="mo_wpns_divided_layout_2">

		<div class="mo_wpns_support_layout" id="mo_wpns_support_layout_tour">
			
			<img src="'.dirname(plugin_dir_url(__FILE__)).'/includes/images/support3.png">
			<h1>Support</h1>
			<p>Need any help? We are available any time, Just send us a query so we can help you.</p>
				<form name="f" method="post" action="">
					<input type="hidden" name="option" value="mo_wpns_send_query"/>
					<table class="mo_wpns_settings_table">
						<tr><td>
							<input type="email" class="mo_wpns_table_textbox" id="query_email" name="query_email" value="'.$email.'" placeholder="Enter your email" required />
							</td>
						</tr>
						<tr><td>
							<input type="text" class="mo_wpns_table_textbox" name="query_phone" id="query_phone" value="'.$phone.'" placeholder="Enter your phone"/>
							</td>
						</tr>
						<tr>
							<td>
								<textarea id="query" name="query" class="mo_wpns_settings_textarea" style="resize: vertical;width:100%" cols="52" rows="7" onkeyup="mo_wpns_valid(this)" onblur="mo_wpns_valid(this)" onkeypress="mo_wpns_valid(this)" placeholder="Write your query here"></textarea>
							</td>
						</tr>
					</table>
				<br />			
		<div class="call-setup-div">
                        <h3 style="margin-top: 0px; margin-left: 5px;">Setup a Call / Screen-share session with miniOrange Technical Team</h3>
                        <label class="switch">
                            <input type="checkbox" id="2fa_setup_call" name="2fa_setup_call">
                            <span class="slider round"></span>
                        </label>
                        <span style="padding-left:5px; font-size: 15px;">
                            <b><label for="2fa_setup_call"></label>Enable this option to setup a call</b><br><br>
                        </span>
                        <div id="call_setup_dets" style="margin-left: 5px; margin-top: 5px;">
                            <div>
                                <div style="width: 26%; float:left;"><strong>TimeZone<font color="#FF0000">*&nbsp</font>&nbsp; : &nbsp;</strong> </div>
                                <div style="width: 74% !important; float: left">
                                    <select id="js-timezone" name="mo_2fa_setup_call_timezone" style="width:93%;">';
                                            $zones = mo_2fa_time_zones::$time_zones;
                                          echo ' <option value="" selected disabled>-------Select your timezone------</option>';
                                           foreach($zones as $zone=>$value) {
                                                echo '<option value="'.$value.'">'. $zone.' </option>';
                                           }
                                           echo '
                                    </select>
                                </div>
                            </div>
                            <br><br><br>
                            <div style="width: 50%; float: left; position: relative;">
                               <strong> Date<font color="#FF0000">*</font>:</strong><br>
                               <input style="width: 90% !important;" type="text" id="datepicker" class="mo_2fa_table_textbox" placeholder="Select Meeting Date" autocomplete="off" name="mo_2fa_setup_call_date">
                            </div>
                            <div style="width: 50%; float: left; position: relative;">
                                <strong> Time (24-hour)<font color="#FF0000">*</font>:</strong><br>
                                <input style="width: 90% !important;" type="text" id="timepicker" placeholder="Select Meeting Time" class="mo_2fa_table_textbox" autocomplete="off" name="mo_2fa_setup_call_time">
                            </div>
                            <br><br><br>
                            <div>
                                <p style="margin-right: 20px; margin-bottom: 10px; margin-top: 15px; margin-left: 5px;">
                                    <b><font color="#dc143c">Call and Meeting details will be sent to your email. Please verify the email before submitting your query. Check the spam folder for any email.</font></b>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div id="mo_2fa_plugin_configuration">
                    <input type="hidden" name="mo_2fa_plugin_configuration" value="mo2f_send_plugin_configuration"/>
                                <input type="checkbox" id="mo2f_send_configuration"
                                       name="mo2f_send_configuration" 
                                       value="1" checked
                                <h3>Send plugin Configuration</h3>
        <br /><br />
    </div>
					<input type="submit" name="send_query" id="send_query" value="Submit Query" style="margin-bottom:3%;" class="mo_wpns_button mo_wpns_button1" />
				</form>
				<br />			
		</div>
		</div>';?>
		<script>
			function moSharingSizeValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:10>t&&(e.value=10)
			}
			function moSharingSpaceValidate(e){
				var t=parseInt(e.value.trim());t>50?e.value=50:0>t&&(e.value=0)
			}
			function moLoginSizeValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:20>t&&(e.value=20)
			}
			function moLoginSpaceValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:0>t&&(e.value=0)
			}
			function moLoginWidthValidate(e){
				var t=parseInt(e.value.trim());t>1000?e.value=1000:140>t&&(e.value=140)
			}
			function moLoginHeightValidate(e){
				var t=parseInt(e.value.trim());t>50?e.value=50:35>t&&(e.value=35)
			}
            var min_time = "00:00";
		    jQuery( function() {
		        jQuery("#call_setup_dets").hide();
                jQuery("#2fa_setup_call").click(function() {
                    if(jQuery(this).is(":checked")) {
                        jQuery("#call_setup_dets").show();
                        document.getElementById("js-timezone").required = true;
                        document.getElementById("datepicker").required = true;
                        document.getElementById("timepicker").required = true;
                        document.getElementById("query").required = false;
                        var date = new Date();
                        var hrs = date.getHours();
                        var mins = date.getMinutes();
                        if(hrs == 23 && mins > 30){
		                    jQuery("#datepicker").datepicker("option", "minDate", 1);
                            jQuery("#datepicker").datepicker("setDate", +1);
                            jQuery("#timepicker").timepicker("option", "minTime", "00:00");
                        } else{
		                    jQuery("#datepicker").datepicker("option", "minDate", 0);
		                    jQuery("#datepicker").datepicker("setDate", new Date());
        		        }
                    } else {
                        jQuery("#call_setup_dets").hide();
                        document.getElementById("timepicker").required = false;
                        document.getElementById("datepicker").required = false;
                        document.getElementById("js-timezone").required = false;
                        document.getElementById("query").required = true;
                    }
                });
                jQuery( "#datepicker" ).datepicker({
                    minDate: 0,
                    dateFormat: "M dd, yy"
                });
                jQuery("#datepicker").datepicker().on("change", function (ev) {
                   var sel_date = jQuery(this).val();
                   var selected_date = new Date(sel_date);
                   var today_date = new Date();
                   if( (selected_date.getDate() == today_date.getDate()) && (selected_date.getMonth() == today_date.getMonth()) ){
                        jQuery("#timepicker").timepicker("option", "minTime", new Date());
                   }
                   else{
                       jQuery("#timepicker").timepicker("option", "minTime", "00:00");
                   }
                });
            jQuery("#timepicker").timepicker({
                timeFormat: "HH:mm",
                interval: 30,
                minTime: new Date(),
                disableTextInput: true,
                dynamic: false,
                dropdown: true,
                scrollbar: true,
                forceRoundTime: true
            });
            });
	  		jQuery(function() { jQuery("#js-timezone").select2(); });
		</script>