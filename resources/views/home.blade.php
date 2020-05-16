@extends('layouts.app', ['isPageHome' => true, 'title' => 'Главная'])) @section('content')
<div class="card card-chart">
	<div class="card-header">
		<h5 class="card-category">Total Shipments</h5>
		<h3 class="card-title"><i class="tim-icons icon-bell-55 text-primary"></i> 763,215</h3>
	</div>
	<div class="card-body">
		<div class="chart-area">
			<div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
				<div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
					<div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
				</div>
				<div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
					<div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
				</div>
			</div>
			<canvas id="chartLinePurple" width="1005" height="660" class="chartjs-render-monitor" style="display: block; width: 335px; height: 220px;"></canvas>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		var gradientChartOptionsConfigurationWithTooltipGreen = {
			maintainAspectRatio: false,
			legend: {
				display: false
			},

			tooltips: {
				backgroundColor: '#f5f5f5',
				titleFontColor: '#333',
				bodyFontColor: '#666',
				bodySpacing: 4,
				xPadding: 12,
				mode: "nearest",
				intersect: 0,
				position: "nearest"
			},
			responsive: true,
			scales: {
				yAxes: [{
					barPercentage: 1.6,
					gridLines: {
						drawBorder: false,
						color: 'rgba(29,140,248,0.0)',
						zeroLineColor: "transparent",
					},
					ticks: {
						suggestedMin: 50,
						suggestedMax: 125,
						padding: 20,
						fontColor: "#9e9e9e"
					}
				}],

				xAxes: [{
					barPercentage: 1.6,
					gridLines: {
						drawBorder: false,
						color: 'rgba(0,242,195,0.1)',
						zeroLineColor: "transparent",
					},
					ticks: {
						padding: 20,
						fontColor: "#9e9e9e"
					}
				}]
			}
		};
		
		var ctxGreen = document.getElementById("chartLineGreen").getContext("2d");

		var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

		gradientStroke.addColorStop(1, 'rgba(66,134,121,0.15)');
		gradientStroke.addColorStop(0.4, 'rgba(66,134,121,0.0)'); //green colors
		gradientStroke.addColorStop(0, 'rgba(66,134,121,0)'); //green colors

		var data = {
			labels: ['JUL', 'AUG', 'SEP', 'OCT', 'NOV'],
			datasets: [{
				label: "My First dataset",
				fill: true,
				backgroundColor: gradientStroke,
				borderColor: '#00d6b4',
				borderWidth: 2,
				borderDash: [],
				borderDashOffset: 0.0,
				pointBackgroundColor: '#00d6b4',
				pointBorderColor: 'rgba(255,255,255,0)',
				pointHoverBackgroundColor: '#00d6b4',
				pointBorderWidth: 20,
				pointHoverRadius: 4,
				pointHoverBorderWidth: 15,
				pointRadius: 4,
				data: [90, 27, 60, 12, 80],
			}]
		};

		var myChart = new Chart(ctxGreen, {
			type: 'line',
			data: data,
			options: gradientChartOptionsConfigurationWithTooltipGreen
		});
	});
</script>
@endsection