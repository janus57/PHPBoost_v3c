	{HEADER}
	<script type="text/javascript">
	<!--
	function insert_popup(code) 
	{
		var area = opener.document.getElementById("{FIELD}");
		var nav = navigator.appName; //Recup�re le nom du navigateur

		window.opener.insertTinyMceContent(code); //insertion pour tinymce.
		
		area.focus();
		if( nav == 'Microsoft Internet Explorer' ) // Internet Explorer
			ie_sel(area, code, 'smile');
		else if( nav == 'Netscape' || nav == 'Opera' ) //Netscape ou opera
			netscape_sel(area, code, 'smile');
		else //insertion normale (autres navigateurs)
			opener.document.getElementById("{FIELD}").value += ' ' + code;
	}
	function close_popup()
	{
		opener=self;
		self.close();
	}
	function Confirm_file() {
		return confirm("{L_CONFIRM_DEL_FILE}");
	}
	function Confirm_folder() {
		return confirm("{L_CONFIRM_DEL_FOLDER}");
	}	
	function Confirm_member() {
		return confirm("{L_CONFIRM_EMPTY_FOLDER}");
	}
	function popup_upload(id, width, height, scrollbars)
	{
		if( height == '0' )
			height = screen.height - 150;
		if( width == '0' )
			width = screen.width - 200;
		window.open('../member/upload_popup.php?id=' + id, "", "width="+width+", height="+height+ ",location=no,status=no,toolbar=no,scrollbars=" + scrollbars + ",resizable=yes");
	}
	var hide_folder = false;
	var empty_folder = 0;
	
	function display_new_folder()
	{
		if( document.getElementById('empty_folder') )
				document.getElementById('empty_folder').style.display = 'none';	
		
		if ( typeof this.divid == 'undefined' )
			this.divid = 0;
		else
			this.divid++;
			
		if( !hide_folder )
		{
			document.getElementById('new_folder').innerHTML += '<div style="width:210px;height:90px;float:left;margin-top:5px;" id="new_folder' + divid + '"><table style="border:0"><tr><td style="width:34px;vertical-align:top;"><img src="../templates/{THEME}/images/upload/folder_max.png" alt="" /></td><td style="padding-top:8px;"><input type="text" name="folder_name" id="folder_name" class="text" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + divid + ');" /></td></tr></table></div>';
			document.getElementById('folder_name').focus();
		}
		else
		{	
			document.getElementById('new_folder' + (divid - 1)).style.display = 'block';
			document.getElementById('new_folder' + (divid - 1)).innerHTML = '<div style="width:210px;height:90px;float:left;margin-top:5px;" id="new_folder' + divid + '"><table style="border:0"><tr><td style="width:34px;vertical-align:top;"><img src="../templates/{THEME}/images/upload/folder_max.png" alt="" /></td><td style="padding-top:8px;"><input type="text" name="folder_name" id="folder_name" class="text" value="" onblur="add_folder(\'{FOLDER_ID}\', \'{USER_ID}\', ' + (divid - 1) + ');" /></td></tr></table></div>';
			document.getElementById('folder_name').focus();
			this.divid--;	
			hide_folder = false;
		}
	}
	function display_rename_folder(id, previous_name, previous_cut_name)
	{
		if( document.getElementById('f' + id) )
		{	
			document.getElementById('f' + id).innerHTML = '<input type="text" name="finput' + id + '" id="finput' + id + '" class="text" value="' + previous_name + '" onblur="rename_folder(\'' + id + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');" />';
			document.getElementById('finput' + id).focus();
		}
	}		
	function rename_folder(id_folder, previous_name, previous_cut_name)
	{
		var name = document.getElementById('finput' + id_folder).value;
		var regex = /\/|\.|\\|\||\?|<|>|\"/;
		
		document.getElementById('img' + id_folder).innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
		if( name != '' && regex.test(name) ) //interdiction des caract�res sp�ciaux dans la nom.
		{
			alert("{L_FOLDER_FORBIDDEN_CHARS}");
			document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="upload.php?f=' + id_folder + '{POPUP}">' + previous_cut_name + '</a>';
			document.getElementById('img' + id_folder).innerHTML = '';
		}
		else if( name != '' )
		{
			name2 = escape_xmlhttprequest(name);
			data = "id_folder=" + id_folder + "&name=" + name2 + "&previous_name=" + previous_name;
			var xhr_object = xmlhttprequest_init('../kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&rename_folder=1');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 ) 
				{
					if( xhr_object.responseText != '' )
					{
						document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="upload.php?f=' + id_folder + '{POPUP}">' + name + '</a>';
						document.getElementById('fhref' + id_folder).innerHTML = '<a href="javascript:display_rename_folder(\'' + id_folder + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a>';
					}
					else
					{	
						alert("{L_FOLDER_ALREADY_EXIST}");
						document.getElementById('f' + id_folder).innerHTML = '<a class="com" href="upload.php?f=' + id_folder + '{POPUP}">' + previous_cut_name + '</a>';
					}
					document.getElementById('img' + id_folder).innerHTML = '';
				}
				else if( xhr_object.readyState == 4 )
					document.getElementById('img' + id_folder).innerHTML = '';
			}
			xmlhttprequest_sender(xhr_object, data);
		}
	}	
	function add_folder(id_parent, user_id, divid)
	{
		var name = document.getElementById("folder_name").value;
		var regex = /\/|\.|\\|\||\?|<|>|\"/;

		if( name != '' && regex.test(name) ) //interdiction des caract�res sp�ciaux dans le nom.
		{
			alert("{L_FOLDER_FORBIDDEN_CHARS}");
			document.getElementById('new_folder' + divid).innerHTML = '';
			document.getElementById('new_folder' + divid).style.display = 'none';
			hide_folder = true;
			if( document.getElementById('empty_folder') && empty_folder == 0 )
				document.getElementById('empty_folder').style.display = 'block';
		}
		else if( name != '' )
		{
			name2 = escape_xmlhttprequest(name);
			data = "name=" + name2 + "&user_id=" + user_id + "&id_parent=" + id_parent;
			var xhr_object = xmlhttprequest_init('../kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&new_folder=1');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 ) 
				{
					if( xhr_object.responseText > 0 )
					{
						document.getElementById('new_folder' + divid).innerHTML = '<table style="border:0"><tr><td style="width:34px;vertical-align:top;"><a href="upload.php?f=' + xhr_object.responseText + '{POPUP}"><img src="../templates/{THEME}/images/upload/folder_max.png" alt="" /></a></td><td style="padding-top:8px;"> <span id="f' + xhr_object.responseText + '"><a class="com" href="upload.php?f=' + xhr_object.responseText + '{POPUP}">' + name + '</a></span></span><div style="padding-top:5px;"><span id="fhref' + xhr_object.responseText + '"><span id="fihref' + xhr_object.responseText + '"><a href="javascript:display_rename_folder(\'' + xhr_object.responseText + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + name.replace(/\'/g, "\\\'") + '\');"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a></span></a></span> <a href="upload.php?delf=' + xhr_object.responseText + '&amp;f={FOLDER_ID}{POPUP}" onclick="javascript:return Confirm_folder();"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="" class="valign_middle" /></a> <a href="upload.php?move=' + xhr_object.responseText + '{POPUP}" title="{L_MOVETO}"><img src="../templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a></div><span id="img' + xhr_object.responseText + '"></span></td></tr></table>';
						var total_folder = document.getElementById('total_folder').innerHTML;
						total_folder++;						
						document.getElementById('total_folder').innerHTML = total_folder;
						
						empty_folder++;
					}
					else
					{	
						alert("{L_FOLDER_ALREADY_EXIST}");
						document.getElementById('new_folder' + divid).innerHTML = '';
						document.getElementById('new_folder' + divid).style.display = 'none';
						hide_folder = true;
					}
				}
			}
			xmlhttprequest_sender(xhr_object, data);
		}
		else
		{
			if( document.getElementById('empty_folder') && empty_folder == 0 )
				document.getElementById('empty_folder').style.display = 'block';
			document.getElementById('new_folder' + divid).innerHTML = '';
			document.getElementById('new_folder' + divid).style.display = 'none';
			hide_folder = true;
		}
	}
	function display_rename_file(id, previous_name, previous_cut_name)
	{
		if( document.getElementById('fi' + id) )
		{	
			document.getElementById('fi1' + id).style.display = 'none';
			document.getElementById('fi' + id).style.display = 'inline';
			document.getElementById('fi' + id).innerHTML = '<input type="text" name="fiinput' + id + '" id="fiinput' + id + '" class="text" value="' + previous_name + '" onblur="rename_file(\'' + id + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');" />';
			document.getElementById('fiinput' + id).focus();
		}
	}	
	function rename_file(id_file, previous_name, previous_cut_name)
	{
		var name = document.getElementById("fiinput" + id_file).value;
		var regex = /\/|\\|\||\?|<|>|\"/;

		document.getElementById('imgf' + id_file).innerHTML = '<img src="../templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />';
		if( name != '' && regex.test(name) ) //interdiction des caract�res sp�ciaux dans la nom.
		{
			alert("{L_FOLDER_FORBIDDEN_CHARS}");	
			document.getElementById('fi1' + id_file).style.display = 'inline';
			document.getElementById('fi' + id_file).style.display = 'none';
			document.getElementById('imgf' + id_file).innerHTML = '';
		}
		else if( name != '' )
		{
			name2 = escape_xmlhttprequest(name);
			data = "id_file=" + id_file + "&name=" + name2 + "&previous_name=" + previous_cut_name;
			var xhr_object = xmlhttprequest_init('../kernel/framework/ajax/uploads_xmlhttprequest.php?token={TOKEN}&rename_file=1');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' ) 
				{					
					if( xhr_object.responseText == '/' )
					{
						alert("{L_FOLDER_ALREADY_EXIST}");	
						document.getElementById('fi1' + id_file).style.display = 'inline';
						document.getElementById('fi' + id_file).style.display = 'none';
					}
					else
					{
						document.getElementById('fi' + id_file).style.display = 'none';
						document.getElementById('fi1' + id_file).style.display = 'inline';
						document.getElementById('fi1' + id_file).innerHTML = xhr_object.responseText;
						document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\');"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a>';
					}
					document.getElementById('imgf' + id_file).innerHTML = '';
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
				{
					document.getElementById('fi' + id_file).style.display = 'none';
					document.getElementById('fi1' + id_file).style.display = 'inline';	
					document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + previous_name.replace(/\'/g, "\\\'") + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');"><img src="../templates/{THEME}/images/{LANG}/edit.png" alt="" class="valign_middle" /></a>';
					document.getElementById('imgf' + id_file).innerHTML = '';					
				}
			}
			xmlhttprequest_sender(xhr_object, data);
		}
	}	
	var delay = 1000; //D�lai apr�s lequel le bloc est automatiquement masqu�, apr�s le d�part de la souris.
	var timeout;
	var displayed = false;
	var previous_block;
	
	//Affiche le bloc.
	function upload_display_block(divID)
	{
		var i;
		
		if( timeout )
			clearTimeout(timeout);
		
		var block = document.getElementById('move' + divID);
		if( block.style.display == 'none' )
		{
			if( document.getElementById(previous_block) )
				document.getElementById(previous_block).style.display = 'none';
			displayed = true;
			block.style.display = 'block';
			previous_block = 'move' + divID;
		}
		else
		{
			block.style.display = 'none';
			displayed = false;
		}
	}
	//Cache le bloc.
	function upload_hide_block(idfield, stop)
	{
		if( stop && timeout )
		{	
			clearTimeout(timeout);
		}
		else if( displayed )
		{
			clearTimeout(timeout);
			timeout = setTimeout('upload_display_block(\'' + idfield + '\')', delay);
		}	
	}
	var selected = 0;
	function select_div(id)
	{
		if( document.getElementById(id) )
		{
			if( selected == 0 )
			{	
				document.getElementById(id).select();
				selected = 1;
			}
			else
			{
				document.getElementById(id).blur();
				selected = 0;
			}
		}	
	}
	-->
	</script>
	
	<table class="module_table" style="margin:8px;margin-bottom:0px;">
		<tr> 
			<th>
				{L_FILES_ACTION}
			</th>
		</tr>							
		<tr> 
			<td class="row2">
				<span style="float:left;">
					<a href="upload.php?root=1{POPUP}"><img src="../templates/{THEME}/images/upload/home.png" class="valign_middle" alt="" /></a>
					<a href="upload.php?root=1{POPUP}">{L_ROOT}</a>
					<br />					
					<a href="upload.php?fup={FOLDER_ID}{POPUP}"><img src="../templates/{THEME}/images/upload/folder_up.png" class="valign_middle" alt="" /></a>
					<a href="upload.php?fup={FOLDER_ID}{POPUP}">{L_FOLDER_UP}</a>
				</span>
				<span style="float:right;">
					<span id="new_folder_link">
						<a href="javascript:display_new_folder();"><img src="../templates/{THEME}/images/upload/folder_new.png" class="valign_middle" alt="" /></a>
						<a href="javascript:display_new_folder();">{L_FOLDER_NEW}</a>
					</span>
					<br />
					<a href="#new_file"><img src="../templates/{THEME}/images/upload/files_add.png" class="valign_middle" alt="" /></a>
					<a href="#new_file">{L_ADD_FILES}</a>		
					<br />
				</span>
			</td>
		</tr>							
		<tr> 
			<td class="row3" style="margin:0px;padding:0px">
				<div style="float:left;padding:2px;padding-left:8px;">
					{L_URL}
				</div>
				<div style="float:right;width:90%;padding:2px;background:#f3f3ee;padding-left:6px;color:black;border:1px solid #7f9db9;">
						<img src="../templates/{THEME}/images/upload/folder_mini.png" class="valign_middle" alt="" /> {U_ROOT}{URL}
				</div>
			</td>
		</tr>	
		
		<tr>	
			<td class="row2" style="padding:10px 2px;">
				<div style="height:260px;overflow:auto;">
					# IF C_EMPTY_FOLDER #
						<p style="text-align:center;padding-top:25px;" id="empty_folder">					
							{L_EMPTY_FOLDER}					
						</p>
					# ENDIF #
					
					# START folder #		
					<div style="width:210px;height:90px;float:left;margin-top:5px;">
						<table style="border:0;">
							<tr>
								<td style="width:34px;vertical-align:top;">
									<a href="upload.php?f={folder.ID}{POPUP}"><img src="../templates/{THEME}/images/upload/folder_max.png" alt="" /></a>
								</td>
								<td style="padding-top:8px;">						
									<span id="f{folder.ID}"><a href="upload.php?f={folder.ID}{POPUP}" class="com">{folder.NAME}</a></span>
									<div style="padding-top:5px;">
										{folder.RENAME_FOLDER}	<a href="upload.php?delf={folder.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" onclick="javascript:return Confirm_folder();" title="{folder.L_TYPE_DEL_FOLDER}">{folder.DEL_TYPE_IMG}</a>
										
										<a href="upload{folder.U_MOVE}" title="{L_MOVETO}"><img src="../templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a>
										
										<span id="img{folder.ID}"></span>
									</div>
								</td>
							</tr>						
						</table>
					</div>
					# END folder #
			
					<span id="new_folder"></span>
					
					# START files #
					<div style="width:210px;height:90px;float:left;margin-top:5px;">
						<table style="border:0;">
							<tr>
								<td style="width:34px;vertical-align:top;">
									{files.IMG}
								</td>
								<td style="padding-top:8px;">	
									<a class="com" href="{files.URL}" title="{files.TITLE}"><span id="fi1{files.ID}">{files.NAME}</span></a><span id="fi{files.ID}"></span><br />
									{files.BBCODE}<br />							
									<span class="text_small">{files.FILETYPE}</span><br />
									<span class="text_small">{files.SIZE}</span><br />
									{files.RENAME_FILE}
									<a href="upload.php?del={files.ID}&amp;f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" onclick="javascript:return Confirm_file();" title="{L_DELETE}"><img src="../templates/{THEME}/images/{LANG}/delete.png" alt="" class="valign_middle" /></a> 
									
									<a href="upload{files.U_MOVE}" title="{L_MOVETO}"><img src="../templates/{THEME}/images/upload/move.png" alt="" class="valign_middle" /></a>								
									
									{files.INSERT}
									<span id="imgf{files.ID}"></span>
								</td>
							</tr>
						</table>
					</div>	
					# END files #				
				</div>
			</td>
		</tr>
		
		# IF C_ERROR_HANDLER #
		<tr>
			<td class="row3">	
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
					<br />	
				</div>
				<br />	
			</td>	
		</tr>
		# ENDIF #
		<tr>				
			<td class="row3" id="new_file">							
				<form action="upload.php?f={FOLDER_ID}&amp;token={TOKEN}{POPUP}" enctype="multipart/form-data" method="post">
					<span style="float:left">						
						<strong>{L_ADD_FILES}</strong>
						<br />
							<input type="file" name="upload_file" size="30" class="file" />					
							<input type="hidden" name="max_file_size" value="2000000" />
							<br />
							<input type="submit" name="valid_up" value="{L_UPLOAD}" class="submit" />							
					</span>	
					<span style="float:right;text-align:right">
						{L_FOLDERS}: <strong><span id="total_folder">{TOTAL_FOLDERS}</span></strong><br />
						{L_FILES}: <strong>{TOTAL_FILES}</strong><br />
						{L_FOLDER_SIZE}: <strong>{TOTAL_FOLDER_SIZE}</strong><br />
						{L_DATA}: <strong>{TOTAL_SIZE}/{SIZE_LIMIT} {PERCENT}</strong>
					</span>	
				</form>				
			</td>
		</tr>	
	</table>
	
	{FOOTER}
	