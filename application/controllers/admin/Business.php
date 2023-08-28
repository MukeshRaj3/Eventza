<?php defined('BASEPATH') OR exit('No direct script access allowed');
// include APPPATH . '/third_party/PHPMailer/PHPMailerAutoload.php';

class Business extends Admin_Controller {

	public function __construct() {
		parent::__construct();
		/* Load :: Language */
		$this->lang->load('admin/business');
		/* Load :: Common */
		$this->load->model('admin/business_model');
		$this->load->model('general_model');
	}

	public function index() {
		if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

		/* Title Page */
		$this->page_title->push(lang('menu_business'));
		$this->data['pagetitle'] = $this->page_title->show();

		/* Load Template */
		$this->template->admin_render('admin/business/index', $this->data);
	}

    public function create() {
    	if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $validation = [
            [
                'field' => 'title',
                'label' => 'title',
                'rules' => 'required',
                'errors' => ['required' => 'Please enter %s.']
            ],
            [
                'field' => 'first_name',
                'label' => 'first name',
                'rules' => 'required',
                'errors' => ['required' => 'Please enter %s.']
            ],
            [
                'field' => 'last_name',
                'label' => 'last name',
                'rules' => 'required',
                'errors' => ['required' => 'Please enter %s.']
            ],
            [
                'field' => 'email',
                'label' => 'email',
                'rules' => 'required|valid_email|is_unique[business.email]',
                'errors' => ['required' => 'Please enter %s.', 'valid_email' => 'Please enter valid %s.', 'is_unique' => 'Email already exists']
            ],
            [
                'field' => 'business_name',
                'label' => 'business name',
                'rules' => 'required',
                'errors' => ['required' => 'Please enter %s.']
            ],
            [
                'field' => 'business_address',
                'label' => 'business address',
                'rules' => 'required',
                'errors' => ['required' => 'Please choose %s from google map.']
            ],
            [
                'field' => 'address_latitude',
                'label' => 'latitude',
                'rules' => 'required',
                'errors' => ['required' => 'Please add pin into google map.']
            ],
            [
                'field' => 'address_longitude',
                'label' => 'longitude',
                'rules' => 'required',
                'errors' => ['required' => 'Please add pin into google map.']
            ],
            [
                'field' => 'business_type',
                'label' => 'business type',
                'rules' => 'required',
                'errors' => ['required' => 'Please choose %s']
            ],
            [
                'field' => 'password',
                'label' => 'password',
                'rules' => 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]',
                'errors' => ['required' => 'Please enter %s.', 'min_length' => 'Password length must 8 - 20 characters']
            ],
            [
                'field' => 'password_confirm',
                'label' => 'confirmation password',
                'rules' => 'required|matches[password]',
                'errors' => ['required' => 'Please enter %s.','matches' => 'Your password and %s do not match.']
            ]
        ];
        $this->form_validation->set_rules($validation);
        if ($this->form_validation->run() == true) {
            // echo "<pre>";
            // print_r();
            // exit;
            $facilities = $this->input->post('facilities');
            if (!empty($facilities)) {
                $facilities = implode(",", $facilities);
            }
            $email = $this->input->post('email');
            $identity = $this->input->post('email');
            $password = $this->input->post('password');

            $additional_data = [
                'title' => $this->input->post('title'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'business_email' => $this->input->post('business_email'),
                'contact_number' => $this->input->post('contact_number'),
                'full_name' => $this->input->post('business_name'),
                'website' => $this->input->post('business_website'),
                'phone' => $this->input->post('business_phone'),
                'address' => $this->input->post('business_address'),
                'latitude' => $this->input->post('address_latitude'),
                'longitude' => $this->input->post('address_longitude'),
                'type' => $this->input->post('business_type'),
                // 'venue_id' => $this->input->post('stadium_id'),
                'facilities' => $facilities,
                'rating' => 5,
                'business_status' => 1
            ];
            /* Register new business  */
            $register = $this->ion_auth->register_business($identity, $password, $email, $additional_data);
            if ($register['success'] == 1) {
                /*Start Add monday.com site */
                $monday_data = [
                    'email' => $email,
                    'title' => $additional_data['title'],
                    'first_name' => $additional_data['first_name'], 
                    'last_name' => $additional_data['last_name'], 
                    'contact_number' => $additional_data['contact_number'] ? $additional_data['contact_number'] : '', 
                    'business_name' => $additional_data['full_name'],
                    'website' => $additional_data['website'],
                    'phone' => $additional_data['phone'],
                    'address' => $additional_data['address'],
                    'location' => $additional_data['latitude'].' '.$additional_data['longitude'].' '.$additional_data['address'],
                    'business_type' => $this->business_model->fetch_business_type($additional_data['type']),
                    'facilities' => $this->business_model->fetch_facility($additional_data['facilities']),
                    'stadium' => 'stadium',
                    'pincode' => ''
                ];
                // $this->account_model->insert_monday_data($monday_data);
                /*End Add monday.com site */

                /* Insert Default timing for Business */
                $timing_data[0] = ['day' => 'Monday','business_id' => $register['id'], 'start_time' => '00:00:00', 'end_time' => '00:00:00', 'closed' => 0];
                $timing_data[1] = ['day' => 'Tuesday','business_id' => $register['id'], 'start_time' => '00:00:00', 'end_time' => '00:00:00', 'closed' => 0];
                $timing_data[2] = ['day' => 'Wednesday','business_id' => $register['id'], 'start_time' => '00:00:00', 'end_time' => '00:00:00', 'closed' => 0];
                $timing_data[3] = ['day' => 'Thursday','business_id' => $register['id'], 'start_time' => '00:00:00', 'end_time' => '00:00:00', 'closed' => 0];
                $timing_data[4] = ['day' => 'Friday','business_id' => $register['id'], 'start_time' => '00:00:00', 'end_time' => '00:00:00', 'closed' => 0];
                $timing_data[5] = ['day' => 'Saturday','business_id' => $register['id'], 'start_time' => '00:00:00', 'end_time' => '00:00:00', 'closed' => 0];
                $timing_data[6] = ['day' => 'Sunday','business_id' => $register['id'], 'start_time' => '00:00:00', 'end_time' => '00:00:00', 'closed' => 0];
            
                $this->general_model->insert_batch('business_timing', $timing_data);

                $venues = $this->input->post('stadium_id');
                $this->business_model->insert_venue($venues, $register['id']);
                if (!empty($_FILES['post_images']['name'])) {
		            $imgsCount = count($_FILES['post_images']['name']);
		            
		            for($i = 0; $i < $imgsCount; $i++) {
		                if (!empty($_FILES['post_images']['name'][$i])) {
		                    $_FILES['image']['name']       = $_FILES['post_images']['name'][$i];
		                    $_FILES['image']['type']       = $_FILES['post_images']['type'][$i];
		                    $_FILES['image']['tmp_name']   = $_FILES['post_images']['tmp_name'][$i];
		                    $_FILES['image']['error']      = $_FILES['post_images']['error'][$i];
		                    $_FILES['image']['size']       = $_FILES['post_images']['size'][$i];

		                    $file_name = 'business_image_' . time() . rand(100, 999);
		                    $config['upload_path'] = './uploads/places/';
		                    $config['file_name'] = $file_name;
		                    $config['allowed_types'] = 'png|jpg|jpeg';
		                    $config['max_size'] = 10240;
		                    $config['file_ext_tolower'] = TRUE;
		                    $config['remove_spaces'] = TRUE;
		    
		                    $this->load->library('upload/', $config);
		                    $this->upload->initialize($config);
		                    if ($this->upload->do_upload('image')) {
		                        $business_image_array[] = [
		                            'image' => 'uploads/places/' . $this->upload->data('file_name'),
		                            'business_id' => $register['id'],
		                            'active' => 1,
		                            'created_on' => time()
		                        ];
		                    }
		                }
		            }
		        }
	            if(!empty($business_image_array)){
	                $this->general_model->insert_batch("business_images", $business_image_array);
	            }
                if ($this->config->item('manual_activation', 'ion_auth')) {
                    $this->data['id'] = $register['id'];
                    $this->data['identity'] = $identity;
                    $this->data['activation_code'] = $register['activation_code'];

                    $message = $this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_business_activate', 'ion_auth'), $this->data, true);

                    $mail = new PHPMailer;
                    $mail->isSMTP();
                    $mail->Host = $this->config->item('aws_ses_host');
                    $mail->SMTPAuth = true;
                    $mail->Username = $this->config->item('aws_ses_username');
                    $mail->Password = $this->config->item('aws_ses_password');
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = 465;
                    $mail->setFrom($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
                    $mail->addAddress($identity, $this->config->item('site_title', 'ion_auth'));
                    $mail->isHTML(true);
                    $mail->Subject = $this->config->item('site_title', 'ion_auth') . ' - Business signup';
                    $mail->Body = $message;
                    if ($mail->send()) {
                        /* if mail sent successfully then return user data */
                        $this->session->set_flashdata('message', ['1', $this->lang->line('business_register_success_mail_sent')]);
                        redirect("admin/business/create", 'refresh');
                    } else {
                        $this->session->set_flashdata('message', ['0', $this->lang->line('business_register_success_mail_failed')]);
                        redirect("admin/business/create", 'refresh');
                    }
                } else {
                    /* if mail not sent successfully then return user data */
                    $this->session->set_flashdata('message', ['1', $this->lang->line('business_register_success_mail_failed')]);
                    redirect("admin/business/create", 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', ['0', $this->lang->line('business_register_failed')]);
                redirect("admin/business/create", 'refresh');
            }
        } else {

        $this->data['pagetitle'] = $this->page_title->show();

        $tables = $this->config->item('tables', 'ion_auth');
        /* Validate form input */
        
		/* Load Template */

			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['business_type_row'] = $this->general_model->getAll('business_types', ['business_type_active' => 1]);
			$this->data['business_types'] = $this->business_model->business_types();

            $this->template->admin_render('admin/business/add', $this->data);

        }
    }

    public function check_email_exists() {
        $email = $this->input->post('email');
        $found = $this->general_model->getOne('business', ['email' => $email]);
        if (!empty($found)) {
            $response = [
                'resultcode' => 0,
                'message' => 'Email address already exist'
            ];
        } else {
            $response = [
                'resultcode' => 1,
                'message' => ""
            ];
        }
        echo json_encode($response);
    }

    public function offer($id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $this->data['business'] = $this->business_model->fetch_business($id);
        $this->data['business_offers'] = $this->general_model->getAll('business_offers', ['business_id' => $id, 'is_deleted' => 0]);
        $validation = [
            [
                'field' => 'title',
                'label' => 'title',
                'rules' => 'required',
                'errors' => ['required' => 'Please enter %s.']
            ],
            [
                'field' => 'description',
                'label' => 'description',
                'rules' => 'required',
                'errors' => ['required' => 'Please enter %s.']
            ],
        ];
        $this->form_validation->set_rules($validation);
        if ($this->form_validation->run() == true) {
            
            $insert_data = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'business_id' => $id,
                'active' => 1,
                'created_on' => time()
            ];
            /* Register new business  */
            $business = $this->general_model->insert('business_offers', $insert_data);
            if ($business) {
                $this->session->set_flashdata('message', array('1', $this->lang->line('business_offer_success')));
                redirect('admin/business/offer/'.$id, 'refresh');
            } else {
                $this->session->set_flashdata('message', array('1', $this->lang->line('business_offer_failed')));
                redirect('admin/business/offer/'.$id, 'refresh');
            }
        } else {
            /* Load Template */
            $this->template->admin_render('admin/business/offer', $this->data);

        }
    }

    public function offer_delete() {
        $offer_id = $this->input->post('offer_id'); 
             
        $deleted = $this->business_model->offer_delete($offer_id);
        if ($deleted) {
            $response = [
                'status' => 1,
                'message' => $this->lang->line('offer_deleted_success')
            ];
        } else {
            $response = [
                'status' => 0,
                'message' => $this->lang->line('offer_deleted_failed')
            ];
        }
        echo json_encode($response);
    }

    public function change_offer_status() {
        $status = $this->input->post('status'); 
        $offer_id = $this->input->post('offer_id'); 
             echo json_encode($offer_id);
        $is_updated = $this->business_model->change_offer_status($offer_id, $status);
        if ($is_updated) {
            $response = [
                'status' => 1,
                'message' => $this->lang->line('offer_deleted_success')
            ];
        } else {
            $response = [
                'status' => 0,
                'message' => $this->lang->line('offer_deleted_failed')
            ];
        }
        echo json_encode($response);
    }

	public function ajax_list() {
		// print_r($_POST);exit;
		$list = $this->business_model->get_datatables();
		$data = array();
		foreach ($list as $key => $business) {
			$business->registered_on = date('d-m-Y H:i:s', $business->created_on);
			if ($business->business_status == 0) {
				$action = '';
				$action .= '<a href="business/approved/'.$business->id.'" class="btn btn-primary waves-effect waves-light mb-1"><i class="mdi mdi-check-all"></i></a> <button type="button" class="btn btn-danger waves-effect waves-light business_rejection_confirmation" data-toggle="modal" data-target="#business_rejected" data-id='.$business->id.'><i class="mdi mdi-close"></i></button>';
			} else if ($business->business_status == 1) {
				$action = '<span class="badge bg-soft-success text-success">Accepted</span>';
			} else {
				$action = '<span class="badge bg-soft-danger text-danger">Rejected</span>';
			}
            $images = '<a href="' .base_url('admin/business/offer/'.$business->id) .'" class="btn btn-dark waves-effect waves-light mb-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Details"> <i class="fa fa-gift" aria-hidden="true"></i></a>';
			$view_detail = '<a href="' .base_url('admin/business/view/'.$business->id) .'" class="btn btn-dark waves-effect waves-light mb-1" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Details"> <i class="fa fa-eye"></i></a>';
			$view_detail .= '</br><a href="business/edit/'.$business->id.'" class="btn btn-dark waves-effect waves-light mb-1" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="mdi mdi-square-edit-outline"></i></a>';
			$view_detail .= '<button type="button" class="btn btn-danger waves-effect waves-light business_rejection_confirmation" data-toggle="modal" data-target="#business_deleted" data-id='.$business->id.'><i class="mdi mdi-delete"></i></button>';

			$row = [];
			$row[] = $business->full_name;
			$row[] = $business->extra_name;
			$row[] = $business->extra_name.' <br>'.$business->address;
			$row[] = $business->email;
			$row[] = $business->phone;
			$row[] = $business->website;
			$row[] = $business->registered_on;
			$row[] = $images;
			$row[] = $action;
            $row[] = $view_detail;
			$data[] = $row;
		}

		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->business_model->count_all(),
			"recordsFiltered" => $this->business_model->count_filtered(),
			"data" => $data,
		];
    	//output to json format
		echo json_encode($output);
	}

	public function approved($id) {
		if (empty($id)) {
			redirect('admin/business', 'refresh');
		}

		$business = $this->general_model->getOne('business',array('id' => $id));
		$is_updated = $this->business_model->business_approve_reject($id, 1);

		if ($is_updated) {
			$this->data['business'] = $business;
			$this->send_business_email_notification($business->email);
			$message = $this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_business_approved', 'ion_auth'), $this->data, true);

			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = $this->config->item('aws_ses_host');
			$mail->SMTPAuth = true;
			$mail->Username = $this->config->item('aws_ses_username');
			$mail->Password = $this->config->item('aws_ses_password');
			$mail->SMTPSecure = 'ssl';
			$mail->Port = 465;
			$mail->setFrom($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
			$mail->addAddress($business->email, $this->config->item('site_title', 'ion_auth'));
			$mail->isHTML(true);
			$mail->Subject = $this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('business_email_approved_subject');
			$mail->Body = $message;
			if ($mail->send()) {
				$this->session->set_flashdata('message', array('1', $this->lang->line('business_approved_success')));
				redirect('admin/business', 'refresh');
			} else {
				$this->session->set_flashdata('message', array('1', $this->lang->line('business_approved_email_sent_failed')));
				redirect('admin/business', 'refresh');
			}
		} else {
			$this->session->set_flashdata('message', array('0', $this->lang->line('business_approved_failed')));
			redirect('admin/business', 'refresh');
		}
	}

	public function rejected() {
		$business_id = $this->input->post('business_id');
		if (!empty($business_id)) {
			$business = $this->general_model->getOne('business',array('id' => $business_id));
			if (!empty($business)) {
				if ($business->business_status == 2) {
					$response = [
						'status' => 0,
						'message' => $this->lang->line('business_already_rejected')
					];
				} else {
					$is_rejected = $this->business_model->business_approve_reject($business_id, 2);

					if ($is_rejected) {
						$this->data['business'] = $business;
						$message = $this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_business_rejected', 'ion_auth'), $this->data, true);

						$mail = new PHPMailer;
						$mail->isSMTP();
						$mail->Host = $this->config->item('aws_ses_host');
						$mail->SMTPAuth = true;
						$mail->Username = $this->config->item('aws_ses_username');
						$mail->Password = $this->config->item('aws_ses_password');
						$mail->SMTPSecure = 'ssl';
						$mail->Port = 465;
						$mail->setFrom($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
						$mail->addAddress($business->email, $this->config->item('site_title', 'ion_auth'));
						$mail->isHTML(true);
						$mail->Subject = $this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('business_email_rejected_subject');
						$mail->Body = $message;
						if ($mail->send()) {
							$response = array(
								'status' => 1,
								'message' => $this->lang->line('business_rejected_success')
							);
						} else {
							$response = array(
								'status' => 1,
								'message' => $this->lang->line('business_rejected_email_sent_failed')
							);
						}
					} else {
						$response = array(
							'status' => 0,
							'message' => $this->lang->line('business_rejected_failed')
						);
					}
				}
			} else {
				$response = array(
					'status' => 0,
					'message' => 'Business detail not found.'
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

	public function deleted() {
		$business_id = $this->input->post('business_id');
		if (!empty($business_id)) {
			$business = $this->general_model->getOne('business',array('id' => $business_id));
			if (!empty($business)) {
				$is_deleted = $this->business_model->business_deleted($business_id);
				if($is_deleted) {
					$this->data['business'] = $business;
					$message = $this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_business_deleted', 'ion_auth'), $this->data, true);
	
					$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->Host = $this->config->item('aws_ses_host');
					$mail->SMTPAuth = true;
					$mail->Username = $this->config->item('aws_ses_username');
					$mail->Password = $this->config->item('aws_ses_password');
					$mail->SMTPSecure = 'ssl';
					$mail->Port = 465;
					$mail->setFrom($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
					$mail->addAddress($business->email, $this->config->item('site_title', 'ion_auth'));
					$mail->isHTML(true);
					$mail->Subject = $this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('business_email_deleted');
					$mail->Body = $message;
					if ($mail->send()) {
						$response = array(
							'status' => 1,
							'message' => $this->lang->line('business_deleted_success')
						);
					} else {
						$response = array(
							'status' => 1,
							'message' => $this->lang->line('business_deleted_email_sent_failed')
						);
					}

				} else {
					$response = array(
						'status' => 0,
						'message' => $this->lang->line('business_deleted_failed')
					);
				}
			} else {
				$response = array(
					'status' => 0,
					'message' => 'Business detail not found.'
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

	public function view($id = NULL) {
		if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        if (is_null($id)) {
        	$this->session->set_flashdata('message', ['0', 'Business not found']);
        	redirect('admin/business', 'refresh');	
        }

		/* Title Page */
		$this->page_title->push(lang('menu_business'));
		$this->data['pagetitle'] = $this->page_title->show();

		$business = $this->business_model->fetch_business($id);
		if (is_null($business)) {
			$this->session->set_flashdata('message', ['0', 'Business not found']);
			redirect('admin/business', 'refresh');
		}

		$this->data['business'] = $business;
		$this->data['business_type'] = $this->business_model->fetch_business_type($business->type);
		$this->data['facility_data'] = $this->business_model->fetch_facility($business->facilities);
		$business_venues = $this->business_model->fetch_venue($id);
		$venue = [];
		if(!empty($business_venues)) {
			foreach ($business_venues as $business_venue) {
				$venue[] = $business_venue->venue_name;
			}
		}

		$this->data['stadium'] = implode(', ', $venue);
		// print_r($this->data['stadium']);exit;
		/* Load Template */
		$this->template->admin_render('admin/business/view', $this->data);
	}

	private function send_business_email_notification($email) {
        $this->data['name'] = ""; 
        $message = $this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_business_live_profile', 'ion_auth'), $this->data, true);
        
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $this->config->item('aws_ses_host');
        $mail->SMTPAuth = true;
        $mail->Username = $this->config->item('aws_ses_username');
        $mail->Password = $this->config->item('aws_ses_password');
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
        $mail->addAddress($email, $this->config->item('site_title', 'ion_auth'));
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to '.$this->config->item('site_title', 'ion_auth');
        $mail->Body = $message;
        $mail->send();
    }

    public function edit($id = NULL) {
    	if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        if (is_null($id)) {
        	$this->session->set_flashdata('message', ['0', 'Business not found']);
        	redirect('admin/business', 'refresh');	
        }

        $business = $this->business_model->fetch_business($id);
		$business_venue = $this->business_model->fetch_venue($id);
		if (is_null($business)) {
			$this->session->set_flashdata('message', ['0', 'Business not found']);
			redirect('admin/business', 'refresh');
		}

		/* Title Page */
		$this->page_title->push(lang('menu_edit_business'));
		$this->data['pagetitle'] = $this->page_title->show();

		$this->form_validation->set_rules('business_address', 'Address', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$facilities = $this->input->post('facilities');
			if (!empty($facilities)) {
				$facilities = implode(",", $facilities);
			}
			$data = [
				'title' => $this->input->post('title'),
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'contact_number' => $this->input->post('contact_number'),
				'full_name' => $this->input->post('business_name'),
				'phone' => $this->input->post('business_phone'),
				'business_email' => $this->input->post('business_email'),
				'website' => $this->input->post('business_website'),
				'type' => $this->input->post('business_type'),
				'address' => $this->input->post('business_address'),
				'pincode' => $this->input->post('postcode'),
				'latitude' => $this->input->post('address_latitude'),
				'longitude' => $this->input->post('address_longitude'),
				// 'venue_id' => $this->input->post('stadium_id'),
				'facilities' => $facilities,
			];
			$update = $this->business_model->update_business($business->id, $data);
			$venues = $this->input->post('stadium_id');
			$this->business_model->update_venue($venues, $business->id);
			if ($update) {
				$this->session->set_flashdata('message', ['1', 'Business has been updated successfully']);
        		redirect('admin/business', 'refresh');
			} else {
				$this->session->set_flashdata('message', ['0', 'unble to update business']);
        		redirect('admin/business', 'refresh');	
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['business'] = $business;
			$this->data['business_type_row'] = $this->general_model->getOne('business_types',array('bt_id' => $business->type));
			$this->data['business_types'] = $this->business_model->business_types();
			$this->data['stadium'] = $this->business_model->fetch_stadium($business_venue);

			/* Load Template */
			$this->template->admin_render('admin/business/edit', $this->data);
		}
    }
}