document.addEventListener('DOMContentLoaded', function() {
	let tooltips = document.querySelectorAll('.tooltipped');
	M.Tooltip.init(tooltips, {});
	let modals = document.querySelectorAll('.modal');
	M.Modal.init(modals, {});
	let materialBoxes = document.querySelectorAll('.materialboxed');
	M.Materialbox.init(materialBoxes, {});
});
