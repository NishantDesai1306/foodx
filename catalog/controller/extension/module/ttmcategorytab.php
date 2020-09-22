<?php
class ControllerExtensionModuleTtmcategorytab extends Controller { 

	private $error = array();

	public function index($setting) {
		$this->load->language('extension/module/ttmcategorytab');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');
		
		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);
		
		$new_results = $this->model_catalog_product->getProducts($filter_data);

		$this->load->model('tool/image');
		
	
		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.transitions.css');
		$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');
 
		$data['products'] = array();
		$data['string'] = $this->GenerateString(5);
		
		$categories_id = $setting['product_category'];
		$categories_id = array_slice($categories_id, 0, $setting['category_limit']);
		$data['categories'] = array();
		$data['products'] = array();
		$cnt = 0;
		foreach($categories_id as $category_id)
		{
			$category_info = $this->model_catalog_category->getCategory($category_id);			
			$category_name = $category_info['name'];
			$tab_active = ($cnt == 0) ? 'class="active"' : '';
			$div_active = ($cnt == 0) ? ' active' : '';
			$data['categories'][] = array(
				'category_info' 	=> $category_info,
				'category_tab' 	=> '<li '. $tab_active .'><a onclick="'.$data['string'].'Ajaxload('."'".$category_id."'".')" href="#'.$category_name.'" data-toggle="tab">'.$category_name.'</a></li>',
				'category_div' 	=> '<div class="tab-product'.$div_active.'" id="'.$category_name.'">',
				'category_id'  		=> $category_id,
				'target_category_id'  => $category_id,						
				'category_name' 	=> $category_name,
			);
			$cnt++;
 		}
		$data['category_href'] = $this->url->link('product/category', 'path=' . $categories_id[0]);

		$products = array();
		$filter_data = array(
			'filter_category_id' => $categories_id[0],
			'sort'               => 'p.sort_order',
			'order'              => 'ASC',
			'start'              => 0 * $setting['product_limit'],
			'limit'              => $setting['product_limit']
		);
 		$results = $this->model_catalog_product->getProducts($filter_data);
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
				$rating = (int)$result['rating'];
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
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'product_quantity'  => $result['quantity'],
					'product_stock'  => $result['stock_status'],
					'text_stock'  => $this->language->get('text_stock'),
					'is_new'      => $is_new,
				'percentsaving' 	 => round((($result['price'] - $result['special'])/$result['price'])*100, 0),
				'rating'      => $result['rating'],
				'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				'quick'        => $this->url->link('product/quick_view','&product_id=' . $result['product_id'])
			);
		} 
		
		$data['setting'] = $setting;

		if ($data['categories']) {
				
			return $this->load->view('extension/module/ttmcategorytab', $data);
			
		}

	}
	public function AjaxLoad() {
		$this->load->language('extension/module/ttmcategorytab');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');
 
		$data['products'] = array();
		$data['string'] = $this->GenerateString(5);
		$data['mytemplate'] = $this->config->get('theme_default_directory');
		
		if(isset($this->request->post['setting'])){
			$setting = $this->request->post['setting'];
		}
		if(isset($this->request->post['category_id'])){
			$target_category_id = $this->request->post['category_id'];
		}

		$categories_id = $setting['product_category'];
		$categories_id = array_slice($categories_id, 0, $setting['category_limit']);
		$data['categories'] = array();
		$data['products'] = array();
		$cnt = 0;
		foreach($categories_id as $category_id)
		{
			$category_info = $this->model_catalog_category->getCategory($category_id);			
			$category_name = $category_info['name'];
	
			$tab_active = ($target_category_id == $category_id) ? 'class="active"' : '';
			$div_active = ($target_category_id == $category_id) ? ' active' : '';
			$data['categories'][] = array(
				'category_info' 	=> $category_info,
				'category_tab' 	=> '<li '. $tab_active .'><a  onclick="'.$data['string'].'Ajaxload('."'".$category_id."'".')"  href="#'.$category_name.'" data-toggle="tab">'.$category_name.'</a></li>',
				'category_div' 	=> '<div class="tab-product'.$div_active.'" id="'.$category_name.'">',
				'category_id'  		=> $category_id,
				'target_category_id' => $target_category_id,			
				'category_name' 	=> $category_name,
				'category_href'        => $this->url->link('product/category', 'path=' . $category_id),
 			);			
			$cnt++;
  		}
		$data['category_href'] = $this->url->link('product/category', 'path=' . $target_category_id);
	
		$products = array();
		$filter_data = array(
			'filter_category_id' => $target_category_id,
			'sort'               => 'p.sort_order',
			'order'              => 'ASC',
			'start'              => 0 * $setting['product_limit'],
			'limit'              => $setting['product_limit']
		);
		
		$data['setting'] = $setting;
		
 		$results = $this->model_catalog_product->getProducts($filter_data);
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
				$rating = (int)$result['rating'];
			} else {
				$rating = false;
			}

			$data['products'][] = array(
				'product_id'  => $result['product_id'],
				'thumb'       => $image,
				'thumb_swap'  => $this->model_tool_image->resize($images , $setting['width'], $setting['height']),
				'name'        => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'percentsaving' 	 => round((($result['price'] - $result['special'])/$result['price'])*100, 0),
				'rating'      => $result['rating'],
				'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'] )
			);
		}
		if ($data['categories']) {	
		 echo $this->load->view('extension/module/ttmcategorytab', $data); 
		}
	}
	public function GenerateString($length = 5) {
		return substr(str_shuffle(implode(array_merge(range('a', 'z')))), 0, $length);
	}
	
}