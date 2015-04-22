![](base/opt/assets/teadaze.png?raw=true)
## MVC / HMVC hybrid

This is Hierarchical/Model-View-Controller framework for PHP. As a base it is fairly small in size, fairly fast and unopinionated.

If anyone is interested in some of the implementation details, the source includes fairly comprehensive comments. You can see the generated documentation at http://teadaze.zunautica.org.

However! as a quick overview these are some of the concepts you'll find within:


### Conceptual Overview

#### Controllers

Controllers generally function in the same way as many web based MVC architectures (which is a little different from the original pattern); they load the model, load the view and pass data from the model to the view. Controllers manage their own set of views, rather than there being a repository of many views. This allows controllers to - by design - handle a particular task.

#### Models

Models have the same function as the pattern - they represent domain logic. A model could represent anything from authentication protocols to user accounts.

#### ModelPresenter

ModelPresenters are a special kind of model that wrap around other models. They implement methods of their contained model to reformat data, otherwise if the method is not implemented in the ModelPresenter, they transparently pass through the call through to the contained model. They are a programmatic and concrete approach to the ModelWrappers (see below)

#### Views

Views represent the visual presentation of the model that was loaded up by the controller. Views are, by default, specific to controllers.

#### Complex Controllers

Complex Controllers function as anchor points for creating composite views; because views are, by default, tied to controllers - if you want more than one view (or rather think of it more in terms of more than one task controller) for a page, then you need to chainload one 'primary' controller in the complex controller to handle building the composite view - this could be a sitewide template, for instance. Next load the other task controllers and merge their views into the primary controller. This allows for top-down creation of where, say, a site template is used for all tasks but has a specific view merged in depending on the task.

#### Task Controllers

Task Controllers are controllers that perform a single task - i.e. a template, an editor, displaying the latest news content etc. Task controllers also act as the end point for dynamic requests. They can be loaded up by themselves or by Complex Controllers

#### Plugins; Hooks, Lines and Sinkers

Hooks are what you might expect, points of the flow where you can insert functionality. One type of hook is where the inserted functionality is built up from strings of plugins and closures on what is called a 'hookline'. The hookline is attached to a hook and then a 'sinker' is passed through the plugins sequentially. The sinker is data to be manipulated depending on the context of the hook. Think of them like audio fitlers.

Plugins or closures are written for specific types of hookline and can even change the flow in certain situations. At the moment you can attach hooklines to controllers, models and certain framework hooks but it will increase where necessary. A plugin can manage logging in, authentication etc. You can create hooks on models and their methods, especially called up by certain controllers and their views. which leads onto...

#### Model Wrappers and CVM Hooks

Model Wrappers, similar to ModelPresenters but setup via configuration, are hooked transparently onto models and their methods in what are called CVM Hooks (because they are described by the Controller/View to Model). Their hooklines hold plugins that manipulate the data coming out of the model. This means that data format can continue to be arbitrarily handled by the model and the controller, but can be formatted specifically for the view. For instance - a model might spit out Markdown; the controller doesn't care what format it is in but the view is expecting HTML so the wrapper transparently formats the data using plugins and the controller just thinks it's just talking directly to the model as usual.

### Dependency Injection

Teadaze uses dependency injection for all it's family of directly usable objects - Models, Views, Controllers, Plugins.

The DI is performed via the use of interfaces - A class can implement a particular kind of DI interface and, as it is sent through the initialiser, it has the dependencies added via setter methods. If the interface is set correctly, the initialiser can locate and load the dependency without it having a specific injection method defined; otherwise an extension to the BaseInitialiser will need to be written with the new injection methods.

### Loaders and Loader Extensions

A loader is the go-to object for loading a specific kind of usable object. These loaders and then injected into objects that require the use of their services. For example, a controller will have the ModelLoader and ViewLoader injected by default which enables it load Models and Views.

Once a loader has instantiated the object, it passes the object through the framework initialiser which injects the dependencies based on the implemented interfaces.

The behaviour can be altered by writing and specifying a separate Loader for a family object. So a different ModelLoader could be written that loads the model in a completely different way.


### Dependencies

\>= PHP 5.3

It can most likely be used on shared web hosting.


### Notes

#### Bundles

There is very little bundled - it's specifically an H/MVC framework. Other libraries are absolutely the user's choice.

#### Routing

Extra routing can be handled simply by a plugin that can be attached to the onrequest hook. There is a bundled - currently extremely basic - plugin in /base/opt which will
demonstrate how to place a router into the hookline. If there is a favorite router, than create an interface via a plugin. 

Addendum: Added a wiki style router as well -  see comments for more details

Other than that - controller names act as landing points for the controller (CI style). Controllers can be locked if they are specifically designed not to be loaded up like that.


#### Subdomain or Root

Currently only tested as functional in a root directory of a (sub)domain.


#### /base/opt/

In /base/opt is the directory tree for a clean site. Copy that 'site' directory into the root - so the site specific stuff goes in /site, the framework stuff is in /base. The reason the directory tree for the site is not in the root of the repo is because there is work going on in that directory as another repo. Not too keen on submodules so the entire directory is ignored.

There's a small JS script that illustrates ajax requests but use of a JS library is recommended.

#### Assets

When setting assets in views, the directory is relative to /site/assets/[scripts,styles].

If the resource is preceeded by http:// or https:// then it is linked to as a seeded resource.

#### Teadaze

Not one of mine, but from the excellent pun 'Around the world in a tea daze'


### License

Released under MIT License
