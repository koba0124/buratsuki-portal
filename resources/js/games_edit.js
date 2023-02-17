document.addEventListener('DOMContentLoaded', function() {
	let form_points = document.querySelectorAll('.form_points');
	for (let form of form_points) {
		form.addEventListener('change', calcTotalPoints);
	}
	calcTotalPoints();
	let button_occupations = document.getElementById('occupations_button');
	button_occupations.addEventListener('click', function() {
		let box = document.getElementById('occupations_box');
		let clone = box.lastElementChild.previousElementSibling.cloneNode(true);
		let id = 'form_occupations_' + (box.childElementCount - 1);
		let form = clone.firstElementChild;
		let label = clone.lastElementChild;
		form.value = null;
		form.setAttribute('id', id);
		label.setAttribute('for', id);
		box.insertBefore(clone, box.lastElementChild);
		M.updateTextFields();
	});
	let button_minor_improvements = document.getElementById('minor_improvements_button');
	button_minor_improvements.addEventListener('click', function() {
		let box = document.getElementById('minor_improvements_box');
		let clone = box.lastElementChild.previousElementSibling.cloneNode(true);
		let id = 'form_minor_improvements_' + (box.childElementCount - 1);
		let form = clone.firstElementChild;
		let label = clone.lastElementChild;
		form.value = null;
		form.setAttribute('id', id);
		label.setAttribute('for', id);
		box.insertBefore(clone, box.lastElementChild);
		M.updateTextFields();
	});
	let button_major_improvements = document.getElementById('major_improvements_button');
	button_major_improvements.addEventListener('click', function() {
		let box = document.getElementById('major_improvements_box');
		let clone = box.lastElementChild.previousElementSibling.cloneNode(true);
		let id = 'form_major_improvements_' + (box.childElementCount - 1);
		let form = clone.firstElementChild;
		let label = clone.lastElementChild;
		form.value = null;
		form.setAttribute('id', id);
		label.setAttribute('for', id);
		box.insertBefore(clone, box.lastElementChild);
		M.updateTextFields();
	});
	let button_others = document.getElementById('others_button');
	button_others.addEventListener('click', function() {
		let box = document.getElementById('others_box');
		let clone = box.lastElementChild.previousElementSibling.cloneNode(true);
		let id = 'form_others_' + (box.childElementCount - 1);
		let form = clone.firstElementChild;
		let label = clone.lastElementChild;
		form.value = null;
		form.setAttribute('id', id);
		label.setAttribute('for', id);
		box.insertBefore(clone, box.lastElementChild);
		M.updateTextFields();
	});
});

let calcTotalPoints = () => {
	let form_points = document.querySelectorAll('.form_points');
	let total_points = 0;
	for (let form of form_points) {
		total_points += Number(form.value);
	}
	document.getElementById('form_total_points').value = total_points;
	M.updateTextFields();
};