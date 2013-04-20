<?php echo form_open("auth/change_password");?>

<div id="infoMessage" style="font-size:14px; color:red; font-weight:bold"><?php echo $message;?></div>
<br /><br />
<div class="BlockBox">
	<div class="TopBox" style="padding-bottom:5px">
		<h3>Change Password</h3>
	</div>
	<div class="MidBox">
		<div class="FormWrapper cf">
			<div class="FormBox" style="width:50%">
				<div class="Elem cf">
					<div class="left">Old Password</div>
					<div class="right"><?php echo form_input($old_password);?></div>
				</div>
				<div class="Elem cf">
					<div class="left">New Password</div>
					<div class="right"> <?php echo form_input($new_password);?></div>
				</div>

				<div class="Elem cf">
					<div class="left">Confirm New Password</div>
					<div class="right"> <?php echo form_input($new_password_confirm);?></div>
				</div>
			</div>

		</div>
	</div>
</div>

<input type="submit" value="Save" class="FormSubmit" />
<?php echo form_close();?>