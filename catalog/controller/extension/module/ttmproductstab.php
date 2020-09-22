<?php
class ControllerExtensionModuleTtmproductstab extends Controller
{
    public function index($setting)
    {
        $this->load->language('extension/module/ttmproductstab');
        $this->load->model('catalog/product');
		
		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);
		$new_results = $this->model_catalog_product->getProducts($filter_data);
		
		
        $this->load->model('tool/image');
        static $module = 0;
        //Featured Products
        if (isset($setting['featured_products_status']) && $setting['featured_products_status']) {
            $data['featured_products_status'] = true;
        } else {
            $data['featured_products_status'] = false;
        }
        $data['featured_products'] = array();
        if (empty($setting['limit'])) {
            $setting['limit'] = 4;
        }
        if (!empty($setting['product'])) {
            $products = array_slice($setting['product'], 0, (int) $setting['limit']);
            foreach ($products as $product_id) {
                $product_info = $this->model_catalog_product->getProduct($product_id);
                if ($product_info) {
                    if ($product_info['image']) {
                        $image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                    }
					
					//added for image swap
				
					$images = $this->model_catalog_product->getProductImages($product_info['product_id']);
	
					if(isset($images[0]['image']) && !empty($images)){
					 $images = $images[0]['image']; 
					   }else
					   {
					   $images = $image;
					   }
					   
                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $price = false;
                    }
                    if ((float) $product_info['special']) {
                        $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $special = false;
                    }
                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float) $product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
                    } else {
                        $tax = false;
                    }
                    if ($this->config->get('config_review_status')) {
                        $rating = $product_info['rating'];
                    } else {
                        $rating = false;
                    }
					
					$is_new = false;
				if ($new_results) { 
					foreach($new_results as $new_r) {
						if($product_info['product_id'] == $new_r['product_id']) {
							$is_new = true;
						}
					}
				}
				
                    $data['featured_products'][] = array(
                        'product_id' => $product_info['product_id'],
                        'thumb' => $image,
						'thumb_swap'  => $this->model_tool_image->resize($images , $setting['width'], $setting['height']),
                        'name' => $product_info['name'],
                        'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price' => $price,
                        'special' => $special,
                        'percentsaving' => round((($product_info['price'] - $product_info['special']) / $product_info['price']) * 100, 0),
                        'tax' => $tax,
                        'rating' => $rating,
						'product_quantity'  => $product_info['quantity'],
					    'product_stock'  => $product_info['stock_status'],
					     'text_stock'  => $this->language->get('text_stock'),
						 'is_new'      => $is_new,
						'quick'        => $this->url->link('product/quick_view','&product_id=' . $product_info['product_id']),
                        'href' => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                    );
                }
            }
        }
        //Latest Products
        if (isset($setting['latest_products_status']) && $setting['latest_products_status']) {
            $data['latest_products_status'] = true;
        } else {
            $data['latest_products_status'] = false;
        }
        $data['latest_products'] = array();
        if (empty($setting['limit'])) {
            $setting['limit'] = 4;
        }
        if (!empty($setting['product'])) {
            $filter_data    = array(
                'sort' => 'p.date_added',
                'order' => 'DESC',
                'start' => 0,
                'limit' => $setting['limit']
            );
            $latest_results = $this->model_catalog_product->getLatestProducts($setting['limit']);
			
            if ($latest_results) {
                foreach ($latest_results as $result) {
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
                    if ((float) $result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $special = false;
                    }
                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
                    } else {
                        $tax = false;
                    }
                    if ($this->config->get('config_review_status')) {
                        $rating = $result['rating'];
                    } else {
                        $rating = false;
                    }
					
					$is_new = false;
				if ($latest_results) { 
					foreach($latest_results as $new_r) {
						if($result['product_id'] == $new_r['product_id']) {
							$is_new = true;
						}
					}
				}
                    $data['latest_products'][] = array(
                        'product_id' => $result['product_id'],
                        'thumb' => $image,
						'thumb_swap'  => $this->model_tool_image->resize($images , $setting['width'], $setting['height']),
                        'name' => $result['name'],
                        'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price' => $price,
                        'special' => $special,
                        'percentsaving' => round((($result['price'] - $result['special']) / $result['price']) * 100, 0),
                        'tax' => $tax,
                        'rating' => $rating,
						'product_quantity'  => $result['quantity'],
						'product_stock'  => $result['stock_status'],
						'text_stock'  => $this->language->get('text_stock'),
						'is_new'      => $is_new,
						'quick'        => $this->url->link('product/quick_view','&product_id=' . $result['product_id']),
                        'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                    );
                }
            }
        }
        //BestSeller Products
        if (isset($setting['bestseller_products_status']) && $setting['bestseller_products_status']) {
            $data['bestseller_products_status'] = true;
        } else {
            $data['bestseller_products_status'] = false;
        }
        $data['bestseller_products'] = array();
		
		
		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);
		
		$new_results = $this->model_catalog_product->getProducts($filter_data);
		
		
        if (!empty($setting['product'])) {
            $bestseller_results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);
            if ($bestseller_results) {
                foreach ($bestseller_results as $result) {
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
                    if ((float) $result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $special = false;
                    }
                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
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
				
                    $data['bestseller_products'][] = array(
                        'product_id' => $result['product_id'],
                        'thumb' => $image,
						'thumb_swap'  => $this->model_tool_image->resize($images , $setting['width'], $setting['height']),
                        'name' => $result['name'],
                        'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price' => $price,
                        'special' => $special,
                        'percentsaving' => round((($result['price'] - $result['special']) / $result['price']) * 100, 0),
                        'tax' => $tax,
                        'rating' => $rating,
						'product_quantity'  => $result['quantity'],
					'product_stock'  => $result['stock_status'],
					'text_stock'  => $this->language->get('text_stock'),
					'is_new'      => $is_new,
						'quick'        => $this->url->link('product/quick_view','&product_id=' . $result['product_id']),
                        'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                    );
                }
            }
        }
        //Specials product
        $data['special_products'] = array();
        if (isset($setting['special_products_status']) && $setting['special_products_status']) {
            $data['special_products_status'] = true;
        } else {
            $data['special_products_status'] = false;
        }
		
		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);
		
		$new_results = $this->model_catalog_product->getProducts($filter_data);
		
        if (!empty($setting['product'])) {
            $special_data    = array(
                'sort' => 'pd.name',
                'order' => 'ASC',
                'start' => 0,
                'limit' => $setting['limit']
            );
            $special_results = $this->model_catalog_product->getProductSpecials($special_data);
            if ($special_results) {
                foreach ($special_results as $result) {
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
                    if ((float) $result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $special = false;
                    }
                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
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
				
                    $data['special_products'][] = array(
                        'product_id' => $result['product_id'],
                        'thumb' => $image,
						'thumb_swap'  => $this->model_tool_image->resize($images , $setting['width'], $setting['height']),
                        'name' => $result['name'],
                        'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price' => $price,
                        'special' => $special,
                        'percentsaving' => round((($result['price'] - $result['special']) / $result['price']) * 100, 0),
						'product_quantity'  => $result['quantity'],
						'product_stock'  => $result['stock_status'],
						'text_stock'  => $this->language->get('text_stock'),
                        'tax' => $tax,
                        'rating' => $rating,
						'is_new'      => $is_new,
						'quick'        => $this->url->link('product/quick_view','&product_id=' . $result['product_id']),
                        'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
                    );
                }
            }
        }
        $data['module'] = $module++;
        return $this->load->view('extension/module/ttmproductstab', $data);
    }
}