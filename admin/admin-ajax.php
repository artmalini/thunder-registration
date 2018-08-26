<?php 
   //$deree = 'thunder_login_style-login-head-header-';
   //$key = explode('-', $deree);
   //$key = str_replace('/-$/g', '', $deree);
 /*   $field = preg_match('/([a-z_-]*)(-)(.*)/i', $deree, $match);
    if ($match[3] == '') {
      $field = $match[1];
    } else {
      $field = $match[0];
    }
   echo $field;
   $deree = 'thunder_login_style-login-head-header-';
   */

//pop-up window 
add_action( 'wp_ajax_thunder_admin_fields', 'thunder_admin_fields' );
function thunder_admin_fields() {	
	if ( ! current_user_can( 'manage_options' ) ) {
		die(); 
	};

	extract( $_POST );
	$output = null;
	$output = '<div class="thunder_sort_main" data-tpl="' . $form . '">
					<div class="thunder_sort"><span class="back-thumb">' . __( 'Back', 'thunder' ) . 
					'</span><span class="add-thumb">' . __( 'ADD', 'thunder') . '</span>
					<span class="list_close">' . __( 'Close', 'thunder') . '</span>
					<ul>' . thunder_admin_list_fields( $form ) . '</ul></div></div>';			
	$output = json_encode( array( 'response' => $output ) );	;
	echo $output;
	die();			
}

//List all fields when click to add
function thunder_admin_list_fields($form) {		
	$output = '';

	$group = array();
	foreach( get_option( 'thunder_fields' ) as $key => $value ) {
		$group[$key] = $value;	
	};

	$group2 = array(); 
	foreach ( thunder_fields_group_by_template( $form, 'default' ) as $zone => $count ) {
		foreach ( $count as $key => $value ) {
			$group2[$key] = $value; 
		};
	};

	$bv = array_diff_key( $group, $group2 );
	foreach ( $bv as $k => $arr ) {
		$output .= sli_fields( $k, $arr );
	};
	return $output;		
}

//fields compared to thunder form
function sli_fields( $k, $arr ) {
	$output = null;
	$zone = $arr['zone'];
	$samerow = __( 'Same row', 'thunder' );
	$newrow = __( 'New row', 'thunder' );
	$output .= '<li class="field">';

	if ( $arr['row'] ) {
		$valkey = $arr['row'];						
		$output .= '<select name="' . $zone . '-' . $k . '-row" id="' . $k . '-row" class="rowselect">';
		$output .= '<option value="newrow" ' . selected( 'newrow', $valkey, 0 ) . '>' . $newrow . '</option>';
		$output .= '<option value="samerow" ' . selected('samerow', $valkey, 0 ) . '>' . $samerow . '</option>';
		$output .= '</select>';
	};
		
	$output .= '<span>' . $arr['label'] . '</span>';
	$output .= '<a href="#" title="' . __( 'Delete Field', 'thunder' ) . '" class="field-remove"></a>';
	$output .= '<a href="#" class="switch-field"></a>';
	$output .= '<div class="admin-option-field-zone">';
	$output .= '<div class="grid">';
	$output .= '<div class="row">';

	if ( isset( $arr['type'] ) && in_array( $arr['type'], array( 'select', 'multiselect', 'checkbox', 'checkbox-full', 'radio', 'radio-full' ) ) ) {

		if ( ! isset( $arr['options'] ) ) {
			$arr['options'] = '';
		};
	};
	
	if ( is_array( $arr ) ) {
		foreach( $arr as $opt => $val ) {
			if ( in_array( $opt, array( 'label', 'help', 'placeholder', 'ajaxcheck', 'icon', 'button_text', 'list_id', 'list_text' ) ) ) {								
				switch ( $opt ) {
					case 'label': 
						$text = __( 'Label', 'thunder' ); 
					break;
					case 'help': 
						$text = __( 'Help Text', 'thunder' ); 
					break;
					case 'placeholder': 
						$text = __( 'Placeholder', 'thunder' ); 
					break;
					case 'ajaxcheck': 
						$text = __( 'Ajax Check Callback (advanced)', 'thunder' ); 
					break;
					case 'section' : 
						$text = __( 'Section Text', 'thunder' );
					break;
					case 'collapsible': 
						$text = __( 'Collapsible Section', 'thunder' );
					break;
					case 'collapsed': 
						$text = __( 'Collapsed', 'thunder' ); 
					break;
					case 'button_text': 
						$text = __( 'Upload Button Text', 'thunder' ); 
					break;
					case 'list_id': 
						$text = __( 'MailChimp List ID', 'thunder' ); 
					break;
					case 'list_text': 
						$text = __( 'MailChimp Subscribe Text', 'thunder' ); 
					break;
					case 'icon': 
						$text = __( 'Font Icon Code', 'thunder'); 
					break;
					case 'style': 
						$text = __( 'Style Code', 'thunder'); 
					break;
				};			
				$output .= '<div class="col-2 col-m1">';
				$output .= '<span class="thunder-field-zone-desc">' . $text . '</span>';
				$output .= '<input type="text" name="' . $zone . '-' . $k . '-' . $opt . '" id="' . $k . '-' . $opt . '" value="' . stripslashes( $val ) . '" />';
				$output .= '</div>';//end col
			};

			if ( in_array( $opt, array( 'options' ) ) ) {			
				if ( $val != '' && is_array( $val ) ) {
					$val = implode( "\n", $val );
				};
				$output .= '<div class="col-2 col-m1">';
				$output .= '<textarea name="' . $zone . '-' . $k . '-' . $opt . '" id="' . $k . '-' . $opt . '" cols="40" rows="2">' . stripslashes( $val ) . '</textarea>';
				$output .= '</div>';//end col
			};
		};

		$output .= '</div>';//end row
		$output .= '<div class="row">';
		//enhanced settings to each field	
		foreach( $arr as $key => $v ) {

			if ( $key && in_array( $key, array( 'html', 'hideable', 'hidden', 'required', 'locked', 'private' ) ) ) {

				if ( $v == 0 ) { 
					$class = 'off'; 
				} else {
				 	$class = 'on'; 
				};			

				$output .= '<div class="col-2 col-m2">';
					$output .= '<div class="switch-wrap ' . $class.'"><span>' . $key . ':</span>';
					$output .= '<div class="switch-box-wrap">';
					$output .= '<label class="switch-inner">';
					$output .= '<input type="hidden" value="0" name="' . $zone . '-' . $k.'-' . $key . '">';
					$output .= '<input class="switch-checkbox" type="checkbox" value="1" name="' . $zone . '-' . $k.'-' . $key . '"';
					$output .= checked( $v, 1, false );					
					$output .= '/>';
					$output .= '<span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label>'; 
					$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			};

			if ( $key && in_array( $key, array( 'type' ) ) ) {
				$output .= '<div class="col-2 col-m2">';
				$output .= '<input type="hidden" value="' . $v . '" name="' . $zone . '-' . $k.'-' . $key . '">';
				$output .= '</div>';
			}
			//zone to place - thead, tcontent, tbottom
			if ( $key && in_array( $key, array( 'zone' ) ) ) {
				$output .= '<div class="col-2 col-m2">';
				$output .= '<input type="hidden" class="zone" value="' . $v. '" name="' . $zone . '-' . $k.'-' . $key . '">';
				$output .= '</div>';
			};				
		};
	};	
	$output .= '</div>';//end row
	$output .= '</div>';//end grid
	$output .= '</div>';//end admin field zone
	$output .= '</li>';
	
	return $output;
}

//behavior to update styles
add_action( 'wp_ajax_update_styles', 'update_styles' );
function update_styles(){
	if ( ! current_user_can( 'manage_options' ) ) {
		die(); 
	};

	extract( $_POST );
	$output = '';
	$num = 0;

	$groups = get_option( 'thunder_fields_styles' );
	if ( $groups == false ) {
		$groups = array();
	};
	
	foreach( $_POST as $k => $value ) {
		if ( $k != 'action' ) {
			$num++;
		    $key = explode( '&', $k );
			if ( $num == 1 || ( $key[2] == 'build' ) ) {
				unset( $groups[$key[0]][$key[1]] ); //reset styles
			};

		    $tick = count( $key );
	     
		    if ( $key[1] == 'style' ) {
		     	$groups['theme'][$key[1]] = $value;
		    };
		   
		    if ( $tick == 3 && ( $key[2] != 'build' ) ) {		     	
		     	$groups[$key[0]][$key[1]][$key[2]] = $value;		     	
		    };

		    if ( $tick == 4 && ( $key[2] != 'build') ) {		     	
		     	$groups[$key[0]][$key[1]][$key[2]][$key[3]] = $value;		     	
		    };
 		};		
	};
	update_option('thunder_fields_styles', $groups);
	$output = json_encode( $output );
	echo $output;
	die();
}

//Save/update field groups
add_action('wp_ajax_thunder_save_group', 'thunder_save_group');
function thunder_save_group(){
	if ( ! current_user_can( 'manage_options' ) ) {
		die(); 
	};		
	
	extract($_POST);
	$output = '';	
	// Save field group
	$groups = get_option( 'thunder_fields_groups' );
	$groups[$_POST['name']][$_POST['templ']] = '';	
	foreach( $_POST as $k => $v ) {
		$v = stripslashes( $v );

		if ( $k != 'name' && $k != 'templ' && $k != 'action' ) { //this values not saved in db
			$key = explode( '-', $k, 3);

			if ( $key[2] != 'options' && $key[2] != 'icon' ) {
				$groups[$_POST['name']][$_POST['templ']][$key[0]][$key[1]][$key[2]] = $v;
			} elseif ( $key[2] == 'options' ) { //country list dropdown
				$groups[$_POST['name']][$_POST['templ']][$key[0]][$key[1]][$key[2]] = preg_split( '/[\r\n]+/', $v, -1, PREG_SPLIT_NO_EMPTY );
			}; /*elseif ( $key[2] == 'icon' ) {
				$fields[$key[0]][$key[1]]['icon'] = $v;
			};*/
		};			
	};
	
	//Save view group
	unset($groups['view']);
	$groups['view'] = $groups['edit'];
	
	update_option( 'thunder_fields_groups', $groups );
	update_option( 'thunder_fields', $fields );
	
	$output = json_encode( $output );		
	echo $output;
	die();
}


/**
 * Gather all fields to form
 * @param  [number] $key   		[thead, tcontent, tbottom]  
 * @param  [array]  $array  	[array contain field params]
 * @param  [number] $master_id  [number for each field]
 * @param  [number] $user_id 	[number of user]
 * @param  [array]  $args    	[array contain extension parameters]
 * @return [type]          [description]
 */
function thunder_view_field( $key, $array, $master_id, $user_id=null, $args ) {
	extract( $array );
	extract( $args );
	$res = null;
	$data = null;		
		
	/*add datta attributes*/
	foreach( $array as $data_option => $data_value ) {
		if ( ! is_array( $data_value ) ) {
			$data .= " data-$data_option='$data_value'";
		};
	};
	
	/* if editing an already user */
	if ( $user_id ) {
		$is_hidden = thunder_profile_data( 'hide_' . $key, $user_id );
		$value = thunder_profile_data( $key, $user_id );

		if ( isset($array['type']) && $array['type'] == 'ava_picture' ) {
			if ( $key == 'ava_picture' ) {
				$value = get_avatar( $user_id, 64 );
			} else {
				$crop = thunder_profile_data( $key, $user_id );
				if ( ! $crop){
					$value = '<span class="thunder-pic-none">'.__('No file has been uploaded.','thunder') . '</span>';
				} else {
					$value = '';
				}
				
				if ( isset( $array['width'] ) ){
					$width = $array['width'];
					$height = $array['height'];
				} else {
					$width = '';
					$height = '';
				}
				
				$value .= '<img src="' . $crop . '" width="' . $width.'" height="' . $height.'" alt="" class="modified" />';
			};
		};

		if ( isset( $array['type'] ) && $array['type'] == 'file' ) {
			$value = '<span class="thunder-pic-none">' . __( 'No file has been uploaded.', 'thunder' ) . '</span>';
			$file = thunder_profile_data( $key, $user_id );

			if ( $file ) {
				$value = '<div class="thunder-file-input"><a href="' . $file . '" ' . thunder_file_type_icon( $file ) . '>'.basename( $file ) . '</a></div>';
			}
		}
	} else {			
		// perhaps in registration
		if ( isset( $array['type']) && $array['type'] == 'ava_picture' ) {
			if ( $key == 'ava_picture' ) {
				$array['default'] = get_avatar( 0, 64 );
			};
		};
		
		if ( isset( $array['hidden'] ) ) {
			$is_hidden = $array['hidden'];
		};

		if ( isset( $array['hidden'] ) ) {
			$hideable = $array['hideable'];
		};	
		
		if ( isset( $array['default'] ) ) {
			$value = $array['default'];
		};
	};
	
	if ( ! isset( $value ) ) {
		$value = null;
	};
	
	if ( ! isset($array['placeholder'] ) ) {
		$array['placeholder'] = null;
	};
	
	/* remove passwords */
	if ( isset($array['type'] ) && $array['type'] == 'password' ) {
		$value = null;
	};

	/* display a section */
	if ( isset( $array['section'] ) ) {
		$res .= "<div class='thunder-section thunder-column thunder-collapsible-" . $array['collapsible'] . " thunder-collapsed-" . $array['collapsed'] . "'>" . $array['section'] . "</div>";
	};
	
	/* user permission */
	if ( ! $user_id ) {
		$user_id = 0;
	};

	if ( isset( $array['type'] ) && thunder_field_by_role( $key, $user_id ) ) {

/*			
		if ( $array['label'] && $array['type'] != 'passwordstrength' ) {
		
		if ($args['field_icons'] == 1) {
		$res .= "<div class='thunder-label iconed'>";
		} else {
		$res .= "<div class='thunder-label'>";
		}
		$res .= "<label for='$key-$master_id'>".$array['label']."</label>";*/
					//
		//
		//
		//
		//
		//Not finish
		//
		//
		//
		//
		//нужно доработать field icon
				/*	if ($args['field_icons'] == 1 && $thunder->field_icon($key)) {
						$res .= '<span class="thunder-field-icon"><i class="thunder-icon-'. $thunder->field_icon($key) .'"></i></span>';
					}*/

					
/*						if ($args['tpl'] != 'login' && isset( $array['help'] ) && $array['help'] != '' ) {
						$res .= '<span class="thunder-tip" title="'.stripslashes( $array['help'] ) . '"></span>';
					}
					
		$res .= "</div>";
		}*/
		$res .= gather_field(  $key, $array, $master_id, $user_id=null, $args, $value, $options, $is_hidden, $hideable, $data  );
		
		
	/* add action for each field */
	//$hook = apply_filters("thunder_field_filter", $key, $user_id);
	//$res .= $hook;
	
	//$res .= "<div class='thunder-clear'></div>";
	//$res .= "</div>";
	//$res .= "</div>";
	//$res .= "<div class='thunder-clear'></div>";
	}		
	return $res;
}


add_action('wp_ajax_thunder_preview_group', 'thunder_preview_group');
	function thunder_preview_group(){
		if ( ! current_user_can( 'manage_options' ) ) {
			die(); // admin priv
		}
			
		extract($_POST);
		$defaults = apply_filters( 'thunder_admin_shortcode_args', array(
		'allow_section'						=> 1,
		'tpl' 								=> null,
		'url'								=> get_permalink($post->ID),
		'layout'							=>'default',
		'field_icons'						=> thunder_get_option('field_icons'),

		'login_header' 						=> __('Login','thunder'),
		'login_passremember'				=> __('Forgot your password?','thunder'),
		'login_passremember_action'			=> 'forgot',		
		'login_button_action'				=> 'register',
		'login_button_primary'				=> __('Login','thunder'),
		'login_button_secondary'			=> __('Create an Account','thunder'),
		'login_group'						=> 'default',
		'login_redirect'					=> '',

		'forgot_heading'						=> __('Reset Password','thunder'),
		'forgot_side'						=> __('Back to Login','thunder'),
		'forgot_side_action'					=> 'login',
		'forgot_button_action'				=> 'change',
		'forgot_button_primary'				=> __('Request Secret Key','thunder'),
		'forgot_button_secondary'			=> __('Change your Password','thunder'),
		'forgot_group'						=> 'default',
		) );

		$args = $defaults;
		$output = '';		
		$array = get_option( 'thunder_fields_groups' );
		$groups = $array[$_POST['name']][$_POST['templ']];
		
		$skins = get_option('thunder_fields_styles');
		$user_id = get_current_user_id();

		$googlestyle = '<link rel="stylesheet" id="thunder-style-googlefont" href="https://fonts.googleapis.com/css?family=';
		$googlflag = array();		
		$stylesheet = '<style type="text/css" id="thunder-css">';
		
		foreach ( $skins[$name] as $tag => $styles ) { //thunder-body			
			if ( preg_match('/0/', $tag ) ) {
				$tag = preg_replace( '/0/', ' .', $tag );//style
				$tag = $tag;
			} 
			if ( preg_match( '/_/', $tag ) ) {
				$tag = preg_replace( '/_/', ' ', $tag );//tag
				$tag = $tag;
			}
			if ( preg_match( '/9/', $tag ) ) {
				$tag = preg_replace( '/9/', ':', $tag );//:hover
				$tag = $tag;
			}
			if ( preg_match( '/8/', $tag ) ) {
				$tag = preg_replace( '/8/', '', $tag);//[type="text"] [type="password"]
				$tag = $tag . ' input[type="text"], ' . '.' . $name . '-'.$tag . ' input[type="password"]';
			}						
			$stylesheet .= '.' . $name . '-' . $tag . '{';//.login-thunder-body
					
			foreach ( $styles as $csskey => $value ) {
				if( $csskey != 'font-weight' && $csskey != 'z-index' && $csskey != 'opacity' && $csskey != 'transition' && is_numeric( $value ) ) {
					$value = $value . 'px';
				}
				if( $csskey == 'font-family' && ( ! in_array( $value, $googlflag ) ) ) {					
					if ( ! empty($googlflag) ) {
						$googlestyle .= '|';
					}
					$googlflag[] = $value;
					$goval = $value;
					$newval = str_replace( " ", "+", $goval );
					$googlestyle .= $newval;
				}				
				if ( is_array( $value ) ) {
					if ( $csskey == 'box-shadow' ) {
						$boxcharr = null;
						foreach ( $value as $key => $val ) {
							if (  is_numeric( $val ) ) {
								$boxcharr .= ' ' . $val . 'px';
							} elseif (  $val != 'outset' ) {
								$boxcharr .= ' ' . $val;
							} else {
								continue;
							}						
						}
						$stylesheet .= $csskey . ':' . $boxcharr . '; ';
						$stylesheet .= '-moz-' . $csskey . ':' . $boxcharr . '; ';
						$stylesheet .= '-webkit-' . $csskey . ':' . $boxcharr . '; ';												
					} else {

						$stylesheet .= $csskey . ':';
						foreach ($value as $key => $val) {
							if (  ($csskey != 'background-image' ) && ( $csskey != 'width' && $csskey != 'height' && is_numeric( $val ) ) ) {
								$stylesheet .= ' ' . $val . 'px';
							} elseif ( $csskey == 'width' || $csskey == 'height' ) {
								$stylesheet .= $val;
							} elseif ( $csskey == 'background-image' ) {
								if ( $key == 'url' ) {
									$stylesheet .= $key . '(' . $val . ')';
								} else {
									$stylesheet .= $val;
								}
							} else {
								$stylesheet .= ' ' . $val;
							}						
						}
						$stylesheet .= '; ';
					}
					
				} else {
					if ( $csskey == 'box-sizing' ) {
							$stylesheet .= '-webkit-' . $csskey . ': ' . $value . '; ';
							$stylesheet .= $csskey . ': ' . $value . '; ';

					} elseif ( $csskey == 'transition' ) {
						$stylesheet .= $csskey . ': ' . $value . '; ';
						$stylesheet .= '-moz-' . $csskey . ': ' . $value . '; ';
						$stylesheet .= '-webkit-' . $csskey . ': ' . $value . '; ';
						$stylesheet .= '-o-' . $csskey . ': ' . $value . '; ';
						$stylesheet .= '-ms-' . $csskey . ': ' . $value . '; ';						
					} else {
						$stylesheet .= $csskey . ': ' . $value . '; ';
					}					
				}

			}
			$stylesheet .= '}';
		}

		$googlestyle .=	'" type="text/css" media="all" />';

		if (empty( $googlflag ) ) {
			$googlflag = null;
		}
		$stylesheet .= '</style>';
		//$stylesheet .= '</p>';		
		
		//$output .= '<p>'.$stylesheet.'</p>';
		$output .= '<div class="backlayout"><div class="puzzle"><span>' . __('Background and Layout','thunder') . '</span></div></div>';

		$output .= '<div class="' . $name . '-thunder-body">';
						$output .= '<form action="" method="post" data-name="' . $name . '" data-templ="' . $templ . '">';							
			$output .= '<div class="thunder-grid">';
				$output .= '<div class="thunder-row">';		
					$output .= '<div class="thead hoverview" data-zone="thead">';
						foreach ( $groups['thead'] as $key => $array ) {		 
						 	$output .= thunder_view_field( $key, $array, $master_id = 1, $user_id, $args );
						}
					$output .= '</div>';//end head
				$output .= '</div>';//end row

				$output .= '<div class="thunder-row">';
					$output .= '<div class="tcontent hoverview" data-zone="tcontent">';
							foreach ( $groups['tcontent'] as $key => $array ) {							 	
								$output .= thunder_view_field( $key, $array, $master_id = 1, $user_id, $args );			
							} 
					$output .= '</div>';//end content
				$output .= '</div>';//end row

				$output .= '<div class="thunder-row">';
					$output .= '<div class="tbottom hoverview" data-zone="tbottom">';
						foreach ( $groups['tbottom'] as $key => $array ) {							 	
							$output .= thunder_view_field( $key, $array, $master_id = 1, $user_id, $args );			
						} 		
					$output .= '</div>';//end bottom
				$output .= '</div>';//end row
	
			$output .= '</div>';//end grid
				$output .= '</form>';		
		$output .= '</div>';//end thunder-body
		$output = json_encode( array ('body' => $output,'css' => $stylesheet,'googlestyle' => $googlestyle));

		echo $output;
		die();
	}


	function th_get_option( $name, $zone, $option, $direction='', $default = '' ) {

        $field = get_option( 'thunder_fields_styles' );

        if ( isset( $field[$name][$zone][$option][$direction] ) ) {

            return $field[$name][$zone][$option][$direction];

        } elseif ( isset( $field[$name][$zone][$option] ) ) {

        	return $field[$name][$zone][$option];

        } else {

        	return $default;

        }
       // return $field['login']['thunder-body*head']['padding:']['top'];
    }

    function th_get_style_option( $name, $zone, $option ) {
        $field = get_option( 'thunder_fields_styles' );

        if ( isset( $field[$name][$zone][$option] ) ) {

        	return true;
        }
        return false;
    }

    function th_get_theme( $theme, $style, $default = '' ) {
    	$field = get_option( 'thunder_fields_styles' );
    	if ( isset( $field[$theme][$style] ) ) {

    		return $field[$theme][$style];

    	}
    	return $default;
    }




				//'name'		=>  array('.thunder-field' => '0thunder-field', '.thunder-field h1' => '0thunder-field_h1', '.thunder-field h1 a' => '0thunder-field_h1_a'),
				//	'register' => array(	'name'		=>  array('' => '', 'h1' => '_h1', 'h1 a' => '_h1_a'),
    function stylelist () {
    	//empty for .thunder-field class
    	// '' => '',  thunder-fiel
    	$groups = array( 				
			'first_name' =>  array(	
				'name'		=>  array(					 
					'thunder-label' 			=> '0thunder-label',
					'thunder-label label' 		=> '0thunder-label0label',
					'thunder-input' 			=> '0thunder-input',
					'thunder-input input' 		=> '0thunder-8',
					'thunder-input input:hover' => '0thunder-input_input9hover',
					'thunder-input input:focus' => '0thunder-input_input9focus',
					'thunder-input select'		=> '0thunder-input_select',
					'thunder-input textarea'	=> '0thunder-input_textarea',
				),
			),
			'last_name' => array(	
				'name'		=>  array(					 
					'thunder-label' 			=> '0thunder-label',
					'thunder-label label' 		=> '0thunder-label0label',
					'thunder-input' 			=> '0thunder-input',
					'thunder-input input' 		=> '0thunder-8',
					'thunder-input input:hover' => '0thunder-input_input9hover',
					'thunder-input input:focus' => '0thunder-input_input9focus',
					'thunder-input select' 		=> '0thunder-input_select',
					'thunder-input textarea' 	=> '0thunder-input_textarea',
				),
			),	
			'display_name' => array(	
				'name'		=>  array(					 
					'thunder-label'				=> '0thunder-label',
					'thunder-label label' 		=> '0thunder-label0label',
					'thunder-input' 			=> '0thunder-input',
					'thunder-input input' 		=> '0thunder-8',
					'thunder-input input:hover' => '0thunder-input_input9hover',
					'thunder-input input:focus' => '0thunder-input_input9focus',
					'thunder-input select'		=> '0thunder-input_select',
					'thunder-input textarea' 	=> '0thunder-input_textarea',
				),
			),
			'user_login' => array(	
				'name'		=>  array(					 
					'thunder-label' 			=> '0thunder-label',
					'thunder-label label' 		=> '0thunder-label0label',
					'thunder-input'				=> '0thunder-input',
					'thunder-input input' 		=> '0thunder-8',
					'thunder-input input:hover' => '0thunder-input_input9hover',
					'thunder-input input:focus' => '0thunder-input_input9focus',
					'thunder-input select'		=> '0thunder-input_select',
					'thunder-input textarea' 	=> '0thunder-input_textarea',
				),
			),	
			'user-email' => array(	
				'name'		=>  array(					 
					'thunder-label' 			=> '0thunder-label',
					'thunder-label label' 		=> '0thunder-label0label',
					'thunder-input' 			=> '0thunder-input',
					'thunder-input input' 		=> '0thunder-8',
					'thunder-input input:hover' => '0thunder-input_input9hover',
					'thunder-input input:focus' => '0thunder-input_input9focus',
					'thunder-input select' 		=> '0thunder-input_select',
					'thunder-input textarea' 	=> '0thunder-input_textarea',
				),
			),	
			'username_or_email' => array(	
				'name'		=>  array(					 
					'thunder-label' 			=> '0thunder-label',
					'thunder-label label' 		=> '0thunder-label0label',
					'thunder-input' 			=> '0thunder-input',
					'thunder-input input'		=> '0thunder-8',
					'thunder-input input:hover' => '0thunder-input_input9hover',
					'thunder-input input:focus' => '0thunder-input_input9focus',
					'thunder-input select' 		=> '0thunder-input_select',
					'thunder-input textarea'	=> '0thunder-input_textarea',
				),
			),	
			'user_password' => array(	
				'name'		=>  array(					
					'thunder-label' 			=> '0thunder-label',
					'thunder-label label' 		=> '0thunder-label0label',
					'thunder-input' 			=> '0thunder-input',
					'thunder-input input'		=> '0thunder-8',
					'thunder-input input:hover' => '0thunder-input_input9hover',
					'thunder-input input:focus' => '0thunder-input_input9focus',
					'thunder-input select' 		=> '0thunder-input_select',
					'thunder-input textarea'	=> '0thunder-input_textarea',
				),				
			),
			'user_password_confirm' => array(	
				'name'		=>  array(					 
					'thunder-label' 			=> '0thunder-label',
					'thunder-label label' 		=> '0thunder-label0label',
					'thunder-input' 			=> '0thunder-input',
					'thunder-input input' 		=> '0thunder-8',
					'thunder-input input:hover' => '0thunder-input_input9hover',
					'thunder-input input:focus' => '0thunder-input_input9focus',
					'thunder-input select'		=> '0thunder-input_select',
					'thunder-input textarea' 	=> '0thunder-input_textarea',
				),
			),
			'passwordstrength' => array(	
				'name'		=>  array(
					'thunder-input password-description' => '0thunder-input0password-description',							
				),
			),		
			'country' => array(	
				'name'		=>  array(					 
					'thunder-label'				=> '0thunder-label',
					'thunder-label label'		=> '0thunder-label0label',
					'thunder-input' 			=> '0thunder-input',
					'thunder-input input' 		=> '0thunder-input8', //должно работать
					'thunder-input input:hover' => '0thunder-input_input9hover',
					'thunder-input input:focus' => '0thunder-input_input9focus',
					'thunder-input select' 		=> '0thunder-input_select',
					'thunder-input textarea' 	=> '0thunder-input_textarea',					
				),
			),
			'role' => array(	
				'name'		=>  array(					 
					'thunder-label' 			=> '0thunder-label',
					'thunder-label label' 		=> '0thunder-label0label',
					'thunder-input' 			=> '0thunder-input',
					'thunder-input input'		=> '0thunder-8',
					'thunder-input input:hover' => '0thunder-input_input9hover',
					'thunder-input input:focus' => '0thunder-input_input9focus',
					'thunder-input select' 		=> '0thunder-input_select',
					'thunder-input textarea' 	=> '0thunder-input_textarea',					
				),
			),
			'gender' => array(
				'name'		=>  array(
					'thunder-input thunder-radio' => '0thunder-input0thunder-radio',																				
				),
			),			
			'ava_picture' => array(	
				'name'		=>  array(					
					'.thunder-input .thunder-pic img' 			 => '0thunder-input0thunder-pic_img',
					'.thunder-input .thunder-pic img:hover'		 => '0thunder-input0thunder-pic_img9hover',					
					'.thunder-input .thunder-pic-uploaded' 		 => '0thunder-input0thunder-pic-uploaded',
					'.thunder-input .thunder-pic-uploaded:hover' => '0thunder-input0thunder-pic-uploaded9hover',
					'.thunder-input .ava-remove'				 => '0thunder-input0ava-remove',
					'.thunder-input .ava-remove:hover' 			 => '0thunder-input0thunder-button9hover',
				),
			),
			'file' => array(	
				'name'		=>  array(
					'.thunder-input .thunder-pic-none' 		   => '0thunder-input0thunder-pic-none',
					'.thunder-input .thunder-pic-upload' 	   => '0thunder-input0thunder-pic-upload',	
					'.thunder-input .thunder-pic-upload:hover' => '0thunder-input0thunder-pic-upload9hover',
					'.thunder-input .file-remove' 			   => '0thunder-input0file-remove',
					'.thunder-input .file-remove:hover'		   => '0thunder-input0thunder-button9hover',
				),
			),
			'logo_img' => array(								
				'name'		=>  array( '' => '', 'h1' => '_h1', 'h1 a' => '_h1_a' ),													
			),
			'user_submit' => array(
				'name'		=>  array(
					'thunder-login-sub'		  => '0thunder-login-sub',
					'thunder-login-sub:hover' => '0thunder-login-sub9hover',					
				),
			),	
			'fields_trigger' => array(
				'name'		=>  array(
					'fields-trigger' 	   => '0fields-trigger',
					'fields-trigger:hover' => '0fields-trigger9hover',					
				),
			),
			'user_lost' => array(
				'name'		=>  array(
					'thunder-passremember' => '0thunder-passremember'										
				),
			),			
			'form_name' => array(	
				'name'		=>  array(
					'h2' => '_h2'
				),
			),	
		);
		return $groups;	
    }

add_action('wp_ajax_thunder_style_preview_group', 'thunder_style_preview_group');
	function thunder_style_preview_group() {
		extract( $_POST );		

		$styler = stylelist();//parse fields
		$flaggroup = array();

		$fieldarr = get_option( 'thunder_fields' );
		$array = get_option( 'thunder_fields_groups' );
		$groups = $array[$_POST['name']][$_POST['templ']];
		
		$output = '';
		$output =	'<div class="form-table">';		
		$output .= '<span class="list_close_style" data-name="' . $name . '">' . __('Close','thunder') . '</span>';
		$output .= '<span class="th-button-primary" data-name="' . $name . '">' .  __('Save and Close', 'thunder') . '</span>';
		
		$output .= '<form method="post">';
		$output .= '<table class="table-left">';	
		$output .= 	'<thead>';
		$output .= '<tr>';
		$output .= '<th>' . __('Enable/Delete', 'thunder') . '</th>';//head
		$output .= '<th>' . __('CSS name:', 'thunder') . '</th>';
		$output .= '<th>' . __('Field name:', 'thunder');
		if ( $zone == 'thunder-body') {
			$output .= '<span class="description" style="display:block;"> .' . $name . '-thunder-body</span>';
		} else {
			$output .= '<span class="description" style="display:block;"> .' . $name . '-thunder-body .' . $zone . '</span>';
		};		
		$output .= '</th>';
		$output .= '<th class="hidem"></th><th class="hidem"></th>';
		$output .= '<th class="hidem-toogle"><a href="#" class="switch-field"></a></th>';
		$output .= '</tr>';
		$output .= 	'</thead>';
		$output .= '<tbody>';	
		$output .= parseopt( $name, $zone, $tag=null, $disabled );
		$output .= '</tbody>';		
		$output .= 	'</table>';

		foreach ($groups[$zone] as $k => $option) {//$k == logo_img
			foreach ($styler[$k] as $key => $tagzone) { //styler[logo_img] login

				if ( ! in_array( $fieldarr[$k]['flaggroup'], $flaggroup ) ) { //check if this flaggroup of field exist and continue

					$flaggroup[] = $fieldarr[$k]['flaggroup'];
					foreach ( $tagzone as $css => $tag ) {//h1  further  a		
						if ( $groups[$zone][$k]['row'] == 'newrow' ) {
							$field = 'thunder-field';
							$tag = '0thunder-field' . $tag;
						} else {
							$field = 'thunder-field-col';
							$tag = '0thunder-field-col' . $tag;
						}

						$output .= '<table class="table-left">';
						foreach ($flaggroup as $val ) {	
							$output .= 	'<div class="my">' . $val . '</div>';
						}
						$output .= '<thead>';
						$output .= '<th>' . __('Enable/Delete', 'thunder') . '</th>';
						$output .= '<th>' . __('CSS name:', 'thunder') . '</th>';
						$output .= '<th>' . __('Element style: ', 'thunder') . $fieldarr[$k]['flaggroup'];;
						if ( $tag ) {
							$output .= '<span class="description" style="display:block;"> .' . $name . '-thunder-body .' . $zone . ' .' . $field . ' ' . $css . '</span>';
						}
						$output .= '</th>';
						$output .= '<th class="hidem"></th><th class="hidem"></th>';
						$output .= '<th class="hidem-toogle"><a href="#" class="switch-field"></a></th>';	
						$output .= 	'</thead>';
						$output .= '<tbody>';				
						$output .= parseopt( $name, $zone, $tag, $disabled );
						$output .= '</tbody>';
						$output .= 	'</table>';
					}
				};
			};
		};

		if ( $zone == 'thunder-body' ) {
			$output .= '<table class="table-right">';
			$output .= '<thead>';			
			$output .= '<tr>';
			$output .= '<th>' . __('Custom Templates', 'thunder') . '</th>';
			$output .= '<th>'. __('Select Layout:', 'thunder') . '</th>';
			$output .= '</tr>';
			$output .= '</thead>';
			$output .= '<tbody>';
			$output .= '<tr>';
			$num = 'default';
			$check =  esc_attr( th_get_theme( 'theme','style' ) );
			$res = checked( $check,'default',false );
			$output .= '<th class="imager">' . __('Default:', 'thunder') . '</th>';
			$output .= '<td class="imager">' . sprintf( '<input type="radio" class="layout-style"  name="&style" value="%1$s" %2$s/>', $num, $res) . '</td>';
			$output .= '</tr>';

			$output .= '<tr>';
			$num = 'layout1';			
			$res = checked( $check, 'layout1', false );
			$output .= '<th class="imager">Layout 1:</th>';
			$output .= '<td class="imager">' . sprintf( '<p><input type="radio" class="layout-style"  name="&style" value="%1$s" %2$s/></p>', $num, $res) . '
			<img src="https://placehold.it/350"/>
			</td>';
			$output .= '</tr>';	
			$output .= '</tbody>';
			$output .= '</table>';			
		};
		$output .= '</form>';
		$output .=	'</div>'; //end form
		$output = json_encode($output);	
		echo $output;
		die();
	}

	function parseopt( $name, $zone=null, $tag=null, $disabled=null ) {
		$res = null;

		if ( $zone == 'thunder-body' ) {
			$arraystyle = $zone;
			$zone = '&' . $zone;
		} elseif ( $zone == 'thead' || $zone == 'tcontent' || $zone == 'tbottom' ) {	
			//0 - style
			//_ - tag
			//$arraystyle = 'thunder-body0'.$zone.''.$tag; //for database check
			//$zone = '&thunder-body0'.$zone.''.$tag;
			$arraystyle = 'thunder-body0' . $zone . '' . $tag; //for database check
			$zone = '&thunder-body0' . $zone . '' . $tag;			
		} else {
			$arraystyle = 'thunder-body0' . $name;
			$zone = '&thunder-body0' . $name;
		};

		$on = '&build';		
		$res .= '<tr style="display: none;"><th>' . sprintf( '<input type="hidden" class="style-build"  name="%1$s%2$s%3$s" value="1"/>', $name, $zone, $on ) . '</th></tr>';

///////////////////////////////////////

		$css = '&position';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'position' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};	
		$res .= '<tr>';	
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-position-check-%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>position</th>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'position', '', 'relative' ) );		
		$res .= '<td class="style-off"  colspan="4">' . sprintf( '<select class="position-check %1$s-position-check-%5$s"  name="%1$s%2$s%3$s" %4$s>', $name, $zone, $css, $disabled, $tag  );//.'</td>';
		$res .= '<option value="relative" ' . selected( 'relative', $number, false ) . '>relative</option>';
		$res .= '<option value="absolute" ' . selected( 'absolute', $number, false ) . '>absolute</option>';
		$res .= '<option value="fixed" ' . selected( 'fixed', $number, false ) . '>fixed</option>';
		$res .= '<option value="static" ' . selected( 'static', $number, false ) . '>static</option>';
		$res .= '<option value="inherit" ' . selected( 'inherit', $number, false ) . '>inherit</option>';		
		$res .= '</select></td>';
		$res .= '</tr>';



		$css = '&display';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'display' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};	
		$res .= '<tr>';	
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-display-check-%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>display</th>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'display', '', 'block' ) );		
		$res .= '<td class="style-off"  colspan="4">' . sprintf( '<select class="display-check %1$s-display-check-%5$s"  name="%1$s%2$s%3$s" %4$s>', $name, $zone, $css, $disabled, $tag  );
		$res .= '<option value="block" ' . selected( 'block', $number, false ) . '>block</option>';
		$res .= '<option value="inline" ' . selected( 'inline', $number, false ) . '>inline</option>';
		$res .= '<option value="inline-block" ' . selected( 'inline-block', $number, false ) . '>inline-block</option>';
		$res .= '<option value="none" ' . selected( 'none', $number, false ) . '>none</option>';
		$res .= '<option value="inline-table" ' . selected( 'inline-table', $number, false ) . '>inline-table</option>';
		$res .= '<option value="inline-flex" ' . selected( 'inline-flex', $number, false ) . '>inline-flex</option>';
		$res .= '<option value="flex" ' . selected( 'flex', $number, false ) . '>flex</option>';
		$res .= '<option value="list-item" ' . selected( 'list-item', $number, false ) . '>list-item</option>';
		$res .= '<option value="run-in" ' . selected( 'run-in', $number, false ) . '>run-in</option>';
		$res .= '<option value="table" ' . selected( 'table', $number, false ) . '>table</option>';
		$res .= '<option value="table-caption" ' . selected( 'table-caption', $number, false ) . '>table-caption</option>';
		$res .= '<option value="list-item" ' . selected( 'list-item', $number, false ) . '>list-item</option>';
		$res .= '<option value="table-cell" ' . selected( 'table-cell', $number, false ) . '>table-cell</option>';
		$res .= '<option value="table-column" ' . selected( 'table-column', $number, false ) . '>table-column</option>';
		$res .= '<option value="table-column-group" ' . selected( 'table-column-group', $number, false ) . '>table-column-group</option>';
		$res .= '<option value="table-footer-group" ' . selected( 'table-footer-group', $number, false ) . '>table-footer-group</option>';
		$res .= '<option value="table-header-group" ' . selected( 'table-header-group', $number, false ) . '>table-header-group</option>';
		$res .= '<option value="table-row" ' . selected( 'table-row', $number, false ) . '>table-row</option>';
		$res .= '<option value="table-row-group" ' . selected( 'table-row-group', $number, false ) . '>table-row-group</option>';					
		$res .= '</select></td>';
		$res .= '</tr>';



		$css = '&background';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'background' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'background', '', 'none' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-background-%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';		
		$res .= '<th>background</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="%1$s-background-%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span class="description" style="display:block;"> ' . __('Cusom style for background', 'thunder') . '</span></td>';
		$res .= '</tr>';
		


		$css = '&background-image';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'background-image' ) );
		if ($check) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'background-image', 'url', '#455' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-background-image-%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';		
		$res .= '<th>background-image</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="%1$s-background-image-%6$s"  name="%1$s%2$s%3$s&url" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<input  class="img-back button" type="button" value="Upload"/><span class="description" style="display:block;"> ' . __('Upload or Select Image', 'thunder') . '</span></td>';
		$res .= '</tr>';



		$css = '&background-color';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'background-color' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'background-color', '', '#fcfcfc' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked($check,1,false) . ' data-trigger="' . sprintf( '%1$s-background-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';		
		$res .= '<th>background color</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="thunder-color-picker %1$s-background-number%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '</td>';
		$res .= '</tr>';



   		$css = '&border';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'border' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};		
		$number = esc_attr( th_get_option( $name, $arraystyle, 'border', 'num', '5' ) );
		$res .= '<tr>';	
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked($check,1,false) . ' data-trigger="' . sprintf( '%1$s-border-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>border</th>';		
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="border-number %1$s-border-number%6$s"  name="%1$s%2$s%3$s&num" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag  ) . '<span>px</span></td>';

		$number = esc_attr( th_get_option( $name, $arraystyle, 'border', 'style', 'none' ) );		
		$res .= '<td class="style-off">' . sprintf( '<select class="border-number-check %1$s-border-number%5$s"  name="%1$s%2$s%3$s&style" %4$s>', $name, $zone, $css, $disabled, $tag  );
		$res .= '<option value="none" ' . selected( 'none', $number, false ) . '>none</option>';
		$res .= '<option value="dashed" ' . selected( 'dashed', $number, false ) . '>dashed</option>';
		$res .= '<option value="dotted" ' . selected( 'dotted', $number, false ) . '>dotted</option>';
		$res .= '<option value="double" ' . selected( 'double', $number, false ) . '>double</option>';
		$res .= '<option value="solid" ' . selected( 'solid', $number, false ) . '>solid</option>';
		$res .= '<option value="groove" ' . selected( 'groove', $number, false ) . '>groove</option>';
		$res .= '<option value="ridge" ' . selected( 'ridge', $number, false ) . '>ridge</option>';
		$res .= '<option value="inset" ' . selected( 'inset', $number, false ) . '>inset</option>';
		$res .= '<option value="outset" ' . selected( 'outset', $number, false ) . '>outset</option>';
		$res .= '</select></td>';

		$number = esc_attr( th_get_option( $name, $arraystyle, 'border', 'color', '#fcfcfc' ) );		
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="thunder-color-picker %1$s-border-number%6$s"  name="%1$s%2$s%3$s&color" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '</td>';
		$res .= '</tr>';

		

   		$css = '&border-top';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'border-top' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};  		
		$number = esc_attr( th_get_option( $name, $arraystyle, 'border-top', 'num', '5' ) );	
		$res .= '<tr>';	
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-border-top-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>border-top</th>';
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="border-top-number %1$s-border-top-number%6$s"  name="%1$s%2$s%3$s&num" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag  ) . '<span>px</span></td>';

		$number = esc_attr( th_get_option( $name, $arraystyle, 'border-top', 'style', 'solid' ) );		
		$res .= '<td class="style-off">' . sprintf( '<select class="border-top-number-check %1$s-border-top-number%5$s"  name="%1$s%2$s%3$s&style" %4$s>', $name, $zone, $css, $disabled, $tag  );
		$res .= '<option value="dashed" ' . selected( 'dashed', $number, false ) . '>dashed</option>';
		$res .= '<option value="dotted" ' . selected( 'dotted', $number, false ) . '>dotted</option>';
		$res .= '<option value="double" ' . selected( 'double', $number, false ) . '>double</option>';
		$res .= '<option value="solid" ' . selected( 'solid', $number, false ) . '>solid</option>';
		$res .= '<option value="groove" ' . selected( 'groove', $number, false ) . '>groove</option>';
		$res .= '<option value="ridge" ' . selected( 'ridge', $number, false ) . '>ridge</option>';
		$res .= '<option value="inset" ' . selected( 'inset', $number, false ) . '>inset</option>';
		$res .= '<option value="outset" ' . selected( 'outset', $number, false ) . '>outset</option>';
		$res .= '</select></td>';

		$number = esc_attr( th_get_option( $name, $arraystyle, 'border-top', 'color', '#fcfcfc' ) );
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="thunder-color-picker %1$s-border-top-number%6$s"  name="%1$s%2$s%3$s&color" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '</td>';
		$res .= '</tr>';
		

   		$css = '&border-bottom';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'border-bottom' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};  		
		$number = esc_attr( th_get_option( $name, $arraystyle, 'border-bottom', 'num', '5' ) );	
		$res .= '<tr>';	
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-border-bottom-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>border-bottom</th>';
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="border-bottom-number %1$s-border-bottom-number%6$s"  name="%1$s%2$s%3$s&num" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag  ) . '<span>px</span></td>';

		$number = esc_attr( th_get_option( $name, $arraystyle, 'border-bottom', 'style', 'solid' ) );		
		$res .= '<td class="style-off">' . sprintf( '<select class="border-bottom-number-check %1$s-border-bottom-number%5$s"  name="%1$s%2$s%3$s&style" %4$s>', $name, $zone, $css, $disabled, $tag  );
		$res .= '<option value="dashed" ' . selected( 'dashed', $number, false ) . '>dashed</option>';
		$res .= '<option value="dotted" ' . selected( 'dotted', $number, false ) . '>dotted</option>';
		$res .= '<option value="double" ' . selected( 'double', $number, false ) . '>double</option>';
		$res .= '<option value="solid" ' . selected( 'solid', $number, false ) . '>solid</option>';
		$res .= '<option value="groove" ' . selected( 'groove', $number, false ) . '>groove</option>';
		$res .= '<option value="ridge" ' . selected( 'ridge', $number, false ) . '>ridge</option>';
		$res .= '<option value="inset" ' . selected( 'inset', $number, false ) . '>inset</option>';
		$res .= '<option value="outset" ' . selected( 'outset', $number, false ) . '>outset</option>';
		$res .= '</select></td>';

		$number = esc_attr( th_get_option( $name, $arraystyle, 'border-bottom', 'color', '#fcfcfc' ) );
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="thunder-color-picker %1$s-border-bottom-number%6$s"  name="%1$s%2$s%3$s&color" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '</td>';
		$res .= '</tr>';


		$css = '&border-radius';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'border-radius' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$res .= '<tr>';	
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-border-radius%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';		
		$number = esc_attr( th_get_option( $name, $arraystyle, 'border-radius', '', '3' ) );					
		$res .= '<th>border-radius</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="border-number %1$s-border-radius%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag  ) . '<span>px</span></td>';
		$res .= '</tr>';		



		$css = '&box-sizing';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'box-sizing' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};	
		$res .= '<tr>';	
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-box-sizing-check-%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>box-sizing</th>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'box-sizing', '', 'border-box' ) );		
		$res .= '<td class="style-off"  colspan="4">' . sprintf( '<select class="box-sizing-check %1$s-box-sizing-check-%5$s"  name="%1$s%2$s%3$s" %4$s>', $name, $zone, $css, $disabled, $tag  );
		$res .= '<option value="border-box" '.selected( 'border-box', $number, false ) . '>border-box</option>';
		$res .= '<option value="content-box" '.selected( 'content-box', $number, false ) . '>content-box</option>';		
		$res .= '</select></td>';
		$res .= '</tr>';



		$css = '&box-shadow';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'box-shadow' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$res .= '<tr>';			
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-box-shadow-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>box-shadow</th>';	
		$res .= '<tr">';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'box-shadow', 'offset', 'inset' ) );
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<select class="%1$s-box-shadow-number%5$s"  name="%1$s%2$s%3$s&offset" %4$s>', $name, $zone, $css, $disabled, $tag );
		$res .= '<option value="inset" ' . selected( 'inset', $number, false ) . '>inset</option>';
		$res .= '<option value="outset" ' . selected( 'outset', $number, false ) . '>outset</option>';
		$res .= '</select></td>';
		$res .= '</tr>';

		if( $disabled == 'disabled' ) {
			$none = 'display: none;';
		} else {
			$none = 'display: table-row;';
		};
		$res .= '<tr class="second-off" style="' . $none . '">';	
		$number = esc_attr( th_get_option( $name, $arraystyle, 'box-shadow', 'horizontal', '0' ) );
		$res .= '<th></th>';		
		$res .= '<th></th>';		
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="box-shadow-number %1$s-box-shadow-number%6$s"  name="%1$s%2$s%3$s&horizontal" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> ' . __( 'horizontal shadow', 'thunder' ) . '</span></td>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'box-shadow', 'vertical', '0' ) );		
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="box-shadow-number %1$s-box-shadow-number%6$s"  name="%1$s%2$s%3$s&vertical" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> ' . __( 'vertical shadow', 'thunder' ) . '</span></td>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'box-shadow', 'blur', '3' ) );
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="box-shadow-number %1$s-box-shadow-number%6$s"  name="%1$s%2$s%3$s&blur" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> ' . __( 'blur distance', 'thunder' ) . '</span></td>';		
		$number = esc_attr( th_get_option( $name, $arraystyle, 'box-shadow', 'color', '#000' ) );
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="thunder-color-picker %1$s-box-shadow-number%6$s"  name="%1$s%2$s%3$s&color" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '</td>';		
		$res .= '</tr>';




		$css = '&cursor';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'cursor' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};	
		$res .= '<tr>';	
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-cursor-check-%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>cursor</th>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'cursor', '', 'default' ) );		
		$res .= '<td class="style-off"  colspan="4">' . sprintf( '<select class="cursor-check %1$s-cursor-check-%5$s"  name="%1$s%2$s%3$s" %4$s>', $name, $zone, $css, $disabled, $tag  );
		$res .= '<option value="default" ' . selected( 'default', $number, false ) . '>default</option>';
		$res .= '<option value="crosshair" ' . selected( 'crosshair', $number, false ) . '>crosshair</option>';
		$res .= '<option value="help" ' . selected( 'help', $number, false ) . '>help</option>';
		$res .= '<option value="move" ' . selected( 'move', $number, false ) . '>move</option>';
		$res .= '<option value="pointer" ' . selected( 'pointer', $number, false ) . '>pointer</option>';
		$res .= '<option value="progress" ' . selected( 'progress', $number, false ) . '>progress</option>';
		$res .= '<option value="text" ' . selected( 'text', $number, false ) . '>text</option>';
		$res .= '<option value="context-menu" ' . selected( 'context-menu', $number, false ) . '>context-menu</option>';
		$res .= '<option value="wait" ' . selected( 'wait', $number, false ) . '>wait</option>';
		$res .= '<option value="cell" ' . selected( 'cell', $number, false ) . '>cell</option>';
		$res .= '<option value="vertical-text" ' . selected( 'vertical-text', $number, false ) . '>vertical-text</option>';
		$res .= '<option value="alias" ' . selected( 'alias', $number, false ) . '>alias</option>';
		$res .= '<option value="copy" ' . selected( 'copy', $number, false ) . '>copy</option>';
		$res .= '<option value="no-drop" ' . selected( 'no-drop', $number, false ) . '>no-drop</option>';
		$res .= '<option value="not-allowed" ' . selected( 'not-allowed', $number, false ) . '>not-allowed</option>';
		$res .= '<option value="all-scroll" ' . selected( 'all-scroll', $number, false ) . '>all-scroll</option>';
		$res .= '<option value="col-resize" ' . selected( 'col-resize', $number, false ) . '>col-resize</option>';
		$res .= '<option value="row-resize" ' . selected( 'row-resize', $number, false ) . '>row-resize</option>';
		$res .= '<option value="zoom-in" ' . selected( 'zoom-in', $number, false ) . '>zoom-in</option>';
		$res .= '<option value="zoom-out" ' . selected( 'zoom-out', $number, false ) . '>zoom-out</option>';								
		$res .= '</select></td>';
		$res .= '</tr>';		



		$css = '&padding';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'padding' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$res .= '<tr>';	
		$number = esc_attr( th_get_option( $name, $arraystyle, 'padding', '', '5px 5px 5px 5px' ) );
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-padding-text%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>padding</th>';		
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="padding-text %1$s-padding-text%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span class="description" style="display:block;"> ' . __( 'write css style for padding and "px" after each number', 'thunder' ) . '</span></td>';		
		$res .= '</tr>';	
		/*$res .= '<td class="style-off">' . sprintf( '<input type="number" class="padding-number %1$s-padding-number%6$s"  name="%1$s%2$s%3$s&top" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> '.__('direction: top', 'thunder') . '</span></td>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'padding', 'right', '5' ) );
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="padding-number %1$s-padding-number%6$s"  name="%1$s%2$s%3$s&right" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> '.__('direction: right', 'thunder') . '</span></td>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'padding', 'bottom', '5' ) );
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="padding-number %1$s-padding-number%6$s"  name="%1$s%2$s%3$s&bottom" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> '.__('direction: bottom', 'thunder') . '</span></td>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'padding', 'left', '5' ) );
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="padding-number %1$s-padding-number%6$s"  name="%1$s%2$s%3$s&left" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> '.__('direction: left', 'thunder') . '</span></td>';
		$res .= '</tr>';*/



		$css = '&margin';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'margin' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$res .= '<tr>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'margin', '', '5px 5px 5px 5px' ) );
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-margin-text%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>margin</th>';		
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="margin-text %1$s-margin-text%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span class="description" style="display:block;"> ' . __( 'write css style for margin and "px" after each number', 'thunder' ) . '</span></td>';		
		$res .= '</tr>';			
		/*$number = esc_attr( th_get_option( $name, $arraystyle, 'margin', 'top', '5' ) );
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-margin-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>margin</th>';		
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="margin-number %1$s-margin-number%6$s"  name="%1$s%2$s%3$s&top" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> '.__('direction: top', 'thunder') . '</span></td>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'margin', 'right', '5' ) );
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="margin-number %1$s-margin-number%6$s"  name="%1$s%2$s%3$s&right" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> '.__('direction: right', 'thunder') . '</span></td>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'margin', 'bottom', '5' ) );
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="margin-number %1$s-margin-number%6$s"  name="%1$s%2$s%3$s&bottom" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> '.__('direction: bottom', 'thunder') . '</span></td>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'margin', 'left', '5' ) );
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="margin-number %1$s-margin-number%6$s"  name="%1$s%2$s%3$s&left" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> '.__('direction: left', 'thunder') . '</span></td>';
		$res .= '</tr>';*/



		$css = '&color';	
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'color' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		}; 
		$number = esc_attr( th_get_option( $name, $arraystyle, 'color', '', '#000' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-color-number%2$s', $name,  $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';		
		$res .= '<th>color</th>';		
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="thunder-color-picker %1$s-color-number%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '</td>';
		$res .= '</tr>';


		$css = '&font-family';	
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'font-family' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		}; 
		$number = esc_attr( th_get_option( $name, $arraystyle, 'font-family', '', 'Lato' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-font-family-number%2$s', $name,  $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';		
		$res .= '<th>font-family</th>';		
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="font-text-style %1$s-font-family-number%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '</td>';
		$res .= '</tr>';
		


		$css = '&font-size';	
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'font-size' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		}; 
		$number = esc_attr( th_get_option( $name, $arraystyle, 'font-size', '', '20' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-font-size-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';		
		$res .= '<th>font-size</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="%1$s-font-size-number%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span></td>';		
		$res .= '</tr>';



		$css = '&font-weight';	
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'font-weight' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		}; 
		$number = esc_attr( th_get_option( $name, $arraystyle, 'font-weight', '', '400' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-font-weight%2$s', $name,  $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';		
		$res .= '<th>font-weight</th>';		
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="%1$s-font-weight%6$s"  name="%1$s%2$s%3$s" step="100" min="100" max="900" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '</td>';	
		$res .= '</tr>';



		$css = '&text-shadow';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'text-shadow' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$res .= '<tr>';			
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-text-shadow-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>text-shadow</th>';	

		$number = esc_attr( th_get_option( $name, $arraystyle, 'text-shadow', 'horizontal', '0' ) );
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="text-shadow-number %1$s-text-shadow-number%6$s"  name="%1$s%2$s%3$s&horizontal" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> ' . __( 'horizontal shadow', 'thunder' ) . '</span></td>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'text-shadow', 'vertical', '0' ) );		
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="text-shadow-number %1$s-text-shadow-number%6$s"  name="%1$s%2$s%3$s&vertical" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> ' . __( 'vertical shadow', 'thunder' ) . '</span></td>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'text-shadow', 'blur', '3' ) );
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="text-shadow-number %1$s-text-shadow-number%6$s"  name="%1$s%2$s%3$s&blur" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span><span class="description" style="display:block;"> ' . __( 'blur', 'thunder' ) . '</span></td>';		
		$number = esc_attr( th_get_option( $name, $arraystyle, 'text-shadow', 'color', '#000' ) );
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="thunder-color-picker %1$s-text-shadow-number%6$s"  name="%1$s%2$s%3$s&color" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '</td>';		
		$res .= '</tr>';



		$css = '&text-align';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'text-align' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};	
		$res .= '<tr>';	
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-text-align-check-%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>text-align</th>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'text-align', '', 'center' ) );		
		$res .= '<td class="style-off"  colspan="4">' . sprintf( '<select class="text-align-check %1$s-text-align-check-%5$s"  name="%1$s%2$s%3$s" %4$s>', $name, $zone, $css, $disabled, $tag  );
		$res .= '<option value="center" ' . selected( 'center', $number, false ) . '>center</option>';
		$res .= '<option value="justify" ' . selected( 'justify', $number, false ) . '>justify</option>';
		$res .= '<option value="left" ' . selected( 'left', $number, false ) . '>left</option>';
		$res .= '<option value="right" ' . selected( 'right', $number, false ) . '>right</option>';
		$res .= '<option value="start" ' . selected( 'start', $number, false ) . '>start</option>';
		$res .= '<option value="end" ' . selected( 'end', $number, false ) . '>end</option>';		
		$res .= '</select></td>';
		$res .= '</tr>';




		$css = '&letter-spacing';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'letter-spacing' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'letter-spacing', '', '0' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-letter-spacing-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>letter-spacing</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="%1$s-letter-spacing-number%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span></td>';

		$res .= '</tr>';	



		$css = '&line-height';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'line-height' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'line-height', '', '18' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-line-height-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>line-height</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="%1$s-line-height-number%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span></td>';

		$res .= '</tr>';




		$css = '&text-decoration';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'text-decoration' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};	
		$res .= '<tr>';	
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-text-decoration-check-%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>text-decoration</th>';
		$number = esc_attr( th_get_option( $name, $arraystyle, 'text-decoration', '', 'none' ) );		
		$res .= '<td class="style-off"  colspan="4">' . sprintf( '<select class="text-decoration-check %1$s-text-decoration-check-%5$s"  name="%1$s%2$s%3$s" %4$s>', $name, $zone, $css, $disabled, $tag  );
		$res .= '<option value="none" ' . selected( 'none', $number, false ) . '>none</option>';
		$res .= '<option value="underline" ' . selected( 'underline', $number, false ) . '>underline</option>';
		$res .= '<option value="overline" ' . selected( 'overline', $number, false ) . '>overline</option>';
		$res .= '<option value="line-through" ' . selected( 'line-through', $number, false ) . '>line-through</option>';
		$res .= '<option value="initial" ' . selected( 'initial', $number, false ) . '>initial</option>';		
		$res .= '</select></td>';
		$res .= '</tr>';



		$css = '&text-indent';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'text-indent' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'text-indent', '', '0' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-text-indent-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>text-indent</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="%1$s-text-indent-number%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span></td>';

		$res .= '</tr>';




		$css = '&width';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'width' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'width', 'num', '50' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-width-number-%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>width</th>';
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="%1$s-width-number-%6$s"  name="%1$s%2$s%3$s&num" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span class="description" style="display:block;"> ' . __( 'type number', 'thunder' ) . '</span></td>';

		$number = esc_attr( th_get_option( $name, $arraystyle, 'width', 'item', 'px' ) );		
		$res .= '<td class="style-off"  colspan="4">' . sprintf( '<select class="%1$s-width-number-%5$s"  name="%1$s%2$s%3$s&item" %4$s>', $name, $zone, $css, $disabled, $tag  );
		$res .= '<option value="px" ' . selected( 'px', $number, false ) . '>px</option>';
		$res .= '<option value="%" ' . selected( '%', $number, false ) . '>%</option>';			
		$res .= '</select><span>px</span><span class="description" style="display:block;"> ' . __( 'choose percent or px', 'thunder' ) . '</span></td>';
		$res .= '</tr>';


		$css = '&height';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'height' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'height', 'num', '50' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-height-field-%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>height</th>';
		$res .= '<td class="style-off">' . sprintf( '<input type="number" class="%1$s-height-field-%6$s"  name="%1$s%2$s%3$s&num" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span class="description" style="display:block;"> ' . __( 'type number', 'thunder' ) . '</span></td>';

		$number = esc_attr( th_get_option( $name, $arraystyle, 'height', 'item', 'px' ) );		
		$res .= '<td class="style-off"  colspan="4">' . sprintf( '<select class="%1$s-height-field-%5$s"  name="%1$s%2$s%3$s&item" %4$s>', $name, $zone, $css, $disabled, $tag );
		$res .= '<option value="px" '.selected( 'px', $number, false ) . '>px</option>';
		$res .= '<option value="%" '.selected( '%', $number, false ) . '>%</option>';			
		$res .= '</select><span>px</span><span class="description" style="display:block;"> '.__('choose percent or px', 'thunder') . '</span></td>';
		$res .= '</tr>';

		

		$css = '&top';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'top' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'top', '', '0' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-top-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>top</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="%1$s-top-number%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span></td>';
		$res .= '</tr>';

		

		$css = '&right';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'right' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'right', '', '0' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-right-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>right</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="%1$s-right-number%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span></td>';
		$res .= '</tr>';


		
		$css = '&bottom';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'bottom' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'bottom', '', '0' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-bottom-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>bottom</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="%1$s-bottom-number%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span></td>';
		$res .= '</tr>';					



		$css = '&left';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'left' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'left', '', '0' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-left-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>left</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="%1$s-left-number%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span>px</span></td>';
		$res .= '</tr>';



		$css = '&z-index';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'z-index' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'z-index', '', '0' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-z-index-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>z-index</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="%1$s-z-index-number%6$s"  name="%1$s%2$s%3$s" step="1" min="0" max="2147483647" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '</td>';
		$res .= '</tr>';



		$css = '&opacity';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'opacity' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$number = esc_attr( th_get_option( $name, $arraystyle, 'opacity', '', '0.7' ) );	
		$res .= '<tr>';
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-opacity-number%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>opacity</th>';
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="number" class="%1$s-opacity-number%6$s"  name="%1$s%2$s%3$s" step="0.1" min="0" max="1" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '</td>';
		$res .= '</tr>';


		$css = '&transition';
		$check = esc_attr( th_get_style_option( $name, $arraystyle, 'transition' ) );
		if ( $check ) { 
			$disabled = null;
		} else {
			$disabled = 'disabled';
		};
		$res .= '<tr>';	
		$number = esc_attr( th_get_option( $name, $arraystyle, 'transition', '', 'all 0.5s' ) );
		$res .= '<th><div class="switch-css-wrap"><label class="switch-css-inner"><input class="switch-css-checkbox" type="checkbox" value="1" ' . checked( $check, 1, false ) . ' data-trigger="' . sprintf( '%1$s-transition-text%2$s', $name, $tag ) . '"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></th>';
		$res .= '<th>transition</th>';		
		$res .= '<td class="style-off" colspan="4">' . sprintf( '<input type="text" class="transition-text %1$s-transition-text%6$s"  name="%1$s%2$s%3$s" value="%4$s" %5$s/>', $name, $zone, $css, $number, $disabled, $tag ) . '<span class="description" style="display:block;"> ' . __( 'write css style for transition', 'thunder' ) . '</span></td>';		
		$res .= '</tr>';


		return $res;
	}
















		/******************************************
	Delete user
	******************************************/
	function delete_user($user_id){
		if ( is_multisite()  ) {
			wpmu_delete_user( $user_id );
		} else {
			wp_delete_user( $user_id );
		}
	}




	/**
	User signup deny
	**/	
	add_action('wp_ajax_thunder_admin_user_deny', 'thunder_admin_user_deny');
	function thunder_admin_user_deny(){
		if ( ! current_user_can( 'manage_options' ) ) {
			die(); // admin priv
		};
			
		//global $thunder, $thunder_admin;
		global $Thunder_Admin;
		extract($_POST);
		$output = '';

		delete_user( $user_id );
		$output['count'] = $this->number_requests_ppl();
		
		$output = json_encode( $output );
		if( is_array( $output ) ) { 
			print_r($output); 
		} else { 
			echo $output; 
		}; 
		die();
	}
	
	/**
	User signup approve
	**/	
	add_action('wp_ajax_thunder_admin_user_approve', 'thunder_admin_user_approve');
	function thunder_admin_user_approve(){
		if ( ! current_user_can( 'manage_options' ) ) {
			die(); // admin priv
		};
			
		
		extract($_POST);
		$output = '';

	//	update_user_meta($user_id, '_account_status', 'pending_admin');
	//	update_user_meta($user_id, '_pending_pass', $user_pass);
	//	update_user_meta($user_id, '_pending_form', $form);*

		activate_user( $user_id );
		$output['count'] = $this->number_requests_ppl();
		
		$output = json_encode( $output );
		if( is_array( $output ) ) { 
			print_r($output); 
		} else { 
			echo $output; 
		}; 
		die();
	}

?>