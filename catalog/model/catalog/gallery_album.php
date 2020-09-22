<?php
class ModelCatalogGalleryAlbum extends Model {
     
	public function getGalleryAlbum() {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallery_album_description");

		return $query->rows;
	}
	public function getGalleryImages($gallery_album_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallery_album_images WHERE gallery_album_id = '" . (int)$gallery_album_id . "'");

		return $query->row;
	}

	public function getGalleryAllImages($data) {
       $sql = "SELECT * FROM " . DB_PREFIX . "gallery_album_images gai  LEFT JOIN " . DB_PREFIX . "gallery_album ga ON (gai.gallery_album_id = ga.gallery_album_id)  WHERE gai.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ga.gallery_album_id='".$data['gallery_album_id']."' LIMIT ". $data['start'] .' , '. $data['limit'];
			$query = $this->db->query($sql);
			return $query->rows;
	}

	public function getGalleryAllImg($gallery_album_id) {
       
       $sql = "SELECT * FROM " . DB_PREFIX . "gallery_album_images gai  LEFT JOIN " . DB_PREFIX . "gallery_album ga ON (gai.gallery_album_id = ga.gallery_album_id)  WHERE gai.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ga.gallery_album_id='".(int)$gallery_album_id."'";	
       echo $sql;die;
			$query = $this->db->query($sql);
			return $query->rows;
	}
	public function getGalleryImgLimit($gallery_album_id) {
		 $sql = "SELECT image_perpage FROM " . DB_PREFIX . "gallery_album WHERE gallery_album_id='".(int)$gallery_album_id."'";	
     
			$query = $this->db->query($sql);
			return $query->rows;
	}


	public function getGalleryAlbums($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "gallery_album i LEFT JOIN " . DB_PREFIX . "gallery_album_description id ON (i.gallery_album_id = id.gallery_album_id) WHERE i.status='1' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_album i LEFT JOIN " . DB_PREFIX . "gallery_album_description id ON (i.gallery_album_id = id.gallery_album_id) WHERE i.status='1' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

				$gallery_album_data = $query->rows;

				$this->cache->set('gallery_album.' . (int)$this->config->get('config_language_id'), $gallery_album_data);
			}

			return $gallery_album_data;
		}
	}

	public function getGalleryAlbumDescriptions($gallery_album_id) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_album_description gad JOIN " . DB_PREFIX . "gallery_album ga ON(`ga`.`gallery_album_id`=`gad`.`gallery_album_id`) WHERE `ga`.`gallery_album_id` = '" . (int)$gallery_album_id . "' AND `gad`.`language_id` = '" . (int)$this->config->get('config_language_id')."'");

		return $query->row;
	}


	public function getGalleryAlbumImage($gallery_album_id) {
				
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_album_images WHERE gallery_album_id = '" . (int)$gallery_album_id . "' ORDER BY sort_order ASC");

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

	public function getTotalGalleryAlbumsImage($gallery_album_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallery_album_images WHERE gallery_album_id='".(int)$gallery_album_id."' ");

		return $query->row['total'];
	}


}
