<div class="mo_wpns_setting_layout">
	<div>
		<h1>4. Session Control
			<span style="text-align: right;font-size: large;"><?php echo '<a href="'.$addons_url     .'" style="color: red">'; ?>[ PREMIUM ]</a></span>
		</h1><hr>
		<table style="width:100%">
		<tr>
			<th align="left">
				<h3>User Session Control:
				<br>
				<p><i class="mo_wpns_not_bold">This will help you limit the number of simultaneous sessions for your users. You can decide to allow access to the new session after limit is reached and destroy all other sessions or block access to the new session when the limit is reached.</i></p>
				</h3>
  			</th>
  			<th align="right">
		  		<label class='mo_wpns_switch'>
				<input type=checkbox/>
				<span class='mo_wpns_slider mo_wpns_round'></span>
				</label>
			</th>
		</tr>
	</table>
</div>
<br>
<div>
	<h3>Number of Sessions
	<input type="text" name="">
	</h3>
</div>
<br>
<div>
	<input type="radio" name="mo2f_allow_access" id="mo2f_allow_access" value="allow" checked>Allow access
	<input type="radio" name="mo2f_allow_access" id="mo2f_block_access" value="block">Block Access
</div>

<hr>
	<div>
		<table style="width:100%">
		<tr>
			<th align="left">
				<h3>Idle Session:
				<br>
				<p><i class="mo_wpns_not_bold">This will allow you to logout a Wordpress user who was inactive for a period of time. You can set the amount of hours after which you want to logout the inactive user.</i></p>
				</h3>
  			</th>
  			<th align="right">
		  		<label class='mo_wpns_switch'>
				<input type=checkbox/>
				<span class='mo_wpns_slider mo_wpns_round'></span>
				</label>
			</th>
		</tr>
	</table>
</div>
<br>
<div>
	<h3>Number of Hours
	<input type="text" name="">
	</h3>
</div>

<hr>
	<div>
		<table style="width:100%">
		<tr>
			<th align="left">
				<h3>Set Session TIme:
				<br>
				<p><i class="mo_wpns_not_bold">This will allow you to set a time limit on the user's session. After that time, the user would be logged out and will be required to login again.</i></p>
				</h3>
  			</th>
  			<th align="right">
		  		<label class='mo_wpns_switch'>
				<input type=checkbox/>
				<span class='mo_wpns_slider mo_wpns_round'></span>
				</label>
			</th>
		</tr>
	</table>
</div>
<br>
<div>
	<h3>Number of Hours
	<input type="text" name="">
	</h3>
</div>
<hr>
	<button type="submit" class="mo_wpns_button mo_wpns_button1">Save Settings</button>
</div>