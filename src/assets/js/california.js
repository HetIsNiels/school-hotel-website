var roomInfo = [];
var roomTypeInfo = [];

var reservationInfo = {
	'part': 1,

	'room': null,
	'roomType': null,

	'checkin': null,
	'checkout': null,
	'people': 1,
	'price': 0
};

function selectRoom(id){
	wait(true);
	$('body').scrollTo(0, 'fast');

	getRoomInfo(function(roomInfo){
		wait(false);

		for(var roomId in roomInfo){
			var room = roomInfo[roomId];

			if(room.id == id){
				reservationInfo.room = room;
				reservationInfo.part = 3;

				var showcase = $('#reservation-rooms-showcase');

				if(showcase.is(':hidden'))
					showcase.slideDown('fast');

				showcase.find('img').attr('src', room['image']);
				showcase.find('h2').html(room['name']);
				showcase.find('p').html(room['description']);

				updateRevervationPart();
			}
		}
	});
}

function updateRevervationPart(){
	$('#reservation-form').children('.part').stop(false, true).slideUp(200).siblings('.part-' + reservationInfo.part).stop(false, true).slideDown(200);
}

function revervationPartNext(event){
	event.preventDefault();

	var newPart = reservationInfo.part + 1;

	switch(newPart){
		case 0:
		case 1:
			alert('Plz no hacks');
			break;

		case 2:
			wait(true);

			var types = $('#reservation-room-types').val() || [];

			if(types.length == 0) {
				alert('Please select one or more room types.');
				wait(false);

				return;
			}

			$.getJSON('inc/ajax/getrooms.php', {types: types}, function(data){
				wait(false);

				var roomSelect = $('#reservation-rooms');
				roomSelect.attr('size', data.length);

				for(var i in data){
					var room = data[i];

					roomSelect.append('<option value="' + room['id'] + '">' + room['name'] + ' - &euro;' + room['price'] + ' p/n</option>');
				}
			}).fail(function(){
				alert('Something went wrong. (getrooms=null,wait=false,part=1)');
				wait(false);

				reservationInfo.part = 1;
				updateRevervationPart();
			});

			break;

		case 3:
			wait(true);
			var rooms = $('#reservation-rooms').val() || [];

			if(rooms.length != 1) {
				alert('You can only reserve one room at a time right now.');
				wait(false);

				return;
			}

			getRoomInfo(function(roomInfo) {
				getRoomTypeInfo(function(roomTypeInfo) {
					wait(false);

					for (var roomId in roomInfo) {
						var room = roomInfo[roomId];

						if (room.id == rooms[0]) {
							reservationInfo.room = room;

							for(var typeId in roomTypeInfo){
								var roomType = roomTypeInfo[typeId];

								if(room.type == roomType.id){
									reservationInfo.roomType = roomType;

									if(roomType['people'] < 2)
										$('#reservation-people-group').hide();
									else
										$('#reservation-people-group').show();

									$('#reservation-people').attr('max', roomType['people']);
								}
							}
						}
					}
				});
			});

			break;

		case 4:
			wait(true);

			reservationInfo.checkin = $('#reservation-checkin').get(0).valueAsDate;
			reservationInfo.checkout = $('#reservation-checkout').get(0).valueAsDate;
			reservationInfo.people = $('#reservation-people').val();

			if(reservationInfo.checkin == null || reservationInfo.checkout == null){
				alert('checkin or checkout is invalid');
				return;
			}

			var days = daydiff(reservationInfo.checkin, reservationInfo.checkout);
			reservationInfo.price = days * reservationInfo.room.price;

			var html  = '<ul>';
				html += '<li>Reservation date: ' + reservationInfo.checkin.getFullYear() + '-' + reservationInfo.checkin.getMonth() + '-' + reservationInfo.checkin.getDate() + ' - ' + reservationInfo.checkout.getFullYear() + '-' + reservationInfo.checkout.getMonth() + '-' + reservationInfo.checkout.getDate() + '</li>';
				html += '<li>Total nights: ' + days + '</li>';
				html += '<li>People: ' + reservationInfo.people + '</li>';
				html += '<li>Price: &euro;' + reservationInfo.price + ',-</li>';
				html += '</ul>';

			$('#reservation-rooms-showcase').find('p').html(html);

			wait(false);

			break;

		case 5:
			wait(true);

			var firstname = $('#reservation-firstname').val();
			var lastname = $('#reservation-lastname').val();

			var address = $('#reservation-address').val();
			var city = $('#reservation-city').val();
			var country = $('#reservation-country').val();

			var email = $('#reservation-email').val();
			var tel = $('#reservation-tel').val();

			var data = {
				firstname: firstname,
				lastname: lastname,
				address: address,
				city: city,
				country: country,
				email: email,
				tel: tel,
				room: reservationInfo.room.id,
				people: reservationInfo.people,
				checkin: reservationInfo.checkin.getTime(),
				checkout: reservationInfo.checkout.getTime()
			};

			$.post('inc/ajax/reserve.php', data, function(data){
				wait(false);

				$('.part.part-5').find('.alert').addClass(data.class).html(data.html);
				$('#reservation-buttons').hide();
			}, 'json');

			break;
	}

	reservationInfo.part = newPart;
	updateRevervationPart();
}

function reservationRoomShowInfo(){
	var rooms = $('#reservation-rooms').val() || [];
	var showcase = $('#reservation-rooms-showcase');

	wait(true);
	getRoomInfo(function(roomInfo) {
		wait(false);

		for (var roomId in roomInfo) {
			var room = roomInfo[roomId];

			if (room.id == rooms[0]) {
				if(showcase.is(':hidden'))
					showcase.slideDown('fast');

				showcase.find('img').attr('src', room['image']);
				showcase.find('h2').html(room['name']);
				showcase.find('p').html(room['description']);
			}
		}
	});
}

function getRoomInfo(callback){
	if(roomInfo.length > 0)
		return callback(roomInfo);

	console.log('Refreshing room information.');
	$.getJSON('inc/ajax/roominfo.php', function(result){
		roomInfo = result;

		callback(roomInfo);
	}).fail(function(){
		alert('Something went wrong. (rooms=null,usecallback=true)');

		callback([]);
	});
}

function getRoomTypeInfo(callback){
	if(roomTypeInfo.length > 0)
		return callback(roomTypeInfo);

	console.log('Refreshing room type information.');
	$.getJSON('inc/ajax/typeinfo.php', function(result){
		roomTypeInfo = result;

		callback(roomTypeInfo);
	}).fail(function(){
		alert('Something went wrong. (roomtypes=null,usecallback=true)');

		callback([]);
	});
}

var waitCount = 0;

function wait(state){
	if(state === true)
		waitCount++;
	else
		waitCount--;

	if(waitCount > 0)
		$('#wait').show();
	else
		$('#wait').hide();
}

function handleContactForm(event){
	event.preventDefault();

	wait(true);

	var form = $('#contact-form');
	var name = form.find('#contact-name');
	var email = form.find('#contact-email');
	var message = form.find('#contact-message');

	$.getJSON('inc/ajax/contact.php', {name: name.val(), email: email.val(), message: message.val()}, function(result){
		wait(false);

		var notif = $('#contact-notif');
		notif.removeClass('hidden');

		if(result.name === false && result.email === false && result.message === false)
			notif.removeClass('alert-danger').addClass('alert-success').html(result.result);
		else
			notif.removeClass('alert-success').addClass('alert-danger').html(result.result);

		if(result.name !== false)
			name.addClass('error').siblings('.error-label').html(result.name);
		else
			name.removeClass('error').siblings('.error-label').html('');

		if(result.email !== false)
			email.addClass('error').siblings('.error-label').html(result.email);
		else
			email.removeClass('error').siblings('.error-label').html('');

		if(result.message !== false)
			message.addClass('error').siblings('.error-label').html(result.message);
		else
			message.removeClass('error').siblings('.error-label').html('');
	});
}

$(document).ready(function(){
	$('#contact-form').on({'submit': handleContactForm});
	$('#reservation-rooms').on({'change': reservationRoomShowInfo});
}).on({'scroll': function(event){
	var topnav = $('#top-nav');

	//if(document.body.scrollTop > window.innerHeight - topnav.height()){
	if(document.body.scrollTop > 0){
		topnav.removeClass('nav-transp');
	}else{
		topnav.addClass('nav-transp');
	}
}});

function parseDate(str) {
	var mdy = str.split('/');
	return new Date(mdy[2], mdy[0]-1, mdy[1]);
}

function daydiff(first, second) {
	return (second-first)/(1000*60*60*24);
}