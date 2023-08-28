<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

  public function __construct() {
      parent::__construct();

      /* Load :: Common */
      $this->load->model('admin/users_model');
      $this->load->model('admin/party_model');
  }

    public function index() {
    /* Title Page */
    $this->page_title->push(lang('menu_dashboard'));
    $this->data['pagetitle'] = $this->page_title->show();
    $this->data['total_users'] = $this->users_model->count_all();
    $this->data['total_active_users'] = $this->users_model->count_active_all();
    $this->data['total_inactive_users'] = $this->users_model->count_inactive_all();
    $this->data['total_verified_users'] = $this->users_model->count_verified_all();
    $this->data['total_unverified_users'] = $this->users_model->count_unverified_all();
    
    $this->data['total_party'] = $this->party_model->count_all();
    $this->data['today_party'] = $this->party_model->count_today_party();
    $this->data['active_party'] = $this->party_model->count_active_all();
    $this->data['inactive_party'] = $this->party_model->count_inactive_all();
    

    $this->data['total_organisations'] = $this->party_model->count_organization_all();
    $this->data['verified_organisations'] = $this->party_model->count_organization_verified_all();

    $this->data['unverified_organisations'] = $this->party_model->count_organization_unverified_all();

    /* Load Template */
    $this->template->admin_render('admin/dashboard/index', $this->data);
  }

  public function index_old() {
    /* Title Page */
    $this->page_title->push(lang('menu_dashboard'));
    $this->data['pagetitle'] = $this->page_title->show();
    $this->data['total_users'] = $this->users_model->count_all();
    $this->data['total_active_users'] = $this->users_model->count_active_all();
    $this->data['total_party'] = $this->party_model->count_all();
    $this->data['today_party'] = $this->party_model->count_today_party();
    /* Load Template */
    $this->template->admin_render('admin/dashboard/index', $this->data);
  }
}