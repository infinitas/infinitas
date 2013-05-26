<?php
if (empty($geoLocationCountries)) {
	$geoLocationCountries = ClassRegistry::init('Shop.ShopAddress')->countries();
}

$geoLocationRegions = array();
$countrySelected = !empty($this->request->data[$model]['geo_location_country_id']);
if ($countrySelected && empty($geoLocationRegions)) {
	$geoLocationRegions = ClassRegistry::init('Shop.ShopAddress')->countries();	
}
echo $this->Form->input('geo_location_country_id', array(
	'options' => $geoLocationCountries,
	'class' => "ajaxSelectPopulate {url:{action:'geo_location_regions'}, target:'" . $model . "GeoLocationRegionId'}",
	'empty' => __d('shop', 'Select country')
));
echo $this->Form->input('geo_location_region_id', array(
	'options' => $geoLocationRegions,
	'empty' => __d('shop', 'Select region')
));