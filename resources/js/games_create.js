document.addEventListener('DOMContentLoaded', function() {
	let elems = document.querySelectorAll('select');
	M.FormSelect.init(elems, {});
	changePlayersNumber();
	document.getElementById('form_players_number').addEventListener('change', changePlayersNumber);
});

let changePlayersNumber = () => {
	let players_number = Number(document.getElementById('form_players_number').value);
	// 表示
	for (let i = 0; i < players_number; i++) {
		document.getElementById('formBox_players' + i).style.display = 'block';
		document.getElementById('form_players' + i).removeAttribute('disabled');
	}
	// 非表示
	for (let i = players_number; i < 6; i++) {
		document.getElementById('formBox_players' + i).style.display = 'none';
		document.getElementById('form_players' + i).setAttribute('disabled', 'disabled');
	}
};