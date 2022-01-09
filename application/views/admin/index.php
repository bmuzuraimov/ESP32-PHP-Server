<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="/public/css/style.css">
		<title>Dashboard</title>
	</head>
	<body>
		<header>
			<div class="logo">
				<div class="logo-text"><a href="/">Dashboard</a></div>
			</div>
			<ul class="nav">
				<li><a href="/logout" class="logout">signout</a></li>
			</ul>
		</header>
		<h1 class="page-title">Automate Actuators</h1>
		<div class="admin-panel">
			<button id="clear-files">Clear all files</button>
			<form action="/automate" method="POST" id="automate-form">
				<div class="actuator">
					<div class="actuator-control">
						<h3 class="text-title">Water Pump</h3>
						<input type="checkbox" class="check-box" id="wtp" name="is_wmp_auto" <?=$wmp_auto[0]?>>
					</div>
					<div class="<?=$wmp_auto[1]?>" id="wtp_setting">
						<select name="sens_wmp" id="wmp-select">
							<option value="sens_tp">Temperature</option>
							<option value="sens_ah">Air Humidity</option>
							<option value="sens_sh">Soil Humidity</option>
							<option value="sens_li">Light Intensity</option>
						</select>
						<input type="text" name="sens_wmp_val" placeholder="turn on value" value="<?=$modes['sens_wmp_val']?>">
					</div>
				</div>
				<div class="actuator">
					<div class="actuator-control">
						<h3 class="text-title">Fan motor</h3>
						<input type="checkbox" class="check-box" id="fmt" name="is_fmt_auto" <?=$fmt_auto[0]?>>
					</div>
					<div class="<?=$fmt_auto[1]?>" id="fmt_setting">
						<select name="sens_fmt" id="fmt-select">
							<option value="sens_tp">Temperature</option>
							<option value="sens_ah">Air Humidity</option>
							<option value="sens_sh">Soil Humidity</option>
							<option value="sens_li">Light Intensity</option>
						</select>
						<input type="text" name="sens_fmt_val" placeholder="turn on value" value="<?=$modes['sens_fmt_val']?>">
					</div>
				</div>
				<div class="actuator">
					<div class="actuator-control">
						<h3 class="text-title">LED</h3>
						<input type="checkbox" class="check-box" id="led" name="is_led_auto" <?=$led_auto[0]?>>
					</div>
					<div class="<?=$led_auto[1]?>" id="led_setting">
						<select name="sens_led" id="led-select">
							<option value="sens_tp">Temperature</option>
							<option value="sens_ah">Air Humidity</option>
							<option value="sens_sh">Soil Humidity</option>
							<option value="sens_li">Light Intensity</option>
						</select>
						<input type="text" name="sens_led_val" placeholder="turn on value" value="<?=$modes['sens_led_val']?>">
					</div>
				</div>
				<button type="button" id="save-btn">Save</button>
			</form>
		</div>
		<!-- JQuery -->
		<script type="text/javascript" src="/public/js/jquery.js"></script>
		<script type="text/javascript">
			$('td[name="tcol1"]')
			$(document).ready(function() {
				var sens_wmp = "<?=$modes['sens_wmp']?>";
				var sens_fmt = "<?=$modes['sens_fmt']?>";
				var sens_led = "<?=$modes['sens_led']?>";
				$('#wtp_setting option[value="'+sens_wmp+'"]').prop('selected', true);
				$('#fmt_setting option[value="'+sens_fmt+'"]').prop('selected', true);
				$('#led_setting option[value="'+sens_led+'"]').prop('selected', true);
			});
			$('#clear-files').click(function () {
				$.ajax({
					type: 'POST',
					url: '/clear_files',
					data: {'auth_key': 'afb8-3a028ca910fd'},
					success: function(response) {
						alert('Deleted all files!');
					}
				});
			});
			$('#save-btn').click(function () {
				$.post($('#automate-form').attr('action'), $('#automate-form :input').serializeArray(), function(response) {
				responseObj = JSON.parse(response);
					if (responseObj.success==true) {
						$('.admin-panel').addClass('admin-panel-success');
					}else {
						$('.admin-panel').addClass('admin-panel-error');
					}
				});
			});
			$('.actuator-setting input').focus(function() {
				$('.admin-panel').removeClass('admin-panel-success admin-panel-error')
			});
			$('.check-box').change(function() {
				if(this.checked){
					$('#'+this.id+'_setting').removeClass('hidden').addClass('actuator-setting');
				}else{
					$('#'+this.id+'_setting').removeClass('actuator-setting').addClass('hidden');
				}
			});
			$('#automate-form').submit(function() {
				return false;
			});
		</script>
	</body>
</html>