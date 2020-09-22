<?php
class ControllerExtensionModuleTtmproductstab extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('extension/module/ttmproductstab');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/module');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('ttmproductstab', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->cache->delete('product');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}
		
		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
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

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/ttmproductstab', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/ttmproductstab', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);			
		}
		
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/ttmproductstab', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/ttmproductstab', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}	
			
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('catalog/product');
		
		$data['products'] = array();
		
		if (isset($this->request->post['product'])) {
			$products = $this->request->post['product'];
		} elseif (!empty($module_info)) {
			$products = $module_info['product'];
		} else {
			$products = array();
		}	
		
		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}
		
		
		if (isset($this->request->post['featured_products_status'])) {
			$data['featured_products_status'] = $this->request->post['featured_products_status'];
		} elseif (!empty($module_info)) {
			$data['featured_products_status'] = $module_info['featured_products_status'];
		} else {
			$data['featured_products_status'] = '';
		}	

		if (isset($this->request->post['latest_products_status'])) {
			$data['latest_products_status'] = $this->request->post['latest_products_status'];
		} elseif (!empty($module_info)) {
			$data['latest_products_status'] = $module_info['latest_products_status'];
		} else {
			$data['latest_products_status'] = '';
		}

		if (isset($this->request->post['bestseller_products_status'])) {
			$data['bestseller_products_status'] = $this->request->post['bestseller_products_status'];
		} elseif (!empty($module_info)) {
			$data['bestseller_products_status'] = $module_info['bestseller_products_status'];
		} else {
			$data['bestseller_products_status'] = '';
		}	

		if (isset($this->request->post['special_products_status'])) {
			$data['special_products_status'] = $this->request->post['special_products_status'];
		} elseif (!empty($module_info)) {
			$data['special_products_status'] = $module_info['special_products_status'];
		} else {
			$data['special_products_status'] = '';
		}
	
		
		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}	
				
		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}	
			
		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
		}
				
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/ttmproductstab', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/ttmproductstab')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if(!isset($this->request->post['special_products'])){
			$this->request->post['special_products'] = 0;
		}

		if(!isset($this->request->post['latest_products'])){
			$this->request->post['latest_products'] = 0;
		}

		if(!isset($this->request->post['featured_products'])){
			$this->request->post['featured_products'] = 0;
		}

		if(!isset($this->request->post['bestseller_products'])){
			$this->request->post['bestseller_products'] = 0;
		}

		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}
		
		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}	

		return !$this->error;
		}
}
