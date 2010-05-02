<?php
    /**
     * Shop suppliers add/edit
     *
     * This page is used to add/edit suppliers for your products.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       shop
     * @subpackage    shop.views.suppliers.form
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since 0.8a
     */

    echo $this->Form->create('Special', array('type' => 'file'));
        echo $this->Infinitas->adminEditHead($this);
        echo $this->Design->niceBox();
        	?>
				<div class="data">
					<?php
						if(isset($this->data['Special']['amount']) && $this->data['Special']['amount'] > 0){
							$discount = $this->data['Special']['amount'];
						}
						else if(isset($this->data['Special']['discount']) && $this->data['Special']['discount'] > 0){
							$discount = $this->data['Product']['price'];
						}

						echo $this->Form->input(
							'product_id',
							array(
								'empty' => __(Configure::read('Website.empty_select'), true),
								'class' => "productChange {url:{action:'getPrices'}, target:'price'}"
							)
						);
						?><div class="priceAdjusters"><?php
							echo $this->Form->input('id');
							echo $this->Form->input('amount', array('before' => Configure::read('Currency.unit')));
							?><div class="SpecialAmountSlider"></div><?php
							echo $this->Form->input('discount', array('after' => '%'));
							?><div class="SpecialDiscountSlider"></div>
						</div>
					<script type="text/javascript">
						$(function() {
							$(".priceAdjusters").hide();
							var currentProduct;
							$(".SpecialDiscountSlider").slider({
								value:0,
								min: 0,
								max: 100,
								range: "min",
								step: 0.01,
								slide: function(event, ui) {
									specialAmount = $.NumberHelper.format((currentProduct.price/100)*ui.value);

									$("#SpecialAmount").val(specialAmount);
									$(".SpecialAmountSlider").slider("option", "value", specialAmount);
									$("#SpecialDiscount").val(ui.value);
								}
							});

							$('.productChange').change(function(){
								if ($(this).val().length != 0) {
									metaData = $.HtmlHelper.getParams($(this));
									metaData.params.product = $(this).val();
									$.HtmlHelper.requestAction(metaData, resetData);
								}
							});

							$('#SpecialAmount').change(function(){
								var amount = $(this).val();
								var discount = $.NumberHelper.format((amount / currentProduct.price) * 100);

								$('#SpecialDiscount').val(discount);
								$(".SpecialDiscountSlider").slider("option", "value", discount);

							});

							$('#SpecialDiscount').change(function(){
								var discount = $(this).val();
								var amount = $.NumberHelper.format((currentProduct.price/100) * discount);

								$('#SpecialAmount').val(amount);
								$(".SpecialDiscountSlider").slider("option", "value", discount);

							});

							var maxDiscount;
							function resetData(data, metaData){
								$(".priceAdjusters").show();
								currentProduct = data.Product;
								maxDiscount = 100 - (currentProduct.cost/currentProduct.price)*100;

								$(".SpecialDiscountSlider").slider("option", "max", maxDiscount);

								$("#SpecialAmount").val(0.00);
								$("#SpecialDiscount").val(0.00);
								$(".SpecialDiscountSlider").slider("option", "value", 0);
							}
						});
					</script>
				</div>
				<div class="config">
					<?php
        				echo $this->Design->niceBox(null, '<h2>'.__('Start', true).'</h2>'.$this->Shop->datePicker(array('start_date'), null, true));
        				echo $this->Design->niceBox(null, '<h2>'.__('End', true).'</h2>'.$this->Shop->datePicker(array('end_date'), null, true));

        				echo $this->Design->niceBox();
        					?><h2><?php __('config'); ?></h2><?php
							echo $this->Form->input('ShopBranch');
        				echo $this->Design->niceBoxEnd();

        				echo $this->Design->niceBox();
        					?><h2><?php __('Image'); ?></h2><?php
							echo $this->Form->input('Image.image', array('label' => __('New image', true), 'type' => 'file'));
							echo $this->Form->input('image_id', array('label' => __('Exsisting image', true), 'empty' => __(Configure::read('Website.empty_select'), true)));
        				echo $this->Design->niceBoxEnd();
					?>
				</div>
				<div class="clr">&nbsp;</div>
			<?php
        echo $this->Design->niceBoxEnd();
    echo $this->Form->end();
?>