<?php
class ModelThemetechmountNewsletters extends Model {
	
	public function subscribes($data) {
		$res = $this->db->query("select * from ". DB_PREFIX ."newsletter where news_email='".$data['email']."'");
		if($res->num_rows == 1)
		{
			return "Email Already Exist";
		}
		else
		{
			if($this->db->query("INSERT INTO " . DB_PREFIX . "newsletter(news_email) values ('".$data['email']."')"))
			{
				$this->load->language('extension/module/ttmnewsletters');
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setTo($data['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(sprintf($this->language->get('email_subject')));
				$mail->setText(sprintf($this->language->get('email_content')));
				$mail->send();
				
				return "Subscription Successfull";
			}
			else
			{
				
				return "Subscription Fail";
			}
		}
	}
	
		
}