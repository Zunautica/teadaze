<?php
	include('../../system/config/config.php');
?>
var Pxi = {
	Ajax: {
		requestURL: "<?php echo "/".$config['dynamic_keyword'] ?>",
		requestOpen: false,
		blocking: false,

		/* TODO
		*  Write code for IE 6
		*  Sort out first in queue bug- always uses
		*  previous callback
		*/
		rxo: function () {
			var self = this;

			self.request = null;

			this.initRequest = function () {
				try {
					self.request = new XMLHttpRequest();
				} catch (e) {
					try {
						self.request = new ActiveXObject("Msxml2.XMLHTTP");
					} catch (e) {
						try {
							self.request = new ActiveXObject("Microsoft.XMLHTTP");
						} catch(e) {
							alert("Failed to create request object!");
						}
					}
				}
			
			}

			this.initRequest();

			self.queue = new Array();
			self.lock = false;
			self.isOpen = false;
			self.valTimeout = 3000;

			this.makeRequest = function (controller, args, callback, post) {
				if(self.isOpen)
					push(controller, args, post, callback);
				else
					open(controller, args, post, callback);
			}


			var push = function (controller, args, post, callback) {
					self.queue.push(new Array(controller, args, post, callback));
			}

			var open = function (controller, args, post, callback) {
				self.initRequest();
				self.isOpen = true;
				url = Pxi.Ajax.requestURL+"/"+controller;
				if(args != null)
					url += "?"+args;
				if(post == undefined || post == null)
					self.request.open('GET', url, true);
				else
					self.request.open('POST', url, true);

				self.request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

				if(post != undefined && post != null)
					self.request.setRequestHeader('Content-Length', post.length);

				var cfunc = callback;
				self.request.cfunc = cfunc
				self.request.onreadystatechange = function () {
					statechange(this.cfunc);
				}

				self.request.timeout = self.valTimeout;
				self.request.ontimeout = timeout;
				if(post == undefined || post == null)
					self.request.send();
				else
					self.request.send(post);
			}

			var timeout = function () {
				try {
					console.error("Request timed out ("+self.valTimeout+"ms)");
				} catch(e) {
					alert("timed out");
				}
				self.isOpen = false;

				checkQueue();
			}

			var statechange = function (callback) {
				switch(self.request.readyState) {
				case 4:
					if(callback != undefined)
						callback(self.request.responseText);

					self.request.abort();
					self.isOpen = false;
					checkQueue();
				break;
				}
			}

			var checkQueue = function () {
				if(self.lock)
					setTimeout(function () {checkQueue();}, 30);
				else
				if(self.isOpen)
					setTimeout(function () {checkQueue();}, 30);
				else
				if(self.queue.length > 0) {
					var p = self.queue[0];
					self.queue.splice(0, 1);
					if(p[1] === undefined)
						p[1] = null;
					self.initRequest();
					open(p[0], p[1], p[2]);
				}
			}
		},
		rox: null,

		request: function (controller, args, callback, post) {
			if(Pxi.Ajax.rox == null)
				Pxi.Ajax.rox = new Pxi.Ajax.rxo();

			Pxi.Ajax.rox.makeRequest(controller, args, callback, post);
		}
	},

	hooks: {
		load: {
			list: new Array(),

			add: function (callback) {
				Pxi.hooks.load.list.push(callback);
			},

			execute: function () {
				for(var i = 0; i < Pxi.hooks.load.list.length; i++)
					Pxi.hooks.load.list[i]();
			}
		}
	},

	addEventListener: function(type, callback) {
		if(Pxi.hooks[type] === undefined)
			return undefined;

		Pxi.hooks[type].add(callback);
	}
}

window.addEventListener('load', Pxi.hooks.load.execute);
