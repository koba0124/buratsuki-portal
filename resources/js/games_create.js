import Http from './services/http.js'

document.addEventListener('DOMContentLoaded', function() {
	let selects = document.querySelectorAll('select');
	M.FormSelect.init(selects, {});
	changePlayersNumber();
	document.getElementById('form_players_number').addEventListener('change', changePlayersNumber);

	let http = new Http();
	http.get('/users', {}, (response) => {
		let elems = document.querySelectorAll('.autocomplete');
		M.Autocomplete.init(elems, {data: response.data, limit: 3});
	}, (error) => {
		console.log(error);
	});
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
