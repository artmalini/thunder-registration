<div class="thunder-admin-wrap">
	<div class="thunder-admin-head"></div>
	<div class="thunder-admin-body"> 	
		<?php echo thunder_admin_list_group(); ?>
	</div>
	<div class="thunder-add-section">
		<ul><?php echo thunder_admin_new_section();?></ul>
	</div>
	<div class="thunder_sort_style">
		<div class="thunder_sort_style_body">
			<div class="load-spinner rotate-circle"></div>
		</div>
	</div>
	<?php 
	$array = get_option('thunder_fields_styles');
	//$array = get_option('thunder_fields_groups');	
	//print_r($array);

		 $stylesheet .= '<div class="mylol">LOL</div>';

		$stylesheet .= '<p>';
		//$stylesheet .= $array['.login'];
				//$stylesheet .= '.thunder-body .head h1 {font-weight: 800;}';



		/*Нужно изменить ['login] определяется по клику секции на форме*/
		foreach ($array['login'] as $tag => $styles) { //.thunder-body			
			if ( preg_match('/0/', $tag ) ) {
				$tag = preg_replace('/0/', ' .', $tag);//style
				//$tag = '.'.$tag;
			} 
			if ( preg_match('/_/', $tag ) ) {
				$tag = preg_replace('/_/', ' ', $tag);//tag
				$tag = $tag;
			}
			$stylesheet .= '.login-'.$tag.'{';//.login-thunder-body
			//$stylesheet .= '.login-'.$tag.'{';//.login-thunder-body			
			foreach ($styles as $csskey => $value) {
				if( $csskey != 'font-weight' && is_numeric($value)) {
					$value = $value.'px';
				}
				if ( is_array($value) ) {
					$stylesheet .= $csskey.': ';
					foreach ($value as $key => $val) {
						if (  ($csskey != 'background') && is_numeric($val) ) {
							$stylesheet .= ' '.$val.'px';
						} elseif ( ($csskey == 'background') ) {
							if ( $key == 'url') {
								$stylesheet .= ' '.$key.'('.$val.')';
							} else {
								$stylesheet .= ' '.$val;
							}
						} else {
							$stylesheet .= ' '.$val;
						}						
					}
					$stylesheet .= '; ';
				} else {
					$stylesheet .= $csskey.': '.$value.'; ';
				}

			}
			$stylesheet .= '}';
		}
		$stylesheet .= '</p>';
		echo $stylesheet;
	 ?>

</div>

<?php 
/** List one group **/
function thunder_admin_list_group() {
	$output = null;
	$array = array(
		'register' => __( 'Registration Fields', 'thunder' ),
		'login' => __( 'Login Fields', 'thunder' ),
		'edit' => __( 'Edit Profile Fields', 'thunder' ),
		'social' => __( 'Social Fields', 'thunder' ),
	);
	foreach( $array as $templ => $group ) {
		$output .= '<div class="th-admin-model th-admin-model-' . $templ . '">
			<form action="" method="post" data-name="' . $templ . '" data-templ="default">
			<div class="admin-field-menu close">
				<p>' . $group . '</p>
				<div class="fields-icon">';

				$output .= '<a href="#" class="button add-section">' . __( 'Add section', 'thunder' ) . '</a>';					
				$output .= '<a href="#" class="button add-field">' . __( 'Add field', 'thunder' ) . '</a>';
				
				if ( $templ != 'social' ) {
					$output .= '<a href="#" class="button preview">' . __( 'View Page', 'thunder' ) . '</a>';
				};
				
				$output .= '<a href="#" class="button resetgroup">' . __( 'Reset', 'thunder' ) . '</a>
					<a href="#" class="button button-save saveform">' . __( 'Save', 'thunder' ) . '</a>
					<a href="#" class="close-tab"></a>
				</div>
			</div>
			<div class="admin-field-body">
				<ul data-zone="thead" class="thead">' . thunder_admin_fields_by_group( $templ, 'default', 'thead' ) . '</ul>
				<ul data-zone="tcontent" class="tcontent">' . thunder_admin_fields_by_group( $templ, 'default', 'tcontent' ) . '</ul>
				<ul data-zone="tbottom" class="tbottom"">' . thunder_admin_fields_by_group( $templ, 'default', 'tbottom' ) . '</ul>
			</div>
			</form>
		</div>';			
	};
	return $output;
}


function thunder_admin_fields_by_group( $templ, $group, $zone ) {
	$array = get_option( 'thunder_fields_groups' );
	$output = null;
	$group = $array[$templ][$group][$zone];	

	if ( isset( $group ) && ! empty( $group ) ) {	
		foreach( $group as $k => $arr ) {
			if ( isset( $arr['section']) && $arr['section'] != '' || isset( $arr['label']) && $arr['label'] != '' ) {
				if ( isset( $arr['section'] ) ) { // seperator						
					$output .= '<li class="section">';					
					$output .= '<span>' . $arr['section'] . '</span>'; 
					$output .= '<span class="fieldkey">' . $k . '</span>';					
					$output .= '<a href="#" title="' . __( 'Delete Section', 'thunder') . '" class="section-remove"></a>';
					
					$output .= '<div class="admin-head-field-zone">';
					$output .= '<a href="#" class="admin-head-zone-cancel"></a>';						
				
				if ( ! isset($arr['collapsible'] ) ) {
					$arr['collapsible'] = 0;
				};
				if ( ! isset( $arr['collapsed'] ) ) {
					$arr['collapsed'] = 0;
				};

				foreach( $arr as $opt => $val ){
					if ( in_array( $opt, array( 'collapsible', 'collapsed' ) ) ) {
						$output .= fields_desc( $opt );
						$output .= "<select name='$zone-$k-$opt' id='$k-$opt'>
										<option value='1' " . selected( 1, $val, 0) . ">" . __( 'Yes', 'thunder') . "</option>
										<option value='0' " . selected(0, $val, 0) . ">" . __( 'No', 'thunder') . "</option>
									</select>";
					};
					if ( in_array( $opt, array( 'section' ) ) ) {
						$output .= fields_desc( $opt );
						$output .= '<input type="text" name="' . $zone . '-' . $k . '-' . $opt . '" id="' . $k . '-' . $opt . '" value="' . stripslashes( $val ) . '" />';
					};
				};

				$output .= '</div>';//end head zone	
				
				foreach( $arr as $opt => $val ) {
					if ( ! in_array( $opt, array( 'section', 'collapsible', 'collapsed' ) ) ) {
						$output .= '<input type="hidden" name="' . $zone . '-' . $k . '-' . $opt . '" id="' . $k . '-' . $opt . '" value="' . $val . '"/>';
					};
				};	
				$output .= '</li>';

				//options
				} else {
					$output .= li_fields( $arr, $k );
				};
			};
		};
	};
	return $output;
}

/**
 * @param  [array] $arr [array with fields values]
 * @param  [number] $k [current field name through iterate]
 * @return [string]    [final html string] 
 */
function li_fields ( $arr, $k ) {
	$zone = $arr['zone'];
	$samerow = __( 'Same row', 'thunder' );
	$newrow = __( 'New row', 'thunder' );

	$output .= '<li class="field">';								
					
	if ( $arr['row'] ) {
		$valkey = $arr['row'];						
		$output .= '<select name="' . $zone . '-' . $k . '-row" id="' . $k . '-row" class="rowselect">';
		$output .=	'<option value="newrow" ' . selected( 'newrow', $valkey, 0 ) . '>' . $newrow . '</option>';						
		$output .= '<option value="samerow" ' . selected( 'samerow', $valkey, 0 ) . '>' . $samerow . '</option>';
		$output .= '</select>';
	};
		
	$output .= '<span>' . $arr['label'] . '</span><p>' . $arr['type'] . '</p>';
	$output .= '<a href="#" title="' . __( 'Delete Field', 'thunder') . '" class="field-remove"></a>';
	$output .= '<a href="#" class="switch-field"></a>';	
	$output .= '<div class="admin-option-field-zone">';
	$output .= '<div class="grid">';
	$output .= '<div class="row">';

	if ( isset( $arr['type'] ) && in_array( $arr['type'], array( 'select','multiselect','checkbox','checkbox-full','radio','radio-full' ) ) ) {
		if ( ! isset( $arr['options'] ) ) {
			$arr['options'] = '';
		};
	};

	if ( is_array( $arr ) ) {
	//ksort($arr);
		foreach( $arr as $opt => $val ) {
			if ( in_array($opt, array('label', 'help', 'placeholder', 'ajaxcheck', 'icon', 'button_text', 'list_id', 'list_text') ) ) {
				$output .= '<div class="col-2 col-m1">';
				$output .= fields_desc( $opt );//span item
				$output .='<input type="text" name="' . $zone . '-' . $k . '-' . $opt .'" id="' . $k . '-' . $opt . '" value="' . stripslashes( $val ) . '" />';
				$output .= '</div>';//end col
			}
			if ( in_array($opt, array('options') ) ) {			
				if ( $val != '' && is_array( $val ) ) {
					$val = implode( "\n", $val );
				};
					$output .= '<div class="col-2 col-m1">';
					$output .= '<textarea name="' . $zone . '-' . $k . '-' . $opt .'" id="' . $k . '-' . $opt . '" cols="40" rows="2">' . stripslashes( $val ) . '</textarea>';
					$output .= '</div>';//end col
			};
		};

		$output .= '</div>';//end row
		$output .= '<div class="row">';	
		foreach( $arr as $key => $v ) {	
			if ( $key && in_array( $key, array( 'html', 'hideable', 'hidden', 'required', 'locked', 'private' ) ) ) {				
				if ( $v == 0 ) { 
					$class = 'off'; 
				} else {
				 	$class = 'on'; 
				};			

			$output .= '<div class="col-2 col-m2">';
				$output .= '<div class="switch-wrap ' . $class . '"><span>' . $key . ':</span>';
				$output .= '<div class="switch-box-wrap">';
				$output .= '<label class="switch-inner">';
				$output .= '<input type="hidden" value="0" name="' . $zone . '-' . $k . '-' . $key . '">';
				$output .= '<input class="switch-checkbox" type="checkbox" value="1" name="' . $zone . '-' . $k . '-' . $key . '"';
				$output .= checked( $v, 1,false );					
				$output .= '/>';
				$output .= '<span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label>'; 
				$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
			};

			if ( $key && in_array( $key, array( 'type' ) ) ) {
				$output .= '<div class="col-2 col-m2">';
				$output .= '<input type="hidden" value="'.$v.'" name="'.$zone.'-'.$k.'-'.$key.'" >';
				$output .= '</div>';
			};
			//zone in form (thead, tcontent, tbottom)
			if ( $key && in_array( $key, array( 'zone' ) ) ) {
				$output .= '<div class="col-2 col-m2">';
				$output .= '<input type="hidden" class="zone" value="' . $v . '" name="' . $zone . '-' . $k . '-' . $key . '" >';
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
		
//List all fields
/*function admin_list_fields(){		
	$output = null;
	$group = array();
	foreach( get_option('thunder_fields') as $key => $value ) {
		$group[$key] = $value;	
	}
	$group2 = array(); 
	foreach ( thunder_fields_group_by_template( 'edit', 'default') as $zone => $count ) {
		foreach ( $count as $key => $value ) {
			$group2[$key] = $value; 
		};
	};
	$bv = array_diff_key( $group, $group2 );
	foreach ( $bv as $k => $arr) {
		$output .= li_fields($arr,$k);
	};
	return $output;
}*/

//new section empty 
function thunder_admin_new_section(){
	$output = null;
	$zone = 'thead';
	$rand = rand( 1, 100 );
	$k = 'section' . $rand;
	$arr['section'] = __( 'My Custom section', 'thunder' );
	$arr['collapsible'] = 0;
	$arr['collapsed'] = 0;

	$output .= '<li class="section"><span>' . __( 'Add Seperator / Section', 'thunder') . '</span>';
	$output .= '<a href="#" title="'. __( 'Delete Section', 'thunder' ) . '" class="section-remove"></a>';
	$output .= '<div class="admin-thead-field-zone"><a href="#" class="upadmin-field-zone-cancel"></a>';
	foreach( $arr as $opt => $val ) {
		if ( in_array( $opt, array( 'section' ) ) ) {
			$output .= fields_desc( $opt ) . '<input type="text" name="' . $zone . '-' . $k . '-' . $opt .'" id="' . $k . '-' . $opt . '" value="' . stripslashes( $val ) . '" />';
		};
		if ( in_array( $opt, array( 'collapsible', 'collapsed' )) ) {
			$output .= fields_desc( $opt );					
			$output .= '<span class="thunder-field-zone-desc">' . $text . '</span>';
			$output .= "<select name='$zone-$k-$opt' id='$k-$opt'>
							<option value='1' " . selected( 1, $val, 0 ) . ">" . __('Yes','thunder') . "</option>
							<option value='0' " . selected( 0, $val, 0 ) . ">" . __('No','thunder') . "</option>
						</select>";
		};
	};
	$output .= '</div>';	
	$output .= '</li>';
	return $output;
}

/* Field description */
function fields_desc($opt) {
	switch ( $opt ){
		case 'label': 
			$text = __( 'Label', 'thunder' ); 
		break;
		case 'help': 
			$text = __( 'Help Text', 'thunder' );
		break;
		case 'placeholder': 
			$text = __( 'Placeholder','thunder' ); 
		break;
		case 'ajaxcheck' : 
			$text = __( 'Ajax Check Callback (advanced)', 'thunder' ); 
		break;
		case 'section': 
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
		/*case 'list_id': 
			$text = __( 'MailChimp List ID', 'thunder');
		break;
		case 'list_text': 
			$text = __( 'MailChimp Subscribe Text', 'thunder' );
		break;
		case 'icon': 
			$text = __( 'Font Icon Code', 'thunder' ); 
		break;*/
		case 'style': 
			$text = __( 'Style Code', 'thunder' ); 
		break;
	};
	return '<span class="thunder-field-zone-desc">'.$text.'</span>';
}




/*function thunder_fields_admin_by_template( $template, $group='default' ) {
	$array = get_option("thunder_fields_groups");
		if (isset($array[$template][$group]))
			if (count($array[$template][$group]) > 0)
				return (array)$array[$template][$group];
		return array('');
	}*/

/* Edit a field */
	

?>