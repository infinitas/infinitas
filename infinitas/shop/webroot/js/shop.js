/*
 * sort out the specials page with a nice slider
 */
$(function() {
	$(".priceAdjusters").hide();
	var $SpecialDiscountSlider = $(".SpecialDiscountSlider");
	var $SpecialAmount = $("#SpecialAmount");
	var $SpecialDiscount = $('#SpecialDiscount');
	var currentProduct;

	$SpecialDiscountSlider.slider({
		value:0,
		min: 0,
		max: 100,
		range: "min",
		step: 0.01,
		slide: function(event, ui) {
			specialAmount = $.NumberHelper.format((currentProduct.price/100)*ui.value);
			$SpecialAmount.val(specialAmount);
			$SpecialDiscountSlider.slider("option", "value", specialAmount);
			$SpecialDiscount.val(ui.value);
		}
	});

	// when the amount field is updated
	$SpecialAmount.change(function(){
		var amount = $(this).val();
		var discount = $.NumberHelper.format((amount / currentProduct.price) * 100);
		$SpecialDiscount.val(discount);
		$SpecialDiscountSlider.slider("option", "value", discount);

	});

	// when the discount field is updated
	$SpecialDiscount.change(function(){
		var discount = $(this).val();
		var amount = $.NumberHelper.format((currentProduct.price/100) * discount);

		$SpecialAmount.val(amount);
		$SpecialDiscountSlider.slider("option", "value", discount);

	});

	// when the product is changed
	$('.productChange').change(function(){
		if ($(this).val().length != 0) {
			$(".priceAdjusters").hide();
			metaData = $.HtmlHelper.getParams($(this));
			metaData.params.product = $(this).val();
			$.HtmlHelper.requestAction(metaData, resetData);
		}
	});

	// after the product is changed. call back for above
	var maxDiscount;
	function resetData(data, metaData){
		$(".priceAdjusters").show();
		currentProduct = data.Product;
		maxDiscount = 100 - (currentProduct.cost/currentProduct.price)*100;

		$SpecialDiscountSlider.slider("option", "max", maxDiscount);

		$SpecialAmount.val(0.00);
		$SpecialDiscount.val(0.00);
		$SpecialDiscountSlider.slider("option", "value", 0);
	}
});