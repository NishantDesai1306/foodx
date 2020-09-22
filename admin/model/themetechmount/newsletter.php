<?php
class ModelThemetechmountNewsletter extends Model {
	
	public function generateNewsletter()
	{
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `".DB_PREFIX."newsletter` (
				  `news_id` int(11) NOT NULL AUTO_INCREMENT,
				  `news_email` varchar(255) NOT NULL,
				  `subscribe` TINYINT(1) NOT NULL DEFAULT '1',
				  PRIMARY KEY (`news_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			");
	
		$this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'themetechmount/newsletter');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'themetechmount/newsletter');
	}
	
	public function getTotalMail($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter";

        if(isset($data['filter_subscribe'])) {
            $sql .= " WHERE subscribe = '" . $this->db->escape($data['filter_subscribe']) . "'";
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

        return $query->row['total'];
    }
	
	public function getNewsLetter() {
		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."newsletter"); 

		return $query->rows;
	}
	

	public function dropNewsletter() {
		$drop_newsletter = "DROP TABLE IF EXISTS `" . DB_PREFIX . "newsletter`;";
		$this->db->query($drop_newsletter);	
		$this->load->model('user/user_group');
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'themetechmount/newsletter');
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'themetechmount/newsletter');
	}
}