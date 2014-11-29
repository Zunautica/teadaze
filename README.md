# Teadaze
## MVC / HMVC hybrid

This is *early* but functional stages of a MVC and sort of Hierarchical MVC hybrid PHP framework mainly built for inhouse use.

If anyone is interested in some of the implementationd details, it's probably best to push the source through PHPDoc to see the fairly comprehensive comments that are included.

However! as a quick overview these are some of the concepts you'll find within:


### Conceptual Overview

#### Controllers

Controllers generally function in the same way as many web based MVC architectures (which is a little different from the original pattern); they load the model, load the view and pass data from the model to the view. Controllers manage their own set of views, rather than there being a repository of many views. This allows controllers to - by design - handle a particular task. Controllers also act as the destination for dynamic requests from their views.

#### Models

Models have the same function as the pattern - they represent domain logic. A model could represent anything from authentication protocols to user accounts.

#### Views

Views represent the visual presentation of the model that was loaded up by the controller. Views are specific to controllers.

#### Complex Controllers

Complex Controllers function as anchor points for creating composite views; because views are tied to controllers, if you want more than one view (or rather think of it more in terms of more than one task controller) for a page, then you need to chainload one 'primary' controller in the complex controller to handle building the composite view, then load the other task controllers and merge their views into the primary controller. This allows for top-down creation of - say - a frame to surround a particular view.

#### Plugins; Hooks, Lines and Sinkers

Hooks are what you might expect, points of the flow where you can insert functionality. The inserted functionality is built up from strings of plugins on what is called a 'hookline'. The hookline is attached to a hook and then a 'sinker' is passed through the plugins sequentially. The sinker is data to be manipulated depending on the context of the hook. Plugins are built for specific types of hookline and can even change the flow in certain situations. At the moment you can attach hooklines to controllers, models and certain framework hooks but it will increase where necessary. A plugin can manage logging in, authentication etc. You can create hooks on models and their methods, especially called up by certain controllers and their views. which leads me onto...

#### Model Wrappers and CVM Hooks

Model Wrappers are hooked transparently onto models and their methods in what are called CVM Hooks (because they are described by the Controller/View to Model). Their hooklines hold plugins that manipulate the data coming out of the model. This means that data format can continue to be arbitrarily handled by the model and the controller, but can be formatted specifically for the view. For instance - a model might spit out Markdown; the controller doesn't care what format it is in but the view is expecting HTML so the wrapper transparently formats the data using plugins and the controller just thinks it's just talking directly to the model as usual.



### Notes

#### Routing

Extra routing can be handled simply by a plugin that can be attached to the onrequest hook. There is a bundled - currently extremely basic - plugin in /base/opt. Other than that - controller names act as landing points for the controller. Controllers can be locked if they are specifically designed not to be loaded up like that.

Addendum: Added a wiki style router as well -  see comments for more details

#### ORM

The only bit of bundled ORM like stuff is an abstraction of a table, callable by models, that makes basic queries easier; That is probably going to be the extent of bundled ORM.

#### Subdomain or Root

Currently only tested as functional in a root directory of a (sub)domain.

#### /base/opt/

In /base/opt is the directory tree for a clean site. Copy that 'site' directory into the root - so the site specific stuff goes in /site, the framework stuff is in /base. The reason the directory tree for the site is not in the root of the repo is because there is work going on in that directory as another repo. Not too keen on submodules so the entire directory is ignored.

/base/opt also contains optional things, like some of the small JS libraries for setting up AJAX and modifying the DOM. Of course you can plugin your favorite library in the /site/assets/scripts directory.

#### Assets

When setting assets in views, the directory is relative to /site/assets/[scripts,styles]. Images are relative to root since the landing is /index.php.

#### Teadaze

Not one of mine, but from the excellent pun 'Around the world in a tea daze'


### License

Released under MIT License
