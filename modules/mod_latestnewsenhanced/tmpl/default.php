<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */
 
// no direct access
defined('_JEXEC') or die;

if ($datasource != 'articles') {
	echo '<div class="alert alert-error">';
	echo JText::_('MOD_LATESTNEWSENHANCEDEXTENDED_ERROR_WRONGLAYOUT');
	echo '</div>';
} elseif (empty($list)) {
	echo '<div class="lnee nonews">';
	echo '<div class="alert alert-info">';
	echo $nodata_message;
	echo '</div>';
	echo '</div>';
} else {

	// items
	
	$i = 0;
	$oddoreven = "even";
	
	$cat_link = '';
	$cat_label = '';
	$nbr_cat = 0;
	foreach ($list as $item) {
		$tmp = $item->catlink;
		if ($cat_link != $tmp) {
			$nbr_cat++;
			$cat_link = $tmp;
			$cat_label = $item->category_title;
		}
	}
	if (!empty($cat_link_text)) {
		$cat_label = $cat_link_text;
	}
}
?>
<?php if ($datasource == 'articles' && !empty($list)) : ?>
	<div id="lnee_<?php echo $class_suffix; ?>" class="lnee newslist <?php echo $alignment; ?>">
			
		<?php if ($show_category && $nbr_cat == 1 && $consolidate_category) : ?>
			<div class="onecatlink">	
				<?php if ($link_category) : ?>
					<a href="<?php echo $cat_link; ?>" title="<?php echo $cat_label; ?>">
						<span><?php echo $cat_label; ?></span>
					</a>
				<?php else : ?>
					<span><?php echo $cat_label; ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>	
		
		<?php if ($animation) : ?>	
			<?php if (!empty($pagination) && ($pagination_position_type == 'above' || $pagination_position_type == 'around')) : ?>
				<?php 
					$_GET = array();
					$_GET['suffix'] = $class_suffix;
					$_GET['prev'] = $label_prev;
					$_GET['next'] = $label_next;
					if ($pagination == 'p' || $pagination == 's') {
						$_GET['prevnext'] = '0';
					}
					$_GET['pos'] = $pagination_position_top;
					if (!empty($extra_pagination_classes)) {
						$_GET['classes'] = $extra_pagination_classes;
					}
				?>
				<?php if (JFile::exists(dirname(__FILE__).'/pagination/'.$animation.'.php')) : ?>
					<?php include 'pagination/'.$animation.'.php'; ?>
					<div class="clearfix"></div>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	
		<?php if ($leading_items_count > 0) : ?>
		<ul class="latestnews-items altered">
		<?php else : ?>
		<ul class="latestnews-items">
		<?php endif; ?>
		
			<?php foreach ($list as $item) :  ?>
			
				<?php
					$extraclasses = "";
					
					$extraclasses .= ($i % 2) ? " even" : " odd";
				
					if ($show_image || $show_calendar) {
						switch ($text_align) {
							case 'l' : $extraclasses .= " head_right"; break;
							case 'r' : $extraclasses .= " head_left"; break;
							case 'lr' : $extraclasses .= ($i % 2) ? " head_left" : " head_right"; break;
							case 'rl' : $extraclasses .= ($i % 2) ? " head_right" : " head_left"; break;
								
							case 't' : $extraclasses .= " text_top"; break;
							case 'b' : $extraclasses .= " text_bottom"; break;
							case 'bt' : $extraclasses .= ($i % 2) ? " text_top" : " text_bottom"; break;
							case 'tb' : $extraclasses .= ($i % 2) ? " text_bottom" : " text_top"; break;
							default :
								
							break;
						}
					}
					
					// if the link is link a..c, replace the label with the text for the links a..c
					// WARNING: 'linktitle' can be the link and not the text of link a..c (in case the text was missing) 
					// -> changed the behavior in helper_standard so that 'linktitle' is 'title' when text is missing
					// note: $item->linktitle and $item->title will always be different if the title is truncated ->strpos (trim the dots)
					$link_label_item = $link_label;
					if ($show_link_label) {
						if (strpos($item->linktitle, rtrim($item->title, '.')) === false) {
							$link_label_item = $item->linktitle;
						}
					}
					
					if ($show_calendar && !empty($item->date)) {
						jimport('joomla.utilities.date');
						$article_date = new JDate($item->date);
	
						$date_params_values["weekday"] = $article_date->format($weekday_format); // 3 letters or full - translate from language .ini file
						$date_params_values["day"] = $article_date->format($day_format); // 01-31 or 1-31
						$date_params_values["month"] = $article_date->format($month_format);
						$date_params_values["year"] = $article_date->format('Y');	
							
						$date_params_values["time"] = JHTML::_('date', $item->date, $time_format); // $date_params_values["time"] = date_format(new DateTime($item->date), $time_format);
						
						$date_params_values["empty"] = '&nbsp;';
					}
					
					if (($show_category && $nbr_cat > 1) || ($show_category && $nbr_cat == 1 && !$consolidate_category)) {
						$cat_label = $cat_link_text;
						if (empty($cat_label)) {
							$cat_label = $item->category_title;
						}
					}
					
					$registry_attribs = new JRegistry;
					$registry_attribs->loadString($item->attribs);
					$info_block = modLatestNewsEnhancedExtendedHelper::getInfoBlock($params, $item, $registry_attribs);				
					
					$i++;
				?>
				
				<?php				
					// check if the link is the same of the article activaly shown 
					$css_active = '';
					$current_url = JURI::current();	
					$url = str_replace(array("tmpl=component", "print=1"), "", $item->link);
					$url = rtrim($url, "?&amp;");
					if (stripos($current_url, $url) !== false) { // the URL contains $item->link
						$css_active = ' active';
					}	
				?>
				
				<?php 
					$css_limited = '';
					if ($leading_items_count > 0) {
						if ($i > $leading_items_count) {
							$css_limited = ' downgraded';
						} else {
							$css_limited = ' full';
						}
					}
	
					$css_shadow = '';
					if ($show_image && $shadow_width_pic > 0) {
						if (!($leading_items_count > 0 && $i > $leading_items_count && $remove_head)) {
							$css_shadow = ' shadow simple';
						}
					}
					
					$css_hover = '';
					if ($show_image && $hover_effect && $show_link && $item->link) {
						$css_hover = ' shrink';
					}
				?>
			
				<li class="latestnews-item catid-<?php echo $item->catid; ?><?php echo $css_active; ?><?php echo $css_limited; ?><?php echo $css_shadow; ?>">
			
					<?php if ($show_errors && !empty($item->error)) : ?>
						<div class="alert alert-error">
		  					<button type="button" class="close" data-dismiss="alert">&times;</button>					
							<?php foreach ($item->error as $error) :  ?>							
		  						<?php echo JText::_('COM_CONTENT_CONTENT_TYPE_ARTICLE').' id '.$item->id.': '.$error; ?>
		  					<?php endforeach; ?>
						</div>
					<?php endif; ?>
				
					<div class="news<?php echo $extraclasses ?>">
						<div class="innernews">						
										
							<?php if ($title_before_head) : ?>
								<div class="newsinfooverhead">
								
									<?php if ($remove_details && $leading_items_count > 0 && $i > $leading_items_count) : ?>
							
									<?php else : ?>
										<?php if (!empty($info_block) && $info_block_placement == 0) : ?>
											<?php echo $info_block; ?>
										<?php endif; ?>
									<?php endif; ?>
								
									<?php if ($show_title) : ?>
										<h<?php echo $title_html_tag; ?> class="newstitle">
											<?php if ($show_link) : ?>
												<?php if ($item->link) : ?>
													<?php echo modLatestNewsEnhancedExtendedHelper::getATag($item, $follow, $popup_width, $popup_height); ?>
														<span><?php echo $item->title; ?></span>
													</a>
												<?php else : ?>
													<span><?php echo $item->title; ?></span>
												<?php endif; ?>	
											<?php else : ?>
												<span><?php echo $item->title; ?></span>
											<?php endif; ?>
										</h<?php echo $title_html_tag; ?>>
									<?php endif; ?>	
										
									<?php if (!empty($info_block) && $info_block_placement == 1) : ?>
										<?php echo $info_block; ?>
									<?php endif; ?>
								</div>
							<?php endif; ?>	
							
							<?php if ($remove_head && $leading_items_count > 0 && $i > $leading_items_count) : ?>
							
							<?php else : ?>
								<?php if ($show_image) : ?>				
									
									<?php if (!empty($item->imagetag) || $keep_space) : ?>	
										<div class="newshead picturetype<?php echo $css_hover; ?>">
											<?php if (!empty($item->imagetag)) : ?>
												<div class="picture">
													<div class="innerpicture">
														<?php if ($show_link) : ?>
															<?php if ($item->link) : ?>
																<?php echo modLatestNewsEnhancedExtendedHelper::getATag($item, $follow, $popup_width, $popup_height); ?>
																	<?php echo $item->imagetag; ?>
																</a>
															<?php else : ?>
																<?php echo $item->imagetag; ?>
															<?php endif; ?>	
														<?php else : ?>
															<?php echo $item->imagetag; ?>
														<?php endif; ?>	
													</div>						
												</div>
											<?php elseif ($keep_space) : ?>	
												<div class="nopicture">
													<?php if ($show_link) : ?>
														<?php if ($item->link) : ?>
															<?php echo modLatestNewsEnhancedExtendedHelper::getATag($item, $follow, $popup_width, $popup_height); ?>
																<span></span>
															</a>
														<?php else : ?>
															<span></span>
														<?php endif; ?>	
													<?php else : ?>
														<span></span>
													<?php endif; ?>							
												</div>
											<?php endif; ?>	
										</div>		
									<?php endif; ?>						
									
								<?php elseif ($show_calendar) : ?>	
									<?php if (!empty($item->date) || $keep_space) : ?>
										<div class="newshead calendartype">
											<?php if (!empty($item->date)) : ?>			
												<div class="calendar <?php echo $extracalendarclass; ?>">
													<?php if (!empty($date_params_keys[0])) : ?>
														<span class="position1 <?php echo $date_params_keys[0]?>"><?php echo $date_params_values[$date_params_keys[0]]; ?></span>
													<?php endif; ?>									
													<?php if (!empty($date_params_keys[1])) : ?>
														<span class="position2 <?php echo $date_params_keys[1]?>"><?php echo $date_params_values[$date_params_keys[1]]; ?></span>
													<?php endif; ?>
													<?php if (!empty($date_params_keys[2])) : ?>
														<span class="position3 <?php echo $date_params_keys[2]?>"><?php echo $date_params_values[$date_params_keys[2]]; ?></span>	
													<?php endif; ?>
													<?php if (!empty($date_params_keys[3])) : ?>
														<span class="position4 <?php echo $date_params_keys[3]?>"><?php echo $date_params_values[$date_params_keys[3]]; ?></span>
													<?php endif; ?>
													<?php if (!empty($date_params_keys[4])) : ?>
														<span class="position5 <?php echo $date_params_keys[4]?>"><?php echo $date_params_values[$date_params_keys[4]]; ?></span>
													<?php endif; ?>											
												</div>
											<?php elseif ($keep_space) : ?>	
												<div class="calendar nodate"></div>
											<?php endif; ?>	
										</div>
									<?php endif; ?>
								<?php endif; ?>
							<?php endif; ?>	
					
							<?php if ($show_image && empty($item->imagetag) && !$keep_space) : ?>
								<div class="newsinfo noimagespace">
							<?php else : ?>
								<div class="newsinfo">
							<?php endif; ?>						
							
								<?php if (!$title_before_head) : ?>
								
									<?php if ($remove_details && $leading_items_count > 0 && $i > $leading_items_count) : ?>
							
									<?php else : ?>
										<?php if (!empty($info_block) && $info_block_placement == 0) : ?>
											<?php echo $info_block; ?>
										<?php endif; ?>
									<?php endif; ?>
								
									<?php if ($show_title) : ?>
										<h<?php echo $title_html_tag; ?> class="newstitle">
											<?php if ($show_link) : ?>
												<?php if ($item->link) : ?>
													<?php echo modLatestNewsEnhancedExtendedHelper::getATag($item, $follow, $popup_width, $popup_height); ?>
														<span><?php echo $item->title; ?></span>
													</a>
												<?php else : ?>
													<span><?php echo $item->title; ?></span>
												<?php endif; ?>	
											<?php else : ?>
												<span><?php echo $item->title; ?></span>
											<?php endif; ?>
										</h<?php echo $title_html_tag; ?>>
									<?php endif; ?>			
								<?php endif; ?>	
									
								<?php if ($remove_details && $leading_items_count > 0 && $i > $leading_items_count) : ?>
							
								<?php else : ?>	
									<?php if (!empty($info_block) && ($info_block_placement == 3 || ($info_block_placement == 1 && !$title_before_head))) : ?>
										<?php echo $info_block; ?>
									<?php endif; ?>	
								<?php endif; ?>
								
								<?php if ($remove_text && $leading_items_count > 0 && $i > $leading_items_count) : ?>
							
								<?php else : ?>
									<?php if (!empty($item->text)) : ?>
										<div class="newsintro">
											<?php echo $item->text; ?>
											<?php if ($show_link_label && $append_link && !empty($link_label_item)) : ?>
												<?php if ($item->link) : ?>
													<?php echo modLatestNewsEnhancedExtendedHelper::getATag($item, $follow, $popup_width, $popup_height); ?>
														<span><?php echo $link_label_item; ?></span>
													</a>
												<?php endif; ?>	
											<?php endif; ?>
										</div>
									<?php endif; ?>
								<?php endif; ?>							
								
								<?php if ($remove_details && $leading_items_count > 0 && $i > $leading_items_count) : ?>
							
								<?php else : ?>	
									<?php if (!empty($info_block) && $info_block_placement == 2) : ?>
										<?php echo $info_block; ?>
									<?php endif; ?>
								<?php endif; ?>							
								
								<?php if ($show_link_label && !$append_link && !empty($link_label_item)) : ?>
									<?php if ($item->link) : ?>								
										<p class="link"<?php echo $extrareadmorestyle; ?>>
											<?php echo modLatestNewsEnhancedExtendedHelper::getATag($item, $follow, $popup_width, $popup_height, $extrareadmoreclass); ?>
												<span><?php echo $link_label_item; ?></span>
											</a>
										</p>
									<?php endif; ?>
								<?php endif; ?>
								
								<?php if (($show_category && $nbr_cat > 1) || ($show_category && $nbr_cat == 1 && !$consolidate_category)) : ?>
									<p class="catlink">		
										<?php if ($link_category) : ?>						
											<a href="<?php echo $item->catlink; ?>" title="<?php echo $cat_label; ?>">
												<span><?php echo $cat_label; ?></span>
											</a>
										<?php else : ?>
											<span><?php echo $cat_label; ?></span>
										<?php endif; ?>
									</p>
								<?php endif; ?>							
							</div>	
						</div>
					</div>
					
				</li>
					
			<?php endforeach; ?>
		
		</ul>
		
		<?php if ($animation) : ?>	
			<?php if (!empty($pagination) && ($pagination_position_type == 'below' || $pagination_position_type == 'around')) : ?>
				<?php 
					$_GET = array();
					$_GET['suffix'] = $class_suffix;
					$_GET['prev'] = $label_prev;
					$_GET['next'] = $label_next;
					if ($pagination == 'p' || $pagination == 's') {
						$_GET['prevnext'] = '0';
					}
					$_GET['pos'] = $pagination_position_bottom;
					if (!empty($extra_pagination_classes)) {
						$_GET['classes'] = $extra_pagination_classes;
					}
				?>
				<?php if (JFile::exists(dirname(__FILE__).'/pagination/'.$animation.'.php')) : ?>
					<div class="clearfix"></div>
					<?php include 'pagination/'.$animation.'.php'; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
		
	</div>
<?php endif; ?>
