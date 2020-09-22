<?php
class ControllerExtensionModuleTtmnewsletters extends Controller {

	private $error = array();

	public function index($setting) {
		$this->load->language('extension/module/ttmnewsletter');
				
		$data = array();
		
		if (isset($setting['ttmnewsletters_popup']) && $setting['ttmnewsletters_popup']) {
            $data['ttmnewsletters_popup'] = true;
        } else {
            $data['ttmnewsletters_popup'] = false;
        }
		
		$this->document->addScript('catalog/view/javascript/themetechmount_js/newsletter/ttmemail.js');			
		return $this->load->view('extension/module/ttmnewsletters', $data);
	}
	public function newsletter_mail()
	{
		$this->load->model('themetechmount/newsletters');
			$json = array();
			
		if(isset($this->request->post['news_email'])) {
            $mail = $this->request->post['news_email'];
        } else {
            $mail = '';
        }
		
		$json['alert'] = $this->model_themetechmount_newsletters->subscribes($this->request->post);
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	public function newsletter_mail_popup()
	{
		$this->load->model('themetechmount/newsletters');
			$json = array();
			
		if(isset($this->request->post['news_email'])) {
            $mail = $this->request->post['news_email'];
        } else {
            $mail = '';
        }
		
		$json['alert'] = $this->model_themetechmount_newsletters->subscribes($this->request->post);
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}

}
