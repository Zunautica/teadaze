<?php
include('system/config/config.php');
include('system/db/dbo.php');
include('system/db/dbhandler.php');
include('system/db/dbinline.php');
include('system/db/dbaccessor.php');

include('system/models/Model.php');
include('system/models/EntityModel.php');
include('system/models/LoadModel.php');

include('system/controller/TemplateContainer.php');
include('system/controller/Controller.php');
include('controller/portal/PortalController.php');

$db = new DBO();
$db->connect();
LoadModel::setDatabase($db);

$ctx = new PortalController('portal');
$ctx->init();
echo $ctx->show();
