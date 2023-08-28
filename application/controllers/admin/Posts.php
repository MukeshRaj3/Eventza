<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends Admin_Controller {

	public function __construct() {
		parent::__construct();

		/* Load Langauge */
        $this->lang->load('admin/posts');
        /* Load Model */
		$this->load->model('admin/posts_model');
	}

	public function index() {
		/* Title Page */
        $this->page_title->push(lang('menu_posts'));
        $this->data['pagetitle'] = $this->page_title->show();

		/* Load Template */
		$this->template->admin_render('admin/posts/index', $this->data);
	}

	public function ajax_list() {
		$list = $this->posts_model->get_datatables();
        $data = array();
        foreach ($list as $key => $post) {
	        $action = '';
            $action .= '<a href="posts/edit/'.$post->id.'" class="btn btn-primary waves-effect waves-light"><i class="fe-edit"></i></a> <button type="button" class="btn btn-dark waves-effect waves-light post_delete_confirmation" data-toggle="modal" data-target="#post_deleted" data-id='.$post->id.'><i class="fe-trash-2"></i></button>';

	        $published_at = date('d M Y', strtotime($post->published_at));

            $row = array();
            $row[] = $post->title;
            $row[] = '<image src='.base_url($post->featured_image).' height="50px" width="90px">';
            $row[] = $published_at;
            $row[] = $action;
            $data[] = $row;
        }

        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->posts_model->count_all(),
            "recordsFiltered" => $this->posts_model->count_filtered(),
            "data" => $data,
		];
        //output to json format
        echo json_encode($output);
	}

	public function create() {
		/* Title Page */
        $this->page_title->push(lang('menu_create_post'));
        $this->data['pagetitle'] = $this->page_title->show();

		 // validate form input
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('body', 'body', 'trim|required');
		$this->form_validation->set_rules('published_at', 'Published At', 'trim|required');

		if (!empty($_FILES['featured_image']['name'])) {
			/* Conf Image */
			$file_name = 'posts_' . time() . rand(100, 999);
			$configImg['upload_path'] = './uploads/posts/';
			$configImg['file_name'] = $file_name;
			$configImg['allowed_types'] = 'png|jpg|jpeg';
			$configImg['max_size'] = 10240;
			$configImg['max_width'] = 9000;
			$configImg['max_height'] = 9000;
			$configImg['file_ext_tolower'] = TRUE;
			$configImg['remove_spaces'] = TRUE;

			$this->load->library('upload', $configImg, 'featured_image');
			if ($this->featured_image->do_upload('featured_image')) {
				$uploadData = $this->featured_image->data();
				$featured_image = 'uploads/posts/' . $uploadData['file_name'];

				$image_width = $uploadData['image_width'];
                $image_height = $uploadData['image_height'];

                $img_config = array(
                    'source_image'      => $featured_image,
                    'new_image'         => './uploads/posts/',
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
				$this->custom_errors['featured_image'] = $this->featured_image->display_errors('', '');
			}
		}

		if ($this->form_validation->run() === TRUE) {
			$data = [
				'user_id' => $this->session->userdata('user_id'),
				'title' => $this->input->post('title'),
				'seo_title' => $this->input->post('title'),
				'slug' => url_title($this->input->post('title'), '-', true),
				'body' => $this->input->post('body'),
				'published_at' => date('Y-m-d', strtotime($this->input->post('published_at'))),
				'featured_image' => $featured_image,
				'created' => date('Y-m-d H:i:s'),
				'modified' => date('Y-m-d H:i:s'),
				'is_favourited' => $this->input->post('is_favourited'),
				'reading_time' => $this->input->post('reading_time'),
				'posted_by' => $this->input->post('posted_by'),
				'status' => 1,
				'type' => 'post'
			];

			$create = $this->posts_model->create_post($data);
			if ($create) {
				$category_ids = $this->input->post('category_ids');
				foreach ($category_ids as $key => $category) {
					$category_data[] = [
						'post_id' => $create,
						'category_id' => $category
					]; 
				}
				$this->posts_model->add_category($category_data);
				$this->session->set_flashdata('message', ['1', $this->lang->line('post_create_success')]);
				redirect('admin/posts');
			} else {
				$this->session->set_flashdata('message', ['0', $this->lang->line('post_create_failed')]);
				redirect('admin/posts');
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['post_categories'] = $this->posts_model->get_categories();
			/* Load Template */
			$this->template->admin_render('admin/posts/create', $this->data);
		}
	}

	public function edit($id = NULL) {
        if (is_null($id)) {
        	$this->session->set_flashdata('message', ['1', 'Post not found']);
        	redirect('auth/posts', 'refresh');	
        }
        
		$post_data = $this->posts_model->fetch_post($id);
		if (is_null($post_data)) {
			$this->session->set_flashdata('message', ['1', 'Post not found']);
        	redirect('auth/posts', 'refresh');	
		}

		/* Title Page */
        $this->page_title->push(lang('menu_edit_post'));
        $this->data['pagetitle'] = $this->page_title->show();

		 // validate form input
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('body', 'body', 'trim|required');
		$this->form_validation->set_rules('published_at', 'Published At', 'trim|required');

		$featured_image = $post_data->featured_image;
    	if (!empty($_FILES['featured_image']['name'])) {
    		/* Conf Image */
    		$file_name = 'posts_' . time() . rand(100, 999);
    		$configImg['upload_path'] = './uploads/posts/';
    		$configImg['file_name'] = $file_name;
    		$configImg['allowed_types'] = 'png|jpg|jpeg';
    		$configImg['max_size'] = 10240;
    		$configImg['max_width'] = 9000;
    		$configImg['max_height'] = 9000;
    		$configImg['file_ext_tolower'] = TRUE;
    		$configImg['remove_spaces'] = TRUE;

    		$this->load->library('upload', $configImg, 'featured_image');
    		if ($this->featured_image->do_upload('featured_image')) {
    			$uploadData = $this->featured_image->data();
    			$featured_image = 'uploads/posts/' . $uploadData['file_name'];
    			if (file_exists($post_data->featured_image)) {
    				unlink($post_data->featured_image);
    			}

    			$image_width = $uploadData['image_width'];
                $image_height = $uploadData['image_height'];

                $img_config = array(
                    'source_image'      => $featured_image,
                    'new_image'         => './uploads/posts/',
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
    			$this->custom_errors['featured_image'] = $this->featured_image->display_errors('', '');
    		}
    	}

		if ($this->form_validation->run() === TRUE) {
			$data = [
				'user_id' => $this->session->userdata('user_id'),
				'title' => $this->input->post('title'),
				'seo_title' => $this->input->post('title'),
				'slug' => url_title($this->input->post('title'), '-', true),
				'body' => $this->input->post('body'),
				'published_at' => date('Y-m-d', strtotime($this->input->post('published_at'))),
				'featured_image' => $featured_image,
				'is_favourited' => $this->input->post('is_favourited'),
				'reading_time' => $this->input->post('reading_time'),
				'posted_by' => $this->input->post('posted_by'),
				'modified' => date('Y-m-d H:i:s')
			];

			$edit = $this->posts_model->edit_post($post_data->id, $data);
			if ($edit) {
				$this->posts_model->remove_port_all_categories($post_data->id);
				$category_ids = $this->input->post('category_ids');
				foreach ($category_ids as $key => $category) {
					$category_data[] = [
						'post_id' => $post_data->id,
						'category_id' => $category
					]; 
				}
				$this->posts_model->add_category($category_data);
				$this->session->set_flashdata('message', ['1', $this->lang->line('post_edit_success')]);
				redirect('admin/posts');
			} else {
				$this->session->set_flashdata('message', ['0', $this->lang->line('post_edit_failed')]);
				redirect('admin/posts');
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['post'] = $post_data;
			$this->data['post_categories'] = $this->posts_model->get_categories();
			$this->data['selected_category'] = $this->posts_model->selected_post_categories($post_data->id);

			/* Load Template */
			$this->template->admin_render('admin/posts/edit', $this->data);
		}
	}

	public function delete() {

        $id = $this->input->post('post_id');

        $post_data = $this->posts_model->fetch_post($id);

        if (!empty($post_data)) {
	        $post_delete = $this->posts_model->delete_post($id);

	        if ($post_delete) {
	        	if (file_exists($post_data->featured_image)) {
    				unlink($post_data->featured_image);
    			}

	            $response = array(
	                'status' => 1,
	                'message' => $this->lang->line('post_delete_success')
	            );
	        } else {
	            $response = array(
	                'status' => 0,
	                'message' => $this->lang->line('post_delete_failed')
	            );
	        }
        } else {
        	$response = array(
                'status' => 0,
                'message' => $this->lang->line('post_not_exist')
            );
        }

        echo json_encode($response);
    }
}