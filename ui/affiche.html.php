<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='fr' lang='fr'>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=iso-8859-1' />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<title><?php echo SITE_TITLE;?></title>
<link rel='shortcut icon' href='<?php echo IMAGE_ICO;?>' /> 
<link rel='stylesheet' type='text/css' media='screen' href='<?php echo auto_version(SCREEN_CSS); ?>' />
<link rel='stylesheet' type='text/css' href='<?php echo auto_version(BASE_CSS); ?>' />
<link rel='stylesheet' type='text/css' media='print' href='<?php echo auto_version(PRINT_CSS); ?>' />
<link rel='stylesheet' type='text/css' href='<?php echo auto_version(CALENDAR_CSS); ?>' />
<link rel='stylesheet' type='text/css' href='<?php echo auto_version(MODALBOX_CSS); ?>' />
<script type='text/javascript' src='<?php echo auto_version(PROTOTYPE); ?>'></script>
<script type='text/javascript' src='<?php echo auto_version(SCRIPTACULOUS); ?>'></script>
<script type='text/javascript' src='<?php echo auto_version(OVERLIB); ?>'></script>
<script type='text/javascript' src='<?php echo auto_version(TINYMCE); ?>'></script>
<script type='text/javascript' src='<?php echo auto_version(RICH_INPLACE_EDITOR); ?>'></script>
<script type='text/javascript' src='<?php echo auto_version(FUNCTION_JS); ?>'></script>
<script type='text/javascript' src='<?php echo auto_version(CALENDAR_JS); ?>'></script>
<script type='text/javascript' src='<?php echo auto_version(CALENDAR2_JS); ?>'></script>
<script type='text/javascript' src='<?php echo auto_version(MULTISELECT); ?>'></script>
<script type='text/javascript' src='<?php echo auto_version(MODALBOX); ?>'></script>
</head>
<body>

<div id="overDiv"></div>
<div id="msg"  style='display: none;z-index: 61;'></div>
<div style="position: fixed; top: 0px; z-index: 60; width: 100%;"><div id="loading"  style='display: none;'><img src="<?php echo IMG_LOADING; ?>" /> Chargement...</div></div>
	<div id='header'>
	    <div id='logoheader' onclick='location.replace("<?php echo PROSERVIA_WEBSITE; ?>")'></div>
            <div id='msgWelcome'>
	    <a href="../public/index.php"><?php echo SITE_TITLE; ?></a><br /><br />
		<?php echo $_SESSION[SESSION_PREFIX.'logged']->getWelcomeMsg(); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;Société <?php echo $_SESSION['societe']; ?>
	    </div>
		<img src="<?php echo IMG_BACK; ?>" onclick="afficheMenu()" onmouseover="return overlib('<div class=commentaire>Afficher les menus</div>', FULLHTML);" onmouseout="return nd();" />&nbsp;
		<img src="<?php echo IMG_FORWARD; ?>" onclick="cacheMenu()" onmouseover="return overlib('<div class=commentaire>Cacher les menus</div>', FULLHTML);" onmouseout="return nd();" />&nbsp;
		<br />
		<img src="<?php echo IMG_DOWN; ?>" onclick="afficheFiltre()" onmouseover="return overlib('<div class=commentaire>Afficher les filtres</div>', FULLHTML);" onmouseout="return nd();" />&nbsp;
		<img src="<?php echo IMG_UP; ?>" onclick="cacheFiltre()" onmouseover="return overlib('<div class=commentaire>Cacher les filtres</div>', FULLHTML);" onmouseout="return nd();" />&nbsp;
	</div>
    
    <div id='conteneur'>
		<div id='filtre'>
		    <?php if($_SESSION['cacheFiltre'] == 0) {echo $filtre;} ?>
		</div>
	    <div id='innerWrapper'>
		        <div id='leftMenu'>
		            <?php if($_SESSION['cacheMenu'] == 0) {include $menuGauche;} ?>
			    </div>
		        <div id='rightMenu'>
		            <?php if($_SESSION['cacheMenu'] == 0) {include $menuDroit;} ?>
		        </div>				
		    <div id='content'>
		    	
			    <h1><?php echo $titre; ?></h1><br /><br />
			    <?php echo $contenu; ?>
		    </div>
		</div>
    </div>
	<div id='footer'><br />
	    <?php echo SITE_TITLE; ?> - <?php echo VERSION;?><br />
	    &copy; <a href='mailto:<?php echo MAIL_CONTACT; ?>'><?php echo PROSERVIA_GROUP;?></a>
	</div>
	
	
	<?php
	if($_SESSION['cacheMenu'] == 0) {
	    echo '<script>document.getElementById("content").style.width = "79%";</script>';
	} else {
	    echo '<script>document.getElementById("content").style.width = "100%";</script>';
	}
	?>	
	
    <script>
        new Draggable('rightMenu');
        new Draggable('leftMenu');
    </script>
</body>
</html>