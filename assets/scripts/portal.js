function onresponsePortal(reply) {
	alert(reply);
}

Pxi.addEventListener('load', function () {
	document.getElementById('test-button').onclick = function () {
		Pxi.Ajax.request('portal', "foo=bar", onresponsePortal, "hello=world");
	};
});
