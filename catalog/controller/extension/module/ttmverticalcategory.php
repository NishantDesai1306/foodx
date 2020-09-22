<?php
class ControllerExtensionModuleTtmverticalCategory extends Controller
{
    public function index($setting)
    {
        
        $this->load->language('extension/module/ttmverticalcategory');
        
        $this->load->model('catalog/category');
        
        $this->load->model('catalog/product');
        
        $data['blogs']        = $this->url->link('information/blogger/blogs');
        $data['return']       = $this->url->link('account/return/add', '', true);
        $data['sitemap']      = $this->url->link('information/sitemap');
        $data['affiliate']    = $this->url->link('affiliate/account', '', true);
        $data['voucher']      = $this->url->link('account/voucher', '', true);
        $data['manufacturer'] = $this->url->link('product/manufacturer');
        $data['contact']      = $this->url->link('information/contact');
        
        
        $data['categories'] = array();
        $categories         = $this->model_catalog_category->getCategories(0);
        
        
        if (isset($this->request->get['path'])) {
            $category_parts = explode('_', (string) $this->request->get['path']);
        } else {
            $category_parts = array();
        }
        
        if (isset($category_parts[0])) {
            $data['category_id'] = $category_parts[0];
        } else {
            $data['category_id'] = 0;
        }
        
        if (isset($category_parts[1])) {
            $data['child_id'] = $category_parts[1];
        } else {
            $data['child_id'] = 0;
        }
        
        foreach ($categories as $category) {
            if ($category['top']) {
                // Level 2
                $children_data = array();
                
                $children = $this->model_catalog_category->getCategories($category['category_id']);
                
                foreach ($children as $child) {
                    $filter_data  = array(
                        'filter_category_id' => $child['category_id'],
                        'filter_sub_category' => true
                    );
                    $subitemchild = array();
                    $subchild     = $this->model_catalog_category->getCategories($child['category_id']);
                    
                    foreach ($subchild as $subchilds) {
                        $filter_data    = array(
                            'filter_category_id' => $subchilds['category_id'],
                            'filter_sub_category' => true
                        );
                        $subitemchild[] = array(
                            'name' => $subchilds['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                            'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $subchilds['category_id'])
                        );
                    }
                    $children_data[] = array(
                        'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                        'subchilds' => $subitemchild,
                        'column' => $child['column'] ? $child['column'] : 1,
                        'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                    );
                }
				
				if ($category['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_category_height'));
				} else {
					$data['thumb'] = '';
				}
				
                // Level 1
                $data['categories'][] = array(
                    'name' => $category['name'],
                    'children' => $children_data,
					'rsimage'  => $category['image'],
                    'column' => $category['column'] ? $category['column'] : 1,
                    'href' => $this->url->link('product/category', 'path=' . $category['category_id'])
                );
            }
        }
        
        return $this->load->view('extension/module/ttmverticalcategory', $data);
    }
}