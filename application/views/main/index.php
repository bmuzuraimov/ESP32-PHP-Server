<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/public/css/style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<title>HKBU IoT</title>
</head>
<body>
	<header>
		<div class="logo">
			<div class="logo-text"><a href="/">HKBU IoT</a></div>
		</div>
	</header>
	<!-- photo slider -->
	<div class="gallery-slider">
		<h1 class="page-title">Images</h1>
		<div class="date-picker">
			<input type="date" name="image-date" id="image-date" value="<?=$date?>">
		</div>
		<i class="fas fa-chevron-left prev"></i>
		<i class="fas fa-chevron-right next"></i>
		<div class="image-wrapper">
			<?php foreach ($gallery as $image): ?>
			<div class="esp-image">
				<img src="<?=$image['path']?>" class="slider-image">
				<div class="image-info">
					<h4><?=$image['name'].' ('.$image['id'].')'?></h4>
				</div>
			</div>
			<?php endforeach;?>
		</div>
	</div>
	<!-- //photo slider -->	
	<h1 class="page-title">Actuators</h1>	
	<div class="graphBox">
		<div class="box control-panel">
			<div class="contol-item">
				<div class="contol-title">
					<ion-icon name="<?=$wmp_state['icon']?>"></ion-icon>
					<h3 class="text-title">Water Pump</h3>
				</div>
				<div class="control-data">
					<input type="checkbox" class="check-box actuators" id="wpm" name="control_wpm" <?=$wmp_state['state']?> <?=$wmp_state['disable']?>>
				</div>
			</div>
			<div class="contol-item">
				<div class="contol-title">
					<ion-icon name="<?=$fmt_state['icon']?>"></ion-icon>
					<h3>Fan motor</h3>
				</div>
				<div class="control-data">
					<input type="checkbox" class="check-box actuators" id="fmt" name="control_fmt" <?=$fmt_state['state']?> <?=$fmt_state['disable']?>>
				</div>
			</div>
			<div class="contol-item">
				<div class="contol-title">
					<ion-icon name="<?=$led_state['icon']?>"></ion-icon>
					<h3>LED</h3>
				</div>
				<div class="control-data">
					<input type="checkbox" class="check-box actuators" id="led" name="control_led" <?=$led_state['state']?> <?=$led_state['disable']?>>
				</div>
			</div>
			<div class="contol-item">
			</div>
			<div class="period">
				<div class="slider-container">
					<input type="range" class="slider" id="actuators_period" step="1" min="1" max="3" value="4">
				</div>
				<div class="label-container">
			      <div class="label-slider">+++</div>
			      <div class="label-slider">++</div>
			      <div class="label-slider">+</div>
			    </div>
			    <div class="sign-in">
			    	<ion-icon name="hardware-chip-outline"></ion-icon>
			    	<a href="/signin">automate</a>
			    </div>
		    </div>
		</div>
		<div class="box">
			<canvas id="actuatorChart" height="300"></canvas>
		</div>
	</div>
	<h1 class="page-title">Sensors</h1>
	<div class="graphBox">
		<div class="box control-panel">
			<div class="contol-item">
				<div class="contol-title">
					<ion-icon name="thermometer"></ion-icon>
					<h3>Temperature</h3>
				</div>
				<div class="control-data">
					<h4 id="sens-tp"><?=$sensors['temperature'];?>&deg;</h4>
				</div>
			</div>
			<div class="contol-item">
				<div class="contol-title">
					<ion-icon name="nuclear-outline"></ion-icon>
					<h3>Air Humidity</h3>
				</div>
				<div class="control-data">
					<h4 id="sens-ah"><?=$sensors['air_humidity'];?>%</h4>
				</div>
			</div>
			<div class="contol-item">
				<div class="contol-title">
					<ion-icon name="leaf"></ion-icon>
					<h3>Soil Humidity</h3>
				</div>
				<div class="control-data">
					<h4 id="sens-sh"><?=$sensors['soil_humidity'];?>%</h4>
				</div>
			</div>
			<div class="contol-item">
				<div class="contol-title">
					<ion-icon name="sunny-outline"></ion-icon>
					<h3>Light Intensity</h3>
				</div>
				<div class="control-data">
					<h4 id="sens-li"><?=$sensors['light_intensity'];?>%</h4>
				</div>
			</div>
			<div class="period">
				<div class="slider-container">
					<input type="range" class="slider" id="sensors_period" step="1" min="1" max="4" value="4">
				</div>
				<div class="label-container">
			      <div class="label-slider">year</div>
			      <div class="label-slider">month</div>
			      <div class="label-slider">week</div>
			      <div class="label-slider">today</div>
			    </div>
		    </div>
		</div>
		<div class="box">
			<canvas id="sensors-chart" height="350"></canvas>
		</div>
	</div>
	<!-- JQuery -->
	<script type="text/javascript" src="/public/js/jquery.js"></script>
	<script type="text/javascript" src="/public/js/chart.min.js"></script>
	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
	<!-- Slick -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
	<!-- Slider -->
	<script type="text/javascript" src="/public/js/script.js"></script>
</body>
</html>