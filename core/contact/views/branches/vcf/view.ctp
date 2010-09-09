<?php
	echo $this->Vcf->begin();
		echo $this->Vcf->attr('organization', Configure::read('Website.name'));
		echo $this->Vcf->attr('fullName', $branch['Branch']['name']);
		echo $this->Vcf->attr('email', $branch['Branch']['name']);
		echo $this->Vcf->attr('url', env('SERVER_NAME'));
		echo $this->Vcf->attr('image', $this->Html->url('/img/content/contact/branch/'.$branch['Branch']['image'], true));
		echo $this->Vcf->address(
			$branch['Address']['name'],
			array(
				'country' => $branch['Address']['Country']['name'],
				'province' => $branch['Address']['province'],
				'postal' => $branch['Address']['postal'],
				'city' => $branch['Address']['city'],
				'street' => $branch['Address']['street']
			)
		);
	echo $this->Vcf->end();
?>