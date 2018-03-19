<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
    public $currentMenus;
    public $socials;
    public function __construct()
    {
        parent::__construct();
        // $isLoggedIn = $this->session->userdata('user-login');
        
        // if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        // {
        //     redirect('/');
        // }
        $this->load->model('menu_model');
        $this->currentMenus = $this->menu_model->getCurrentMenus();

        $this->load->model('social_model');
        $this->socials = $this->social_model->getSocials();

        $this->load->model('coinlist_model');
        $this->load->model('actionlist_model');
    }

    public function index() {
        $data['title'] = "FCC | Home";
        $data['menus'] = $this->currentMenus;
        $data['socials'] = $this->socials;

        $data['coin_list'] = $this->coinlist_model->getAllCoinList();
        $data['seehowtobuy_label'] = $this->menu_model->getSeeHowToBuy()['menu_label'];
        $data['seehowtobuy_target'] = $this->menu_model->getSeeHowToBuy()['menu_target'];

        $this->load->model('background_model');
        $this->load->view('home/header', $data);
        $this->load->view('home/homepage', $data);
        $this->load->view('home/footer', $data);

        // var_dump($this->menu_model->getSeeHowToBuy());
    }

    public function myaccount() {
         $isLoggedIn = $this->session->userdata('user-login');
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            redirect('/user');
        }
        $data['title'] = 'FCC | MY Account';
        $data['menus'] = $this->currentMenus;
        $data['socials'] = $this->socials;

        $this->load->view('home/header', $data);
        $this->load->view('home/myaccount', $data);
        $this->load->view('home/footer', $data);
    }

    public function seeactions() {
         $isLoggedIn = $this->session->userdata('user-login');
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            redirect('/user');
        }
        $data['title'] = 'FCC | See Actions';
        $data['menus'] = $this->currentMenus;
        $data['socials'] = $this->socials;
        $data['action_list'] = $this->actionlist_model->getAllActionList();

        $this->load->view('home/header', $data);
        $this->load->view('home/seeactions', $data);
        $this->load->view('home/footer', $data);

        // $this->load->model('email_model');
        // $this->email_model->sendEmail();
    }

    public function logout(){
        $isLoggedIn = $this->session->userdata('user-login');
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            redirect('/user');
        }
        $this->session->unset_userdata('user-login');
        redirect('/');
    }

    public function b_action_add() {
        $action_id = $this->input->post('action_id');
        $action = $this->actionlist_model->getActionById($action_id);
        $action_counter = $action[0]->action_counter;
        $data['action_counter'] = 1 + $action_counter;
        $this->actionlist_model->updateActionById($action_id, $data);
        $return_data['action_counter'] = $data['action_counter'];
        echo json_encode($return_data);  
    }

    public function b_action_down() {
        $action_id = $this->input->post('action_id');
        $action = $this->actionlist_model->getActionById($action_id);
        $action_counter = $action[0]->action_counter;
        $data['action_counter'] = -1 + $action_counter;
        $this->actionlist_model->updateActionById($action_id, $data);
        $return_data['action_counter'] = $data['action_counter'];
        echo json_encode($return_data);  
    }

}
