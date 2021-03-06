<?php
class ControllerExtensionModuleBestSeller extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/bestseller');

		$this->load->model('catalog/product');
		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);
		$new_results = $this->model_catalog_product->getProducts($filter_data);

		$this->load->model('tool/image');

		$data['products'] = array();
 $data['grid'] = $setting['grid']; 

		$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				
			$images = $this->model_catalog_product->getProductImages($result['product_id']);
				
						if(isset($images[0]['image']) && !empty($images)){
						$images = $images[0]['image']; 
						}else
						{
						$images = $image;
						}
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				
				$is_new = false;
				if ($new_results) { 
					foreach($new_results as $new_r) {
						if($result['product_id'] == $new_r['product_id']) {
							$is_new = true;
						}
					}
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					
			'thumb'       => $image,
			'thumb_swap'  => $this->model_tool_image->resize($images , $setting['width'], $setting['height']),
			
					'name'        => $result['name'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'product_quantity'  => $result['quantity'],
					'product_stock'  => $result['stock_status'],
					'text_stock'  => $this->language->get('text_stock'),
					'is_new'      => $is_new,
					
			'percentsaving' 	 => round((($result['price'] - $result['special'])/$result['price'])*100, 0),
			'rating'      => $rating,
			
					
			'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			'quick'        => $this->url->link('product/quick_view','&product_id=' . $result['product_id'])
			
				);
			}

			return $this->load->view('extension/module/bestseller', $data);
		}
	}
}
