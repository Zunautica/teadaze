<html>
<head>
	<title><?php echo $this->view->getTitle(); ?></title>
	<?php
		foreach($this->assets['styles'] as $style)
			echo asset_style_url($style);

		foreach($this->assets['scripts'] as $script)
			echo asset_script_url($script);
	?>
<div>
<h1>Frame</h1>
</h1>
<?php
	echo $this->locked->loadTemplate();
	echo $this->view->loadTemplate();
?>
<div>
<h4>End Frame</h4>
</div>
