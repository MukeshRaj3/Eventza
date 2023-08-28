<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// include APPPATH . '/third_party/PHPMailer/PHPMailerAutoload.php';

class Facilities extends Admin_Controller {

    public function __construct() {
      parent::__construct();

      /* Load :: Language */
      $this->lang->load('admin/facilities');
      /* Load :: Common */
      $this->load->model('admin/facilities_model');
      $this->load->model('general_model');
    }

    public function index() {
        /* Title Page */
        $this->page_title->push(lang('menu_facilities'));
        $this->data['pagetitle'] = $this->page_title->show();
        
        /* Load Template */
        $this->template->admin_render('admin/facilities/index', $this->data);
    }

    public function ajax_list() {
        $list = $this->facilities_model->get_datatables();
        $data = array();
        foreach ($list as $key => $facility) {
            $action = '';
            $action .= '<a href="facilities/edit/'.$facility->id.'" class="btn btn-primary waves-effect waves-light"><i class="fe-edit"></i></a> <button type="button" class="btn btn-dark waves-effect waves-light facility_delete_confirmation" data-toggle="modal" data-target="#facility_deleted" data-id='.$facility->id.'><i class="fe-trash-2"></i></button>';

            $row = array();
            $row[] = $facility->business_name;
            $row[] = $facility->facility;
            $row[] = '<image src='.base_url($facility->icon).' height="50px" width="50px">';
            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->facilities_model->count_all(),
            "recordsFiltered" => $this->facilities_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function delete()
    {
        $facility_id = $this->input->post('facility_id');
        if (!empty($facility_id)) {
            $facility_data = $this->general_model->getOne('facilities',array('id' => $facility_id));

            if(!empty($facility_data)){
              try{
                if(!empty($facility_data->icon)){
                  unlink($facility_data->icon);
                }
              }catch(Exception $e) {}

              $is_deleted = $this->general_model->delete('facilities',array('id' => $facility_id));

              if($is_deleted){
                $response = array(
                    'status' => 0,
                    'message' => $this->lang->line('facility_delete_success')
                );
              }else{
                $response = array(
                    'status' => 0,
                    'message' => $this->lang->line('facility_delete_failed')
                );
              }
            }else{
              $response = array(
                  'status' => 0,
                  'message' => 'Detail not found.'
              );
            }
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Something went wrong.'
            );
        }
        echo json_encode($response);
    }

    public function create() {
        /* Title Page */
        $this->page_title->push(lang('facilities_create'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Validate form input */
        $validation = array(
            array(
                'field' => 'business_type_id',
                'label' => 'Business Type',
                'rules' => 'required',
                'errors' => array('required' => 'Please select %s')
            ),
            array(
                'field' => 'facility_name',
                'label' => 'facility name.',
                'rules' => 'required',
                'errors' => array('required' => 'Please select %s')
            ),
        );
        
        $this->form_validation->set_rules($validation);

        if ($this->form_validation->run() == TRUE) {

            if (!empty($_FILES['facility_icon']['name'])) {
                /* Conf */
                $file_name = 'facility_icon_' . time() . rand(100, 999);
                $config['upload_path'] = './uploads/facility_icons/';
                $cofig['file_name'] = $file_name;
                $config['allowed_types'] = '*';
                $config['max_size'] = 5120;
                $config['max_width'] = 3072;
                $config['max_height'] = 3072;
                $config['file_ext_tolower'] = TRUE;
                $config['remove_spaces'] = TRUE;

                $this->load->library('upload/', $config);
                if ($this->upload->do_upload('facility_icon')) {
                    $uploadData = $this->upload->data();
                    $facility_icon = 'uploads/facility_icons/' . $uploadData['file_name'];
                } else {
                    $this->session->set_flashdata('message', array('0',$this->upload->display_errors()));
                    redirect('admin/facilities/create', 'refresh');
                }
            }else{
                $this->session->set_flashdata('message', array('0', 'Please select facility icon.'));
                redirect('admin/facilities/create', 'refresh');
            }

            $data = array(
                'business_type_id' => $this->input->post('business_type_id'),
                'facility' => $this->input->post('facility_name'),
                'icon' => $facility_icon
            );

            $create = $this->general_model->insert('facilities', $data);
            if ($create) {
                $this->session->set_flashdata('message', array('1', $this->lang->line('facility_added_success')));
                redirect('admin/facilities', 'refresh');
            } else {
                $this->session->set_flashdata('message', array('0', $this->lang->line('facility_added_failed')));
                redirect('admin/facilities', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['business_types'] = $this->general_model->getAll('business_types');
            /* Load Template */
            $this->template->admin_render('admin/facilities/create', $this->data);
        }
    }

    public function edit($id = NULL) {
        /* Title Page */
        $this->page_title->push(lang('facilities_edit'));
        $this->data['pagetitle'] = $this->page_title->show();

        $facility_data = $this->general_model->getOne('facilities',array('id' => $id));

        if(empty($facility_data)){
            redirect('admin/facilities', 'refresh');
        }
        /* Validate form input */
        $validation = array(
            array(
                'field' => 'business_type_id',
                'label' => 'Business Type',
                'rules' => 'required',
                'errors' => array('required' => 'Please select %s')
            ),
            array(
                'field' => 'facility_name',
                'label' => 'facility name.',
                'rules' => 'required',
                'errors' => array('required' => 'Please select %s')
            ),
        );
        
        $this->form_validation->set_rules($validation);

        if ($this->form_validation->run() == TRUE) {

            if (!empty($_FILES['facility_icon']['name'])) {
                /* Conf */
                $file_name = 'facility_icon_' . time() . rand(100, 999);
                $config['upload_path'] = './uploads/facility_icons/';
                $cofig['file_name'] = $file_name;
                $config['allowed_types'] = '*';
                $config['max_size'] = 5120;
                $config['max_width'] = 3072;
                $config['max_height'] = 3072;
                $config['file_ext_tolower'] = TRUE;
                $config['remove_spaces'] = TRUE;

                $this->load->library('upload/', $config);
                if ($this->upload->do_upload('facility_icon')) {
                    try{
                        if(!empty($facility_data->icon)){
                          unlink($facility_data->icon);
                        }
                    }catch(Exception $e) {}
                    $uploadData = $this->upload->data();
                    $facility_icon = 'uploads/facility_icons/' . $uploadData['file_name'];
                } else {
                    $this->session->set_flashdata('message', array('0',$this->upload->display_errors()));
                    redirect('admin/facilities/edit', 'refresh');
                }
            }else{
                $facility_icon = $facility_data->icon;
            }

            $data = array(
                'business_type_id' => $this->input->post('business_type_id'),
                'facility' => $this->input->post('facility_name'),
                'icon' => $facility_icon
            );

            $is_update = $this->general_model->update('facilities',array('id' => $id),$data);
            if ($is_update) {
                $this->session->set_flashdata('message', array('1', $this->lang->line('facility_updated_success')));
                redirect('admin/facilities', 'refresh');
            } else {
                $this->session->set_flashdata('message', array('0', $this->lang->line('facility_updated_failed')));
                redirect('admin/facilities', 'refresh');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['business_types'] = $this->general_model->getAll('business_types');
            $this->data['facility_data'] = $facility_data;
            /* Load Template */
            $this->template->admin_render('admin/facilities/edit', $this->data);
        }
    }
}