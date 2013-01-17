<?php
/**
 * TemplatesController
 *
 * @package Infinitas.Newsletter.Controller
 */

/**
 * TemplatesController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class NewsletterTemplatesController extends NewsletterAppController {
/**
 * Template sample text
 *
 * @var string
 */
	public $sampleText = '<p>This is some sample text to test your template</p>';

/**
 * List all templates
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'fields' => array(
				$this->modelClass . '.id',
				$this->modelClass . '.name',
				$this->modelClass . '.created',
				$this->modelClass . '.modified',
			)
		);

		$templates = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name'
		);

		$this->set(compact('templates', 'filterOptions'));
	}

/**
 * View a template
 *
 * @param string $id the template id
 *
 * @return void
 */
	public function admin_view($id = null) {
		if (!$id) {
			$this->notice('invalid');
		}

		$this->set('newsletterTemplate', $this->{$this->modelClass}->read(null, $id));
	}

/**
 * Export newsletters
 *
 * @param string $id the newsletter id
 *
 * @return void
 */
	public function admin_export($id = null) {
		if (!$id) {
			$this->notice('invalid');
		}

		$template = $this->{$this->modelClass}->read(array('name', 'description', 'author', 'header', 'footer'), $id);

		if (empty($template)) {
			$this->notice('invalid');
		}

		$pattern = "/src=[\\\"']?([^\\\"']?.*(png|jpg|gif|jpeg))[\\\"']?/i";
		preg_match_all($pattern, $template[$this->modelClass]['header'], $images);

		$path = TMP . 'cache' . DS . 'newsletter' . DS . $this->modelClass . DS . $template[$this->modelClass]['name'];

		$Folder = new Folder($path, 0777);
		$slash = $Folder->correctSlashFor($path);

		App::import('File');
		App::import('Folder');

		$File = new File($path . DS . $this->modelClass . '.xml', true, 0777);

		$imageFiles = array();
		if (!empty($images[1])) {
			foreach ($images[1] as $img) {
				$img = str_replace('/', $slash, $img);
				$img = str_replace('\\', $slash . $slash, $img);

				$imageFiles[] = $img;

				if (is_file(APP . 'webroot' . $img)) {
					$Folder->create(dirname($path . $img), 0777);
					$File->path = APP . 'webroot' . $img;
					$File->copy(dirname($path . $img) . DS . basename($img));
				}
			}
		}

		$xml[$this->modelClass]['name'] = 'Infinitas Newsletter Template';
		$xml[$this->modelClass]['generator'] = 'Infinitas Template Generator';
		$xml[$this->modelClass]['version'] = Configure::read('Infinitas.version');
		$xml[$this->modelClass][$this->modelClass] = $template[$this->modelClass]['name'];
		$xml[$this->modelClass]['description'] = $template[$this->modelClass]['description'];
		$xml[$this->modelClass]['author'] = $template[$this->modelClass]['author'];
		$xml['data']['header'] = $template[$this->modelClass]['header'];
		$xml['data']['footer'] = $template[$this->modelClass]['footer'];
		$xml['files']['images'] = $imageFiles;

		App::Import('Helper', 'Xml');
		$Xml = new XmlHelper();

		$File->path = $path . DS . $this->modelClass . '.xml';
		$File->write($Xml->serialize($xml));

		App::import('Vendor', 'Zip', array('file' => 'zip.php'));

		$Zip = new CreateZipFile();
		$Zip->zipDirectory($path, null);
		$File = new File($path . DS . $this->modelClass . '.zip', true, 0777);
		$File->write($Zip->getZippedfile());

		$this->view = 'Media';
		$params = array(
			'id' => $this->modelClass . '.zip',
			'name' => $template[$this->modelClass]['name'],
			'download' => true,
			'extension' => 'zip',
			'path' => $path . DS
		);

		$this->set($params);
		$Folder = new Folder($path);
		$Folder->read();
		$Folder->delete($path);
	}

/**
 * Preview a template
 *
 * @param string $id the template id
 *
 * @return void
 */
	public function admin_preview($id = null) {
		$this->layout = 'ajax';

		if (!$id) {
			return $this->set('data', __d('newsletter', 'The template was not found'));
		}

		$template = $this->{$this->modelClass}->read(array('header', 'footer'), $id);
		$this->set('data', $template[$this->modelClass]['header'] . $this->sampleText . $template[$this->modelClass]['footer']);
	}

/**
 * Handle mass action deletes
 *
 * @param array $ids list of ids to delete
 *
 * @return boolean
 */
	public function __massActionDelete($ids) {
		return $this->MassAction->delete($this->__canDelete($ids));
	}

/**
 * Check if the templates can be deleted
 *
 * @param array $ids Ids to be deleted
 *
 * @return array
 */
	private function __canDelete($ids) {
		$newsletters = $this->{$this->modelClass}->Newsletter->find('list', array(
			'fields' => array(
				'Newsletter.template_id',
				'Newsletter.template_id'
			),
			'conditions' => array(
				'Newsletter.template_id' => $ids
			)
		));

		foreach ($ids as $k => $v) {
			if (isset($newsletters[$v])) {
				unset($ids[$k]);
			}
		}

		if (empty($ids)) {
			$this->notice(
				__d('newsletter', 'There are some newsletters using that template.'),
				array(
					'redirect' => true
				)
			);
		}

		$campaigns = $this->{$this->modelClass}->NewsletterCampaign->find('list', array(
			'fields' => array(
				'NewsletterCampaign.template_id',
				'NewsletterCampaign.template_id'
			),
			'conditions' => array(
				'NewsletterCampaign.template_id' => $ids
			)
		));

		foreach ($ids as $k => $v) {
			if (isset($campaigns[$v])) {
				unset($ids[$k]);
			}
		}

		if (empty($ids)) {
			$this->notice(
				__d('newsletter', 'There are some campaigns using that template.'),
				array(
					'redirect' => true
				)
			);
		}

		return $ids;
	}

}