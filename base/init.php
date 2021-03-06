<?php
/* Copyright 2014, Zunautica Initiatives Ltd.
*  Distributed under the MIT License (http://opensource.org/licenses/MIT)
*/

/**
 * Loads all the headers for the framework
 */

session_start();

include('base/config/config.php');
include('base/config/autoload.php');
include('base/base.php');
include('base/db/dbo.php');
include('base/db/dbaccessor.php');

include('base/helpers/url.php');
include('base/helpers/strings.php');
include('base/helpers/metric.php');
include('base/helpers/hooks.php');

include('base/loaders/GenericLoader.php');
include('base/interfaces/iframework.php');
include('base/loaders/GenericInitialiser.php');
include('base/loaders/BaseInitialiser.php');

include('base/model/Model.php');
include('base/model/BaseModels.php');
include('base/model/ModelPresenter.php');
include('base/model/ModelWrapper.php');

include('base/view/AssetHandler.php');

include('base/view/TemplateContainer.php');
include('base/view/View.php');
include('base/view/Frame.php');

include('base/controller/ControlType.php');
include('base/controller/Controller.php');
include('base/controller/BaseControllers.php');

include('base/plugin/Plugin.php');
include('base/plugin/HookLines.php');

include('base/loaders/BaseLoaders.php');

