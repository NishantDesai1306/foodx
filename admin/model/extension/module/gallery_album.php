<?php

class ModelExtensionModuleGalleryAlbum extends Model {

	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallery_album` (
			 `gallery_album_id` int(11) NOT NULL AUTO_INCREMENT,
			  `sort_order` int(3) NOT NULL DEFAULT '0',
			  `status` tinyint(1) NOT NULL DEFAULT '1',
			   `aheight` varchar(100) NOT NULL,
			   `awidth` varchar(100) NOT NULL,
			   `img_position` varchar(100) NOT NULL,
			   `gheight` varchar(100) NOT NULL,
			   `gwidth` varchar(100) NOT NULL,
			   `popup_type` varchar(100) NOT NULL,
			   `popup_resize` varchar(100) NOT NULL,
			   `pheight` varchar(100) NOT NULL,
			   `pwidth` varchar(100) NOT NULL,
			   `image_perrow` varchar(100) NOT NULL,
			   `image_perpage` varchar(100) NOT NULL,
			  PRIMARY KEY (`gallery_album_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
    
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallery_album_description` (
			  `gallery_album_id` int(11) NOT NULL,
			  `language_id` int(11) NOT NULL,
			  `title` varchar(64) NOT NULL,
			  `image` VARCHAR(255),			  
			  `description` mediumtext NOT NULL,
			  `meta_title` varchar(255) NOT NULL,
			  `meta_description` varchar(255) NOT NULL,
			  `meta_keyword` varchar(255) NOT NULL,
			  PRIMARY KEY (`gallery_album_id`,`language_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
			  ");

        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallery_album_images` (
			  `gallery_album_img_id` int(11) NOT NULL AUTO_INCREMENT,
			  `gallery_album_id` int(11) NOT NULL,
			  `language_id` int(11) NOT NULL,	
			  `title` VARCHAR(64),		
			  `link` VARCHAR(255),
			  `image` VARCHAR(255),		
			  `sort_order` int(3) NOT NULL DEFAULT '0',	  
			  PRIMARY KEY (`gallery_album_img_id`,`language_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
			  ");

        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallery_album_to_store` (
			  `gallery_album_id` int(11) NOT NULL,
			  `store_id` int(11) NOT NULL,
			  PRIMARY KEY (`gallery_album_id`,`store_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
			  ");

		
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "gallery_album;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "gallery_album_description;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "gallery_album_images;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "gallery_album_to_store;");
	}
}
