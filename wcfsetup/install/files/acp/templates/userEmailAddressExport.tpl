{include file='header'}

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		new WCF.ACP.Options();
	});
	//]]>
</script>

<header class="mainHeading">
	<img src="{@RELATIVE_WCF_DIR}icon/eMail1.svg" alt="" />
	<hgroup>
		<h1>{lang}wcf.acp.user.exportEmailAddress{/lang}</h1>
	</hgroup>
</header>

<form method="post" action="index.php/UserEmailAddressExport/">
	<div class="border content">
		<fieldset>
			<legend>{lang}wcf.acp.user.exportEmailAddress.markedUsers{/lang}</legend>
			
			<ul>
				{implode from=$users item=$user}<li><a href="index.php/UserEdit/{@$user->userID}/{@SID_ARG_1ST}" class="badge badgeButton">{$user}</a></li>{/implode}
			</ul>
		</fieldset>	
		
		<fieldset>
			<legend>{lang}wcf.acp.user.exportEmailAddress.format{/lang}</legend>
			
			<dl>
				<dt>
					<label>{lang}wcf.acp.user.exportEmailAddress.fileType{/lang}</label>
				</dt>
				<dd>
					<fieldset>
						<legend>{lang}wcf.acp.user.exportEmailAddress.fileType{/lang}</legend>
						
						<dl>
							<dd><!-- ToDo: Definition List -->
								<li><label><input type="radio" class="enablesOptions" data-disableOptions="[ ]" data-enableOptions="[ 'separatorDiv', 'textSeparatorDiv' ]" name="fileType" value="csv" {if $fileType == 'csv'}checked="checked" {/if}/> {lang}wcf.acp.user.exportEmailAddress.fileType.csv{/lang}</label></li>
								<li><label><input type="radio" class="enablesOptions" data-disableOptions="[ 'separatorDiv', 'textSeparatorDiv' ]" data-enableOptions="[ ]" name="fileType" value="xml" {if $fileType == 'xml'}checked="checked" {/if}/> {lang}wcf.acp.user.exportEmailAddress.fileType.xml{/lang}</label></li>
							</dd>
						</dl>
					</fieldset>
				</dd>
			</dl>
		
			<dl id="separatorDiv">
				<dt><label for="separator">{lang}wcf.acp.user.exportEmailAddress.separator{/lang}</label></dt>
				<dd>
					<textarea id="separator" name="separator" rows="2" cols="40">{$separator}</textarea>
				</dd>
			</dl>
			
			<dl id="textSeparatorDiv"><!-- ToDo: Checkbox -->
				<dt><label for="textSeparator">{lang}wcf.acp.user.exportEmailAddress.textSeparator{/lang}</label></dt>
				<dd>
					<input type="text" id="textSeparator" name="textSeparator" value="{$textSeparator}" class="medium" />
				</dd>
			</dl>
		</fieldset>
		
	</div>
	
	<div class="formSubmit">
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SID_INPUT_TAG}
 	</div>
</form>

{include file='footer'}
