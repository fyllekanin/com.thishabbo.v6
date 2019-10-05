'use strict';
sessionStorage.removeItem('log');
var urlRoute = {
	//Holding the base url of the webpage, example "http://thishabbo.com"
	baseUrl: "",
	//Holding the previous url
	previousUrl: "",
	//Current URL
	currentUrl: "",
	currentNotis: 0,
	currentTitle: '',
	lastPage: '',
	lastId: 0,
	setTitle: function (title) {
		this.currentTitle = title;
		document.title = this.currentNotis > 0 ? '(' + this.currentNotis + ') ' + title : title;
	},
	reloadTitle: function () {
		document.title = this.currentNotis > 0 ? '(' + this.currentNotis + ') ' + this.currentTitle : this.currentTitle;
	},
	//Setting the baseUrl
	setBaseUrl: function (url) {
		this.baseUrl = url + '/';
		this.loadNotifications();

		setInterval(function () { this.loadNotifications(true) }.bind(this), 60000);
		return this;
	},
	setPreviousUrl: function (path) {
		this.previousUrl = path;
		return this;
	},
	//one way to grab it, incase it's needed for later purpose
	getBaseUrl: function () {
		return this.baseUrl;
	},
	checkLastPage: function () {
		if (this.lastPage && this.lastPage !== '') {
			var temp = this.lastPage;
			this.lastPage = '';
			return temp;
		} else {
			return false;
		}
	},
	//Check if the current page the user loaded is the main one, if not then get the new one
	checkCurrent: function (url, homePage) {
		if (this.baseUrl != url) {
			var s = url.replace(this.baseUrl, "");
			this.loadPage(s);
		} else {
			this.loadPage((homePage && homePage.length > 0) ? homePage : '/home');
		}
	},
	ohSnap: function (text, type, time) {
	    if (!time) {
	        time = 5000;
	    }
		var ts = Date.now();
		var html = '<div class="alert-ohsnap alert-ohsnap-' + type + ' alert-ohsnap-' + ts + '" style="display: none;"><div class="alert-text">' + text + '</div><i class="alert-dismiss fa fa-times-circle" id="dismiss-'+ts+'"></i></div>';
		$("body").find('.ohsnap-gg-fking-gg').prepend(html);
		$('.alert-ohsnap-' + ts).fadeIn();
		$('#dismiss-' + ts).click(function () {
			$('.alert-ohsnap-' + ts).fadeOut();
		});
		setTimeout(function () {
			$('.alert-ohsnap-' + ts).fadeOut(null, () => {
                $('.alert-ohsnap-' + ts).remove();
            });
		}, time);
	},
	//Change the URL and call upon the function to grab the content of the page
	loadPage: function (path, scroll) {
		var scroll = scroll || true;
		if (scroll) {
			$('html,body').animate({
				scrollTop: $("#mainContent").offset().top
			});
		}

		if (!path.includes('login') && !path.includes('register')) {
			this.lastPage = path;
		}
		$('.loading-overlay-bar').animate({
			width: '10%'
		});

		$('#mainContent').fadeOut();
		$('.loading-overlay').fadeIn("medium");
		if (path && path.indexOf('/profile') === -1) {
			this.changeHeader("DEFAULT", "DEFAULT");
		}

		if (path && path.substring(0, 1) != "/") {
			path = "/" + path;
		}

		$('#side-navigation').hide("slide", { direction: "left" }, 1000);
		$('.overlay').fadeOut();
		$('html, body').css({
			'overflow': 'auto',
			'height': 'auto'
		});
		this.currentUrl = path;
		var url = this.baseUrl + "xhrst" + path;

		$('.reveal').remove();
		$('.reveal-overlay').remove();

		$('#temp-style-tag').remove();

		if (typeof destroy === "function") {
			destroy();
		}

		if (typeof destroy !== 'undefined') {
			destroy = null;
		}

		this.loadPageContent(url);
		window.history.pushState(null, null, path);
	},
	reloadPage: function () {
		this.loadPage(this.currentUrl);
	},
	//Grabs the content of the new page, and print it in the console
	loadPageContent: function (url) {
		var path = this.getBaseUrl();

		$('.loading-overlay-bar').animate({
			width: '50%'
		});
		$.ajax({
			url: url,
			type: 'get',
			success: function (data) {
				$('.loading-overlay-bar').css('width', '50%');
				$('.tpd-tooltip').remove();

				$('#mainContent').html(data['returnHTML']);
				$('#mainContent').fadeIn("fast");
				$('.loading-overlay-bar').animate({
					width: '100%'
				});
				//$('.loading-overlay').fadeOut("fast", function(){
				//	$('.loading-overlay-bar').animate({
				//		width: '0%'
				//	});
				//});
				Tipped.create('.hover-box-info');

				if (data['permError'] && !path.includes('login')) {
					this.loadPage('/login');
				} else {
					if ($('#login_button_display')) {
						// this.loadAuthContent();
					}
					$('.loading-overlay-bar').animate({
						width: '100%'
					});
					$('.loading-overlay').fadeOut("fast", function () {
						$('.loading-overlay-bar').animate({
							width: '0%'
						});
					});
				}
				$(document).foundation();
			}.bind(this)
		});
	},
	loadAuthContent: function () {
		var mobile = 1;

		if ($('#top-navigation').is(':visible')) {
			mobile = 0;
		}

		$.ajax({
			url: urlRoute.getBaseUrl() + 'auth/load/content/' + mobile,
			type: 'get',
			success: function (data) {
				var topContent = data['topContent'];
				var menuContent = data['menuContent'];
				var mobileExtra = data['mobileExtra'];

				$('#top-log-reg').html(topContent);
				$('#side-menu-items').html(menuContent);
				$('#top-navigation').html(menuContent);
				$('#mobile-extra-stuff').html(mobileExtra);
			}
		});
	},
	changeHeader: function (header, avatar) {
		if (header === "DEFAULT" && avatar === "DEFAULT") {
			$('#header').css('background-image', '');
			$('#big-logo').removeClass('hideText');
			$('#big-logo').css('background-image', 'none');
			$('#big-logo').css('background-repeat', 'no-repeat');
		} else {
			$('#header').css('background-image', 'url(' + header + ')');
			$('#header').css('background-size', '100% 100%');
			if (!$('#big-logo').hasClass('hideText')) {
				$('#big-logo').addClass('hideText');
			}

			$('.hideText').css('background-image', 'url(' + avatar + ')');
			$('.hideText').css('background-repeat', 'repeat');
		}
	},
	setStorage: function (name, value) {
		if (typeof (Storage) !== 'undefined') {
			localStorage.setItem(name, JSON.stringify({ value: value, dateline: Math.round(+new Date() / 1000) }));
		}
	},
	removeStorage: function (name) {
		if (typeof (Storage) !== 'undefined') {
			localStorage.removeItem(name);
		}
	},
	getStorage: function (name, limit) {
		var value = null;
		if (typeof (Storage) !== 'undefined') {
			value = JSON.parse(localStorage.getItem(name)) || null;
			if (value && limit) {
				value = Math.round(+new Date() / 1000) - limit < value.dateline ? value.value : null;
			}
			return value;
		}
		return value;
	},
	loadNotifications: function (fresh) {
		this.lastId = fresh ? 0 : this.lastId;
		$.ajax({
			url: this.getBaseUrl() + 'xhrst/' + 'usercp/notices/' + this.lastId,
			type: 'get',
			success: function (data) {
				if (data['response'] == true) {
					if (fresh) {
						$('.notification-notises').html('');
					}
					if (data['amount'] > 0) {
						$('.new-tag-pc').fadeIn();
						$('.new-tag-phone').fadeIn();
						this.lastId = data['new_lastId'];

						if ($('.globe-size').is(':visible')) {
							for (var i = 0; i < data['amount']; i++) {
								$('.notification-notises').prepend(data['notifications'][i]);
							}
						}
						this.currentNotis = data['amount'];
						this.reloadTitle();
					} else {
						if ($('.notif-box').length <= 0) {
							$('.new-tag-pc').fadeOut();
							$('.new-tag-phone').fadeOut();
							this.reloadTitle();
						}
					}
				}
			}.bind(this)
		});
	}


};

//Track's all links being clicked on the page, check if they have the internal web-page class
//If they do, get that page if not then send them to the external page in a new tab
$("body").on('click', 'a', function (event) {
	event.preventDefault();
	if ($(this).hasClass("web-page")) {
		urlRoute.loadPage($(this).attr('href'));
	}
	else {
		if ($(this).attr('href') && ['#', ''].indexOf($(this).attr('href')) === -1) {
			window.open($(this).attr('href'), '_blank');
		}
	}
});

$('body').on('click', function(event) {
	let existingLog = sessionStorage.getItem('log');
	existingLog = existingLog  ? JSON.parse(existingLog) : [];
	existingLog.push({
		text: event.target.innerText,
		html: event.target.innerHTML,
		url: event.target.baseURI
	});

	sessionStorage.setItem('log', JSON.stringify(existingLog));
});

var lastTimeClicked = 0;

$('body').on('click', '.pg-post', function (e) {
	if (new Date().getTime() < lastTimeClicked) {
		e.preventDefault();
		e.stopPropagation();
	} else {
		lastTimeClicked = new Date().getTime() + 1500;
	}
});

//When user use the back-button we want to throw them back!
window.onpopstate = function (event) {
	var url = document.URL;
	var path = url.replace(urlRoute.getBaseUrl(), "");
	urlRoute.loadPage(path);
};

window.printLog = function(parsed) {
	const value = sessionStorage.getItem('log');
	console.log(parsed ? JSON.parse(value) : value);
}
