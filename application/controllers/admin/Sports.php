<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sports extends Admin_Controller {

	public function __construct() {
		parent::__construct();

		/* Load Langauge */
        $this->lang->load('admin/sports');
        /* Load Model */
		$this->load->model('admin/sports_model');
	}

	public function index() {
		/* Title Page */
        $this->page_title->push(lang('menu_sports'));
        $this->data['pagetitle'] = $this->page_title->show();

		/* Load Template */
		$this->template->admin_render('admin/sports/index', $this->data);
	}

	public function ajax_list() {
		$list = $this->sports_model->get_datatables();
        $data = array();
        foreach ($list as $key => $sport_event) {
	        $action = '';
            $action .= '<a href="sports/edit/'.$sport_event->id.'" class="btn btn-primary waves-effect waves-light"><i class="fe-edit"></i></a> <button type="button" class="btn btn-dark waves-effect waves-light sport_event_delete_confirmation" data-toggle="modal" data-target="#sport_event_deleted" data-id='.$sport_event->id.'><i class="fe-trash-2"></i></button>';
            $text_content = '';	
            /*$text_content = '<div class="more_less_class">
                    <div class="Readless">'. nl2br(substr($sport_event->text_content, 0, 50)) .'';
                    if (strlen($sport_event->text_content) > 50) { 
                        $text_content .= '<br><a href="javascript:void(0)" class="readmore_button" style="color: #4AA8E6">Read More</a>';
                    }
            $text_content .= '</div>';
                    if (strlen($sport_event->text_content) > 50) { 
            $text_content .= '<div class="Readmore" style="display: none;">'. nl2br($sport_event->text_content) .'<br>
                        <a href="javascript:void(0)" class="readless_button" style="color: #4AA8E6">Read Less</a>
                    </div>';
                    }
            $text_content .= '</div>';*/

            $row = array();
            $row[] = $sport_event->title;
            $row[] = '<image src='.base_url($sport_event->sport_event_image).' height="50px" width="90px">';
            $row[] = $text_content;
            $row[] = $action;
            $data[] = $row;
        }

        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->sports_model->count_all(),
            "recordsFiltered" => $this->sports_model->count_filtered(),
            "data" => $data,
		];
        //output to json format
        echo json_encode($output);
	}

	public function create() {
		/* Title Page */
        $this->page_title->push(lang('menu_create_sport'));
        $this->data['pagetitle'] = $this->page_title->show();

		 // validate form input
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
		$this->form_validation->set_rules('text_content', 'text_content', 'trim|required');

		if (!empty($_FILES['sport_event_image']['name'])) {
			/* Conf Image */
			$file_name = 'sports_' . time() . rand(100, 999);
			$configImg['upload_path'] = './uploads/sports_event/';
			$configImg['file_name'] = $file_name;
			$configImg['allowed_types'] = 'png|jpg|jpeg';
			$configImg['max_size'] = 10240;
			$configImg['max_width'] = 9000;
			$configImg['max_height'] = 9000;
			$configImg['file_ext_tolower'] = TRUE;
			$configImg['remove_spaces'] = TRUE;

			$this->load->library('upload', $configImg, 'sport_event_image');
			if ($this->sport_event_image->do_upload('sport_event_image')) {
				$uploadData = $this->sport_event_image->data();
				$sport_event_image = 'uploads/sports_event/' . $uploadData['file_name'];

				$image_width = $uploadData['image_width'];
                $image_height = $uploadData['image_height'];

                $img_config = array(
                    'source_image'      => $sport_event_image,
                    'new_image'         => './uploads/sports_event/',
                    'maintain_ratio'    => false,
                    'width'             => $this->input->post('dataWidth_1'),
                    'height'            => $this->input->post('dataHeight_1'),
                    'x_axis'            => $this->input->post('dataX_1'),
                    'y_axis'            => $this->input->post('dataY_1')
                );

                $this->load->library('image_lib',$img_config);
                
                if(($this->input->post('dataWidth_1') != 0) && ($this->input->post('dataHeight_1') != 0))
                {
                    $this->image_lib->crop();
                    $this->image_lib->clear();
                }
			} else {
				$this->custom_errors['sport_event_image'] = $this->sport_event_image->display_errors('', '');
			}
		}

		if ($this->form_validation->run() === TRUE) {
			$data = [
				'title' => $this->input->post('title'),
				'start_date' => date('Y-m-d', strtotime($this->input->post('start_date'))),
				'end_date' => date('Y-m-d', strtotime($this->input->post('end_date'))),
				'text_content' => $this->input->post('text_content'),
				'sport_event_image' => $sport_event_image,
				'created_on' => time()
			];

			$create = $this->sports_model->create_sports_event($data);
			if ($create) {
				$this->session->set_flashdata('message', ['1', $this->lang->line('sport_create_success')]);
				redirect('admin/sports');
			} else {
				$this->session->set_flashdata('message', ['0', $this->lang->line('sport_create_failed')]);
				redirect('admin/sports');
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			/* Load Template */
			$this->template->admin_render('admin/sports/create', $this->data);
		}
	}

	public function edit($id = NULL) {
        if (is_null($id)) {
        	$this->session->set_flashdata('message', ['1', 'Sport event not found']);
        	redirect('auth/sports', 'refresh');	
        }
        
		$sport_data = $this->sports_model->fetch_sport_event($id);
		if (is_null($sport_data)) {
			$this->session->set_flashdata('message', ['1', 'Sport event not found']);
        	redirect('auth/sports', 'refresh');	
		}

		/* Title Page */
        $this->page_title->push(lang('menu_edit_sport'));
        $this->data['pagetitle'] = $this->page_title->show();

		 // validate form input
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
		$this->form_validation->set_rules('text_content', 'Text Cntent', 'trim|required');

		$sport_event_image = $sport_data->sport_event_image;
    	if (!empty($_FILES['sport_event_image']['name'])) {
    		/* Conf Image */
    		$file_name = 'sports_' . time() . rand(100, 999);
    		$configImg['upload_path'] = './uploads/sports_event/';
    		$configImg['file_name'] = $file_name;
    		$configImg['allowed_types'] = 'png|jpg|jpeg';
    		$configImg['max_size'] = 10240;
    		$configImg['max_width'] = 9000;
    		$configImg['max_height'] = 9000;
    		$configImg['file_ext_tolower'] = TRUE;
    		$configImg['remove_spaces'] = TRUE;

    		$this->load->library('upload', $configImg, 'sport_event_image');
    		if ($this->sport_event_image->do_upload('sport_event_image')) {
    			$uploadData = $this->sport_event_image->data();
    			$sport_event_image = 'uploads/sports_event/' . $uploadData['file_name'];
    			if (file_exists($sport_data->sport_event_image)) {
    				unlink($sport_data->sport_event_image);
    			}

    			$image_width = $uploadData['image_width'];
                $image_height = $uploadData['image_height'];

                $img_config = array(
                    'source_image'      => $sport_event_image,
                    'new_image'         => './uploads/sports_event/',
                    'maintain_ratio'    => false,
                    'width'             => $this->input->post('dataWidth_1'),
                    'height'            => $this->input->post('dataHeight_1'),
                    'x_axis'            => $this->input->post('dataX_1'),
                    'y_axis'            => $this->input->post('dataY_1')
                );

                $this->load->library('image_lib',$img_config);
                
                if(($this->input->post('dataWidth_1') != 0) && ($this->input->post('dataHeight_1') != 0))
                {
                    $this->image_lib->crop();
                    $this->image_lib->clear();
                }
    		} else {
    			$this->custom_errors['sport_event_image'] = $this->sport_event_image->display_errors('', '');
    		}
    	}

		if ($this->form_validation->run() === TRUE) {
			$data = [
				'title' => $this->input->post('title'),
				'start_date' => date('Y-m-d', strtotime($this->input->post('start_date'))),
				'end_date' => date('Y-m-d', strtotime($this->input->post('end_date'))),
				'text_content' => $this->input->post('text_content'),
				'sport_event_image' => $sport_event_image,
				'updated_on' => time()
			];

			$edit = $this->sports_model->edit_sports_event($sport_data->id, $data);
			if ($edit) {
				$this->session->set_flashdata('message', ['1', $this->lang->line('sport_edit_success')]);
				redirect('admin/sports');
			} else {
				$this->session->set_flashdata('message', ['0', $this->lang->line('sport_edit_failed')]);
				redirect('admin/sports');
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['sport_data'] = $sport_data;

			/* Load Template */
			$this->template->admin_render('admin/sports/edit', $this->data);
		}
	}

	public function delete() {

        $id = $this->input->post('sport_event_id');

        $sport_data = $this->sports_model->fetch_sport_event($id);

        if (!empty($sport_data)) {
	        $sport_event_delete = $this->sports_model->delete_sport_event($id);

	        if ($sport_event_delete) {
	        	if (file_exists($sport_data->sport_event_image)) {
    				unlink($sport_data->sport_event_image);
    			}

	            $response = array(
	                'status' => 1,
	                'message' => $this->lang->line('sport_delete_success')
	            );
	        } else {
	            $response = array(
	                'status' => 0,
	                'message' => $this->lang->line('sport_delete_failed')
	            );
	        }
        } else {
        	$response = array(
                'status' => 0,
                'message' => $this->lang->line('sport_not_exist')
            );
        }

        echo json_encode($response);
    }
}