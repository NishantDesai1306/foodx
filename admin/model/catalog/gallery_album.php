<?php
class ModelCatalogGalleryAlbum extends Model {
	public function addGalleryAlbum($data) { 

	 
		$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_album SET sort_order = '" . (int)$data['sort_order'] . "',  status = '" . (int)$data['status'] . "' ,aheight = '" . (int)$data['aheight'] . "' ,awidth = '" . (int)$data['awidth'] . "' ,img_position = '" . $data['img_position'] . "' ,gheight = '" . (int)$data['gheight'] . "' ,gwidth = '" . (int)$data['gwidth'] . "' ,popup_type = '" . (int)$data['popup_type'] . "' ,popup_resize = '" . (int)$data['popup_img_resize'] . "' ,pheight = '" . (int)$data['pheight'] . "' ,pwidth = '" . (int)$data['pwidth'] . "' ,image_perrow = '" . (int)$data['img_perrow'] . "' ,image_perpage = '" . (int)$data['img_perpage'] . "' ");


		$gallery_album_id = $this->db->getLastId();

		foreach ($data['gallery_album_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_album_description SET gallery_album_id = '" . (int)$gallery_album_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', image = '" . $this->db->escape($value['image']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		 $gallery_album_img_id = $this->db->getLastId();
		 if(!empty($data['gallery_album_images'])){
		 foreach ($data['gallery_album_images'] as $language_id => $values) {
			foreach($values as $value){
			$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_album_images SET gallery_album_img_id = '" . (int)$gallery_album_img_id . "', gallery_album_id = '" . (int)$gallery_album_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', link = '" . $this->db->escape($value['link']) . "', image = '" . $this->db->escape($value['image']) . "',  sort_order = '" . $this->db->escape($value['sort_order']) . "'");
			}
		}
		 }
		 
		if (isset($data['gallery_album_store'])) {
			foreach ($data['gallery_album_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_album_to_store SET gallery_album_id = '" . (int)$gallery_album_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// SEO URL
		if (isset($data['gallery_album_seo_url'])) {
			foreach ($data['gallery_album_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'gallery_album_id=" . (int)$gallery_album_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
		

		$this->cache->delete('gallery_album');

		return $gallery_album_id;
	}
	public function addModule($code, $data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($code) . "', `setting` = '" . $this->db->escape(json_encode($data)) . "'");

		$module_id = $this->db->getLastId();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE module_id = '" . (int)$module_id . "'");

		$settings = json_decode($query->row['setting'], true);

		$settings['module_id'] = $module_id;

		$this->db->query("UPDATE " . DB_PREFIX . "module SET setting = '" . $this->db->escape(json_encode($settings)) . "' WHERE module_id = '" . (int)$module_id . "'");

		return $module_id;
	}
	public function editGalleryAlbum($gallery_album_id, $data) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "gallery_album SET sort_order = '" . (int)$data['sort_order'] . "',  status = '" . (int)$data['status'] . "' ,aheight = '" . (int)$data['aheight'] . "' ,awidth = '" . (int)$data['awidth'] . "' ,img_position = '" . $data['img_position'] . "' 
			,gheight = '" . (int)$data['gheight'] . "' ,gwidth = '" . (int)$data['gwidth'] . "' ,popup_type = '" . (int)$data['popup_type'] . "' ,popup_resize = '" . (int)$data['popup_img_resize'] . "' ,pheight = '" . (int)$data['pheight'] . "' ,pwidth = '" . (int)$data['pwidth'] . "' ,image_perrow = '" . (int)$data['img_perrow'] . "' ,image_perpage = '" . (int)$data['img_perpage'] . "' WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_album_description WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");

		foreach ($data['gallery_album_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_album_description SET gallery_album_id = '" . (int)$gallery_album_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', image = '" . $this->db->escape($value['image']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_album_images WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");
        $gallery_album_img_id = $this->db->getLastId();
         if(!empty($data['gallery_album_images'])){
		foreach ($data['gallery_album_images'] as $language_id => $values) {
			
			foreach($values as $value){
         
			$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_album_images SET gallery_album_img_id = '" . (int)$gallery_album_img_id . "', gallery_album_id = '" . (int)$gallery_album_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', link = '" . $this->db->escape($value['link']) . "', image = '" . $this->db->escape($value['image']) . "',  sort_order = '" . $this->db->escape($value['sort_order']) . "'");
			}
		}
         }
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_album_to_store WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");

		if (isset($data['gallery_album_store'])) {
			foreach ($data['gallery_album_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_album_to_store SET gallery_album_id = '" . (int)$gallery_album_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'gallery_album_id=" . (int)$gallery_album_id . "'");

		if (isset($data['gallery_album_seo_url'])) {
			foreach ($data['gallery_album_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (trim($keyword)) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'gallery_album_id=" . (int)$gallery_album_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}


		$this->cache->delete('gallery_album');
	}

	public function deleteGalleryAlbum($gallery_album_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "gallery_album` WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "gallery_album_description` WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "gallery_album_to_store` WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "gallery_album_images` WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'gallery_album_id=" . (int)$gallery_album_id . "'");

		$this->cache->delete('gallery_album');
	}

	public function getGalleryAlbum($gallery_album_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallery_album WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");

		return $query->row;
	}
	public function getGalleryImages($gallery_album_id) {
	
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallery_album_images WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");

		return $query->row;
	}

	public function getGalleryAlbums($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gallery_album i LEFT JOIN " . DB_PREFIX . "gallery_album_description id ON (i.gallery_album_id = id.gallery_album_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'id.title',
				'i.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$gallery_album_data = $this->cache->get('gallery_album.' . (int)$this->config->get('config_language_id'));

			if (!$gallery_album_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_album i LEFT JOIN " . DB_PREFIX . "gallery_album_description id ON (i.gallery_album_id = id.gallery_album_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

				$gallery_album_data = $query->rows;

				$this->cache->set('gallery_album.' . (int)$this->config->get('config_language_id'), $gallery_album_data);
			}

			return $gallery_album_data;
		}
	}

	public function getGalleryAlbumDescriptions($gallery_album_id) {
		$gallery_album_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_album_description WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");

		return $query->rows;
	}


	public function getGalleryAlbumImage($gallery_album_id) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_album_images WHERE gallery_album_id = '" . (int)$gallery_album_id . "'  ORDER BY sort_order ASC");
        
		return $query->rows;
	}

	

	public function getGalleryAlbumStores($gallery_album_id) {
		$gallery_album_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_album_to_store WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");

		foreach ($query->rows as $result) {
			$gallery_album_store_data[] = $result['store_id'];
		}

		return $gallery_album_store_data;
	}

	public function getGalleryAlbumSeoUrls($gallery_album_id) {
		$gallery_album_seo_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'gallery_album_id=" . (int)$gallery_album_id . "'");

		foreach ($query->rows as $result) {
			$gallery_album_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $gallery_album_seo_url_data;
	}

	public function getTotalGalleryAlbums() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallery_album");

		return $query->row['total'];
	}

	public function getTotalGalleryAlbumsImages() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallery_album_images");

		return $query->row['total'];
	}
	
	public function getModule($module_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");

		return $query->row;
	}
	
}