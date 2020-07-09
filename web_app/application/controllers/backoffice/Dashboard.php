<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    public $module_name = 'Dashboard';
    public $controller_name = 'dashboard';    
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
		$this->load->model(ADMIN_URL.'/dashboard_model');
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
    }
    public function index() {
        $arr['meta_title'] = $this->lang->line('title_admin_dashboard').' | '.$this->lang->line('site_title');   
        $arr['restaurantCount'] = $this->dashboard_model->getRestaurantCount(); 
        $arr['user'] = $this->dashboard_model->gettotalAccount(); 
        $arr['totalOrder'] = $this->dashboard_model->getOrderCount();
        $arr['restaurant'] = $this->dashboard_model->restaurant();
        $arr['orders'] = $this->dashboard_model->getLastOrders();
        $this->load->view(ADMIN_URL.'/dashboard',$arr);
    }
}