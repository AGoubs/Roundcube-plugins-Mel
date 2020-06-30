var current_page;

$(document).ready(function() {
	$('#_item_logo').change(function(event) {
		if ($(this).val() == "") {
			$('#itemlogo .logobg').hide();
			$('#itemimg img').attr('src', '/plugins/mel_portail/modules/' + $('#_item_type').val() + '/logo.png')
			$('#itemimg img').removeClass('custom')
			$('#itemimg img').attr('style', '')
			$('#_item_logobg').val('');
		}
		else {
			$('#itemlogo .logobg').show();
			$('#itemimg img').attr('src', $(this).val())
			$('#itemimg img').addClass('custom')
		}
	});
	$('#_item_type').change(function(event) {
		if (!rcmail.env.personal_item_is_new) {
			if ($('#_item_logo').val() == "") {
				$('#itemimg img').attr('src', '/plugins/mel_portail/modules/' + $(this).val() + '/logo.png')
			}
			changeType($(this).val());
		}
		else {
			$('#itemlogo .item_logos img.default').attr('src', '/plugins/mel_portail/modules/' + $(this).val() + '/logo.png')
		}
	});
	$('#_item_logobg').change(function(event) {
		if (rcmail.env.personal_item_is_new) {
			$('#itemlogo .item_logos img.custom').attr('style', 'background-color : ' + $(this).val());
		}
		else {
			$('#itemimg img').attr('style', 'background-color : ' + $(this).val());
		}
	});
	$('#itemtype .item_types > span').click(function(event) {
		if (!$(this).hasClass('selected')) {
			$('#_item_type').val($(this).attr('class'));
			$('#_item_type').change();
			$('#itemtype .item_types > span').removeClass('selected');
			$('#itemtype > legend').text(rcmail.get_label('mel_portail.itemtype_choosen') + rcmail.get_label('mel_portail.' + $(this).attr('class')));
			$(this).addClass('selected');
		}
	});
	$('#itemlogo .item_logos > span').click(function(event) {
		if (!$(this).hasClass('selected')) {
			if ($(this).hasClass('default')) {
				$('#_item_logo').val('');
			}
			else {
				$('#_item_logo').val($(this).find('> img').attr('src'));
			}
			$('#_item_logo').change();
			$('#itemlogo .item_logos > span').removeClass('selected');
			$('#itemlogo > legend').text(rcmail.get_label('mel_portail.itemlogo_choosen') + $(this).attr('class'));
			$(this).addClass('selected');
		}
	});
	if (rcmail.env.personal_item_is_new) {
		$('fieldset.more').hide();
		$('#itemlogo').hide();
		$('.formbuttons .save').hide();
		$('#itemtype .item_types > span.' + $('#_item_type').val()).addClass('selected');
		$('#itemtype > legend').text(rcmail.get_label('mel_portail.itemtype_choice'));
		$('#itemlogo .item_logos img.default').attr('src', '/plugins/mel_portail/modules/' + $('#_item_type').val() + '/logo.png')
		$('#itemlogo .item_logos span.default').addClass('selected');
		$('#itemlogo > legend').text(rcmail.get_label('mel_portail.itemlogo_choice'));
		setPage('type');
	}
	else {
		// Affichage du type
		changeType(rcmail.env.personal_item.type);
	}
	
	// Gestion du logo
	if ($('#_item_logo').val() != undefined && $('#_item_logo').val() != "" || $('#itemlogo .logo .readonly').length) {
		$('#itemlogo .logobg').show();
		if ($('#_item_logobg').val() != "" && $('#_item_logo').val() != undefined) {
			$('#itemimg img').attr('style', 'background-color : ' + $('#_item_logobg').val());
			$('#itemimg img').addClass('custom');
		}
		else if ($('#itemlogo .logobg .readonly').length) {
			$('#itemimg img').attr('style', 'background-color : ' + $('#itemlogo .logobg .readonly').text());
			$('#itemimg img').addClass('custom');
		}
	}
});

if (window.rcmail) {
	rcmail.addEventListener('init', function(evt) {
		rcmail.enable_command('previous_edit', true);
		rcmail.enable_command('next_edit', true);
		// register commands
		rcmail.register_command('previous_edit', function() {
			rcmail.previous_edit();
		});
		rcmail.register_command('next_edit', function() {
			rcmail.next_edit();
		});
	});
}

/**
 * Show/hide elements when changing type
 */
function changeType(type) {
	$('fieldset.more').hide();
	$('fieldset.' + type).show();
	if (rcmail.env.nologo.includes(type)) {
		$('#table_logo').hide();
		$('#iteminfo .name').hide();
		$('#iteminfo .tooltip').hide();
		$('#iteminfo .description').hide();
	}
	else {
		$('#table_logo').show();
		$('#iteminfo tr').show();
	}
}

rcube_webmail.prototype.mel_portail_reload_page = function() {
	setTimeout(function() {
		if (rcmail.env.framed) {
			window.parent.location = rcmail.url('settings/plugin.mel_resources_portail');
		}
		else {
			window.location = rcmail.url('settings/plugin.mel_resources_portail');
		}
	}, 500);
};

function setPage(page) {
	window.page = page;
	switch (page) {
		case 'type':
			$('#itemtype').show();
			$('.formbuttons .next').show();
			$('.formbuttons .previous').hide();
			$('.formbuttons .save').hide();
			break;
		case 'logo':
			$('#itemlogo').show();
			$('.formbuttons .next').show();
			$('.formbuttons .previous').show();
			$('.formbuttons .save').hide();
			break;
		case 'more':
			$('fieldset.' + $('#_item_type').val()).show();
			$('.formbuttons .save').show();
			$('.formbuttons .previous').show();
			$('.formbuttons .next').hide();
			break;
	}
	$(window).resize();
	$(window).scrollTop(0);
}

rcube_webmail.prototype.previous_edit = function() {
	switch (window.page) {
		case 'type':
			break;
		case 'logo':
			$('#itemlogo').hide();
			setPage('type');
			break;
		case 'more':
			var type = $('#_item_type').val();
			$('fieldset.' + type).hide();
			if (rcmail.env.nologo.includes(type)) {
				setPage('type');
			}
			else {
				setPage('logo');
			}
			break;
	}
};

rcube_webmail.prototype.next_edit = function() {
	switch (window.page) {
		case 'type':
			$('#itemtype').hide();
			if (rcmail.env.nologo.includes($('#_item_type').val())) {
				setPage('more');
			}
			else {
				setPage('logo');
			}
			break;
		case 'logo':
			$('#itemlogo').hide();
			setPage('more');
			break;
		case 'more':
			break;
	}
};