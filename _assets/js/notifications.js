$(document).ready(function() {
	function readNotifications(notificationid, fadeOut = 1) {
		$.ajax({
			url: urlRoute.getBaseUrl() + 'xhrst/' + 'usercp/notices/read/'+notificationid,
			type: 'get',
			success: function(data) {
				if(data['response'] == true) {
					urlRoute.currentNotis--;
					urlRoute.reloadTitle();
					if(fadeOut == 1) {
						$('#notification-'+notificationid).fadeOut();
					}

          if(data['amount'] < 1) {
						$('.new-tag-pc').fadeOut();
						$('.new-tag-phone').fadeOut();
					}

          urlRoute.reloadTitle();
				}
			}
		});
	}

	$("body").on('click', '.notif-link', function(event) {
		event.preventDefault();
		var id = $(this).parent().attr('id');
		if(!id) {
			id = $(this).parent().parent().attr('id');
		}
		id = id.replace("notification-", "");
		readNotifications(id);
		urlRoute.reloadTitle();
	 });
});
