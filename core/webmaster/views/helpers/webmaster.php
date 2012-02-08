<?php
	class WebmasterHelper extends AppHelper {
		public function seoMetaTags() {
			$View = ClassRegistry::getObject('view');

			$contentIndex = $contentFollow = $canonicalUrl = null;
			if(!empty($View->viewVars['seoContentIndex'])) {
				$contentIndex = $View->viewVars['seoContentIndex'];
			}

			if(!empty($View->viewVars['seoContentFollow'])) {
				$contentFollow = $View->viewVars['seoContentFollow'];
			}

			if(!empty($View->viewVars['seoCanonicalUrl'])) {
				$canonicalUrl = $View->viewVars['seoCanonicalUrl'];
			}

			$contentTitle = Configure::read('Website.name');
			if(!empty($View->viewVars['title_for_layout'])) {
				$contentTitle = sprintf('%s :: %s', $View->viewVars['title_for_layout'], Configure::read('Website.name'));
			}
			unset($View);
			
			return implode('', array(
				$this->metaIcon(),
				$this->metaCharset(),
				$this->metaRobotTag($contentIndex, $contentFollow),
				$this->metaDescription(Configure::read('Website.description')),
				$this->metaKeywords(Configure::read('Website.keywords')),
				$this->metaTitle($contentTitle),
				$this->metaCanonicalUrl($canonicalUrl),
				$this->metaAuthor(),
				$this->metaGenerator(),
				$this->metaGoogleVerification(),
			));
		}

		public function metaRobotTag($contentIndex = null, $contentFollow = null) {
			$index = 'noindex';
			if($contentIndex !== false) {
				$index = 'index';
			}

			$follow = 'nofollow';
			if($contentFollow !== false) {
				$follow = 'follow';
			}

			return $this->Html->meta(
				array('name' => 'robots', 'content' => sprintf('%s %s', $follow, $index))
			);
		}

		public function metaDescription($description = null) {
			if(!$description) {
				return false;
			}

			return $this->Html->meta('description', $description);
		}

		public function metaKeywords($keywords = null) {
			if(is_array($keywords)) {
				$keywords = implode(',', $keywords);
			}

			if(!$keywords) {
				return false;
			}
			
			return $this->Html->meta('keywords', $keywords);
		}

		public function metaCanonicalUrl($url = null) {
			if(empty($canonicalUrl)) {
				return false;
			}

			return sprintf('<link rel="canonical" href="%s" />', Router::url($canonicalUrl, true));
		}

		public function metaAuthor($author = null) {
			if(!$author) {
				$author = Configure::read('Website.name');
			}

			return $this->Html->meta(array('name' => 'author', 'content' => $author));
		}

		public function metaGenerator($generator = null) {
			if(!$generator) {
				$generator = sprintf('Infinitas %s', Configure::read('Infinitas.version'));
			}
			
			return $this->Html->meta(array('name' => 'generator', 'content' => $generator));
		}

		public function metaIcon() {
			return $this->Html->meta('icon');
		}
		
		public function metaCharset() {
			return $this->Html->charset();
		}

		public function metaGoogleVerification() {
			if(!Configure::read('Webmaster.google_site_verification')) {
				return false;
			}
			
			return $this->Html->meta(
				array('name' => 'google-site-verification', 'content' => Configure::read('Webmaster.google_site_verification'))
			);
		}

		public function metaTitle($title = null) {
			if(!$title) {
				return false;
			}

			return sprintf('<title>%s</title>', $title);
		}

		public function metaRss($feed = null) {
			if(!is_array($feed)) {
				$feed = array('url' => $feed);
			}
			
			$feed = array_merge(
				array(
					'url' => $this->here . '.rss',
					'type' => 'application/rss+xml',
					'title' => sprintf(__d('feed', '%s RSS feed', true), Configure::read('Website.name'))
				),
				$feed
			);

			return sprintf(
				'<link rel="alternate" type="%s" title="%s" href="%s"/>',
				$feed['type'],
				$feed['title'],
				$feed['href']
			);

		}
	}