<?php
	echo $this->Vcf->begin();
		echo $this->Vcf->attr('organization', Configure::read('Website.name'));
		echo $this->Vcf->attr('fullName', $branch['Branch']['name']);
		echo $this->Vcf->attr('email', $branch['Branch']['name']);
		echo $this->Vcf->attr('url', env('SERVER_NAME'));
		echo $this->Vcf->attr('image', $this->Html->url('/img/content/contact/branch/'.$branch['Branch']['image'], true));
		echo $this->Vcf->address(
			$branch['ContactAddress']['name'],
			array(
				'country' => $branch['ContactAddress']['Country']['name'],
				'province' => $branch['ContactAddress']['province'],
				'postal' => $branch['ContactAddress']['postal'],
				'city' => $branch['ContactAddress']['city'],
				'street' => $branch['ContactAddress']['street']
			)
		);
	echo $this->Vcf->end();
?>