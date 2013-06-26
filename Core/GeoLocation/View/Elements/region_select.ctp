<?php
if (empty($geoLocationCountries)) {
	$geoLocationCountries = ClassRegistry::init('Shop.ShopAddress')->countries();
}

$geoLocationRegions = array();
$countrySelected = !empty($this->request->data[$model]['geo_location_country_id']);
if ($countrySelected && empty($geoLocationRegions)) {
	$geoLocationRegions = ClassRegistry::init('Shop.ShopAddress')->regions($this->request->data[$model]['geo_location_country_id']);	
}
if (!isset($label)) {
	$label = array(
		'region' => __d('geo_location', 'Region'),
		'country' => __d('geo_location', 'Country')
	);
}
echo $this->Form->input('geo_location_country_id', array(
	'options' => $geoLocationCountries,
	'class' => "ajaxSelectPopulate {url:{action:'geo_location_regions'}, target:'" . $model . "GeoLocationRegionId'}",
	'empty' => __d('shop', 'Select country'),
	'label' => !empty($label['country']) ? $label['country'] : false
));
echo $this->Form->input('geo_location_region_id', array(
	'options' => $geoLocationRegions,
	'empty' => __d('shop', 'Select region'),
	'label' => !empty($label['region']) ? $label['region'] : false
));