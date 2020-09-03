<?php
class ControllerSFToolSettings extends Controller {
	private $error = array();

	public function index() {
		require_once(DIR_SYSTEM . 'library/sftool.php');
		require_once(DIR_SYSTEM . 'library/sfcache.php');
		
		$this->load->language('sftool/upload');

		$this->document->setTitle($this->language->get('setting_title'));
		
		$data['heading_title'] = $this->language->get('setting_title');
		
		$data['text_product'] = $this->language->get('text_product');	
		$data['text_register'] = $this->language->get('text_register');	
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_edit'] = $this->language->get('text_edit');	
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_image'] = $this->language->get('text_image');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_upload'] = $this->language->get('entry_upload');
		$data['entry_prod_status'] = $this->language->get('entry_prod_status');
		$data['entry_reg_status'] = $this->language->get('entry_reg_status');
		$data['entry_cust_status'] = $this->language->get('entry_cust_status');
		$data['entry_prod_status'] = $this->language->get('entry_prod_status');	
		$data['entry_reg_status'] = $this->language->get('entry_reg_status');	
		$data['entry_cust_status'] = $this->language->get('entry_cust_status');	
		$data['entry_preview'] = $this->language->get('entry_preview');	
		$data['entry_popup'] = $this->language->get('entry_popup');	
		$data['entry_no_image'] = $this->language->get('entry_no_image');		
		$data['entry_max_size'] = $this->language->get('entry_max_size');
		$data['entry_max_quantity'] = $this->language->get('entry_max_quantity');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['help_prod_status'] = $this->language->get('help_prod_status');		
		$data['help_reg_status'] = $this->language->get('help_reg_status');
		$data['help_cust_status'] = $this->language->get('help_cust_status');
		$data['help_prod_max_filesize'] = $this->language->get('help_prod_max_filesize');
		$data['help_reg_max_filesize'] = $this->language->get('help_reg_max_filesize');
		$data['help_prod_max_quantity'] = $this->language->get('help_prod_max_quantity');
		$data['help_reg_max_quantity'] = $this->language->get('help_reg_max_quantity');

		$this->load->model('setting/setting');
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('sftool', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_save_success');

			$this->response->redirect($this->url->link('sftool/settings', 'user_token=' . $this->session->data['user_token'], true));
		}
			
		$appData=aplVerifyLicense($db);
		
		if ($appData['notification_case']!="notification_license_ok") {
			echo "License verification failed because of this reason: ".$appData['notification_text'];
			exit();
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('sftool/settings', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('sftool/settings', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->request->post['sftool_product_status'])) {
			$data['sftool_product_status'] = $this->request->post['sftool_product_status'];
		} else {
			$data['sftool_product_status'] = $this->config->get('sftool_product_status');
		}
		
		if (isset($this->request->post['sftool_prod_max_file_size'])) {
			$data['sftool_prod_max_file_size'] = $this->request->post['sftool_prod_max_file_size'];
		} else {
			$data['sftool_prod_max_file_size'] = $this->config->get('sftool_prod_max_file_size');
		}
		
		if (isset($this->request->post['sftool_prod_max_quantity'])) {
			$data['sftool_prod_max_quantity'] = $this->request->post['sftool_prod_max_quantity'];
		} else {
			$data['sftool_prod_max_quantity'] = $this->config->get('sftool_prod_max_quantity');
		}
		
		if (isset($this->request->post['sftool_register_status'])) {
			$data['sftool_register_status'] = $this->request->post['sftool_register_status'];
		} else {
			$data['sftool_register_status'] = $this->config->get('sftool_register_status');
		}
		
		if (isset($this->request->post['sftool_reg_max_file_size'])) {
			$data['sftool_reg_max_file_size'] = $this->request->post['sftool_reg_max_file_size'];
		} else {
			$data['sftool_reg_max_file_size'] = $this->config->get('sftool_reg_max_file_size');
		}
		
		if (isset($this->request->post['sftool_reg_max_quantity'])) {
			$data['sftool_reg_max_quantity'] = $this->request->post['sftool_reg_max_quantity'];
		} else {
			$data['sftool_reg_max_quantity'] = $this->config->get('sftool_reg_max_quantity');
		}
		
		if (isset($this->request->post['sftool_customer_status'])) {
			$data['sftool_customer_status'] = $this->request->post['sftool_customer_status'];
		} else {
			$data['sftool_customer_status'] = $this->config->get('sftool_customer_status');
		}
		
		if (isset($this->request->post['sftool_image_preview_width'])) {
			$data['sftool_image_preview_width'] = $this->request->post['sftool_image_preview_width'];
		} else {
			$data['sftool_image_preview_width'] = $this->config->get('sftool_image_preview_width');
		}
		
		if (isset($this->request->post['sftool_image_preview_height'])) {
			$data['sftool_image_preview_height'] = $this->request->post['sftool_image_preview_height'];
		} else {
			$data['sftool_image_preview_height'] = $this->config->get('sftool_image_preview_height');
		}
		
		if (isset($this->request->post['sftool_image_popup_width'])) {
			$data['sftool_image_popup_width'] = $this->request->post['sftool_image_popup_width'];
		} else {
			$data['sftool_image_popup_width'] = $this->config->get('sftool_image_popup_width');
		}
		
		if (isset($this->request->post['sftool_image_popup_height'])) {
			$data['sftool_image_popup_height'] = $this->request->post['sftool_image_popup_height'];
		} else {
			$data['sftool_image_popup_height'] = $this->config->get('sftool_image_popup_height');
		}
		
		if (isset($this->request->post['sftool_image_no_image_width'])) {
			$data['sftool_image_no_image_width'] = $this->request->post['sftool_image_no_image_width'];
		} else {
			$data['sftool_image_no_image_width'] = $this->config->get('sftool_image_no_image_width');
		}
		
		if (isset($this->request->post['sftool_image_no_image_height'])) {
			$data['sftool_image_no_image_height'] = $this->request->post['sftool_image_no_image_height'];
		} else {
			$data['sftool_image_no_image_height'] = $this->config->get('sftool_image_no_image_height');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sftool/settings', $data));
	}
	
	public function initialize() {
		$this->document->setTitle('License Activation');
		
		$data['text_edit'] = 'License Activation';
		
		require_once(DIR_SYSTEM . 'library/sftool.php');
		require_once(DIR_SYSTEM . 'library/sfcache.php');
		
		$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
		$data['root_url'] = str_ireplace('/admin/index.php?route=sftool/settings/initialize&' . 'user_token=' . $this->session->data['user_token'], '', 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {			
    		extract(array_map("trim", $_POST), EXTR_SKIP);
			
			$license_notifications_array=aplInstallLicense($root_url, $client_email, $license_code, $db);
				
			if ($license_notifications_array['notification_case']=="notification_license_ok") {
				$this->InitializedB();
				$this->clear();
				$this->refresh();
				$this->response->redirect($this->url->link('sftool/settings', 'user_token=' . $this->session->data['user_token'], true));
			} else  {
				$data['heading_title'] = 'License Activation';
			    	
				if (isset($license_notifications_array['notification_text'])) {
					$data['error_warning'] = $license_notifications_array['notification_text'];
				} else {
					$data['error_warning'] = '';
				}
				
				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
					'text'      => $this->language->get('text_home')
				);

				$data['breadcrumbs'][] = array(
					'href'      => $this->url->link('sftool/settings/initialize', 'user_token=' . $this->session->data['user_token'], true),
					'text'      => 'License Activation'
				);
							
				$data['action'] = $this->url->link('sftool/settings/initialize', 'user_token=' . $this->session->data['user_token'], true);
							
				$data['header'] = $this->load->controller('common/header');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['footer'] = $this->load->controller('common/footer');
					
				$this->response->setOutput($this->load->view('sftool/sflinitialize', $data));	
			}
		}
		
		$data['heading_title'] = 'License Activation';
			    	
		if (isset($license_notifications_array['notification_text'])) {
			$data['error_warning'] = $license_notifications_array['notification_text'];
		} else {
			$data['error_warning'] = '';
		}
										
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('sftool/settings/initialize', 'user_token=' . $this->session->data['user_token'], true),
			'text'      => 'License Activation'
		);
										
		$data['action'] = $this->url->link('sftool/settings/initialize', 'user_token=' . $this->session->data['user_token'], true);
					
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
			
		$this->response->setOutput($this->load->view('sftool/sflinitialize', $data));
	}
	
	public function InitializedB() {
		$this->load->model('sftool/upload');
		$this->model_sftool_upload->initializedb();
		
		if (!file_exists(DIR_UPLOAD . 'temp')) {
			mkdir(DIR_UPLOAD . 'temp', 0777);
		}
	}
	
	public function refresh($data = array()) {
		if (version_compare(VERSION, '2.3.0.2', '>')) {
			$this->load->language('marketplace/modification');
			$this->load->model('setting/modification');
		} else {
			$this->load->language('extension/modification');
			$this->load->model('extension/modification');
		}

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->validate()) {
			// Just before files are deleted, if config settings say maintenance mode is off then turn it on
			$maintenance = $this->config->get('config_maintenance');

			$this->load->model('setting/setting');

			$this->model_setting_setting->editSettingValue('config', 'config_maintenance', true);

			//Log
			$log = array();

			// Clear all modification files
			$files = array();

			// Make path into an array
			$path = array(DIR_MODIFICATION . '*');

			// While the path array is still populated keep looping through
			while (count($path) != 0) {
				$next = array_shift($path);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			// Reverse sort the file array
			rsort($files);

			// Clear all modification files
			foreach ($files as $file) {
				if ($file != DIR_MODIFICATION . 'index.html') {
					// If file just delete
					if (is_file($file)) {
						unlink($file);

					// If directory use the remove directory function
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}
			}

			// Begin
			$xml = array();

			// Load the default modification XML
			$xml[] = file_get_contents(DIR_SYSTEM . 'modification.xml');

			// This is purly for developers so they can run mods directly and have them run without upload sfter each change.
			$files = glob(DIR_SYSTEM . '*.ocmod.xml');

			if ($files) {
				foreach ($files as $file) {
					$xml[] = file_get_contents($file);
				}
			}

			// Get the default modification file
			if (version_compare(VERSION, '2.3.0.2', '>')) {
				$results = $this->model_setting_modification->getModifications();
			} else {
				$results = $this->model_extension_modification->getModifications();
			}
			

			foreach ($results as $result) {
				if ($result['status']) {
					$xml[] = $result['xml'];
				}
			}

			$modification = array();

			foreach ($xml as $xml) {
				if (empty($xml)){
					continue;
				}
				
				$dom = new DOMDocument('1.0', 'UTF-8');
				$dom->preserveWhiteSpace = false;
				$dom->loadXml($xml);

				// Log
				$log[] = 'MOD: ' . $dom->getElementsByTagName('name')->item(0)->textContent;

				// Wipe the past modification store in the backup array
				$recovery = array();

				// Set the a recovery of the modification code in case we need to use it if an abort attribute is used.
				if (isset($modification)) {
					$recovery = $modification;
				}

				$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');

				foreach ($files as $file) {
					$operations = $file->getElementsByTagName('operation');

					$files = explode('|', $file->getAttribute('path'));

					foreach ($files as $file) {
						$path = '';

						// Get the full path of the files that are going to be used for modification
						if ((substr($file, 0, 7) == 'catalog')) {
							$path = DIR_CATALOG . substr($file, 8);
						}

						if ((substr($file, 0, 5) == 'admin')) {
							$path = DIR_APPLICATION . substr($file, 6);
						}

						if ((substr($file, 0, 6) == 'system')) {
							$path = DIR_SYSTEM . substr($file, 7);
						}

						if ($path) {
							$files = glob($path, GLOB_BRACE);

							if ($files) {
								foreach ($files as $file) {
									// Get the key to be used for the modification cache filename.
									if (substr($file, 0, strlen(DIR_CATALOG)) == DIR_CATALOG) {
										$key = 'catalog/' . substr($file, strlen(DIR_CATALOG));
									}

									if (substr($file, 0, strlen(DIR_APPLICATION)) == DIR_APPLICATION) {
										$key = 'admin/' . substr($file, strlen(DIR_APPLICATION));
									}

									if (substr($file, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
										$key = 'system/' . substr($file, strlen(DIR_SYSTEM));
									}

									// If file contents is not already in the modification array we need to load it.
									if (!isset($modification[$key])) {
										$content = file_get_contents($file);

										$modification[$key] = preg_replace('~\r?\n~', "\n", $content);
										$original[$key] = preg_replace('~\r?\n~', "\n", $content);

										// Log
										$log[] = PHP_EOL . 'FILE: ' . $key;
									}

									foreach ($operations as $operation) {
										$error = $operation->getAttribute('error');

										// Ignoreif
										$ignoreif = $operation->getElementsByTagName('ignoreif')->item(0);

										if ($ignoreif) {
											if ($ignoreif->getAttribute('regex') != 'true') {
												if (strpos($modification[$key], $ignoreif->textContent) !== false) {
													continue;
												}
											} else {
												if (preg_match($ignoreif->textContent, $modification[$key])) {
													continue;
												}
											}
										}

										$status = false;

										// Search and replace
										if ($operation->getElementsByTagName('search')->item(0)->getAttribute('regex') != 'true') {
											// Search
											$search = $operation->getElementsByTagName('search')->item(0)->textContent;
											$trim = $operation->getElementsByTagName('search')->item(0)->getAttribute('trim');
											$index = $operation->getElementsByTagName('search')->item(0)->getAttribute('index');

											// Trim line if no trim attribute is set or is set to true.
											if (!$trim || $trim == 'true') {
												$search = trim($search);
											}

											// Add
											$add = $operation->getElementsByTagName('add')->item(0)->textContent;
											$trim = $operation->getElementsByTagName('add')->item(0)->getAttribute('trim');
											$position = $operation->getElementsByTagName('add')->item(0)->getAttribute('position');
											$offset = $operation->getElementsByTagName('add')->item(0)->getAttribute('offset');

											if ($offset == '') {
												$offset = 0;
											}

											// Trim line if is set to true.
											if ($trim == 'true') {
												$add = trim($add);
											}

											// Log
											$log[] = 'CODE: ' . $search;

											// Check if using indexes
											if ($index !== '') {
												$indexes = explode(',', $index);
											} else {
												$indexes = array();
											}

											// Get all the matches
											$i = 0;

											$lines = explode("\n", $modification[$key]);

											for ($line_id = 0; $line_id < count($lines); $line_id++) {
												$line = $lines[$line_id];

												// Status
												$match = false;

												// Check to see if the line matches the search code.
												if (stripos($line, $search) !== false) {
													// If indexes are not used then just set the found status to true.
													if (!$indexes) {
														$match = true;
													} elseif (in_array($i, $indexes)) {
														$match = true;
													}

													$i++;
												}

												// Now for replacing or adding to the matched elements
												if ($match) {
													switch ($position) {
														default:
														case 'replace':
															$new_lines = explode("\n", $add);

															if ($offset < 0) {
																array_splice($lines, $line_id + $offset, abs($offset) + 1, array(str_replace($search, $add, $line)));

																$line_id -= $offset;
															} else {
																array_splice($lines, $line_id, $offset + 1, array(str_replace($search, $add, $line)));
															}

															break;
														case 'before':
															$new_lines = explode("\n", $add);

															array_splice($lines, $line_id - $offset, 0, $new_lines);

															$line_id += count($new_lines);
															break;
														case 'after':
															$new_lines = explode("\n", $add);

															array_splice($lines, ($line_id + 1) + $offset, 0, $new_lines);

															$line_id += count($new_lines);
															break;
													}

													// Log
													$log[] = 'LINE: ' . $line_id;

													$status = true;
												}
											}

											$modification[$key] = implode("\n", $lines);
										} else {
											$search = trim($operation->getElementsByTagName('search')->item(0)->textContent);
											$limit = $operation->getElementsByTagName('search')->item(0)->getAttribute('limit');
											$replace = trim($operation->getElementsByTagName('add')->item(0)->textContent);

											// Limit
											if (!$limit) {
												$limit = -1;
											}

											// Log
											$match = array();

											preg_match_all($search, $modification[$key], $match, PREG_OFFSET_CAPTURE);

											// Remove part of the the result if a limit is set.
											if ($limit > 0) {
												$match[0] = array_slice($match[0], 0, $limit);
											}

											if ($match[0]) {
												$log[] = 'REGEX: ' . $search;

												for ($i = 0; $i < count($match[0]); $i++) {
													$log[] = 'LINE: ' . (substr_count(substr($modification[$key], 0, $match[0][$i][1]), "\n") + 1);
												}

												$status = true;
											}

											// Make the modification
											$modification[$key] = preg_replace($search, $replace, $modification[$key], $limit);
										}

										if (!$status) {
											// Abort applying this modification completely.
											if ($error == 'abort') {
												$modification = $recovery;
												// Log
												$log[] = 'NOT FOUND - ABORTING!';
												break 5;
											}
											// Skip current operation or break
											elseif ($error == 'skip') {
												// Log
												$log[] = 'NOT FOUND - OPERATION SKIPPED!';
												continue;
											}
											// Break current operations
											else {
												// Log
												$log[] = 'NOT FOUND - OPERATIONS ABORTED!';
											 	break;
											}
										}
									}
								}
							}
						}
					}
				}

				// Log
				$log[] = '----------------------------------------------------------------';
			}

			// Log
			$ocmod = new Log('ocmod.log');
			$ocmod->write(implode("\n", $log));

			// Write all modification files
			foreach ($modification as $key => $value) {
				// Only create a file if there are changes
				if ($original[$key] != $value) {
					$path = '';

					$directories = explode('/', dirname($key));

					foreach ($directories as $directory) {
						$path = $path . '/' . $directory;

						if (!is_dir(DIR_MODIFICATION . $path)) {
							@mkdir(DIR_MODIFICATION . $path, 0777);
						}
					}

					$handle = fopen(DIR_MODIFICATION . $key, 'w');

					fwrite($handle, $value);

					fclose($handle);
				}
			}

			// Maintance mode back to original settings
			$this->model_setting_setting->editSettingValue('config', 'config_maintenance', $maintenance);
		}
	}

	public function clear() {
		if (version_compare(VERSION, '2.3.0.2', '>')) {
			$this->load->language('marketplace/modification');
			$this->load->model('setting/modification');
		} else {
			$this->load->language('extension/modification');
			$this->load->model('extension/modification');
		}

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->validate()) {
			$files = array();

			// Make path into an array
			$path = array(DIR_MODIFICATION . '*');

			// While the path array is still populated keep looping through
			while (count($path) != 0) {
				$next = array_shift($path);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			// Reverse sort the file array
			rsort($files);

			// Clear all modification files
			foreach ($files as $file) {
				if ($file != DIR_MODIFICATION . 'index.html') {
					// If file just delete
					if (is_file($file)) {
						unlink($file);

					// If directory use the remove directory function
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}
			}
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'sftool/settings')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}