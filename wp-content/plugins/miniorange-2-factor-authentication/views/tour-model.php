<!-- The Modal -->
<form name="f" method="post" id="show_pointers">
	<?php wp_nonce_field("clear_pointers");?>
	<input type="hidden" name="option" value="clear_pointers"/>
	<input type="hidden" name="button_name" id="button_name" />
</form>

<form name="f" method="post" id="restart-plugin-tour">
	<?php wp_nonce_field("restart_plugin_tour");?>
	<input type="hidden" name="option" value="restart_plugin_tour"/>
	<input type="hidden" name="page" value="mo_2fa_two_fa" id="page">
</form>

<form name="f" method="post" id="skip-plugin-tour">
	<?php wp_nonce_field("skip_plugin_tour");?>
	<input type="hidden" name="option" value="skip_plugin_tour"/>
</form>
<?php
	$tour_box_size = MoWpnsUtility::get_mo2f_db_option('mo_wpns_2fa_with_network_security', 'get_option');
	$tour_box_size = $tour_box_size == 1 ? '70%' : '50%';
?>
<div id="getting-started" class="modal">
    <!-- Modal content -->
    <div class="modal-content" style="width: <?php echo $tour_box_size; ?>">
    <!--    <span class="close">&times;</span>  -->
        <div class="modal-header">
            <h3 class="modal-title" style="text-align: center; font-size: 30px; color: #2980b9">Let's Get Started</h3><span id="tour-model" class="modal-span-close">X</span>
        </div>
        <div class="modal-body" style="height: 310px;">
  			<?php
  				echo $tour_body;
  			?>
        </div>
        <div class="modal-footer">
            <button type="button" class="mo_wpns_button mo_wpns_button1 modal-button modalhover" id="skip-plugin-tour" style="width: 40%;color: #111111; background: none;text-decoration: underline;font-weight: bold;border: 2px solid black;" onclick="skip_plugin_tour()" >Skip tour</button>
            <button type="button" class="mo_wpns_button mo_wpns_button1 modal-button logout" id="start-plugin-tour" style= "width: 40%;background-color:#2EB150;">Start tour</button>

        </div>
    </div>
</div>
<div class='overlay' id="overlay" hidden></div>
<script type="text/javascript">
		var current_pointer = 0;
		var site_type = '';
		var site_elmt = '';

		var display = '<?php echo $display; ?>';
		var getting_started_modal = document.getElementById("getting-started");

		jQuery('#getting-started').css('display', display);
		jQuery('#start-plugin-tour').html('Start a tour');
		jQuery('.modal-footer a').css('display', 'inline-block');
		
		jQuery('#2fa').css("border", "5px solid #20b2aa");

		jQuery('input[type=radio][name=mo2f_two_factor]').click(function(){
			var ele = document.getElementsByName("mo2f_two_factor");
			var selected = '';
			
			for(i = 0; i < ele.length; i++) { 
             	if(ele[i].checked) 	
                {
                	selected = ele[i].value;
            	}
            }

            jQuery('#2fa').css("border", "1px solid black");
            jQuery('#waf').css("border", "1px solid black");
            jQuery('#malware').css("border", "1px solid black");
            jQuery('#backup').css("border", "1px solid black");
            jQuery('#login').css("border", "1px solid black");

			jQuery('#'+selected).css("border", "5px solid #20b2aa");

		});
		
		jQuery('#start-plugin-tour').click(function(){

			var ele = document.getElementsByName("mo2f_two_factor");
			var selected = '';
			
			for(i = 0; i < ele.length; i++) { 
             	if(ele[i].checked) 	
                {
                	selected = ele[i].value;
            	}
            }
            

            var pageurl = '';
            switch(selected){
            	case '2fa':
            		pageurl = 'mo_2fa_two_fa';
            		break;
            	case 'waf':
            		pageurl = 'mo_2fa_waf';
            		break;
            	case 'malware':
            		pageurl = 'mo_2fa_malwarescan';
            		break;
            	case 'login':
            		pageurl = 'mo_2fa_login_and_spam';
            		break;
            	case 'backup':
            		pageurl = 'mo_2fa_backup';
            		break;

            }
            document.getElementById('page').value = pageurl;
			var data = {
		        'action'	: 'mo_wpns_tour',
			    'call_type'	: 'entire_plugin_tour_started',
		    };
		    jQuery.post(ajaxurl, data, function(response) {
		        getting_started_modal.style.display = "none";
		    });

			var url = '<?php echo $_REQUEST["page"]; ?>';	
			switch(url){
				case 'mo_2fa_two_fa':
					document.getElementById("setup_2fa").click();	
					break;

				case 'mo_2fa_waf':
					document.getElementById("settingsTab").click();	
					break;

				case 'mo_2fa_login_and_spam':
					document.getElementById("login_sec").click();	
					break;

				case 'mo_2fa_malwarescan':
					document.getElementById("malware_view").click();	
					break;

				case 'mo_2fa_backup':
					document.getElementById("backup_set").click();
					break;
			}
			jQuery('#restart-plugin-tour').submit();

		});
		function skip_plugin_tour(){

		    var data = {
		        'action'	: 'mo_wpns_tour',
			    'call_type'	: 'skip_entire_plugin_tour',
		    };
		    jQuery.post(ajaxurl, data, function(response) {
		        getting_started_modal.style.display = "none";
		    });
		}



		
		jQuery('#restart-tour').click(function(){
			var data={
				'action': 'mo_wpns_tour',
				'call_type': 'wpns_enable_tour'
			};
			jQuery.post(ajaxurl, data, function(response){
				
				current_pointer = 0;
				jQuery('#start-plugin-tour').html('Start tour');
				jQuery('.modal-footer a').css('display', 'inline-block');
				jQuery('#getting-started').css('display', 'block');
			});
		});

		jQuery('.modal-footer a').click(function(){
			close_modal();
		});
		jQuery('#tour-model').click(function(){
			close_modal();
		});
		function close_modal(){
			var data = {
		        'action'	: 'mo_wpns_tour',
			    'call_type'	: 'skip_entire_plugin_tour',
		    };
		    jQuery.post(ajaxurl, data, function(response) {
		        getting_started_modal.style.display = "none";
		    });
		}

		function open_hide(gettag){
			if(gettag.text == '+'){
				gettag.text='-';
				jQuery('#div-'+gettag.id).css({'overflow': '', 'height': ''});
			} else {
				gettag.text='+';
				jQuery('#div-'+gettag.id).css({'overflow': 'hidden', 'height': '50px'});
			}
		}

</script>
