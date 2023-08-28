<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();

        /* COMMON :: ADMIN & PUBLIC */
        /* Load */
        $this->load->database();
        $this->load->config('common/dp_config');
        $this->load->config('common/dp_language');
        $this->load->library(array('form_validation', 'ion_auth', 'template'));
        $this->load->helper(array('array', 'language', 'url'));

        /* Data */
        $this->data['lang'] = element($this->config->item('language'), $this->config->item('language_abbr'));
        $this->data['charset'] = $this->config->item('charset');
        $this->data['frameworks_dir'] = $this->config->item('frameworks_dir');
        $this->data['plugins_dir'] = $this->config->item('plugins_dir');
        $this->data['avatar_dir'] = $this->config->item('avatar_dir');
    }

}

class Admin_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            /* Load */
            $this->load->config('admin/dp_config');
            $this->load->library('admin/page_title');
            $this->load->library('admin/breadcrumbs');
            $this->load->helper('menu');
            $this->lang->load(array('admin/main_header', 'admin/main_sidebar', 'admin/actions'));

            /* Load library function  */
            $this->breadcrumbs->unshift(0, $this->lang->line('menu_dashboard'), 'admin/dashboard');

            /* Data */
            $this->data['title'] = $this->config->item('title');
            $this->data['title_lg'] = $this->config->item('title_lg');
            $this->data['title_mini'] = $this->config->item('title_mini');
        }
    }
}

class Public_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('user_agent');
        $this->load->library('admin/page_title');
        $this->load->config('public/dp_config');

    }
}


