document.addEventListener('DOMContentLoaded', function() {
	var button_occupations = document.getElementById('occupations_button');
	button_occupations.addEventListener('click', function() {
		var box = document.getElementById('occupations_box');
		var clone = box.lastElementChild.previousElementSibling.cloneNode(true);
		var id = 'form_occupations_' + (box.childElementCount - 1);
		var form = clone.firstElementChild;
		var label = clone.lastElementChild;
		form.value = null;
		form.setAttribute('id', id);
		label.setAttribute('for', id);
		box.insertBefore(clone, box.lastElementChild);
		M.updateTextFields();
	});
	var button_minor_improvements = document.getElementById('minor_improvements_button');
	button_minor_improvements.addEventListener('click', function() {
		var box = document.getElementById('minor_improvements_box');
		var clone = box.lastElementChild.previousElementSibling.cloneNode(true);
		var id = 'form_minor_improvements_' + (box.childElementCount - 1);
		var form = clone.firstElementChild;
		var label = clone.lastElementChild;
		form.value = null;
		form.setAttribute('id', id);
		label.setAttribute('for', id);
		box.insertBefore(clone, box.lastElementChild);
		M.updateTextFields();
	});
});