<?php
class ControllerExtensionModuleGalleryAlbum extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/gallery_album');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_gallery_album', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/gallery_album', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/gallery_album', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_gallery_album_status'])) {
			$data['module_gallery_album_status'] = $this->request->post['module_gallery_album_status'];
		} else {
			$data['module_gallery_album_status'] = $this->config->get('module_gallery_album_status');
		}

		if (isset($this->request->post['module_gallery_album_width'])) {
			$data['module_gallery_album_width'] = $this->request->post['module_gallery_album_width'];
		} else {
			$data['module_gallery_album_width'] = $this->config->get('module_gallery_album_width');
				}
		if (isset($this->request->post['module_gallery_album_height'])) {
			$data['module_gallery_album_height'] = $this->request->post['module_gallery_album_height'];
		} else {
			$data['module_gallery_album_height'] = $this->config->get('module_gallery_album_height');
		}
		if (isset($this->request->post['module_gallery_album_popwidth'])) {
			$data['module_gallery_album_popwidth'] = $this->request->post['module_gallery_album_popwidth'];
		} else {
			$data['module_gallery_album_popwidth'] = $this->config->get('module_gallery_album_popwidth');
		}
		if (isset($this->request->post['module_gallery_album_popheight'])) {
			$data['module_gallery_album_popheight'] = $this->request->post['module_gallery_album_popheight'];
		} else {
			$data['module_gallery_album_popheight'] = $this->config->get('module_gallery_album_popheight');
		}	
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gallery_album', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/gallery_album')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	
	public function install() {
	   
		$this->load->model('extension/module/gallery_album');

		$this->model_extension_module_gallery_album->install();
	}

	public function uninstall() {
		$this->load->model('extension/module/gallery_album');

		$this->model_extension_module_gallery_album->uninstall();
	}
}