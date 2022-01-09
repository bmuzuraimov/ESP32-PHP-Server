//datasets for actuators
var actuators_chart;
var act_labels = [];
var wmp_states = [];
var fmt_states = [];
var led_states = [];

//datasets for sensors
var sensors_chart;
var sns_labels = [];
var temp_data = [];
var air_data = [];
var soil_data = [];
var led_data = [];

//checkbox states
var wmp_checkbox;
var fmt_checkbox;
var led_checkbox;

var actuators_period = $('#actuators_period').val();;
var sensors_period = $('#sensors_period').val();;
const interval = 1000;

$('.image-wrapper').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    nextArrow: $('.next'),
    prevArrow: $('.prev'),
    responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true
            }
        }, {
            breakpoint: 750,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        }, {
            breakpoint: 550,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
    ],
});

const ctx  = document.getElementById('actuatorChart').getContext('2d');
const actuators = new Chart(ctx, {
    type: 'line',
    data: {
        labels: act_labels,
        datasets: [{
            label: 'water pump',
            data: wmp_states,
            borderColor: 'rgba(240, 50, 50, 0.8)',
            backgroundColor: 'rgba(255, 0, 0, 50)',
            yAxisID: 'y',
            stepped: true,
            pointStyle: 'circle',
            pointRadius: 5,
        }, {
            label: 'fan motor',
            data: fmt_states,
            borderColor: 'rgba(50, 50, 240, 0.8)',
            backgroundColor: 'rgba(0, 0, 255, 50)',
            yAxisID: 'y',
            stepped: true,
            pointStyle: 'circle',
            pointRadius: 5,
        }, {
            label: 'light',
            data: led_states,
            borderColor: 'rgba(50, 255, 50, 0.8)',
            backgroundColor: 'rgba(0, 255, 0, 50)',
            yAxisID: 'y',
            stepped: true,
            pointStyle: 'circle',
            pointRadius: 5,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: true, 
        interaction: {
            mode: 'index',
            iteraction: false,
        },
        stacked: false,
        plugins: {
            title: {
                display: true,
                text: 'Actuator\'s State',
            },
        },
        scales: {
            y: {
                type: 'category',
                labels: ['ON', 'OFF'],
                offset: true,
                display: true,
                position: 'left',
            }
        },
    }
});

const scanvas = document.getElementById('sensors-chart').getContext('2d');
const sensors = new Chart(scanvas, {
    type: 'line',
    data: {
        labels: sns_labels,
        datasets: [
        {
            label: 'Temp',
            data: temp_data,
            borderColor: 'rgba(240, 50, 50, 1)',
            backgroundColor: 'rgba(240, 50, 50, 0.8)',
            yAxisID: 'y',
        }, {
            label: 'Air',
            data: air_data,
            borderColor: 'rgba(33, 255, 170, 1)',
            backgroundColor: 'rgba(0, 255, 157, 0.8)',
            yAxisID: 'y',
        }, {
            label: 'Soil',
            data: soil_data,
            borderColor: 'rgba(186, 36, 255, 1)',
            backgroundColor: 'rgba(186, 36, 255, 0.8)',
            yAxisID: 'y',
        }, {
            label: 'Light',
            data: led_data,
            borderColor: 'rgba(36, 160, 255, 1)',
            backgroundColor: 'rgba(36, 160, 255, 0.8)',
            yAxisID: 'y',
        }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: true,
        interaction: {
            mode: 'index',
            iteraction: false,
        },
        stacked: false,
        plugins: {
            title: {
                display: true,
                text: 'Sensors',
            },
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
            },
        },
    }
});
$('#image-date').change(function(){
    var date = $(this).val();
    window.location.replace("/index?date="+date);
});

function update_actuator_data(){
	$.ajax({
		type: 'POST',
		url: '/live_actuators',
		data: {'auth_key': '4bf2-843d', 'period':actuators_period},
		success: function(response) {
			actuators_chart = JSON.parse(response);
			act_labels = [];
			wmp_states = [];
			fmt_states = [];
			led_states = [];
            $.each(actuators_chart,function(index){
                act_labels.push(this.date);
                wmp_states.push(this.is_wpmp==1 ? 'ON' : 'OFF');
                fmt_states.push(this.is_fmt==1 ? 'ON' : 'OFF');
                led_states.push(this.is_led==1 ? 'ON' : 'OFF');
                if(index == actuators_chart.length-1){
                    wmp_checkbox = (this.is_wpmp==1) ? true : false
                    fmt_checkbox = (this.is_fmt==1) ? true : false
                    led_checkbox = (this.is_led==1) ? true : false
                }
            });
            actuators.data.labels = act_labels;
            actuators.data.datasets[0].data = wmp_states;
            actuators.data.datasets[1].data = fmt_states;
            actuators.data.datasets[2].data = led_states;
            $('#wpm').prop('checked', wmp_checkbox);
            $('#fmt').prop('checked', fmt_checkbox);
            $('#led').prop('checked', led_checkbox);
		}
	});
}

function update_sensors_data(){
    $.ajax({
        type: 'POST',
        url: '/live_sensors',
        data: {'auth_key': '843d-4bf2', 'period':sensors_period},
        success: function(response) {
            sensors_chart = JSON.parse(response);
            sns_labels = [];
            temp_data = [];
            air_data = [];
            soil_data = [];
            led_data = [];
            $.each(sensors_chart,function(index){
                sns_labels.push(this.date);
                temp_data.push(this.temperature);
                air_data.push(this.air_humidity);
                soil_data.push(this.soil_humidity);
                led_data.push(this.light_intensity);
                if(index == (sensors_chart.length) - 1){
                    $('#sens-tp').html(this.temperature);
                    $('#sens-ah').html(this.air_humidity);
                    $('#sens-sh').html(this.soil_humidity);
                    $('#sens-li').html(this.light_intensity);
                }
            });
            sensors.data.labels = sns_labels;
            sensors.data.datasets[0].data = temp_data;
            sensors.data.datasets[1].data = air_data;
            sensors.data.datasets[2].data = soil_data;
            sensors.data.datasets[3].data = led_data;
        }
    });
}

$('.actuators').change(function() {
    var wmp_val = $('.actuators#wpm').is(":checked") ? 1 : 0;
    var fmt_val = $('.actuators#fmt').is(":checked") ? 1 : 0;
    var led_val = $('.actuators#led').is(":checked") ? 1 : 0;
    $.ajax({
        type: 'POST',
        url: '/update_actuator',
        data: {'wmp_val': wmp_val, 'fmt_val': fmt_val, 'led_val': led_val},
        success: function(response) {
            update_actuator_data();
            actuators.update();
        }
    });
});
$('.slider').change(function() {
    var id = $(this).attr('id');
    var val = $(this).val();
    if(id == 'actuators_period'){
        actuators_period = val;
        update_actuator_data();
        actuators.update();
    }else{
        sensors_period = val;
        update_sensors_data();
        sensors.update();
    }
});
update_actuator_data();
update_sensors_data();

window.setInterval(function(){
    update_actuator_data();
    actuators.update();
    update_sensors_data();
    sensors.update();
}, interval);