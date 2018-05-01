<?php
if ( ! defined('BASEPATH')) { exit('No direct script access allowed'); }

if (!function_exists('gen_barcode'))
{
	function gen_barcode($string = '',$format = 'html')
	{
		$return_val = '';
		// $formats = array("html", "svg", "jpg", "png");   //TODO: issue on encoding in png & jpg needed to fix
		$formats = array("html", "svg"); //avoiding issue on encoding in png & jpg

		if((!empty($string) && is_string($string)) && in_array($format, $formats)) 
		{				
			include APPPATH.'third_party/Barcode/vendor/picqer/php-barcode-generator/src/BarcodeGenerator.php';
			include APPPATH.'third_party/Barcode/vendor/picqer/php-barcode-generator/src/BarcodeGeneratorJPG.php'; //TODO: issue on encoding
			include APPPATH.'third_party/Barcode/vendor/picqer/php-barcode-generator/src/BarcodeGeneratorPNG.php'; //TODO: issue on encoding
			include APPPATH.'third_party/Barcode/vendor/picqer/php-barcode-generator/src/BarcodeGeneratorSVG.php';
			include APPPATH.'third_party/Barcode/vendor/picqer/php-barcode-generator/src/BarcodeGeneratorHTML.php';

			$bar = '';
            if($format == 'html')
            {
                $bar = new Picqer\Barcode\BarcodeGeneratorHTML(); 
            }
            elseif($format == 'png')
            {
                $bar = new Picqer\Barcode\BarcodeGeneratorPNG();
            }
            elseif($format == 'svg')
            {
                $bar = new Picqer\Barcode\BarcodeGeneratorSVG();
            }
            elseif($format == 'jpg')
            {
                $bar = new Picqer\Barcode\BarcodeGeneratorJPG();
            }
            
			if(!empty($bar)) 
				$code = $bar->getBarcode($string, $bar::TYPE_CODE_128);

			$return_val =  '<div id="qrbox">'.$code.'</div>';
		}
		
		return $return_val;
	}
}

if (!function_exists('mb_ucwords'))
{
	function mb_ucwords($string)
	{
		return mb_convert_case($string,MB_CASE_TITLE,'UTF-8');
	}
}

if (!function_exists('mb_ucfirst'))
{
	function mb_ucfirst($string)
	{
		$strlen = mb_strlen($string, 'UTF-8');
		$firstChar = mb_substr($string, 0, 1, 'UTF-8');
		$then = mb_substr($string, 1, $strlen - 1, 'UTF-8');
		return mb_strtoupper($firstChar,'UTF-8') . $then;
	}
}

if (!function_exists('mb_ucfirst_ext'))
{
	function mb_ucfirst_ext($string, $encoding)
	{
		$strlen = mb_strlen($string, $encoding);
		$firstChar = mb_substr($string, 0, 1, $encoding);
		$then = mb_substr($string, 1, $strlen - 1, $encoding);
		return mb_strtoupper($firstChar, $encoding) . $then;
	}
}



if (!function_exists('alte_bcrumb'))
{
	function alte_bcrumb($bcrumb_arr=array())
	{
		$return_val = '';
		if(!empty($bcrumb_arr))
		{	
			if(empty($bcrumb_arr[0])) $breadcrumb_arr[0] = $bcrumb_arr;			
			else $breadcrumb_arr = $bcrumb_arr;
			
			$return_val .= '<ol class="breadcrumb">'; 	
			foreach($breadcrumb_arr as $bc_key => $bc_val)
			{
				$return_val .= '<li>'; 
				$b_text = '';
				$b_text .= (!empty($bc_val['icon']))?'<i class="'.$bc_val['icon'].'"></i>':'<i class=""></i>';
				$b_text .= $bc_val['name'];

				if(!empty($bc_val['url'])){
					$a_text = '<a href="'.$bc_val['url'].'">';
					$b_text = $a_text.$b_text.'</a>';
				}
				
				$return_val .= $b_text;
				$return_val .= '</li>'; 
			}
			$return_val .= '</ol>'; 
		}
		return $return_val;
	}
}

if (!function_exists('alte_sub_menu'))
{
	function alte_sub_menu($item = array(), $extra = '')
	{
		$return_val         = '';
		$html['arrow']      = '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
		$html['ul_start']   = '<ul class="treeview-menu">';
		$html['ul_end']     = '</ul>';


		$li = '';
		if (!empty($item['dropdown'])) 
		{            
			//if it has a child
			$class = (empty($item['class'])) ? '' : $item['class'];
			$route = '#';
			$icon = (empty($item['icon'])) ? 'fa fa-circle-o' : $item['icon'];
			$text = (empty($item['text'])) ? 'text placeholder' : $item['text'];

			$item_subs  = $item['dropdown'];

			$li .= '<li class="treeview ' . $class . '">';
			$li .= '<a href="' . $route . '">';
			$li .= '<i class="' . $icon . '"></i>';
			$li .= '<span>' . $text . '</span>'. $html['arrow'];
			$li .= '</a>';
			$li .= $html['ul_start'];

			foreach ($item_subs as $key => $item_sub) 
			{
				$li .= alte_sub_menu($item_sub);
			}

			$li .= $html['ul_end'];
			$li .= '</li>';
		}
		else
		{
			if(!empty($item['route']))
			{				
				$class = (empty($item['class'])) ? '' : $item['class'];
				$route = (empty($item['route'])) ? '#' : $item['route'].'';
				$icon = (empty($item['icon'])) ? 'fa fa-circle-o' : $item['icon'];
				$text = (empty($item['text'])) ? 'text placeholder' : $item['text'];

				$li .= '<li class="' . $class . '">';
				$li .= '<a href="' . $route . '">';
				$li .= '<i class="' . $icon . '"></i>';
				$li .= '<span>' . $text . '</span>';
				$li .= '</a>';
				$li .= '</li>';
			}
		}

		$return_val .= $li;

		
		return $return_val;

	}
}

if (!function_exists('alte_sidebar_menu'))
{
	function alte_sidebar_menu($items = array())
	{		
		$return_val = '';
		if(!empty($items) && is_array($items))
		{
			$return_val .= '<ul class="sidebar-menu tree" data-widget="tree">';
			$html['arrow'] = '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';

			foreach ($items as $key => $item) 
			{
				$li = '';
				if (!empty($item['class']) && (strpos($item['class'], 'header') !== false)) 
				{
					//if it is header
					$li .= '<li class="' . $item['class'] . '">' . $item['text'] . '</li>';
				} 
				else 
				{
					$li .= alte_sub_menu($item);
				}
				$return_val .= $li;
			}
			$return_val .= '</ul>';
		}

		return $return_val;
	}
}

if (!function_exists('xml_to_array'))
{
	function xml_to_array($xml_obj)
	{
		$return_val = array();
		if(!empty($xml_obj)){
			$return_val = json_decode(json_encode($xml_obj), true);
		}
		return $return_val;
	}
}

if (!function_exists('json_output'))
{
	function json_output($statusHeader,$response)
	{
		$ci =& get_instance();
		$ci->output->set_content_type('application/json');
		$ci->output->set_status_header($statusHeader);
		$ci->output->set_output(json_encode($response));
	}
}

if (!function_exists('sys_info'))
{
	function sys_info()
	{	
		$return_val = '';
		$ci =& get_instance();		
		$return_val .= 'php              : '.phpversion();
		$return_val .= '&#013;jquery          : 1.11.1';
		$return_val .= '&#013;bootstrap    : 3.3.6';
		$return_val .= '&#013;ace               : 1.3.4';
		$return_val .= '&#013;ci version    : '.CI_VERSION;
		// $return_val .= '&#013;sys version  : '.$ci->common_model->get_migration_info(3);
		return $return_val;
	}
}


if (!function_exists('checkbox_script'))
{
	function checkbox_script($target)
	{	
		$return_val = '';
		if(!empty($target))
		{
			$return_val .= '<script>';
			$return_val .= '$("#'.$target.'").click(function(){';
			$return_val .= 'var target = $(this);';
			$return_val .= 'var target_2 = $("input[name='.$target.']");';
			$return_val .= 'console.log(target.prop("checked"));';
			$return_val .= 'if(target.prop("checked")){target_2.val(1);}else{target_2.val(0);}';
			$return_val .= '});';
			$return_val .= '</script>';
		}
		return $return_val;
	}
}

if (!function_exists('convert_id_into_str'))
{
	function convert_id_into_str($table = '',$id = array(), $select_str = '*')
	{	
		$return_val = '';
		if(!empty($select_str) && !empty($table) && !empty($id) && is_array($id))
		{	
			$ci =& get_instance();	
			$return_val = $ci->common_model->convert_id_into_str($table,$id,$select_str);		
		}
		return $return_val;
	}
}

if (!function_exists('gen_action_log_desc'))
{
	function gen_action_log_desc($arr='')
	{	
		$return_val = '';
		if(!empty($arr))
		{
			$ci =& get_instance();		
			$return_val = $ci->common_model->gen_action_log_desc($arr);
		}
		return $return_val;
	}
}

if (!function_exists('get_info_schema'))
{
	function get_info_schema($table='',$conditions='')
	{	
		$return_val = '';
		if(!empty($table))
		{
			$arr 					= array();
			$arr['for_table']		= $table;
			if(!empty($conditions) && is_array($conditions))
			{
				$arr['where_custom'] = $conditions;
			}
			else
			{
				$arr['where_custom'][]	= '`COLUMN_COMMENT` LIKE "%FK::%"';
			}
			$ci =& get_instance();		
			$result = $ci->common_model->get_info_schema($arr);
			$return_val 			= $result['custom'];			
		}
		return $return_val;
	}
}

if (!function_exists('get_info_schema_custom_bk'))
{
	function get_info_schema_custom_bk($result_arr)
	{
		$return_val 	= '';
		if(!empty($result_arr))
		{
			foreach($result_arr as $r_key => $r_val)
			{
				$header	=  $result_arr[$r_key]['COLUMN_NAME'];

				if(!empty($header))
				{
					$prefix = 'FK::';
					$str	=  $result_arr[$r_key]['COLUMN_COMMENT'];

					if(substr($str, 0, strlen($prefix)) == $prefix)
					{
						$str = substr($str, strlen($prefix));
					}					
					$return_val[$header] = explode('::',$str);
				}
			}
		}
		return $return_val;
	}
}


if (!function_exists('id_to_str_type_of_action'))
{
	function id_to_str_type_of_action($str)
	{
		$return_val 	= '1';
		$val['others'] 	= '1';
		$val['add'] 	= '2';
		$val['edit'] 	= '3';
		$val['delete'] 	= '4';

		if(($str == $val['others']) || ($str == ucwords($val['others'])) || ($str == strtoupper($val['others']))) $return_val = 'others';		
		else if(($str == $val['add']) || ($str == ucwords($val['add'])) || ($str == strtoupper($val['add']))) $return_val = 'add';
		else if(($str == $val['edit']) || ($str == ucwords($val['edit'])) || ($str == strtoupper($val['edit']))) $return_val = 'edit';
		else if(($str == $val['delete']) || ($str == ucwords($val['delete'])) || ($str == strtoupper($val['delete']))) $return_val = 'delete';

		return $return_val;
	}
}

if (!function_exists('str_to_id_type_of_action'))
{
	function str_to_id_type_of_action($str)
	{
		$return_val = '1';
		$val[1] 	= 'others';
		$val[2] 	= 'add';
		$val[3] 	= 'edit';
		$val[4] 	= 'delete';

		if(($str == $val[1]) || ($str == ucwords($val[1])) || ($str == strtoupper($val[1]))) $return_val = '1';		
		else if(($str == $val[2]) || ($str == ucwords($val[2])) || ($str == strtoupper($val[2]))) $return_val = '2';
		else if(($str == $val[3]) || ($str == ucwords($val[3])) || ($str == strtoupper($val[3]))) $return_val = '3';
		else if(($str == $val[4]) || ($str == ucwords($val[4])) || ($str == strtoupper($val[4]))) $return_val = '4';

		return $return_val;
	}
}

if (!function_exists('get_email_by_user_id'))
{
	function get_email_by_user_id($id)
	{	
		$return_val = '';
		$ci =& get_instance();		

		if(!empty($id))
		{
			$model = 'user_account_model';
			$ci->load->model($model);
			$arr['where_custom'][] = '`user_id` = "'.$id.'"';

			$result 	= $ci->$model->get_by_arr($arr);
			$data 		= $result['result']->result_array();

			$user_email 		= '';
			if(!empty($data[0]['user_email']))
			{
				$user_email .= $data[0]['user_email'];
			}
			$return_val	= $user_email;	
		}

		return $return_val;
	}
}

if (!function_exists('get_emp_name_by_user_id'))
{
	function get_emp_name_by_user_id($id)
	{	
		$return_val = '';
		$ci =& get_instance();		

		if(!empty($id))
		{
			$model = 'emp_profile_model';
			$ci->load->model($model);
			$arr['where_custom'][] = '`emp_user_id` = "'.$id.'"';

			$result 	= $ci->$model->get_by_arr($arr);
			$data 		= $result['result']->result_array();

			$name 		= '';
			if(!empty($data[0]['emp_firstname']))
			{
				$name .= $data[0]['emp_firstname'];
			}
			if(!empty($data[0]['emp_lastname']))
			{
				$name .= ' '.$data[0]['emp_lastname'];
			}
			$return_val	= $name;	
		}

		return $return_val;
	}
}

if (!function_exists('role_name'))
{
	function role_name($role_num)
	{	
		$return_val = '';
		$ci =& get_instance();		

		if(!empty($role_num))
		{
			$ci->load->model('acl_model');
			$result 	= $ci->acl_model->get_by_arr($role_num);
			$data 		= $result['result']->result_array();
			$return_val	= (empty($data[0]['name']))?'':$data[0]['name'];	
		}

		return $return_val;
	}
}

if(!function_exists('form_input_group'))
{
	function form_input_group($data = '')
	{
		$return_val = ''; 

		if(!empty($data) && (!empty($data['input'])) && (!empty($data['label'])))
		{	
			$g_class 	= (empty($data['group']['class']))?'':$data['group']['class'];
			$div_class 	= (empty($data['input_div']['class']))?'':$data['input_div']['class'];
			$l_attr = (!empty($data['label']['attr']) && is_array($data['label']['attr']))?$data['label']['attr']:'';
			$l_info = (!empty($data['label']['content']))?$data['label']['content']:'';		

			$return_val .= '<div class="form-group '.$g_class.'" >';	
			$return_val .= form_label($l_info, '', $l_attr);
			$return_val .= '<div class="'.$div_class.'" >';
			$return_val .= form_input($data['input']);
			$return_val .= '</div>';
			$return_val .= '</div>';
		}

		return $return_val;
	}
}


if (!function_exists('gen_menu_main'))
{
	function gen_menu_main($menu_arr=array())
	{
		$return_val = '';		
		if(!empty($menu_arr))
		{
			$return_val .= '<ul class="nav nav-list">';
			foreach($menu_arr as $bc_key => $bc_val)
			{
				if(!empty($bc_val['ddmenu']))
				{
					$m_arr = array();
					$m_arr = $bc_val['ddmenu'];

					$return_val .= '<li class="ddmenu">';
					$return_val .= '<a href="#" class="dropdown-toggle">';
					$return_val .= (empty($bc_val['icon']))?' ':$bc_val['icon'].' ';
					$return_val .= '<span class="menu-text">';
					$return_val .= (empty($bc_val['name']))?'':ucwords($bc_val['name']);
					$return_val .= '</span><b class="arrow fa fa-angle-down"></b></a>';	
					$return_val .= '<ul class="submenu nav-hide">';	

					foreach ($bc_val['ddmenu'] as $ddmenu_key => $ddmenu_val)
					{
						if(!empty($ddmenu_val['ddmenu']))
						{	
							$return_val .= '<li class="ddmenu">';
							$return_val .= '<a href="#" class="dropdown-toggle">';
							$return_val .= (empty($ddmenu_val['icon']))?' ':$ddmenu_val['icon'].' ';
							$return_val .= '<span class="menu-text">';
							$return_val .= (empty($ddmenu_val['name']))?'':ucwords($ddmenu_val['name']);
							$return_val .= '</span><b class="arrow fa fa-angle-down"></b></a>';
							$return_val .= '<ul class="submenu nav-hide">';					
							$return_val .= gen_menu($ddmenu_val['ddmenu']);
							$return_val .= '</ul></li>';	
						}
						else
						{
							$m_arr = array();
							$m_arr[] = $ddmenu_val;
							$return_val .= gen_menu($m_arr);
						}
					}
					$return_val .= '</ul></li>';	
				}
				else
				{
					$m_arr = array();
					$m_arr[] = $menu_arr[$bc_key];
					$return_val .= gen_menu($m_arr);
				}
			}
			$return_val .= '</ul>';
		}
		return $return_val;
	}
}

if (!function_exists('gen_menu'))
{
	function gen_menu($menu_arr=array())
	{
		$return_val = '';
		if(!empty($menu_arr))
		{		
			foreach($menu_arr as $bc_key => $bc_val)
			{				
				$return_val .= '<li>';
				$b_text 	 = '';
				$b_text 	.= (!empty($bc_val['icon']))?$bc_val['icon']:'';
				$b_text 	.= (!empty($bc_val['name']))?$bc_val['name']:'';

				$btn_id 	= (!empty($bc_val['id']))?$bc_val['id']:'';
				if(!empty($bc_val['url'])){
					$a_text = '<a href="'.$bc_val['url'].'" class="menu_button '.$btn_id.'" >';
					$b_text = $a_text.$b_text.'</a>';
				}
				$return_val .= $b_text;
				$return_val .= '</li>'; 
			}
		}
		
		return $return_val;
	}
}


if (!function_exists('gen_top_bar'))
{
	function gen_top_bar($top_arr=array())
	{
		$return_val = '';
		if(!empty($top_arr))
		{	
			$return_val .= '<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">'; 	
			foreach($top_arr as $bc_key => $bc_val)
			{
				$return_val .= '<li>'; 
				$b_text = '';
				$b_text = (!empty($bc_val['icon']))?$bc_val['icon']:'';
				$b_text .= (!empty($bc_val['name']))?$bc_val['name']:'';
				$b_text .= (!empty($bc_val['divider']))?'<li class="divider"></li>':'';

				if(!empty($bc_val['url'])){
					$class = (empty($bc_val['class']))?'class=""':'class="'.$bc_val['class'].'"';
					$a_text = '<a href="'.$bc_val['url'].'" '.$class.'>';
					$b_text = $a_text.$b_text.'</a>';
				}
				$return_val .= $b_text;
				$return_val .= '</li>'; 
			}
			$return_val .= '</ul>'; 
		}
		return $return_val;
	}
}

if (!function_exists('gen_breadcrumb'))
{
	function gen_breadcrumb($breadcrumb_arr=array())
	{
		$return_val = '';
		if(!empty($breadcrumb_arr))
		{	
			$return_val .= '<ul class="breadcrumb">'; 	
			foreach($breadcrumb_arr as $bc_key => $bc_val)
			{
				$return_val .= '<li>'; 
				$b_text = '';
				$b_text = (!empty($bc_val['icon']))?$bc_val['icon']:'';
				$b_text .= $bc_val['name'];

				if(!empty($bc_val['url'])){
					$a_text = '<a href="'.$bc_val['url'].'">';
					$b_text = $a_text.$b_text.'</a>';
				}
				$return_val .= $b_text;
				$return_val .= '</li>'; 
			}
			$return_val .= '</ul>'; 
		}
		return $return_val;
	}
}



if(!function_exists('special_char'))
{
	function special_char($data = '')
	{
		$return_data = '';
		if(!empty($data))
		{
			if(strpos($data, "'") !== false)
			{
				$data = str_replace("'","‘",$data);
			}

			if(strpos($data, '"') !== false)
			{
				$data = str_replace('"',"‘",$data);
			}

			$return_data = $data;	 
		}
		return $return_data;
	}
}

if(!function_exists('shorten_email_user'))
{
	function shorten_email_user($data = '')
	{
		$return_data = '';
		if(!empty($data)){
			if(strpos($data, '@') === false)
			{
				$return_data = $data;
			}
			else
			{
				$new_data = explode("@",$data);

				$return_data = $new_data[0];
			}			 
		}
		return $return_data;
	}
}


if (!function_exists('tooltip_helper'))
{
	function tooltip_helper($data = '')
    {
		$return_data = '';
		if(!empty($data)){
			$return_data =  "data-toggle='tooltip' title='".$data."'";
		}
		return $return_data;
	}
}

if (!function_exists('top_menu'))
{
    function top_menu($page_title,$btn_array=array(),$active ='')
    {
    	$return_val = '';
    	$class		= '';
    	if(!empty($page_title))
    	{
    		$class .= (!empty($active))?'active':'';
    		$return_val = "<li class='".$class."'><a href='".$url."'>".$page_title."</a></li>";
    	}
    	return $return_val;
    }
}

if (!function_exists('bs_li_a'))
{
    function bs_li_a($btn_array=array())
    {
    	$return_val = '';
    	if(!empty($btn_array)){
    		foreach($btn_array as $b_key => $b_val)
    		if(!empty($btn_array[$b_key]['title']))
    		{
	    		$att = '';
				if(!empty($btn_array[$b_key]['att']))
				{
					foreach($btn_array[$b_key]['att'] as $attr_key => $attr_val)$att .=$attr_key.'="'.$attr_val.'"';
				}
	    		$return_val .='<li><a '.$att.'>'.$btn_array[$b_key]['title'].'</a></li>';
    		}
    	}
    	return $return_val;
    }
	
}

if (!function_exists('bs_li_dropdown'))
{
    function bs_li_dropdown($page_title='',$btn_array=array())
    {
    	$return_val = '';
    	
    	if(!empty($btn_array))
    	{
    		$return_val .= '<li class="dropdown">';
    		$return_val .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">';
    		$return_val .= 	$page_title.'<span class="caret"></span></a><ul class="dropdown-menu">';
	      
			foreach($btn_array as $ba_key => $ba_val)
			{
			    if($btn_array[$ba_key]['type'] == 'link')
			    {
			    	$att = '';
			    	if(!empty($btn_array[$ba_key]['att']))
			    	{
				    	foreach($btn_array[$ba_key]['att'] as $attr_key => $attr_val)$att .=$attr_key.'="'.$attr_val.'"';
			    	}
		    		$return_val .= '<li><a '.$att.'>'.$btn_array[$ba_key]['button_title'].'</a></li>';
			    }
			    if($btn_array[$ba_key]['type'] == 'devider')
			    {
		    		$return_val .= '<li role="separator" class="divider"></li>';
			    }
			    if($btn_array[$ba_key]['type'] == 'title')
			    {
					$return_val .= '<li class="dropdown-header">'.$btn_array[$ba_key]['button_title'].'</li>';
			    }
			 }
	                  
	      //dropdown_type,'link' = normal, 'title' = 'title', 'devider'
	      //$test = '<li><a "'.$attr.'">'.$button_title.'</a></li>';
	      //$test = '<li role="separator" class="divider"></li>';
	      //$test = '<li class="dropdown-header">'.$button_title.'</li>';
	      
	      $return_val .= '</ul></li>';
    	}
		return $return_val;
    }
	
}


if (!function_exists('side_menu'))
{
    function side_menu($page_title,$url = '#',$active ='')
    {
    	$return_val = '';
    	$class		= '';
    	if(!empty($page_title))
    	{
    		$class .= (!empty($active))?'active':'';
    		$return_val = "<li class='".$class."'><a href='".$url."'>".$page_title."</a></li>";
    	}
    	return $return_val;
    }
}

if(!function_exists('paginationSettings')){
	function paginationSettings($table = null, $total_rows= null, $per_page = 20, $num_links=2, $uri_segment = 3, $first_link=false, $last_link=false)
    {
		$ci =& get_instance();
		$ci->load->library('pagination');

		if ( empty($total_rows) ) $total_rows = 0;

        //pagination settings
		$config['base_url'] = $ci->config->item('base_url').$ci->router->fetch_class().'/'.$ci->router->method.'/';
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['num_links'] = $num_links;
		$config['uri_segment'] = $uri_segment;

		//config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '<';//'&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '>';//'&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $ci->pagination->initialize($config);
        return $ci->pagination->create_links();
	}
}

if(!function_exists('alert_helper'))
{
	/**
	 * @usage:
	 *
	 * http://www.w3schools.com/bootstrap/bootstrap_alerts.asp
	 * the $key is using bootstrap default success,info,warning,danger
	 *
	 * */
    function alert_helper()
    {
		$return_val = '';
		$CI 		= & get_instance();
		$data 		= $CI->session->flashdata('alert');
		if(!empty($data))
		{
			foreach ($data as $key => $val){
				$return_val .= "<div class='alert alert-$key alert-dismissible' ><button type='button' class='close alert-button' data-dismiss='alert' ><span>&times;</span></button><span class='alert-val'>";
				$return_val .= $val;
				$return_val .= "</span></div>";
				$return_val .= "<script> setTimeout(function(){\$('.alert-button').click(); },5000); </script>";
			}
		}
		return $return_val;
	}
}

if(!function_exists('flash_data_helper'))
{
	/**
	 * @usage:
	 *
	 * return true if today is bigger than the date in the field vice versa.
	 *
	 * */
    function flash_data_helper($msg = '',$add_on=array())
    {    	 
		$CI = & get_instance();
		if (empty($msg)) {
			$msg = array('msg'=>'', 'error_msg'=>'', 'warning_msg'=>'');
		}
		$close_btn = ((empty($add_on['close']))?'':'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>');

		if(($CI->session->flashdata('msg') != '')||((!empty($msg['msg'])) && $msg['msg'] != ''))
		{
			$flash_msg = '';
			if(!empty($msg['msg'])) 
			{
				if(is_array($msg['msg']))
				{
					foreach ($msg['msg'] as $f_msg_key =>  $f_msg_val) $flash_msg .= $f_msg_val;
				}
				else
				{
					$flash_msg = $msg['msg'];
				}
			}

			if($CI->session->flashdata('msg') != '') $flash_msg = $CI->session->flashdata('msg');
			echo "<div id='success' class='alert alert-success ' role='alert'>".$close_btn;
			print_r($flash_msg);
			echo "</div>";
			echo "<script> setTimeout(function(){\$('#success').fadeOut();},5000); </script>";
		}
		if(($CI->session->flashdata('error_msg') != '')||((!empty($msg['error_msg'])) && $msg['error_msg'] != '')){
			$flash_msg = '';
			if(!empty($msg['error_msg'])) 
			{
				if(is_array($msg['error_msg']))
				{
					foreach ($msg['error_msg'] as $f_msg_key =>  $f_msg_val) $flash_msg .= $f_msg_val;
				}
				else
				{
					$flash_msg = $msg['error_msg'];
				}
			}
			// if(!empty($msg['error_msg']))	$flash_msg = $msg['error_msg'];
			if($CI->session->flashdata('error_msg') != '')	$flash_msg = $CI->session->flashdata('error_msg');

			echo "<div id='error_msg' class='alert alert-danger ' role='alert'>".$close_btn;;
			print_r($flash_msg);
			echo "</div>";
			echo "<script> setTimeout(function(){\$('#error_msg').fadeOut();},10000); </script>";
		}
		if(($CI->session->flashdata('warning_msg') != '')||((!empty($msg['warning_msg'])) && $msg['warning_msg'] != '')){
			$flash_msg = '';
			if(!empty($msg['warning_msg'])) 
			{
				if(is_array($msg['warning_msg']))
				{
					foreach ($msg['warning_msg'] as $f_msg_key =>  $f_msg_val) $flash_msg  .= $f_msg_val;
				}
				else
				{
					$flash_msg = $msg['warning_msg'];
				}
			}
			// if(!empty($msg['warning_msg']))	$flash_msg = $msg['warning_msg'];
			if($CI->session->flashdata('warning_msg') != '') $flash_msg = $CI->session->flashdata('warning_msg');

			echo "<div id='warning_msg' class='alert alert-warning' role='alert'>".$close_btn;;
			print_r($flash_msg);
			echo "</div>";
			echo "<script> setTimeout(function(){\$('#warning_msg').fadeOut();},10000); </script>";
		}
    }
}

if(!function_exists('gen_input'))
{
	function gen_input(
		$type			='',
		$name_or_id		='',
		$value			='',
		$placeholder	='',
		$style			='',
		$class			='')
	{
		if($type == '') 		$type 			='text';
		if($name_or_id == '') 	$name_or_id 	='';
		if($value == '') 		$value 			='';
		if($placeholder == '') 	$placeholder 	='';
		if($style == '') 		$style 			='';
		if($class == '') 		$class 			='form-control';

		if($type != 'checkbox')
		{
			$have_error					= form_error($name_or_id,"<label for='$name_or_id' class='text-danger' style='padding-left:0px;'>", "</label>");
			$wp_header					= ''; //<label for="'.$name_or_id.'" class="sr-only">'.$placeholder.'</label>
			$wp_footer					= '';
			$input_arr['id']			= $name_or_id;
			$input_arr['name']			= $name_or_id;
			$input_arr['type']			= $type;
			$input_arr['value']			= set_value($name_or_id, $value);
			$input_arr['placeholder']	= $placeholder;
			$input_arr['class']			= $class;
			$input_arr['style']			= $style;
			$input_arr['style']			.= (empty($have_error))?'':';border-color:#a94442;';
			$has_error 					= (empty($have_error))?'':$have_error;

			$return_val					= $wp_header.form_input($input_arr).$has_error.$wp_footer;
		}
		return $return_val;
	}
}

if(!function_exists('de_code'))
{
	function de_code($str)
	{
 		$return_val = '';
		if(!empty($str))
		{
			$CI = & get_instance();
			$CI->load->library('encrypt');
			$base64 = strtr($str, '-_', '+/');
			$return_val = $CI->encrypt->decode($base64);
		}
		return $return_val;
	}
}

if(!function_exists('en_code'))
{
	function en_code($str)
	{
 		$return_val = '';
		if(!empty($str))
		{
			$CI = & get_instance();
			$CI->load->library('encrypt');
	    	$base64 =  $CI->encrypt->encode($str);
			$return_val =  strtr($base64, '+/', '-_');
		}
		return $return_val;
	}
}


if(!function_exists('gen_img'))
{
	function  gen_img(
	$src 	='',
	$width 	='',
	$height ='',
	$id 	='',
	$class 	='',
	$alt 	='',
	$title 	='',
	$rel 	=''
	)
	{
		if($src == '') 		$src 	='';
		if($width == '') 	$width 	='100';
		if($height == '') 	$height	='100';
		if($id == '') 		$id		='test';
		if($class == '') 	$class	='';
		if($alt == '') 		$alt	= ucwords(lang('app_name'));
		if($title == '') 	$title	='';
		if($rel == '') 		$rel	='';

		$image_arr['src'] 		= (empty($src))?'':$src.'?'.lang('setup_js_changes');
		$image_arr['height'] 	= $height;
		$image_arr['width'] 	= $width;
		$image_arr['id'] 		= $id;
		$image_arr['class'] 	= (empty($class))?$id:$class;
		$image_arr['data-src'] 	= 'holder.js/'.$height.'x'.$width;
		$image_arr['alt'] 		= $alt;
		$image_arr['title'] 	= (empty($title))?$alt:$title;
		$image_arr['rel'] 		= (empty($rel))?$alt:$rel;

		$return_val = img($image_arr);

		return $return_val;
	}
}


if(!function_exists('gen_form_button'))
{
	function  gen_form_button(
		$content	='',
		$name_or_id	='',
		$value		='',
		$class		='',
		$style 		='',
		$type		='',
		$role 		='',
		$aria 		='')
	{
		if($content == '') 		$content 	='';
		if($name_or_id == '') 	$name_or_id	='';
		if($value == '') 		$value		='';
		if($class == '') 		$class 		='btn btn-lg btn-primary btn-block';
		if($style == '') 		$style		='';
		if($type == '') 		$type 		='submit';
		if($role == '') 		$role 		='button';
		if($aria == '') 		$aria 		='aria-disabled';

		if($type == 'button' || $type == 'reset' || $type == 'submit')
		{
			if($content == '') $content = '<i class="menu-icon fa fa-chevron-left"></i>test';

			$button_arr['content']			= $content;
			$button_arr['class']			= $class;
			$button_arr['type']				= $type;
			$button_arr['id']				= $name_or_id;
			$button_arr['name']				= $name_or_id;
			$button_arr['value']			= $value;

			$button_arr['style']			= $style;
			$button_arr['role']				= $role;
			$button_arr['aria-disabled']	= $aria;
			//~ $button_arr['onclick']			= $onclick;//"location.href='".base_url($controller)."'";
			$return_val						= form_button($button_arr);
		}
		return $return_val;
	}
}

if (!function_exists('convert_date'))
{
	function convert_date($data = '' ,$type = '1')
    {
		$result ='';
		if(!empty($data))
		{
			if($data > 0)
			{
				$date = new DateTime($data);
				if($type == '1') $result = strtoupper($date->format('d M Y'));
				if($type == '2') $result = $date->format('d M Y');
				elseif($type == '3') $result = $date->format('Y-m-d');
				elseif($type == '4') $result = $date->format('D, d M Y');
			}
		}
		return $result;
	}
}


if (!function_exists('fontAwesome'))
{
    function fontAwesome($font = '', $style ='', $msg='', $cursor='',$extra='')
    {
		if(!empty($font))
		{
			if($cursor == '') $cursor = 'pointer';
			return '<i '. $extra .' style="cursor:'.$cursor.';'.$style.'" class="menu-icon fa '.$font.'" data-toggle="tooltip" title="'.$msg.'"></i>&nbsp;';
		}
		else
		{
			$cursor = 'auto';
			$font	= 'fa-thumbs-up';
			$class	= 'fa-lg';
			$msg	= 'fontAwesome helper can be found in application/helpers/custome_helper';
			return '<i style="cursor:'.$cursor.'" class="menu-icon fa '.$font.' '.$class.'" data-toggle="tooltip" title="'.$msg.'"></i>&nbsp;';
		}
    }
}
if ( ! function_exists('_debug_array'))
{
	function _debug_array($arr) {
		echo "<pre>".print_r($arr,true)."</pre>\n";
	}
}

if ( ! function_exists('_log'))
{
	function _log($file,$msg) {
		if ($f = fopen($file,'a+')) {
			fwrite($f,date('Y/m/d H:i:s ')." $msg\n");
			fclose($f);
		}
	}
}



