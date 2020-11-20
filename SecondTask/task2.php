<?php 

class SecondTask
{
	function getAveragePrice(){
		$data = json_decode(file_get_contents('data.json'), true);
		$sumPrices = 0;
		for ($i=0; $i < count($data[0]["data"]); $i++) { 
			$sumPrices += $data[0]["data"][$i]["price"];
		}
		$averagePrice = $sumPrices / count($data[0]["data"]);
		return round($averagePrice);
	}

	function generateAssocArray($averagePrice){
		$array = array();

		$averagePriceMin = $averagePrice / 2;
		$averagePriceMax = $averagePrice + $averagePrice / 2;

		$dateStart = new DateTime();
		$dateStart->sub(new DateInterval('P10D'));
		foreach (new DatePeriod($dateStart, new DateInterval('P1D'), new DateTime()) as $date) {
			$array[$date->format('d.m.Y')] = rand($averagePriceMin, $averagePriceMax);
		}
		$array[date("d.m.Y")] = $averagePrice;
		return $array;
	}

	function showResult($array){
		?>
			<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<script src="./SecondTask/lib/Chart.min.js"></script>
			</head>

			<body>
				<div style="width:75%;">
					<canvas id="canvas" style="display: block; width: 1397px; height: 698px;" width="1397" height="698" class="chartjs-render-monitor"></canvas>
				</div>
			</body>
			<script>
				var config = {
					type: 'line',
					data: {
						labels: ["<?= implode('", "', array_keys($array)) ?>"],
						datasets: [{
							label: 'Средняя цена на товары',
							backgroundColor: 'rgb(255, 99, 132)',
							borderColor: 'rgb(255, 99, 132)',
							data: [<?=implode(', ', $array)?>],
							fill: false,
						}]
					},
					options: {
						responsive: true,
						tooltips: {
							mode: 'index',
							intersect: false,
						},
						hover: {
							mode: 'nearest',
							intersect: true
						},
						scales: {
							xAxes: [{
								display: true,
								scaleLabel: {
									display: true,
									labelString: 'День'
								}
							}],
							yAxes: [{
								display: true,
								scaleLabel: {
									display: true,
									labelString: 'Значение'
								}
							}]
						},
						title: {
							display: true,
							text: 'Средняя цена на товары'
						},
					}
				};

				window.onload = function() {
					var ctx = document.getElementById('canvas').getContext('2d');
					window.myLine = new Chart(ctx, config);
				};
			</script>
		<?php
	}
}
?>