<div class="imageGallery">
	<div class="mainImage">
		<?php
			$product['ProductImage'][] = $product['Image'];
			if(count($product['ProductImage']) == 1){
				$_image['Product']['Image'] = $product['ProductImage'][0];
				echo $this->Shop->getImage(
					$_image,
					array(
						'width' => '80px',
						'title' => $product['Product']['name'],
						'alt' => $product['Product']['name']
					)
				);
			}
		?>
	</div>
	<ul class="gallery">
		<?php
			if(count($product['ProductImage']) > 1){
				shuffle($product['ProductImage']);
				foreach((array)$product['ProductImage'] as $otherImage){
					$_image['Product']['Image'] = $otherImage;
					echo '<li>', $this->Shop->getImage(
						$_image,
						array(
							'width' => '80px',
							'title' => $product['Product']['name'],
							'alt' => $product['Product']['name']
						)
					), '</li>';
				}
			}
		?>
	</ul>
</div>