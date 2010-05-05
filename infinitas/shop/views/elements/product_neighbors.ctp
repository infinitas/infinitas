<div class="neighbors">
	<div class="prev">
		<?php
			if(isset($neighbors['prev']) || !empty($neighbors['prev'])){
				$prev = $neighbors['prev'];
				$prev['Product']['plugin'] = 'shop';
				$prev['Product']['controller'] = 'products';
				$prev['Product']['action'] = 'view';
				$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'products', 'data' => $prev['Product']));
				echo $this->Html->link(
					$this->Shop->getImage(
						$prev,
						array(
							'width' => '50px',
							'title' => $prev['Product']['name'],
							'alt' => $prev['Product']['name']
						)
					).$prev['Product']['name'],
					current($eventData['slugUrl']),
					array(
						'escape' => false
					)
				);
			}
		?>
	</div>
	<div class="next">
		<?php
			if(isset($neighbors['next']) || !empty($neighbors['next'])){
				$next = $neighbors['next'];
				$next['Product']['plugin'] = 'shop';
				$next['Product']['controller'] = 'products';
				$next['Product']['action'] = 'view';
				$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'products', 'data' => $next['Product']));
				echo $this->Html->link(
					$this->Shop->getImage(
						$next,
						array(
							'width' => '50px',
							'title' => $next['Product']['name'],
							'alt' => $next['Product']['name']
						)
					).$next['Product']['name'],
					current($eventData['slugUrl']),
					array(
						'escape' => false
					)
				);
			}
		?>
	</div>
</div>