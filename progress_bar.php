<?php
/*

Plugin Name: Progress Bar
Version: 0.1a
Plugin URI: http://irgeek.net/projects/progress-bar/
Description: Uses <a href="http://wiphey.com">Kristin</a>'s Progress Bar code to add a customizable progress bar to your WP sidebar
Author: Pablo
Author URI: http://irgeek.net

*/

/*  Copyright 2006  Robert Jorgenson  (email : rjorgy@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class progressBar {
	
	var $options;
	
	function progressBar() {
		// Grab plugin options
		if (!($this->options = get_option('progressBar'))) {
			$this->options = array();
			$this->options['bar_height'] = 15;
			$this->options['bar_width'] = 200;
			$this->options['background_color'] = 'fff';
			$this->options['border_width'] = 1;
			$this->options['border_style'] = 'solid';
			$this->options['border_color'] = 'silver';
			$this->options['bar_color'] = '000';
			$this->options['starting_weight'] = 0;
			$this->options['current_weight'] = 0;
			$this->options['percent_done'] = 0;
			$this->options['header_text'] = 'Progress';
			$this->storeOptions();
		}
	}
	
	function storeOptions() {
		update_option('progressBar', $this->options);
	}
	
	function showProgressBar() { ?>
		<div class="progress_bar">
			<h2><?php echo $this->options['header_text']; ?></h2>
			<?php echo stripslashes($this->options['before']); ?>
			<div class="progress-border">
				<div class="progress-bar" style="width: <?php echo $this->options['percent_done']; ?>%;"></div>
			</div>
			<?php echo stripslashes($this->options['after']); ?>
		</div>
	<? }
	
	function progressBarCss() { ?>
	<style type="text/css">
		.progress-border {
			height: <?php echo $this->options['bar_height']; ?>px;
			width: <?php echo $this->options['bar_width']; ?>px;
			background: <?php echo $this->options['background_color']; ?>;
			border: <?php echo $this->options['border_width']; ?>px <?php echo $this->options['border_style']; ?> <?php echo $this->options['border_color']; ?>;
			margin: 2px !important;
			padding: 0px;
			
		}
		
		.progress-bar {
			height: <?php echo ($this->options['bar_height'] - 4) ?>px;
			margin: 2px !important;
			padding: 0px;
			background: #<?php echo $this->options['bar_color']; ?>;
		}
	</style>
	<?php }
	
	function progressBarOptions() {
		// Options page
		if (isset($_POST['progress_bar']) && is_array($_POST['progress_bar']) && !empty($_POST['progress_bar'])) {
			
			$newopts = $_POST['progress_bar'];
			
			// parse/validate options
			if (!empty($newopts['bar_height'])) {
				$this->options['bar_height'] = $newopts['bar_height'];
			}
			if (!empty($newopts['bar_width'])) {
				$this->options['bar_width'] = $newopts['bar_width'];
			}
			if (!empty($newopts['background_color'])) {
				$this->options['background_color'] = $newopts['background_color'];
			}
			if (!empty($newopts['border_width'])) {
				$this->options['border_width'] = $newopts['border_width'];
			}
			if (!empty($newopts['border_style'])) {
				$this->options['border_style'] = $newopts['border_style'];
			}
			if (!empty($newopts['border_color'])) {
				$this->options['border_color'] = $newopts['border_color'];
			}
			if (!empty($newopts['bar_color'])) {
				$this->options['bar_color'] = $newopts['bar_color'];
			}
			if (!empty($newopts['starting_value'])) {
				$this->options['starting_value'] = $newopts['starting_value'];
			} else {
				$this->options['starting_value'] = 0;
			}
			if (!empty($newopts['end_value'])) {
				$this->options['end_value'] = $newopts['end_value'];
			} else {
				$this->options['end_value'] = 0;
			}
			if (!empty($newopts['current_value'])) {
				$this->options['current_value'] = $newopts['current_value'];
			} else {
				$this->options['current_value'] = 0;
			}
			if (!empty($newopts['percent_done'])) {
				$this->options['percent_done'] = $newopts['percent_done'];
			}
			if (!empty($newopts['header_text'])) {
				$this->options['header_text'] = $newopts['header_text'];
			}
			if (!empty($newopts['before'])) {
				$this->options['before'] = $newopts['before'];
			}
			if (!empty($newopts['after'])) {
				$this->options['after'] = $newopts['after'];
			}
			// save options
						
			if ((!empty($this->options['starting_value']) || $this->options['starting_value'] == '0') && (!empty($this->options['current_value']) || $this->options['current_value'] == '0') && (!empty($this->options['end_value']) || $this->options['end_value'] == '0')) {
				if ($this->options['starting_value'] < $this->options['end_value']) {
					$diff = $this->options['end_value'] - $this->options['starting_value'];
					$done = $this->options['current_value'] - $this->options['starting_value'];
					$this->options['percent_done'] = round((($done / $diff) * 100));
					$this->storeOptions();
				} elseif ($this->options['starting_value'] > $this->options['end_value']) {
					$diff = $this->options['starting_value'] - $this->options['end_value'];
					$done = $this->options['starting_value'] - $this->options['current_value'];
					$this->options['percent_done'] = round((($done / $diff) * 100));
					$this->storeOptions();
				}
			} else {
				$this->storeOptions();
			}
		}
		?>
		<div class="wrap">
			<h2>Progress Bar Options</h2>
			<form action="options-general.php?page=<?php echo basename(__FILE__); ?>" method="post" name="DPBOptions">
				<table width="700px" cellspacing="2" cellpadding="5" class="editform">
					<tr>
						<th scope="row">Progress</th>
						<td width="50px">
							<table width="100%" cellspacing="2" cellpadding="5" class="editform">
								<tr>
									<td>Starting Value:</td>
									<td>
										<input type="text" name="progress_bar[starting_value]" value="<?php echo $this->options['starting_value']; ?>" id="starting_value" />
									</td>
								</tr>
								<tr>
									<td>Goal Value:</td>
									<td>
										<input type="text" name="progress_bar[end_value]" value="<?php echo $this->options['end_value']; ?>" id="starting_value" />
									</td>
								</tr>
								<tr>
									<td>Current Value:</td>
									<td>
										<input type="text" name="progress_bar[current_value]" value="<?php echo $this->options['current_value']; ?>" id="current_value" />
									</td>
								</tr>
								<tr>
									<td>Percent of Goal:</td>
									<td>
										<?php echo $this->options['percent_done']; ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<th scope="row">Before/After</th>
						<td>
							<table width="100%" cellpadding-"2" cellspacing="5" class="editform">
								<tr>
									<td>Header Text:</td>
									<td>
										<input type="text" name="progress_bar[header_text]" value="<?php echo $this->options['header_text']; ?>" id="header_text" />
									</td>
								</tr>
								<tr>
									<td>Before:</td>
									<td>
										<textarea rows="4" cols="56" name="progress_bar[before]" id="before"><?php echo stripslashes($this->options['before']); ?></textarea>
									</td>
								</tr>
								<tr>
									<td>After:</td>
									<td>
										<textarea rows="4" cols="56" name="progress_bar[after]" id="after"><?php echo stripslashes($this->options['after']); ?></textarea>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<th scope="row">Display Options</th>
						<td>
							<table width="100%" cellpadding-"2" cellspacing="5" class="editform">
								<tr>
									<td>Border Color:</td>
									<td>
										<input type="text" name="progress_bar[border_color]" value="<?php echo $this->options['border_color']; ?>" id="border_color" />
									</td>
								</tr>
								<tr>
									<td>Border Style:</td>
									<td>
										<input type="text" name="progress_bar[border_style]" value="<?php echo $this->options['border_style']; ?>" id="border_style" />
									</td>
								</tr>
								<tr>
									<td>Border Width:<td>
										<input type="text" name="progress_bar[border_width]" value="<?php echo $this->options['border_width']; ?>" id="border_width" />
									</td>
								</tr>
								<tr>
									<td>Bar Height:</td>
									<td>
										<input type="text" name="progress_bar[bar_height]" value="<?php echo $this->options['bar_height']; ?>" id="bar_height" />
									</td>
								</tr>
								<tr>
									<td>Background Color:<td>
										<input type="text" name="progress_bar[background_color]" value="<?php echo $this->options['background_color']; ?>" id="background_color" />
									</td>
								</tr>
								<tr>
									<td>Bar Width:</td>
									<td>
										<input type="text" name="progress_bar[bar_width]" value="<?php echo $this->options['bar_width']; ?>" id="bar_width" />
									</td>
								</tr>
								<tr>
									<td>Bar Color:</td>
									<td>
										<input type="text" name="progress_bar[bar_color]" value="<?php echo $this->options['bar_color']; ?>" id="bar_color" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" value="Update Options &raquo;" />
				</p>
			</form>
		</div>
<?php }
}

$irgPB = new progressBar();

if (!function_exists('pbAddMenus')) {
	function pbAddMenus() {
		global $irgPB;
		if (function_exists('add_options_page')) {
			add_options_page('Progress Bar Options', 'Progress Bar', 9, basename(__FILE__), array(&$irgPB, 'progressBarOptions'));
		}
	}
}

if (!function_exists('irgProgressBar')) {
	function irgProgressBar() {
		global $irgPB;
		return $irgPB->showProgressBar();
	}
}

if (function_exists(add_action)) {
	add_action('wp_head', array(&$irgPB, 'progressBarCss'));
	add_action('admin_menu', 'pbAddMenus');
}

?>