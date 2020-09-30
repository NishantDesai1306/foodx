<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		// Menu
		$this->load->model('catalog/category');

		
			$this->load->model('catalog/product');
			$type="module";
    	$this->load->model('setting/module');
		$result=$this->model_setting_module->getModule($type);
		foreach($result as $result){
			if($result['code']==="ttmblog"){
				$data['blog_enable'] =1;
			}
		}
			
		
		$this->load->model('tool/image');

		$data['categories'] = array();

		
			$categories = $this->model_catalog_category->getCategories(0);
			$data['home'] = $this->url->link('common/home');
			$data['text_blog'] = $this->language->get('text_blog');
            $data['all_blogs'] = $this->url->link('information/article/articlelist');
			
	

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					
			$childs_data = array();
			$child_2 = $this->model_catalog_category->getCategories($child['category_id']);

			foreach ($child_2 as $childs) {
				$filter_data = array(
					'filter_category_id'  => $childs['category_id'],
					'filter_sub_category' => true
				);

				$childs_data[] = array(
					'name'  => $childs['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $childs['category_id'])
				);
			}
				
			$children_data[] = array(
			
						
			'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
			'childs' => $childs_data,
			'column'   => $child['column'] ? $child['column'] : 1,
			
						
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}
				
				if ($category['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height'));
				} else {
					$data['thumb'] = '';
				}
			
				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'rsimage'  => $category['image'],
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
				
				$data['return'] = $this->url->link('account/return/add', '', true);
				$data['sitemap'] = $this->url->link('information/sitemap');
				$data['affiliate'] = $this->url->link('affiliate/login', '', true);
				$data['voucher'] = $this->url->link('account/voucher', '', true);
				$data['manufacturer'] = $this->url->link('product/manufacturer');
				$data['contact'] = $this->url->link('information/contact');
				$data['aboutus'] = $this->url->link('information/information&information_id=4');
			}
		}

		return $this->load->view('common/menu', $data);
	}
}
