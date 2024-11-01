<?php
/**
 * @package Today_In_History
 * @author Randall Hinton
 * @version 0.4.1
 */
/*
Plugin Name: Today In History
Plugin URI: http://www.macnative.com/todayInHistory
Description: This Widget allows the user to randomly display historically significant events that happened on that specific day in history
Author: Randall Hinton
Version: 0.5.1
Author URI: http://www.macnative.com/
*/

function TIH_Get($url) 
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_USERAGENT, "Today In History Widget v0.5");
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
 }		

function TIH_ReqString($length = 12)
{      
    $chars = 'AaBbCcDdEeFfGHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz1234567890';
    for ($p = 0; $p < $length; $p++)
    {
        $result .= ($p%2) ? $chars[mt_rand(52, 60)] : $chars[mt_rand(0, 51)];
    }
    return $result;
}

if (!class_exists('TIH_Widget')) :

	class TIH_Widget extends WP_Widget {


		function TIH_Widget() {
									
			// Widget settings
			$widget_ops = array('classname' => 'tih-widget', 'description' => 'Display Events that Happened On This Day Throughout History.');

			// Create the widget
			$this->WP_Widget('tih-widget', 'Today In History', $widget_ops);
		}		
		
		function widget($args, $instance) {
			
			extract($args);
			
			// User-selected settings
			$title = $instance['title'];
			$category_1 = $instance['category_1'];
			$category_2 = $instance['category_2'];
			$category_3 = $instance['category_3'];
			$category_4 = $instance['category_4'];
			$category_5 = $instance['category_5'];
			$category_6 = $instance['category_6'];
			$style = $instance['style'];
			$link = $instance['link'];

			//verify that options are set
			if( !get_option('Today_In_History_Timer') ) {
				add_option('Today_In_History_Timer', '0');
			}
			if( !get_option('Today_In_History_Key') ) {
				add_option('Today_In_History_Key', '0');
			}else{
				$key = get_option('Today_In_History_Key');
			}
			if( !get_option('Today_In_History_Response') ) {
				add_option('Today_In_History_Response', '0');
			}
			if( !get_option('Today_In_History_Force_Update') ) {
				add_option('Today_In_History_Force_Update', '0');
			}
			
			if(get_option('Today_In_History_Timer') < mktime() || get_option('Today_In_History_Response') == "" || get_option('Today_In_History_Force_Update') == "1"){
				
				if(get_option('Today_In_History_Force_Update') == "1"){
					$force_update = update_option('Today_In_History_Force_Update', "0");
				}
				
				if(get_option('Today_In_History_Key') == 0){
					$str_req = TIH_ReqString();
					//echo $str_req."<br >";
					while(!strlen($str_req)==12){
						//echo "trying again";
						$str_req = TIH_ReqString();
					}
					//echo "http://www.macnative.com/history/?reqStr=".$str_req;
					$key = TIH_Get("http://www.macnative.com/history/?reqStr=".$str_req);
					if(strlen($key) == 32){
						update_option('Today_In_History_Key', $key);
					}
				}
				
				//echo $key;//c81d8d19f48f4fe33b9126fde9b7215d//05aedcf93a860f6ad063ab67d147d3af
				
				if($key != 0 && strlen($key) == 32){
					//echo "http://10.0.1.11/history/?tihKey=".$key."&day=".strftime('%j');
					$tih_cats = "";
					$num = 0;
					for($x=1; $x <= 6; $x++){
						$varstring = "category_".$x;
						if($instance[$varstring]){
							if($num > 0){
								$tih_cats .= ",";
							}
							$tih_cats .= $x;
							$num++;
						}
					}
					$history_url = "http://www.macnative.com/history/?tihKey=".$key."&time=".strftime('%s');
					if($tih_cats != ""){
						$history_url .= "&cats=".$tih_cats;
					}
					//echo $history_url;
					$history = TIH_Get($history_url);
					//echo $history;
					if(strlen($history) > 24){
						$got_history = update_option('Today_In_History_Response', $history);
					}else{
						if($history == "Invalid Key"){
							update_option('Today_In_History_Key', 0);
						}
					}
				}
				
				//if(1){
				if($got_history){
					//echo mktime()."<br>";
					$next_day = mktime() + 86400; 
					//echo $next_day."blah<br>";
					$newday = strftime("%e", $next_day);
					//echo $newday."<br>";
					$newmonth = strftime("%m", $next_day);
					//echo $newmonth."<br>";
					$newyear = strftime("%Y", $next_day);
					//echo $newyear."<br>";
					$new_time = mktime(0,5,0,$newmonth,$newday,$newyear) + rand(10,3600);
					//echo $new_time."<br>";
					$updated_timer = update_option('Today_In_History_Timer', $new_time);
				}
			}

			// Before widget (defined by themes)
			echo $before_widget . $before_title . $title . $after_title;


				$siteurl = get_option('siteurl');
				$img_url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/images/';
				
				?>
				
				<?php 
				if($style){
				?>
				<style type="text/css">
					h5.TIH_Date {
						font-size: 18px;
						font-style: italic;
						color: #555;
						font-weight: bold;
						text-align: center;
					}
					p.TIH_Event {
						text-indent: 25px;
						margin-top: 10px;
						padding-bottom: 10px;
					}
					div#TIH_Bottom {
						margin: 5px 0 5px 20px;
						clear: both;
						font-size: 8px !important;
					}
					#tihBottom a {
						font-size: 8px !important;
					}
				</style>
				<?php
				}


				$TIH = json_decode(get_option('Today_In_History_Response'), true);
				$item = rand(0,$TIH["number"]);
				//print_r($TIH["history"][$item]);
			?>
			<h5 class="TIH_Date"><?php echo $TIH["history"][$item]["long_date"]; ?></h5>
			<p class="TIH_Event"><?php echo $TIH["history"][$item]["event"]; ?></p>
			<?php
			echo "<div id='TIH_Bottom'>";
			if($link){
				echo "<a href='http://www.macnative.com/development/todayInHistory/'>Today In History</a> Provided By <a href='http://www.macnative.com'>Macnative</a>";
			}
			echo "</div>";
			// After widget (defined by themes)
			echo $after_widget;
		}

		
		function update($new_instance, $old_instance) {
			
			$instance = $old_instance;
//change the selection to use the plugin options, so that if it is enabled in the plugin it will display it in the widget.... may make it easier...
			$instance['title'] = $new_instance['title'];
			$instance['category_1'] = $new_instance['category_1'];
			$instance['category_2'] = $new_instance['category_2'];
			$instance['category_3'] = $new_instance['category_3'];
			$instance['category_4'] = $new_instance['category_4'];
			$instance['category_5'] = $new_instance['category_5'];
			$instance['category_6'] = $new_instance['category_6'];
			$instance['style'] = $new_instance['style'];
			$instance['link'] = $new_instance['link'];

			if($old_instance["category_1"] != $new_instance['category_1'] || $old_instance["category_2"] != $new_instance['category_2'] || $old_instance["category_3"] != $new_instance['category_3'] || $old_instance["category_4"] != $new_instance['category_4'] || $old_instance["category_5"] != $new_instance['category_5'] || $old_instance["category_6"] != $new_instance['category_6'] ){
				$force_update = update_option('Today_In_History_Force_Update', "1");
			}

			return $instance;
		}
		
		
		function form($instance) {

			// Set up some default widget settings
			//$defaults = array('title' => 'Latest Tweets', 'username' => '', 'posts' => 5, 'interval' => 1800, 'date' => 'j F Y', 'facebook' => true, 'twitter' => true, 'feedburner' => true, 'youtube' => true, 'vimeo' => false);
			$defaults = array('title' => 'Today In History', 'category_1' => true, 'category_2' => true, 'category_3' => true, 'category_4' => true, 'category_5' => true, 'category_6' => true, 'style' => true, 'link' => true);
			$instance = wp_parse_args((array) $instance, $defaults);
?>
				
				
				<p>
					<label for="<?php echo $this->get_field_id('title'); ?>">Widget Title:</label>
					<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>">
				</p>
				
				<b>Categories</b><br>
				<p>
					
				<input class="checkbox" type="checkbox" <?php if ($instance['category_1']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('category_1'); ?>" name="<?php echo $this->get_field_name('category_1'); ?>">
				<label for="<?php echo $this->get_field_id('category_1'); ?>">&nbsp;&nbsp;General</label><br />
				
				<input class="checkbox" type="checkbox" <?php if ($instance['category_2']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('category_2'); ?>" name="<?php echo $this->get_field_name('category_2'); ?>">
				<label for="<?php echo $this->get_field_id('category_2'); ?>">&nbsp;&nbsp;Sports</label><br />
				
				<input class="checkbox" type="checkbox" <?php if ($instance['category_3']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('category_3'); ?>" name="<?php echo $this->get_field_name('category_3'); ?>">
				<label for="<?php echo $this->get_field_id('category_3'); ?>">&nbsp;&nbsp;Entertainment</label><br />
				
				<input class="checkbox" type="checkbox" <?php if ($instance['category_4']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('category_4'); ?>" name="<?php echo $this->get_field_name('category_4'); ?>">
				<label for="<?php echo $this->get_field_id('category_4'); ?>">&nbsp;&nbsp;U.S. History</label><br />
				
				<input class="checkbox" type="checkbox" <?php if ($instance['category_5']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('category_5'); ?>" name="<?php echo $this->get_field_name('category_5'); ?>">
				<label for="<?php echo $this->get_field_id('category_5'); ?>">&nbsp;&nbsp;World History</label><br />
				
				<input class="checkbox" type="checkbox" <?php if ($instance['category_6']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('category_6'); ?>" name="<?php echo $this->get_field_name('category_6'); ?>">
				<label for="<?php echo $this->get_field_id('category_6'); ?>">&nbsp;&nbsp;Achievements</label><br />
				</p>
				
				<b>Custom or Default Styling?</b><br>
				<p>
					
				<input class="checkbox" type="checkbox" <?php if ($instance['style']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
				<label for="<?php echo $this->get_field_id('style'); ?>">&nbsp;&nbsp;Default Styling</label><br />
				Check plugin documentation for instructions on using custom styles
				
				</p>
				<b>Share the Love</b>
				<p>
				
				<input class="checkbox" type="checkbox" <?php if ($instance['link']) echo 'checked="checked" '; ?>id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>">
				<label for="<?php echo $this->get_field_id('link'); ?>">&nbsp;&nbsp;Display "Credit" Link</label>
			
			</p>
			
<?php
		}
	} 
endif;





// Register the plugin/widget
if (class_exists('TIH_Widget')) :

	function loadTIHWidget() {
		
		register_widget('TIH_Widget');
	}

	add_action('widgets_init', 'loadTIHWidget');

endif;

?>