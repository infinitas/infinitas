<?php
	echo $this->Vcf->begin();
		echo $this->Vcf->attr('organization', Configure::read('Website.name'));
		echo $this->Vcf->attr('fullName', $contact['Contact']['last_name'].', '.$contact['Contact']['first_name']);
		echo $this->Vcf->attr('email', $contact['Contact']['email']);
		echo $this->Vcf->attr('url', env('SERVER_NAME'));
		echo $this->Vcf->attr('workPhone', $contact['Contact']['phone']);
		echo $this->Vcf->attr('cellPhone', $contact['Contact']['mobile']);
		echo $this->Vcf->attr('image', $this->Html->url('/img/content/contact/contact/'.$branch['Branch']['image'], true));
		echo $this->Vcf->address(
			$contact['Branch']['ContactAddress']['name'],
			array(
				'country' => $contact['Branch']['ContactAddress']['Country']['name'],
				'province' => $branch['ContactAddress']['province'],
				'postal' => $branch['ContactAddress']['postal'],
				'city' => $branch['ContactAddress']['city'],
				'street' => $branch['ContactAddress']['street']
			)
		);
	echo $this->Vcf->end();
?>