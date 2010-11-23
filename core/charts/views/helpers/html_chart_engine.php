<?php
	class HtmlChartEngineHelper extends ChartsHelper{
		public $helpers = array(
			'Html'
		);

		private $__chartWrapper = '<div class="html-chart bar verticle">%s %s</div>';

		public function bar($data){
			echo $this->Html->css('/charts/css/html_chart_engine');
			$return  = sprintf('<style type=text/css>%s %s</style>', $this->__generateBarCss($data), $this->__css($data));
			$return .= $this->__generateBarHtml($data);

			return $return;
		}

		private function __generateBarCss($data){
			$colWidth = (($data['width'] + $data['spacing']['padding']) / count($data['data'][0]) / $data['width']) * 100;
			$margin = round($data['spacing']['padding'] / 2);
			$colWidth -= $data['spacing']['padding'];

			return <<<cssData
	.html-chart.bar.verticle{
		width: {$data['width']}px;
		height: {$data['height']}px;
		background-color: #{$data['color']['background']};
		color: #{$data['color']['text']};
		border-bottom: 1px solid #{$data['color']['lines']};
	}

	.html-chart.bar.verticle .y-axis{
		border-right: 1px solid #{$data['color']['lines']};
	}

	.html-chart.bar.verticle table{
		height: {$data['height']}px;
	}	

	.html-chart.bar.verticle td.col div{
		margin-left: {$margin}px;
		margin-right: {$margin}px;
	}

	.html-chart.bar.verticle .empty{
		background-color: #{$data['color']['background']};
	}

	.html-chart.bar.verticle .fill{
		background-color: #{$data['color']['fill']};
	}
	
cssData;

		}

		private function __generateBarHtml($data){
			$legend = '';
			$chart = '';

			$y = $rows = $cols = array();
			foreach($data['data'][0] as $key => $value){
				$cols[] = sprintf('<td class="col"><div class="empty e%d"></div><div class="fill f%d"></div></td>', 100 - $value, $value);
			}

			foreach($data['labels']['y'] as $label){
				$y[] = $label;
			}

			rsort($y);

			$rows[] = '<tr class="data"><td class="y-axis"><table><tr><td>' . implode('</td></tr><tr><td>', $y) . '</td></tr></table></td>' . implode('', $cols) . '</tr>';
			$rows[] = '<tr class="x-axis"><td>&nbsp;</td><td>' . implode('</td><td>', $data['labels'][$data['axes'][0]]) . '</td><tr>';
			
			$chart = sprintf(
				'<table>%s</table><div class="legend">%s</div>',
				implode('', $rows),
				$legend
			);

			return sprintf($this->__chartWrapper, $data['title'], $chart);
		}

		private function __css($data){
			$css = array();
			foreach(range(1, 100) as $num){
				$css[] = '.html-chart.bar.verticle .empty.e' . $num . ', .html-chart.bar.verticle .fill.f' . $num . ' {height: ' . round($num * ($data['height'] / 100)) .'px;}' . "\n";
			}

			return implode('', $css);
		}
	}


	