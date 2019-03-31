document.addEventListener('DOMContentLoaded', function() {
	let tooltips = document.querySelectorAll('.tooltipped');
	M.Tooltip.init(tooltips, {});
	let modals = document.querySelectorAll('.modal');
	M.Modal.init(modals, {});
});