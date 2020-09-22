<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$this->load->model('catalog/information');
	
	   $this->document->addScript('catalog/view/javascript/themetechmount_js/newsletter/ttmemail.js');
	   
		/* $data['gallery'] = $this->load->controller('common/gallery_album');   */

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}
		
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		$data['home'] = $this->url->link('common/home');
		
		$data['contact'] = $this->url->link('information/contact');
		$data['module_status']=$this->config->get('module_gallery_album_status');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));
		
		if (is_file(DIR_IMAGE . $this->config->get('config_footerlogo'))) {
			$data['footerlogo'] = $server . 'image/' . $this->config->get('config_footerlogo');
		} else {
			$data['footerlogo'] = '';
		}
		
		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		
					
		$this->load->model('catalog/gallery_album');	
		$this->load->model('setting/setting');	
		
        $data['gallery_album'] = array();
		$data['gallery_album'] = $this->url->link('product/gallery_album', '', true);
		/* $gallery_album_total = $this->model_catalog_gallery_album->getTotalGalleryAlbums();

		$results = $this->model_catalog_gallery_album->getGalleryAlbums();
      
		foreach ($results as $result) {
			
			$data['gallery_albums'][] = array(
				'gallery_album_id' => $result['gallery_album_id'],
				'title'          => $result['title'],
				'description'     => $result['description'],
				'aheight'     => $result['aheight'],
				'awidth'     => $result['awidth'],
				'image'     => $this->model_tool_image->resize(($result['image'])?($result['image']):'placeholder.png',$result['awidth'],$result['aheight']),
				'url'       =>$this->url->link('product/gallery_album/info','gallery_album_id='.$result['gallery_album_id'], true)
			);
			 
		}  */
$data['scripts'] = $this->document->getScripts('footer');
		return $this->load->view('common/footer', $data);
		
	}
}
