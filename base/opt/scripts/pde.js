Node.prototype.clearChildren = function () {
	while(this.firstChild != null)
		this.removeChild(this.firstChild);
}

Node.prototype.appendElement = function (type)  {
	if(type == "input")
		throw { name: 'PDE.ElementException', message: 'Use appendInput for input elements' };

	try {
		var e = document.createElement(type);
		return this.appendChild(e);
	} catch(exc) {
		throw exc;
	}

}

Node.prototype.appendText = function (str) {
	this.appendChild(document.createTextNode(str));
}

Node.prototype.getLast = function () {
	return this.lastChild;
}

Node.prototype.getParent = function () {
	return this.parentNode;
}

Node.prototype.getNext = function () {
	return this.nextSibling;
}
