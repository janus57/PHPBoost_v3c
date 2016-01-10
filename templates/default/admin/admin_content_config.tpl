		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_CONTENT_CONFIG}</li>
				<li>
					<a href="admin_content_config.php"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/content.png" alt="" /></a>
					<br />
					<a href="admin_content_config.php" class="quick_link">{L_CONTENT_CONFIG}</a>
				</li>
			</ul>
		</div>
		
		<script type="text/javascript">
		<!--
			function check_select_multiple(id, status)
			{
				var i;
				
				for(i = 0; i < {NBR_TAGS}; i++)
				{	
					if( document.getElementById(id + i) )
						document.getElementById(id + i).selected = status;			
				}
			}	
		-->
		</script>
		
		<div id="admin_contents">
			<form action="admin_content_config.php?token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_LANGUAGE_CONFIG}</legend>
					<dl> 
						<dt><label for="formatting_language">{L_DEFAULT_LANGUAGE}</label></dt>
						<dd>
							<select name="formatting_language" id="formatting_language">
								<option value="bbcode" {BBCODE_SELECTED}>BBCode</option>
								<option value="tinymce" {TINYMCE_SELECTED}>TinyMCE</option>
							</select>
						</dd>
					</dl>
					<dl> 
						<dt><label for="forbidden_tags">{L_FORBIDDEN_TAGS}</label></dt>
						<dd>
								<select id="forbidden_tags" name="forbidden_tags[]" size="10" multiple="multiple">
								# START tag #
									# IF tag.C_ENABLED #
									<option id="tag{tag.IDENTIFIER}" selected="selected" value="{tag.CODE}">{tag.TAG_NAME}</option>
									# ELSE #
									<option id="tag{tag.IDENTIFIER}" value="{tag.CODE}">{tag.TAG_NAME}</option>
									# ENDIF #
								# END tags #
								</select>
								<br />
								<span class="text_small">({L_EXPLAIN_SELECT_MULTIPLE})</span>
								<br />
								<a class="small_link" href="javascript:check_select_multiple('tag', true);">{L_SELECT_ALL}</a>/<a class="small_link" href="javascript:check_select_multiple('tag', false);">{L_SELECT_NONE}</a>
						</dd>
					</dl>
				</fieldset>
				
				<fieldset>
					<legend>{L_HTML_LANGUAGE}</legend>
					<dl> 
						<dt><label for="groups_auth1">{L_AUTH_USE_HTML}</label></dt>
						<dd>
							{SELECT_AUTH_USE_HTML}
						</dd>
					</dl>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_UPDATE}</legend>
					<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
					<input type="reset" value="{L_RESET}" class="reset" />					
				</fieldset>	
			</form>
		</div>
		
