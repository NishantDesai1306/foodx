<?php
class ControllerCommonGalleryAlbum extends Controller {

	public function index() {

		$this->load->language('common/gallery_album');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery_album');	
		$this->load->model('setting/setting');	
	
	   $this->document->addScript('catalog/view/javascript/themetechmount_js/newsletter/ttmemail.js');
       

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
				'image'     => $this->model_tool_image->resize(($result['image'])?($result['image']):'placeholder.png',$result['awidth'],$result['aheight'])
				

				
			);
			 
		}
	

		return $this->load->view('common/gallery_album', $data);

	}


}