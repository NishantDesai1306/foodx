<?php
class ControllerCatalogGalleryAlbum extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/gallery_album');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery_album');	
		$this->load->model('setting/setting');	

		$this->getList();
	}

	public function add() {
		
		$this->load->language('catalog/gallery_album');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery_album');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		
			$this->model_catalog_gallery_album->addGalleryAlbum($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/gallery_album', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
        
		$this->getForm();
	}


	public function edit() {
		
		$this->load->language('catalog/gallery_album');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery_album');
		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_catalog_gallery_album->editGalleryAlbum($this->request->get['gallery_album_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/gallery_album', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/gallery_album');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery_album');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $gallery_album_id) {
				$this->model_catalog_gallery_album->deleteGalleryAlbum($gallery_album_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/gallery_album', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id.title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/gallery_album', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/gallery_album/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/gallery_album/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['gallery_album'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		 $gallery_album_total = $this->model_catalog_gallery_album->getTotalGalleryAlbums();

		

		$results = $this->model_catalog_gallery_album->getGalleryAlbums($filter_data);

		foreach ($results as $result) {
			$data['gallery_albums'][] = array(
				'gallery_album_id' => $result['gallery_album_id'],
				'title'          => $result['title'],
				'sort_order'     => $result['sort_order'],
				'edit'           => $this->url->link('catalog/gallery_album/edit', 'user_token=' . $this->session->data['user_token'] . '&gallery_album_id=' . $result['gallery_album_id'] . $url, true)
			);
			 
		}


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('catalog/gallery_album', 'user_token=' . $this->session->data['user_token'] . '&sort=id.title' . $url, true);
		
		$data['sort_sort_order'] = $this->url->link('catalog/gallery_album', 'user_token=' . $this->session->data['user_token'] . '&sort=i.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $gallery_album_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/gallery_album', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($gallery_album_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($gallery_album_total - $this->config->get('config_limit_admin'))) ? $gallery_album_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $gallery_album_total, ceil($gallery_album_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/gallery_album_list', $data));
	}

	protected function getForm() {
		
		$data['text_form'] = !isset($this->request->get['gallery_album_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		
		$this->load->model('tool/image');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}
		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);


		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/gallery_album', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);


		if (!isset($this->request->get['gallery_album_id'])) {
			$data['action'] = $this->url->link('catalog/gallery_album/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/gallery_album/edit', 'user_token=' . $this->session->data['user_token'] . '&gallery_album_id=' . $this->request->get['gallery_album_id'] . $url, true);
		}


		$data['cancel'] = $this->url->link('catalog/gallery_album', 'user_token=' . $this->session->data['user_token'] . $url, true);



		if (isset($this->request->get['gallery_album_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$gallery_album_info = $this->model_catalog_gallery_album->getGalleryAlbum($this->request->get['gallery_album_id']);
		}	


        
		$data['user_token'] = $this->session->data['user_token'];		

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		if(isset($this->request->get['gallery_album_id'])){
			$results=$this->model_catalog_gallery_album->getGalleryAlbumDescriptions($this->request->get['gallery_album_id']);
		}
        


		if(!empty ($results)){
		foreach($results as $result){

			$data['gallery_album_description'][$result['language_id']]=array(
				'title'            => $result['title'],
				'image'     	   => $result['image'],
				'thumb'     	   => $this->model_tool_image->resize($result['image'],100,100),
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}
	}

		if(isset($this->request->get['gallery_album_id'])){
            $galleryimages = $this->model_catalog_gallery_album->getGalleryAlbumImage($this->request->get['gallery_album_id']);
             //  echo '<pre>';print_r($galleryimages);die; 
			   foreach ($galleryimages as $galleryimage) {
			   $data['gallery_album_images'][$galleryimage['language_id']][] = array(
			    	'gallery_album_id' => $galleryimage['gallery_album_id'],
			    	'title'          => $galleryimage['title'],
			    	'image'     => $galleryimage['image'],
			    	'thumb'     => $this->model_tool_image->resize($galleryimage['image'],100,100),
			    	'sort_order'     => $galleryimage['sort_order'],
			    	'link'     => $galleryimage['link']
			);
		}	
	} 
		else {

			$data['gallery_album_images']=array();
		 }
           
		    $this->load->model('setting/store');

		    $data['stores'] = array();
		
		    $data['stores'][] = array(
			     'store_id' => 0,
			     'name'     => $this->language->get('text_default')
		);
		
		$stores = $this->model_setting_store->getStores();

		      foreach ($stores as $store) {
		        	$data['stores'][] = array(
			     	'store_id' => $store['store_id'],
			    	'name'     => $store['name']
			    );
	    	}


		if (isset($this->request->post['gallery_album_store'])) {
			$data['gallery_album_store'] = $this->request->post['gallery_album_store'];
		} elseif (isset($this->request->get['gallery_album_id'])) {
			$data['gallery_album_store'] = $this->model_catalog_gallery_album->getGalleryAlbumStores($this->request->get['gallery_album_id']);
		} else {
			$data['gallery_album_store'] = array(0);
		}

      $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		


		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($gallery_album_info)) {
			$data['status'] = $gallery_album_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['aheight'])) {
			$data['aheight'] = $this->request->post['aheight'];
		} elseif (!empty($gallery_album_info)) {
			$data['aheight'] = $gallery_album_info['aheight'];
		} else {
			$data['aheight'] = 300;
		}

		if (isset($this->request->post['awidth'])) {
			$data['awidth'] = $this->request->post['awidth'];
		} elseif (!empty($gallery_album_info)) {
			$data['awidth'] = $gallery_album_info['awidth'];
		} else {
			$data['awidth'] = 300;
		}

		if (isset($this->request->post['img_position'])) {
			$data['img_position'] = $this->request->post['img_position'];
		} elseif (!empty($gallery_album_info)) {
			$data['img_position'] = $gallery_album_info['img_position'];
		} else {
			$data['img_position'] = '';
		}

		if (isset($this->request->post['gheight'])) {
			$data['gheight'] = $this->request->post['gheight'];
		} elseif (!empty($gallery_album_info)) {
			$data['gheight'] = $gallery_album_info['gheight'];
		} else {
			$data['gheight'] = 300;
		}

		if (isset($this->request->post['gwidth'])) {
			$data['gwidth'] = $this->request->post['gwidth'];
		} elseif (!empty($gallery_album_info)) {
			$data['gwidth'] = $gallery_album_info['gwidth'];
		} else {
			$data['gwidth'] = 300;
		}

		if (isset($this->request->post['popup_type'])) {
			$data['popup_type'] = $this->request->post['popup_type'];
		} elseif (!empty($gallery_album_info)) {
			$data['popup_type'] = $gallery_album_info['popup_type'];
		} else {
			$data['popup_type'] = '';
		}

		if (isset($this->request->post['popup_img_resize'])) {
			$data['popup_img_resize'] = $this->request->post['popup_img_resize'];
		} elseif (!empty($gallery_album_info)) {
			$data['popup_img_resize'] = $gallery_album_info['popup_resize'];
		} else {
			$data['popup_img_resize'] = '';
		}

		if (isset($this->request->post['pheight'])) {
			$data['pheight'] = $this->request->post['pheight'];
		} elseif (!empty($gallery_album_info)) {
			$data['pheight'] = $gallery_album_info['pheight'];
		} else {
			$data['pheight'] = 300;
		}

		if (isset($this->request->post['pwidth'])) {
			$data['pwidth'] = $this->request->post['pwidth'];
		} elseif (!empty($gallery_album_info)) {
			$data['pwidth'] = $gallery_album_info['pwidth'];
		} else {
			$data['pwidth'] = 300;
		}

		if (isset($this->request->post['img_perrow'])) {
			$data['img_perrow'] = $this->request->post['img_perrow'];
		} elseif (!empty($gallery_album_info)) {
			$data['img_perrow'] = $gallery_album_info['image_perrow'];
		} else {
			$data['img_perrow'] = '';
		}

		if (isset($this->request->post['img_perpage'])) {
			$data['img_perpage'] = $this->request->post['img_perpage'];
		} elseif (!empty($gallery_album_info)) {
			$data['img_perpage'] = $gallery_album_info['image_perpage'];
		} else {
			$data['img_perpage'] = '';
		}

        

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($gallery_album_info)) {
			$data['sort_order'] = $gallery_album_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		
		if (isset($this->request->post['gallery_album_seo_url'])) {
			$data['gallery_album_seo_url'] = $this->request->post['gallery_album_seo_url'];
		} elseif (isset($this->request->get['gallery_album_id'])) {
			$data['gallery_album_seo_url'] = $this->model_catalog_gallery_album->getGalleryAlbumSeoUrls($this->request->get['gallery_album_id']);
		} else {
			$data['gallery_album_seo_url'] = array();
		}
		

		if (isset($this->request->get['gallery_album_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$gallery_album_img_info = $this->model_catalog_gallery_album->getGalleryAlbumImage($this->request->get['gallery_album_id']);
		}
            

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
			
		$this->response->setOutput($this->load->view('catalog/gallery_album_form', $data));

	}


	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/gallery_album')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['gallery_album_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 1) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if (utf8_strlen($value['description']) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}

			if ((utf8_strlen($value['meta_title']) < 1) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if ($this->request->post['gallery_album_seo_url']) {
			$this->load->model('design/seo_url');
			
			foreach ($this->request->post['gallery_album_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						if (count(array_keys($language, $keyword)) > 1) {
							$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
						}						
						
						$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);
						
						foreach ($seo_urls as $seo_url) {
							if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['gallery_album_id']) || ($seo_url['query'] != 'gallery_album_id=' . $this->request->get['gallery_album_id']))) {
								$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');
							}
						}
					}
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/gallery_album')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');

		foreach ($this->request->post['selected'] as $gallery_album_id) {
			if ($this->config->get('config_account_id') == $gallery_album_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}

			if ($this->config->get('config_checkout_id') == $gallery_album_id) {
				$this->error['warning'] = $this->language->get('error_checkout');
			}

			if ($this->config->get('config_affiliate_id') == $gallery_album_id) {
				$this->error['warning'] = $this->language->get('error_affiliate');
			}

			if ($this->config->get('config_return_id') == $gallery_album_id) {
				$this->error['warning'] = $this->language->get('error_return');
			}

			$store_total = $this->model_setting_store->getTotalStoresByGalleryAlbumId($gallery_album_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}
		}

		return !$this->error;
	}
}