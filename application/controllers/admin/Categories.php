<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        /* Load Langauge */
        $this->lang->load('admin/categories');
        /* Load Model */
        $this->load->model('admin/categories_model');
    }

    public function index() {
        /* Title Page */
        $this->page_title->push(lang('menu_categories'));
        $this->data['pagetitle'] = $this->page_title->show();

        $this->data['categories'] = $this->categories_model->get_categories('categories');

        /* Load Template */
        $this->template->admin_render('admin/categories/index', $this->data);
    }

    public function create() {
        /* Data */
        $data = array(
            'name' => $this->input->post('category_name'),
            'slug' => url_title($this->input->post('category_name'), '-', true),
            'status' => $this->input->post('active_status')
        );

        $is_created = $this->categories_model->add_category($data);
        
        if ($is_created) {
            $this->session->set_flashdata('message', array('1', $this->lang->line('category_add_success')));
        } else {
            $this->session->set_flashdata('message', array('0', $this->lang->line('category_add_failed')));
        }
        
        redirect('admin/categories', 'refresh');
    }

    public function delete() {

        $id = $this->input->post('category_id');

        $category_delete = $this->categories_model->delete_category($id);

        if ($category_delete) {
            $response = array(
                'status' => 1,
                'message' => $this->lang->line('category_delete_success')
            );
        } else {
            $response = array(
                'status' => 0,
                'message' => $this->lang->line('category_delete_failed')
            );
        }

        echo json_encode($response);
    }

    public function edit($id = NULL) {
        /* Title Page */
        $this->page_title->push(lang('categories_edit'));
        $this->data['pagetitle'] = $this->page_title->show();

        $category_data = $this->general_model->getOne('categories',array('id' => $id));

        if(empty($category_data)){
            redirect('admin/categories', 'refresh');
        }
        /* Validate form input */
        $validation = array(
            array(
                'field' => 'category_name',
                'label' => 'category name.',
                'rules' => 'required',
                'errors' => array('required' => 'Please enter %s')
            ),
        );
        
        $this->form_validation->set_rules($validation);

        if ($this->form_validation->run() == TRUE) {

            $data = [
                'name' => $this->input->post('category_name'),
                'status' => $this->input->post('active_status')
            ];

            $is_update = $this->general_model->update('categories', array('id' => $id), $data);
            
            if ($is_update) {
                $this->session->set_flashdata('message', array('1', $this->lang->line('category_edit_success')));
                redirect('admin/categories', 'refresh');
            } else {
                $this->session->set_flashdata('message', array('0', $this->lang->line('category_edit_failed')));
                redirect('admin/categories', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['category_data'] = $category_data;
            /* Load Template */
            $this->template->admin_render('admin/categories/edit', $this->data);
        }
    }
    
    public function ajax_list() {
        $list = $this->categories_model->get_datatables();
        $data = array();
        foreach ($list as $key => $category) {
            $action = '';
            $action .= '<a href="categories/edit/'.$category->id.'" class="btn btn-primary waves-effect waves-light"><i class="fe-edit"></i></a> <button type="button" class="btn btn-dark waves-effect waves-light category_delete_confirmation" data-toggle="modal" data-target="#category_deleted" data-id='.$category->id.'><i class="fe-trash-2"></i></button>';

            $status = $category->status == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>';
            $row = array();
            $row[] = $category->name;
            $row[] = $status;
            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->categories_model->count_all(),
            "recordsFiltered" => $this->categories_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}
