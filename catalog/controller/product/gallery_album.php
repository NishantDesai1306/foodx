<?php
class ControllerProductGalleryAlbum extends Controller {

	private $error = array();

	public function index() {

		$this->load->language('product/gallery_album');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery_album');	
		$this->load->model('setting/setting');	
	
	   $this->document->addScript('catalog/view/javascript/themetechmount_js/newsletter/ttmemail.js');
       
       $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('product/gallery_album')
		);

        $this->load->model('tool/image');
		$data['gallery_album'] = array();

		$gallery_album_total = $this->model_catalog_gallery_album->getTotalGalleryAlbums();



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
			 
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('product/gallery_album', $data));
	}


	public function info()
	{
		$this->load->language('product/gallery_album');
        $this->document->addScript('catalog/view/javascript/themetechmount_js/newsletter/ttmemail.js');
		$this->document->setTitle($this->language->get('title'));
        $this->load->model('catalog/gallery_album');	
		$this->load->model('setting/setting');	
        $this->load->model('tool/image');
       
		  $data['breadcrumbs'] = array();


       $data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('product/gallery_album')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('title'),
			'href' => $this->url->link('product/gallery_album/info','gallery_album_id='.$this->request->get['gallery_album_id'], true)
		);

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		 $re = $this->model_catalog_gallery_album->getGalleryImgLimit($this->request->get['gallery_album_id']);
		
		foreach ($re as $res) {
          $limits=$res['image_perpage'];
		}
		
        $filter_data = array(
				'start'              => ($page - 1) * $limits,
				'limit'              => $limits,
				'gallery_album_id'   =>  $this->request->get['gallery_album_id']
			);

	
		$album_image=$this->model_catalog_gallery_album->getGalleryAlbumDescriptions($this->request->get['gallery_album_id']);

			$data['album_images'][] = array(
				'album_image' => $this->model_tool_image->resize(($album_image['image'])?($album_image['image']):'placeholder.png',$album_image['awidth'],$album_image['aheight']),
				'album_description' => $album_image['description'],
				'title'          => $album_image['title'],
				'img_position' => $album_image['img_position']
			);
        
		$total_image=$this->model_catalog_gallery_album->getTotalGalleryAlbumsImage($this->request->get['gallery_album_id']);
		
        $results = $this->model_catalog_gallery_album->getGalleryAllImages($filter_data);
       
		foreach ($results as $result) {
			
			$data['gallery_images'][] = array(
				'gallery_album_id' => $result['gallery_album_id'],
				'title'          => $result['title'],
				'gheight'        => $result['gheight'],
				'gwidth'        => $result['gwidth'],
				'image'     => $this->model_tool_image->resize(($result['image'])?($result['image']):'placeholder.png',$result['gwidth'],$result['gheight']),
				'pop_image'     =>$this->model_tool_image->resize(($result['image'])?($result['image']):'placeholder.png',$result['pwidth'],$result['pheight']),
				'img_position'        => $result['img_position'],
				'popup_type'        => $result['popup_type'],
				'popup_resize'        => $result['popup_resize'],
				'pheight'        => $result['pheight'],
				'pwidth'        => $result['pwidth'],
				'image_perrow'        => $result['image_perrow'],
                'image_perpage'        => $result['image_perpage'],
	
			);
			 
		}

		$pagination = new Pagination();
		$pagination->total = $total_image;
		$pagination->page = $page;
		$pagination->limit = $limits;
		$pagination->url = $this->url->link('product/gallery_album/info','gallery_album_id='.$this->request->get['gallery_album_id'].'&page={page}', true);

		$data['pagination'] = $pagination->render();
		
        if($limits!=0){
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_image) ? (($page - 1) * $limits) + 1 : 0, ((($page - 1) * $limits) > ($total_image - $limits)) ? $total_image : ((($page - 1) * $limits) + $limits), $total_image, ceil($total_image / $limits));
     
}
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('product/gallery', $data));

	}

}