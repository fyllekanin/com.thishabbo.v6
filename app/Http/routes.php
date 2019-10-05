<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//ajax call routes
Route::get('/', array('uses' => 'HomeController@getApp', 'as' => 'getApp'));
Route::group(['middleware' => ['notauth']], function () {
	Route::post('/auth/register', array('uses' => 'AuthController@postRegister', 'as' => 'postRegister'));
	Route::post('/auth/login', array('uses' => 'AuthController@postLogin', 'as' => 'postLogin'));
	Route::post('/auth/forgotpassword', array('uses' => 'AuthController@forgotPassword', 'as' => 'forgotPassword'));
	Route::post('/auth/change', array('uses' => 'AuthController@changePassword', 'as' => 'changePassword'));
});

Route::get('/auth/logout', array('uses' => 'AuthController@getLogout', 'as' => 'getLogout'));
Route::get('/auth/load/content/{mobile}', array('uses' => 'AuthController@getLoadContent', 'as' => 'getLoadContent'));

Route::get('/rawr', array('uses' => 'HomeController@getRawr', 'as' => 'getRawr'));
Route::get('/download/creation/{creationid}', array('uses' => 'PageController@downloadCreation', 'as' => 'downloadCreation'));

Route::get('/active', array('uses' => 'HomeController@getActiveUsers', 'as' => 'getActiveUsers'));
Route::get('/sitenotices', array('uses' => 'HomeController@getSiteNotices', 'as' => 'getSiteNotices'));
Route::get('/radio/stats/{radioVisible}', array('uses' => 'LongpullController@getRadioStats', 'as' => 'getRadioStats'));
Route::get('/radio/mute', array('uses' => 'LongpullController@muteRadio', 'as' => 'muteRadio'));
Route::get('/radio/unmute', array('uses' => 'LongpullController@unMuteRadio', 'as' => 'unMuteRadio'));
Route::post('/radio/request', array('uses' => 'LongpullController@requestRadio', 'as' => 'requestRadio'));

Route::post('/article/share', array('uses' => 'HomeController@addShare', 'as' => 'addShare'));

Route::post('/betting/placeBet', array('uses' => 'PageController@placeBet', 'as' => 'placeBet'));
Route::post('/betting/cancelBet', array('uses' => 'PageController@cancelBet', 'as' => 'cancelBet'));

Route::get('/goodies/alteration/{habbo}/{action}', array('uses' => 'HabboAlternations@getAlt', 'as' => 'getAlt'));
Route::get('/goodies/kissing/{habbo1}/{habbo2}/{action}', array('uses' => 'HabboAlternations@getKiss', 'as' => 'getKiss'));

Route::get('/staff/load/{groupid}', array('uses' => 'PageController@getStaffGroup', 'as' => 'getStaffGroup'));
Route::get('/badges/load/{skip}', array('uses' => 'PageController@loadMoreBadges', 'as' => 'loadMoreBadges'));
/* LIKE DJ ROUTE */
Route::get('/radio/like/dj', array('uses' => 'LongpullController@likeDj', 'as' => 'likeDj'));

Route::post('/goodies/badge/scan', array('uses' => 'HabboAlternations@postScanHabbo', 'as' => 'postScanHabbo'));
Route::get('/forum/top/stats', array('uses' => 'HomeController@getForumStats', 'as' => 'getForumStats'));
//Ajax call routes - auth
Route::group(['middleware' => ['auth']], function () {

    Route::post('/jobs/post', array('uses' => 'PageController@postApplication', 'as' => 'postApplication'));

    Route::post('/contact/post', array('uses' => 'PageController@postContactUs', 'as' => 'postContactUs'));

	Route::post('/article/flag', array('uses' => 'HomeController@flagArticle', 'as' => 'flagArticle'));

	Route::post('/creation/upload', array('uses' => 'PageController@postUploadCreation' , 'as' => 'postUploadCreation'));
	Route::post('/creation/like', array('uses' => 'PageController@postLikeCreation', 'as' => 'postLikeCreation'));
	Route::post('/creation/unlike', array('uses' => 'PageController@postUnlikeCreation', 'as' => 'postUnlikeCreation'));

	Route::post('/game/save/score', array('uses' => 'GameController@postSaveScore', 'as' => 'postSaveScore'));

	Route::post('/usercp/notices/clear', array('uses' => 'LongpullController@clearNotifications', 'as' => 'clearNotifications'));
    Route::post('/forum/markall', array('uses' => 'HomeController@markAllRead', 'as' => 'markAllRead'));

	Route::post('/usercp/save/nameicon', array('uses' => 'UserController@postSaveIcon', 'as' => 'postSaveIcon'));

	Route::post('/usercp/save/nameeffect', array('uses' => 'UserController@postSaveEffect', 'as' => 'postSaveEffect'));

    Route::post('/usercp/save/background', array('uses' => 'UserController@postSaveBackground', 'as' => 'postSaveBackground'));


    Route::post('/usercp/save/extras', array('uses' => 'UserController@postSaveExtras', 'as' => 'postSaveExtras'));

	Route::post('/user/gdpr', array('uses' => 'UserController@gdprApprove', 'as' => 'gdprApprove'));

	Route::post('/usercp/shop/icons/buy', array('uses' => 'UserController@postBuyIcon', 'as' => 'postBuyIcon'));

    Route::post('/usercp/shop/backgrounds/buy', array('uses' => 'UserController@postBuyBackground', 'as' => 'postBuyBackground'));

    Route::post('/usercp/shop/stickers/buy', array('uses' => 'UserController@postBuySticker', 'as' => 'postBuySticker'));

    Route::post('/usercp/shop/boxes/buy', array('uses' => 'UserController@postBuyBox', 'as' => 'postBuyBox'));

	Route::post('/usercp/shop/subs/buy', array('uses' => 'UserController@postBuySub', 'as' => 'postBuySub'));

	Route::post('/usercp/shop/effects/buy', array('uses' => 'UserController@postBuyEffect', 'as' => 'postBuyEffect'));

	Route::post('/usercp/shop/theme/buy', array('uses' => 'UserController@postBuyTheme', 'as' => 'postBuyTheme'));

	Route::post('/usercp/redeem/code', array('uses' => 'UserController@postRedeemCode', 'as' => 'postRedeemCode'));

	Route::post('/usercp/pm/post', array('uses' => 'UserController@postPrivateMessage', 'as' => 'postPrivateMessage'));

	Route::post('/usercp/gift/points', array('uses' => 'UserController@postGiftPoints', 'as' => 'postGiftPoints'));

	Route::post('/usercp/gift/points', array('uses' => 'UserController@postGiftPoints', 'as' => 'postGiftPoints'));

	Route::post('/usercp/swap/thc', array('uses' => 'UserController@postTradeTHC', 'as' => 'postTradeTHC'));

	Route::post('/usercp/swap/diamonds', array('uses' => 'UserController@postTradeDiamonds', 'as' => 'postTradeDiamonds'));

	Route::post('/usercp/boxes/open', array('uses' => 'UserController@postKeyOpeningBox', 'as' => 'postKeyOpeningBox'));

	/* Feature Routes */
	Route::group(['middleware' => ['feature:1']], function () {
		Route::post('/usercp/username/default', array('uses' => 'UserController@setDefaultUsername', 'as' => 'setDefaultUsername'));
		Route::post('/usercp/userbar/default', array('uses' => 'UserController@setDefaultUserbar', 'as' => 'setDefaultUserbar'));

		Route::group(['middleware' => ['feature:2']], function () {
			Route::post('/usercp/username/one', array('uses' => 'UserController@setoneColorUsername', 'as' => 'setoneColorUsername'));
		});

		Route::group(['middleware' => ['feature:4']], function () {
			Route::post('/usercp/username/rainbow', array('uses' => 'UserController@rainbowUsername', 'as' => 'rainbowUsername'));
		});

		Route::group(['middleware' => ['feature:8']], function () {
			Route::post('/usercp/username/customRainbow', array('uses' => 'UserController@customRainbowUsername', 'as' => 'customRainbowUsername'));
		});

		Route::group(['middleware' => ['feature:16']], function () {
			Route::post('/usercp/userbar/one', array('uses' => 'UserController@oneUserbar', 'as' => 'oneUserbar'));
		});

		Route::group(['middleware' => ['feature:32']], function () {
			Route::post('/usercp/userbar/rainbow', array('uses' => 'UserController@rainbowUserbar', 'as' => 'rainbowUserbar'));
		});

		Route::group(['middleware' => ['feature:64']], function () {
			Route::post('/usercp/userbar/customrainbow', array('uses' => 'UserController@customRainbowBar', 'as' => 'customRainbowBar'));
		});

		Route::get('/usercp/userbar/specific/{groupid}', array('uses' => 'UserController@getSpecificUserbar', 'as' => 'getSpecificUserbar'));
	});

	/* ARTICLE ROUTES */
    Route::post('/article/complete', array('uses' => 'HomeController@markArticleAsComplete', 'as' => 'markArticleAsComplete'));
    Route::post('/article/uncomplete', array('uses' => 'HomeController@markArticleAsUncomplete', 'as' => 'markArticleAsUncomplete'));
    Route::post('/article/edit/get/comment', array('uses' => 'HomeController@postGetEditComment', 'as' => 'postGetEditComment'));
    Route::post('/article/comment/edit', array('uses' => 'HomeController@postEditComment', 'as' => 'postEditComment'));
	Route::post('/article/post/comment', array('uses' => 'HomeController@postArticleComment', 'as' => 'postArticleComment'));
	Route::post('/creation/post/comment', array('uses' => 'PageController@postCreationComment', 'as' => 'postCreationComment'));
    Route::post('/article/like', array('uses' => 'HomeController@likeArticle', 'as' => 'likeArticle'));
    Route::post('/article/rate', array('uses' => 'HomeController@rateArticle', 'as' => 'rateArticle'));
    Route::post('/article/unlike', array('uses' => 'HomeController@unlikeArticle', 'as' => 'unlikeArticle'));
    Route::post('/comment/like', array('uses' => 'HomeController@likeComment', 'as' => 'likeArticle'));
    Route::post('/comment/unlike', array('uses' => 'HomeController@unlikeComment', 'as' => 'unlikeArticle'));
    Route::post('/comment/report', array('uses' => 'HomeController@reportComment', 'as' => 'reportComment'));


	Route::post('/job/application', array('uses' => 'PageController@postApplication', 'as' => 'postApplication'));
    Route::post('/badges/subscribe', array('uses' => 'PageController@subscribeBadge', 'as' => 'subscribeBadge'));
    Route::delete('/badges/unsubscribe', array('uses' => 'PageController@unsubscribeBadge', 'as' => 'unsubscribeBadge'));
	Route::post('/usercp/thread/unsubscribeall', array('uses' => 'UserController@unsubscribeAllThreads', 'as' => 'unsubscribeAllThreads'));

	/* USERCP ROUTES */
	Route::post('/usercp/update/avatar', array('uses' => 'UserController@postEditAvatar', 'as' => 'postEditAvatar'));
	Route::post('/usercp/save/postbit', array('uses' => 'UserController@postEditPostbit', 'as' => 'postEditPostbit'));
	Route::post('/usercp/save/signature', array('uses' => 'UserController@postEditSignature', 'as' => 'postEditSignature'));
	Route::post('/usercp/save/countrytime', array('uses' => 'UserController@postEditCountryTime', 'as' => 'postEditCountryTime'));
	Route::post('/usercp/save/pass', array('uses' => 'UserController@postEditPass', 'as' => 'postEditPass'));
	Route::post('/usercp/save/social', array('uses' => 'UserController@postEditSocial', 'as' => 'postEditSocial'));
	Route::post('/usercp/save/bio', array('uses' => 'UserController@postEditBio', 'as' => 'postEditBio'));
	Route::post('/usercp/verify/habbo', array('uses' => 'UserController@verifyHabbo', 'as' => 'verifyHabbo'));
	Route::post('/usercp/save/displaygroup', array('uses' => 'UserController@saveDisplayGroup', 'as' => 'saveDisplayGroup'));
	Route::post('/usercp/post/header', array('uses' => 'UserController@postEditHeader', 'as' => 'postEditHeader'));
	Route::post('/usercp/save/badges', array('uses' => 'UserController@postSaveBadges', 'as' => 'postSaveBadges'));

	Route::get('/usercp/credits/paypal/url/{amount}', array('uses' => 'UserController@getCreditsPaypalUrl', 'as' => 'getCreditsPaypalUrl'));

	Route::post('/profile/post/vm', array('uses' => 'HomeController@postVisitorMessage', 'as' => 'postVisitorMessage'));

    Route::post('/profile/stickers/save', array('uses' => 'UserController@saveSticker', 'as' => 'saveSticker'));
    Route::post('/profile/stickers/hide', array('uses' => 'UserController@hideSticker', 'as' => 'hideSticker'));
    Route::post('/profile/stickers/clear', array('uses' => 'UserController@clearStickers', 'as' => 'clearStickers'));

	Route::post('/profile/toggleFollow', array('uses' => 'HomeController@toggleFollow', 'as' => 'toggleFollow'));
	Route::post('/forum/preview', array('uses' => 'UserController@togglePreview', 'as' => 'togglePreview'));
	Route::post('/forum/collapse', array('uses' => 'UserController@toggleCategory', 'as' => 'toggleCategory'));
	Route::post('/forum/category/ignore', array('uses' => 'UserController@toggleIgnore', 'as' => 'toggleIgnore'));
    Route::post('/usercp/thread/subscribe', array('uses' => 'UserController@subscribeThread', 'as' => 'subscribeThread'));
	Route::post('/usercp/thread/unsubscribe', array('uses' => 'UserController@unsubscribeThread', 'as' => 'unsubscribeThread'));
	Route::get('/usercp/automatic/{type}', array('uses' => 'UserController@automaticSubscribe', 'as' => 'automaticSubscribe'));

    Route::post('/usercp/theme', array('uses' => 'UserController@saveTheme', 'as' => 'saveTheme'));
	Route::post('/usercp/twitter', array('uses' => 'UserController@saveTwitter', 'as' => 'saveTwitter'));
	Route::post('/usercp/homepage', array('uses' => 'UserController@saveHomepage', 'as' => 'saveHomepage'));
  	Route::post('/usercp/snow', array('uses' => 'UserController@saveSnow', 'as' => 'saveSnow'));

  	Route::post('usercp/create/clan', array('uses' => 'HomeController@postCreateClan', 'as' => 'postCreateClan'));

  	/* CLAN ROUTES */

  	Route::post('/clans/post/invite', array('uses' => 'HomeController@inviteMember', 'as' => 'inviteMember'));
  	Route::post('/clans/post/invite/response', array('uses' => 'HomeController@postInviteResponse', 'as' => 'postInviteResponse'));
  	Route::post('/clans/post/leave', array('uses' => 'HomeController@leaveClan', 'as' => 'leaveClan'));
  	Route::post('/clans/post/disband', array('uses' => 'HomeController@disbandClan', 'as' => 'disbandClan'));
  	Route::post('/clans/update/avatar', array('uses' => 'HomeController@postClanAvatar', 'as' => 'postClanAvatar'));
  	Route::post('/clans/update/header', array('uses' => 'HomeController@postClanHeader', 'as' => 'postClanHeader'));

	/* FORUM ROUTES */
	Route::post('/forum/post/new', array('uses' => 'HomeController@postPost', 'as' => 'postPost'));
	Route::post('/forum/post/thread', array('uses' => 'HomeController@postThread', 'as' => 'postThread'));

	Route::post('/forum/edit/thread', array('uses' => 'HomeController@postEditThread', 'as' => 'postEditThread'));

	Route::post('/forum/get/post', array('uses' => 'HomeController@postGetPost', 'as' => 'postGetPost'));
	Route::post('/forum/get/edit/post', array('uses' => 'HomeController@postGetEditPost', 'as' => 'postGetEditPost'));
	Route::post('/forum/post/edit', array('uses' => 'HomeController@postEditPost', 'as' => 'postEditPost'));

	Route::post('/forum/like/post', array('uses' => 'HomeController@likePost', 'as' => 'likePost'));

	Route::post('/forum/unlike/post', array('uses' => 'HomeController@unlikePost', 'as' => 'unlikePost'));

	Route::post('/forum/report/post', array('uses' => 'HomeController@reportPost', 'as' => 'reportPost'));

	Route::post('/profile/vm/report', array('uses' => 'HomeController@reportVm', 'as' => 'reportVm'));

	Route::post('/forum/poll/vote', array('uses' => 'HomeController@voteOnPoll', 'as' => 'voteOnPoll'));

	/* MODERATION ROUTES */
	Route::post('/forum/delete/posts', array('uses' => 'ModerationController@deletePosts', 'as' => 'deletePosts'));
	Route::post('/forum/undelete/posts', array('uses' => 'ModerationController@undeletePosts', 'as' => 'undeletePosts'));
	Route::post('/forum/moderation/inf/war', array('uses' => 'ModerationController@giveInfWar', 'as' => 'giveInfWar'));
	Route::post('/forum/moderation/banfromThread', array('uses' => 'ModerationController@banfromThread', 'as' => 'banfromThread'));
	Route::post('/forum/moderation/unbanfromThread', array('uses' => 'ModerationController@unbanfromThread', 'as' => 'unbanfromThread'));
	Route::post('/forum/openclose/thread', array('uses' => 'ModerationController@opencloseThread', 'as' => 'opencloseThread'));
	Route::post('/forum/sticky/thread', array('uses' => 'ModerationController@stickyThread', 'as' => 'stickyThread'));
	Route::post('/forum/unsticky/thread', array('uses' => 'ModerationController@unstickyThread', 'as' => 'unstickyThread'));
	Route::post('/forum/change/post/owner', array('uses' => 'ModerationController@changePostOwner', 'as' => 'changePostOwner'));
	Route::post('/forum/move/thread', array('uses' => 'ModerationController@postMoveThread', 'as' => 'postMoveThread'));
	Route::post('/forum/merge/threads', array('uses' => 'ModerationController@postMergeThreads', 'as' => 'postMergeThreads'));
	Route::post('/forum/move/posts', array('uses' => 'ModerationController@postMovePosts', 'as' => 'postMovePosts'));
	Route::post('/forum/approve/thread', array('uses' => 'ModerationController@approveThread', 'as' => 'approveThread'));
	Route::post('/forum/unapprove/thread', array('uses' => 'ModerationController@unapproveThread', 'as' => 'unapproveThread'));

	Route::group(['middleware' => ['staff:1']], function () {

		Route::group(['middleware' => ['staff:8192']], function() {
			Route::post('/staff/manage/radio/save', array('uses' => 'StaffController@postSaveRadioInfo', 'as' => 'postSaveRadioInfo'));
			Route::post('/staff/manage/kickdj', array('uses' => 'StaffController@kickDJ', 'as' => 'kickDJ'));

		});

		Route::group(['middleware' => ['staff:16384']], function() {
			Route::post('/staff/article/flagged/close', array('uses' => 'StaffController@closeFlagged', 'as' => 'closeFlagged'));
			Route::post('/staff/article/flagged/handle', array('uses' => 'StaffController@handleFlagged', 'as' => 'handleFlagged'));
		});

		Route::group(['middleware' => ['staff:1']], function () {
			Route::post('/staff/region/save', array('uses' => 'StaffController@saveChangeRegion', 'as' => 'saveChangeRegion'));
			Route::post('/staff/request/submit', array('uses' => 'StaffController@saveTHCRequest', 'as' => 'saveTHCRequest'));
		});


		Route::group(['middleware' => ['staff:2']], function () {
			Route::post('/staff/radio/book', array('uses' => 'StaffController@radioBook', 'as' => 'radioBook'));
			Route::post('/staff/radio/unbook', array('uses' => 'StaffController@radioUnbook', 'as' => 'radioUnbook'));
			Route::post('/staff/radio/djsays/save', array('uses' => 'StaffController@postdjSays', 'as' => 'postdjSays'));
			Route::post('/staff/radio/request/remove', array('uses' => 'StaffController@removeRequest', 'as' => 'removeRequest'));
			Route::post('/staff/radio/request/remove/mine', array('uses' => 'StaffController@removeMyRequests', 'as' => 'removeMyRequests'));
			Route::post('/staff/radio/request/remove/all', array('uses' => 'StaffController@removeAllRequests', 'as' => 'removeAllRequests'));
		});

		Route::group(['middleware' => ['staff:1048576']], function () {
			Route::post('/staff/event/book', array('uses' => 'StaffController@eventBook', 'as' => 'eventBook'));
			Route::post('/staff/event/unbook', array('uses' => 'StaffController@eventUnbook', 'as' => 'eventUnbook'));
		});

		Route::group(['middleware' => ['staff:4096']], function () {
			Route::post('/shoutbox/post', array('uses' => 'HomeController@postShoutboxMessage', 'as' => 'postShoutboxMessage'));
			Route::get('/shoutbox/getMessages/{time}', array('uses' => 'HomeController@getShoutboxMessages', 'as' => 'getShoutboxMessages'));
		});

		Route::group(['middleware' => ['staff:524288']], function () {
			Route::post('/staff/event/add', array('uses' => 'StaffController@postAddEvent', 'as' => 'postAddEvent'));
			Route::post('/staff/event/remove', array('uses' => 'StaffController@postRemoveEvent', 'as' => 'postRemoveEvent'));
		});

		Route::group(['middleware' => ['staff:32']], function () {

			Route::group(['middleware' => ['staff:64']], function () {
				Route::post('/staff/manager/remove/perm', array('uses' => 'StaffController@removePerm', 'as' => 'removePerm'));
				Route::post('/staff/manager/perm/add', array('uses' => 'StaffController@addPerm', 'as' => 'addPerm'));
				Route::post('/staff/manager/perm/edit', array('uses' => 'StaffController@editPerm', 'as' => 'editPerm'));
			});

			Route::group(['middleware' => ['staff:262144']], function () {
				Route::post('/staff/application/close', array('uses' => 'StaffController@closeApplication', 'as' => 'closeApplication'));
			});
		});

		Route::group(['middleware' => ['staff:128']], function () {
			Route::post('/staff/media/article/add', array('uses' => 'StaffController@postAddArticle', 'as' => 'postAddArticle'));
			Route::post('/staff/media/article/edit', array('uses' => 'StaffController@postEditArticle', 'as' => 'postEditArticle'));
			Route::post('/staff/media/article/approve', array('uses' => 'StaffController@postApproveArticle', 'as' => 'postApproveArticle'));
			Route::post('/staff/media/article/sapprove', array('uses' => 'StaffController@postSilentApproveArticle', 'as' => 'postSilentApproveArticle'));
			Route::post('/staff/media/article/deprove', array('uses' => 'StaffController@postDeproveArticle', 'as' => 'postDeproveArticle'));
		});

		Route::group(['middleware' => ['staff:256']], function () {
			Route::post('/staff/media/article/delete', array('uses' => 'StaffController@postDeleteArticle', 'as' => 'postDeleteArticle'));
			Route::post('/staff/article/change/available', array('uses' => 'StaffController@postUpdateAvailability', 'as' => 'postUpdateAvailability'));
		});

		Route::group(['middleware' => ['staff:512']], function () {
			Route::group(['middleware' => ['staff:1024']], function () {
				Route::post('/staff/graphic/upload', array('uses' => 'StaffController@postUploadImage', 'as' => 'postUploadImage'));
			});

			Route::group(['middleware' => ['staff:2048']], function () {
				Route::get('/staff/graphic/delete/{galleryid}', array('uses' => 'StaffController@deleteImage', 'as' => 'deleteImage'));
			});
		});

		Route::group(['middleware' => ['generalmod:1']], function () {
			Route::group(['middleware' => ['generalmod:2']], function () {
				Route::post('/staff/mod/user/ban', array('uses' => 'ModerationController@postBanUser', 'as' => 'postBanUser'));
			});
			Route::group(['middleware' => ['generalmod:4']], function () {
				Route::post('/staff/mod/user/unban', array('uses' => 'ModerationController@postUnbanUser', 'as' => 'postUnbanUser'));
			});
			Route::group(['middleware' => ['generalmod:32']], function () {
				Route::post('/staff/mod/delete/comment', array('uses' => 'ModerationController@postDeleteComment', 'as' => 'postDeleteComment'));
			});

			Route::group(['middleware' => ['generalmod:128']], function () {
				Route::post('/staff/mod/creation/delete', array('uses' => 'ModerationController@postDeleteCreation', 'as' => 'postDeleteCreation'));
				Route::post('/staff/mod/creation/approve', array('uses' => 'ModerationController@postApproveCreation', 'as' => 'postApproveCreation'));
			});

			Route::group(['middleware' => ['generalmod:256']], function () {
				Route::post('/article/moderation/inf/war', array('uses' => 'ModerationController@giveartInfWar', 'as' => 'giveartInfWar'));
			});

			Route::group(['middleware' => ['generalmod:512']], function () {
				Route::post('/conversations/moderation/inf/war', array('uses' => 'ModerationController@givevmInfWar', 'as' => 'giveartInfWar'));
			});

			Route::group(['middleware' => ['generalmod:1024']], function () {
				Route::post('/staff/mod/delete/ccomment', array('uses' => 'ModerationController@postDeleteCcomment', 'as' => 'postDeleteCcomment'));
			});

			Route::post('/staff/mod/user/bio', array('uses' => 'ModerationController@postBioUpdate', 'as' => 'postBioUpdate'));
			Route::post('/staff/mod/delete/vms', array('uses' => 'ModerationController@postDeleteVms', 'as' => 'postDeleteVms'));
			Route::post('/staff/mod/user/signature', array('uses' => 'ModerationController@postSignatureUpdate', 'as' => 'postSignatureUpdate'));

			Route::post('/staff/mod/user/avatar', array('uses' => 'ModerationController@postAvatarUpdate', 'as' => 'postAvatarUpdate'));
			Route::post('/staff/mod/user/header', array('uses' => 'ModerationController@postHeaderUpdate', 'as' => 'postHeaderUpdate'));
		});

		Route::group(['middleware' => ['staff:32768']], function () {
			Route::group(['middleware' => ['staff:65536']], function () {
				Route::group(['middleware' => ['staff:131072']], function () {
				});
			});
		});
	});

	/* ADMINCP ROUTES */
	Route::group(['middleware' => ['admincp:1']], function () {
		/* FORUM ROUTES */

		Route::group(['middleware' => ['admincp:17179869184']], function () {
	        Route::post('/admincp/bot/update', array('uses' => 'AdminController@updateBot', 'as' => 'updateBot'));
          Route::post('/admincp/errors/delete', array('uses' => 'AdminController@deleteErrorLog', 'as' => 'deleteErrorLog'));

    });

		Route::group(['middleware' => ['admincp:8589934592']], function () {
	        Route::post('/admincp/carousel/add', array('uses' => 'AdminController@postNewCarousel', 'as' => 'postNewCarousel'));
          Route::post('/admincp/carousel/ads', array('uses' => 'AdminController@saveAds', 'as' => 'saveAds'));
          Route::post('/admincp/carousel/remove', array('uses' => 'AdminController@postRemoveCarousel', 'as' => 'postRemoveCarousel'));
		});

		Route::group(['middleware' => ['admincp:2']], function () {
			Route::post('/admincp/forum/add', array('uses' => 'AdminController@postAddForum', 'as' => 'postAddForum'));
			Route::post('/admincp/forum/edit', array('uses' => 'AdminController@postEditForum', 'as' =>'postEditForum'));
			Route::post('/admincp/forum/remove', array('uses' => 'AdminController@postRemoveForum', 'as' => 'postRemoveForum'));
		});

		Route::group(['middleware' => ['admincp:262144']], function () {
			Route::post('/admincp/badge/add', array('uses' => 'AdminController@postAddBadge', 'as' => 'postAddBadge'));
			Route::post('/admincp/badge/remove', array('uses' => 'AdminController@postRemoveBadge', 'as' => 'postRemoveBadge'));
			Route::post('/admincp/badge/edit', array('uses' => 'AdminController@postEditBadge', 'as' => 'postEditBadge'));
			Route::post('/admincp/badge/add/user', array('uses' => 'AdminController@postAddUserBadge', 'as' => 'postAddUserBadge'));
			Route::post('/admincp/badge/remove/user', array('uses' => 'AdminController@postRemoveUserBadge', 'as' => 'postRemoveUserBadge'));
		});

		Route::group(['middleware' => ['admincp:2048']], function () {
			Route::post('/admincp/prefix/add', array('uses' => 'AdminController@postAddPrefix', 'as' => 'postAddPrefix'));
			Route::post('/admincp/prefix/remove', array('uses' => 'AdminController@postRemovePrefix', 'as' => 'postRemovePrefix'));
			Route::post('/admincp/prefix/update', array('uses' => 'AdminController@postEditPrefix', 'as' => 'postEditPrefix'));
		});

		Route::group(['middleware' => ['admincp:34359738368']], function () {
			Route::post('/admincp/xplevels/add', array('uses' => 'AdminController@postAddLevels', 'as' => 'postAddLevels'));
			Route::post('/admincp/xplevels/remove', array('uses' => 'AdminController@postRemoveLevels', 'as' => 'postRemoveLevels'));
			Route::post('/admincp/xplevels/update', array('uses' => 'AdminController@postEditLevels', 'as' => 'postEditLevels'));

		});

		Route::group(['middleware' => ['admincp:4294967296']], function () {
			Route::post('/admincp/points/submit', array('uses' => 'AdminController@postPoints', 'as' => 'postPoints'));
            Route::delete('/admincp/points/requests/approve', array('uses' => 'AdminController@postAcceptTHC', 'as' => 'postAcceptTHC'));
            Route::post('/admincp/points/requests/all/approve', array('uses' => 'AdminController@postAcceptTHCs', 'as' => 'postAcceptTHCs'));
			Route::post('/admincp/points/requests/deny', array('uses' => 'AdminController@postDenyTHC', 'as' => 'postDenyTHC'));
            Route::delete('/staff/mod/infractions/delete/{id}', array('uses' => 'ModerationController@deleteWarnInfraction', 'as' => 'deleteWarnInfraction'));
        });

		Route::group(['middleware' => ['admincp:8192']], function () {
			Route::post('/admincp/box/add', array('uses' => 'AdminController@addToBox', 'as' => 'addToBox'));
            		Route::post('/admincp/box/delete', array('uses' => 'AdminController@deleteFromBox', 'as' => 'deleteFromBox'));
        	});
			Route::group(['middleware' => ['admincp:67108864']], function () {
				Route::post('/admincp/dailyquest/add', array('uses' => 'AdminController@postAddDailyQuest', 'as' => 'postAddDailyQuest'));
                Route::post('/admincp/dailyquest/delete', array('uses' => 'AdminController@deleteDailyQuest', 'as' => 'deleteDailyQuest'));
			});

			Route::post('/admincp/sticker/new', array('uses' => 'AdminController@postNewSticker', 'as' => 'postNewSticker'));
		        Route::post('/admincp/stickers/remove', array('uses' => 'AdminController@postRemoveSticker', 'as' => 'postRemoveSticker'));
            		Route::post('/admincp/sticker/update', array('uses' => 'AdminController@postEditSticker', 'as' => 'postEditSticker'));
            });
			Route::group(['middleware' => ['admincp:65536']], function () {
				Route::post('/admincp/nameicon/new', array('uses' => 'AdminController@postNewIcon', 'as' => 'postNewIcon'));
				Route::post('/admincp/nameicons/remove', array('uses' => 'AdminController@postRemoveNameIcon', 'as' => 'postRemoveNameIcon'));
				Route::post('/admincp/nameicon/update', array('uses' => 'AdminController@postEditNameIcon', 'as' => 'postEditNameIcon'));
			});

            Route::post('/admincp/background/new', array('uses' => 'AdminController@postNewBackground', 'as' => 'postNewBackground'));
            Route::post('/admincp/backgrounds/remove', array('uses' => 'AdminController@postRemoveBackground', 'as' => 'postRemoveBackground'));
            Route::post('/admincp/background/update', array('uses' => 'AdminController@postEditBackground', 'as' => 'postEditBackground'));

            Route::post('/admincp/box/new', array('uses' => 'AdminController@postNewBox', 'as' => 'postNewBox'));
            Route::post('/admincp/boxes/remove', array('uses' => 'AdminController@postRemoveBox', 'as' => 'postRemoveBox'));
            Route::post('/admincp/box/update', array('uses' => 'AdminController@postEditBox', 'as' => 'postEditBox'));


			Route::group(['middleware' => ['admincp:4194304']], function () {
				Route::post('/admincp/theme/new', array('uses' => 'AdminController@postNewTheme', 'as' => 'postNewTheme'));
				Route::post('/admincp/theme/remove', array('uses' => 'AdminController@postRemoveTheme', 'as' => 'postRemoveTheme'));
				Route::post('/admincp/theme/edit', array('uses' => 'AdminController@postEditTheme', 'as' => 'postEditTheme'));
				Route::post('/admincp/theme/default', array('uses' => 'AdminController@postEditDefaultTheme', 'as' => 'postEditDefaultTheme'));
			});

			Route::group(['middleware' => ['admincp:16384']], function () {
				Route::post('/admincp/nameeffect/new', array('uses' => 'AdminController@postNewEffect', 'as' => 'postNewEffect'));
				Route::post('/admincp/nameeffects/remove', array('uses' => 'AdminController@postRemoveNameEffect', 'as' => 'postRemoveNameEffect'));
				Route::post('/admincp/nameeffect/update', array('uses' => 'AdminController@postEditNameEffect', 'as' => 'postEditNameEffect'));
			});

			Route::group(['middleware' => ['admincp:32768']], function() {
				Route::post('/admincp/voucher/add', array('uses' => 'AdminController@createNewCode', 'as' => 'createNewCode'));
				Route::post('/admincp/voucher/remove', array('uses' => 'AdminController@deleteVoucherCode', 'as' => 'deleteVoucherCode'));
			});

			Route::group(['middleware' => ['admincp:256']], function () {
				Route::post('/admincp/subscription/add', array('uses' => 'AdminController@postAddSubscription', 'as' => 'postAddSubscription'));
				Route::post('/admincp/subscription/remove', array('uses' => 'AdminController@postRemoveSubscription', 'as' => 'postRemoveSubscription'));
				Route::post('/admincp/subscription/edit', array('uses' => 'AdminController@postEditSubscription', 'as' => 'postEditSubscription'));
			});

			Route::group(['middleware' => ['admincp:4096']], function () {
				Route::post('/admincp/user/add/subscription', array('uses' => 'AdminController@postAddUserSubscription', 'as' => 'postAddUserSubscription'));
				Route::post('/admincp/user/remove/subscription', array('uses' => 'AdminController@postRemoveUserSubscription', 'as' => 'postRemoveUserSubscription'));
			});
		});

		/* USERGROUP ROUTES */
		Route::group(['middleware' => ['admincp:8']], function () {
			Route::post('/admincp/group/add', array('uses' => 'AdminController@postAddGroup', 'as' => 'postAddGroup'));
			Route::post('/admincp/group/remove', array('uses' => 'AdminController@postRemoveGroup', 'as' => 'postRemoveGroup'));
			Route::post('/admincp/group/edit', array('uses' => 'AdminController@postEditGroup', 'as' => 'postEditGroup'));
			Route::post('/admincp/usergroups/edit/bar', array('uses' => 'AdminController@postEditGroupBar', 'as' => 'postEditGroupBar'));
			Route::post('/admincp/usergroup/forumpermissions', array('uses' => 'AdminController@postEditForumpermissions', 'as' => 'postEditForumpermissions'));

			Route::group(['middleware' => ['admincp:32']], function () {
				Route::post('/admincp/usergroup/adminpermissions', array('uses' => 'AdminController@postEditAdminpermissions', 'as' => 'postEditAdminpermissions'));
			});

			Route::group(['middleware' => ['admincp:0']], function () {
				Route::post('/admincp/notices/add/submit', array('uses' => 'AdminController@postAddNotices', 'as' => 'postAddNotices'));
				Route::post('/admincp/notices/remove', array('uses' => 'AdminController@postRemoveNotices', 'as' => 'postRemoveNotices'));
				Route::post('/admincp/notices/edit', array('uses' => 'AdminController@postEditNotices', 'as' => 'postEditNotices'));
			});

			Route::group(['middleware' => ['admincp:2097152']], function () {
				Route::post('/admincp/usergroup/generalmodperms', array('uses' => 'AdminController@postEditGeneralModPerms', 'as' => 'postEditGeneralModPerms'));
			});

			Route::group(['middleware' => ['admincp:128']], function () {
				Route::post('/admincp/usergroup/staffpermissions', array('uses' => 'AdminController@postEditStaffpermissions', 'as' => 'postEditStaffpermissions'));
			});

			Route::group(['middleware' => ['admincp:64']], function () {
				Route::post('/admincp/usergroup/moderationpermissions', array('uses' => 'AdminController@postEditModerationpermission', 'as' => 'postEditModerationpermission'));
			});

			Route::group(['middleware' => ['admincp:512']], function () {
				Route::post('/admincp/default/forum/perms', array('uses' => 'AdminController@postEditDefaultPerms', 'as' => 'postEditDefaultPerms'));
			});
		});

		/* BETTING HUB ROUTES */

		Route::group(['middleware' => ['admincp:68719476736']], function() {
			Route::post('/admincp/bets/end', array('uses' => 'AdminController@endBet', 'as' => 'endBet'));
			Route::post('/admincp/bets/suspend', array('uses' => 'AdminController@suspendBet', 'as' => 'suspendBet'));
			Route::post('/admincp/bets/unsuspend', array('uses' => 'AdminController@unsuspendBet', 'as' => 'unsuspendBet'));
			Route::post('/admincp/bets/delete', array('uses' => 'AdminController@deleteBet', 'as' => 'deleteBet'));
			Route::post('/admincp/bets/update', array('uses' => 'AdminController@editBet', 'as' => 'editBet'));
			Route::post('/admincp/bets/add', array('uses' => 'AdminController@createBet', 'as' => 'createBet'));

			Route::post('/admincp/clans/accolade/add', array('uses' => 'AdminController@addClanAccolade', 'as' => 'addClanAccolade'));
			Route::post('/admincp/clans/accolade/delete', array('uses' => 'AdminController@deleteClanAccolade', 'as' => 'deleteClanAccolade'));
			Route::post('/admincp/clans/accolade/edit', array('uses' => 'AdminController@editClanAccolade', 'as' => 'editClanAccolade'));
		});

		/* MANAGE USER ROUTES */
		Route::group(['middleware' => ['admincp:4']], function () {
			Route::post('/admincp/users/exact', array('uses' => 'AdminController@checkExactMatch', 'as' => 'checkExactMatch'));
			Route::post('/admincp/user/accolade/add', array('uses' => 'AdminController@addAccolade', 'as' => 'addAccolade'));
			Route::post('/admincp/user/accolade/delete', array('uses' => 'AdminController@deleteAccolade', 'as' => 'deleteAccolade'));
			Route::post('/admincp/user/accolade/edit', array('uses' => 'AdminController@editAccolade', 'as' => 'editAccolade'));

			Route::group(['middleware' => ['admincp:524288']], function () {
				Route::post('/admincp/user/update/usergroups', array('uses' => 'AdminController@updateUser', 'as' => 'updateUser'));
			});
			Route::group(['middleware' => ['admincp:1024']], function () {
				Route::post('/admincp/user/update/general', array('uses' => 'AdminController@updateGeneral', 'as' => 'updateGeneral'));
				Route::post('/admincp/user/update/timecountry', array('uses' => 'AdminController@updateTimeCountry', 'as' => 'updateTimeCountry'));
			});
			Route::post('/admincp/user/remove/group', array('uses' => 'AdminController@removeUserGroup', 'as' => 'removeUserGroup'));
			Route::post('/admincp/user/update/signature', array('uses' => 'AdminController@updateSignature', 'as' => 'updateSignature'));
			Route::post('/admincp/user/update/timecountry', array('uses' => 'AdminController@updateTimeCountry', 'as' => 'updateTimeCountry'));
			Route::post('/admincp/user/update/habbo', array('uses' => 'AdminController@updateHabbo', 'as' => 'updateHabbo'));
			Route::post('/admincp/user/update/avatar', array('uses' => 'AdminController@updateAvatar', 'as' => 'updateAvatar'));
			Route::post('/admincp/user/update/header', array('uses' => 'AdminController@updateHeader', 'as' => 'updateHeader'));
			Route::post('/admincp/users/ban', array('uses' => 'AdminController@banUser', 'as' => 'banUser'));
			Route::post('/admincp/users/unban', array('uses' => 'AdminController@unBanUser', 'as' => 'unBanUser'));
			Route::post('/admincp/user/update/bio', array('uses' => 'AdminController@updateBio', 'as' => 'updateBio'));
		});

		Route::group(['middleware' => ['admincp:131072']], function() {
			Route::post('/admincp/settings/save/rules', array('uses' => 'AdminController@updateSiteRules', 'as' => 'updateSiteRules'));
		});

		Route::group(['middleware' => ['admincp:134217728']], function() {
			Route::post('/admincp/settings/save/partners', array('uses' => 'AdminController@updateLinkPartners', 'as' => 'updateLinkPartners'));
		});

		Route::group(['middleware' => ['admincp:268435456']], function() {
			Route::post('/admincp/site/sotw/submit', array('uses' => 'AdminController@updateSOTW', 'as' => 'updateSOTW'));
			Route::post('/admincp/site/motm/submit', array('uses' => 'AdminController@updateMOTM', 'as' => 'updateMOTM'));
			Route::post('/admincp/site/photo/submit', array('uses' => 'AdminController@updatePCMW', 'as' => 'updatePCMW'));
		});

		Route::group(['middleware' => ['admincp:16']], function () {
			Route::post('/admincp/settings/add/bbcode', array('uses' => 'AdminController@postAddBBcode', 'as' => 'postAddBBcode'));
			Route::post('/admincp/settings/remove/bbcode', array('uses' => 'AdminController@postRemoveBBcode', 'as' => 'postRemoveBBcode'));
			Route::post('/admincp/settings/edit/bbcode', array('uses' => 'AdminController@postEditBBcode', 'as' => 'postEditBBcode'));
		});

		Route::group(['middleware' => ['admincp:8388608']], function () {
			Route::post('/admincp/settings/post/automated', array('uses' => 'AdminController@postAutomated', 'as' => 'postAutomated'));
			Route::post('/admincp/settings/delete/automated', array('uses' => 'AdminController@postDeleteAutomated', 'as' => 'postDeleteAutomated'));
			Route::post('/admincp/settings/save/automated', array('uses' => 'AdminController@postSaveAutomated', 'as' => 'postSaveAutomated'));
		});

		Route::group(['middleware' => ['admincp:33554432']], function () {
			Route::post('/admincp/settings/maintenance/start', array('uses' => 'AdminController@postStartMaintenance', 'as' => 'postStartMaintenance'));
			Route::post('/admincp/settings/maintenance/stop', array('uses' => 'AdminController@postStopMaintenance', 'as' => 'postStopMaintenance'));
		});

		Route::group(['middleware' => ['admincp:2']], function () {
			Route::post('/admincp/settings/modforum/add', array('uses' => 'AdminController@postAddModforum', 'as' => 'postAddModforum'));
			Route::post('/admincp/settings/modforum/remove', array('uses' => 'AdminController@postRemoveModforum', 'as' => 'postRemoveModforum'));
		});

		Route::group(['middleware' => ['admincp:16777216']], function () {
			Route::post('/admincp/settings/add/staff', array('uses' => 'AdminController@postAddStaff', 'as' => 'postAddStaff'));
			Route::post('/admincp/settings/remove/staff', array('uses' => 'AdminController@postRemoveStaff', 'as' => 'postRemoveStaff'));
		});

/* pages you use GET to fetch */
Route::group(['prefix' => 'xhrst'], function () {

    Route::get('/vx-progress', ['uses' => 'HomeController@getVxProgress', 'as' => 'getVxProgress']);
    Route::get('/megarate', ['uses' => 'HomeController@getMegarate', 'as' => 'getMegarate']);

	Route::group(['middleware' => ['notauth']], function () {
		Route::get('/login', array('uses' => 'AuthController@getLogin', 'as' => 'getLogin'));
		Route::get('/auth/forgot/password', array('uses' => 'AuthController@getForgot', 'as' => 'getForgot'));
		Route::get('/register', array('uses' => 'AuthController@getRegister', 'as' => 'getRegister'));
		Route::get('/auth/change/password', array('uses' => 'AuthController@getChangePassword', 'as' => 'getChangePassword'));
	});

    Route::get('/leaguetournament', array('uses' => 'HomeController@getLeague', 'as' => 'getLeague'));
	Route::get('/success', function(){return view('extras.success');});
	Route::get('/fail', function(){return view('extras.fail');});

	Route::get('/maintenance', array('uses' => 'HomeController@getMaintenance', 'as' => 'getMaintenance'));
	Route::group(['middleware' => ['maintenance']], function() {
		Route::get('/', array('uses' => 'HomeController@getHome', 'as' => 'getHome'));
		Route::get('/home', array('uses' => 'HomeController@getHome', 'as' => 'getHome'));
		Route::get('/rules', array('uses' => 'PageController@getRules', 'as' => 'getRules'));
		Route::get('/partners', array('uses' => 'PageController@getPartners', 'as' => 'getPartners'));

        Route::get('/search', array('uses' => 'PageController@getSearch', 'as' => 'getSearch'));

		Route::get('/leaderboard', array('uses' => 'PageController@getLeaderboard', 'as' => 'getLeaderboard'));

		Route::get('/creations/page/{nr}', array('uses' => 'PageController@getCreations', 'as' => 'getCreations'));
		Route::get('/creation/{creationid}', array('uses' => 'PageController@getCreation', 'as' => 'getCreation'));

		Route::get('/forum', array('uses' => 'HomeController@getForum', 'as' => 'getForum'));

		Route::get('/staff/list', array('uses' => 'PageController@getStaffList', 'as' => 'getStaffList'));

		Route::get('/badges', array('uses' => 'PageController@getScannedBadges', 'as' => 'getScannedBadges'));
        Route::post('/badges', array('uses' => 'PageController@getSubscribeBadge', 'as' => 'getSubscribeBadge'));
		Route::get('/badges/{badge}', array('uses' => 'PageController@getSearchedBadges', 'as' => 'getSearchedBadges'));

		Route::get('/events', array('uses' => 'PageController@getEvents', 'as' => 'getEvents'));

		Route::get('/timetable', array('uses' => 'PageController@getRadioTimetable', 'as' => 'getRadioTimetable'));

		Route::get('/djleaderboard', array('uses' => 'PageController@getLoveLeaderboard', 'as' => 'getLoveLeaderboard'));

		Route::get('/requests', array('uses' => 'PageController@getRequestLine', 'as' => 'getRequestLine'));


		Route::get('/articles/{type}/page/{pagenr}', array('uses' => 'PageController@getArticleSection', 'as' => 'getArticleSection'));

		Route::get('/credits', array('uses' => 'PageController@getCredits', 'as' => 'getCredits'));
		Route::get('/event/types', array('uses' => 'PageController@listEventTypes', 'as' => 'listEventTypes'));

		Route::get('/article/{articleid}', array('uses' => 'HomeController@getArticle', 'as' => 'getArticle'));
        Route::get('/article/{articleid}/comment/{focuscomment}', array('uses' => 'HomeController@getArticle', 'as' => 'getArticle'));
        Route::get('/article/{articleid}/page/{pagenr}', array('uses' => 'HomeController@getArticle', 'as' => 'getArticle'));

        Route::get('/jobs', array('uses' => 'PageController@getJobs', 'as' => 'getJobs'));
        Route::get('/aaron', array('uses' => 'PageController@aaronsPage', 'as' => 'aaronsPage'));

        Route::get('/betting', array('uses' => 'PageController@getBettingHub', 'as' => 'getBettingHub'));
        Route::get('/betting/own', array('uses' => 'PageController@getOwnBets', 'as' => 'getOwnBets'));
        Route::get('/betting/history', array('uses' => 'PageController@getBettingHistory', 'as' => 'getBettingHistory'));

        Route::get('/market', array('uses' => 'PageController@getMarket', 'as' => 'getMarket'));

        Route::get('/about', array('uses' => 'PageController@getAboutUs', 'as' => 'getAboutUs'));

        Route::get('/contact', array('uses' => 'PageController@getContactUs', 'as' => 'getContactUs'));

        Route::get('/badgehub', array('uses' => 'PageController@getBadgeHub', 'as' => 'getBadgeHub'));

        Route::get('/eventseu', array('uses' => 'PageController@getEventsEU', 'as' => 'getEventsEU'));
        Route::get('/eventsna', array('uses' => 'PageController@getEventsNA', 'as' => 'getEventsNA'));
        Route::get('/eventsoc', array('uses' => 'PageController@getEventsOC', 'as' => 'getEventsOC'));

        Route::get('/radioeu', array('uses' => 'PageController@getRadioEU', 'as' => 'getRadioEU'));
        Route::get('/radiona', array('uses' => 'PageController@getRadioNA', 'as' => 'getRadioNA'));
        Route::get('/radiooc', array('uses' => 'PageController@getRadioOC', 'as' => 'getRadioOC'));

		/* GOODIE ROUTES */
		Route::get('/goodies/habbo/imager', array('uses' => 'HabboAlternations@getImager', 'as' => 'getImager'));
		Route::get('/goodies/alterations', array('uses' => 'HabboAlternations@getAlternations', 'as' => 'getAlternations'));
		Route::get('/goodies/kissing', array('uses' => 'HabboAlternations@getKissing', 'as' => 'getKissing'));
		Route::get('/goodies/badge/scanner', array('uses' => 'HabboAlternations@getBadgeScanner', 'as' => 'getBadgeScanner'));
		Route::get('/goodies/top25/badge/collectors', array('uses' => 'HabboAlternations@getTop25Collectors', 'as' => 'getTop25Collectors'));

		/* ONLY LOGGED IN USERS CAN REACH THESE */
		Route::group(['middleware' => ['auth']], function () {

			/* Clan Routes*/

			Route::get('/clans/{name}', array('uses' => 'HomeController@getClan', 'as' => 'getClan'));
			Route::get('/clans/{name}/edit/avatar', array('uses' => 'HomeController@getEditClanAvatar', 'as' => 'getEditClanAvatar'));
			Route::get('/clans/{name}/edit/header', array('uses' => 'HomeController@getEditClanHeader', 'as' => 'getEditClanHeader'));
			Route::get('/clans', array('uses' => 'HomeController@getClanLeaderboard', 'as' => 'getClanLeaderboard'));

			/* Profile Routes */

			Route::get('/profile/{username}', array('uses' => 'HomeController@getProfile', 'as' => 'getProfile'));

			Route::get('/profile/{username}/page/{page}', array('uses' => 'HomeController@getProfile', 'as' => 'getProfile'));

			Route::get('/profile/{username}/followers', array('uses' => 'HomeController@getFollowers', 'as' => 'getFollowers'));

			Route::get('/profile/{username}/following', array('uses' => 'HomeController@getFollowing', 'as' => 'getFollowing'));

			Route::get('/conversation/{username1}/{username2}/page/{pagenr}', array('uses' => 'HomeController@getConverstation', 'as' => 'getConverstation'));

			Route::get('/forum/newposts/page/{pagenr}', array('uses' => 'HomeController@getNewPosts', 'as' => 'getNewPosts'));

            /* Feature Routes */
			Route::group(['middleware' => ['feature:1']], function () {
			});

			Route::get('/activity/page/{pagenr}', array('uses' => 'PageController@getActivity', 'as' => 'getActivity'));

			Route::get('/creations/upload', array('uses' => 'PageController@getUploadCreation', 'as' => 'getUploadCreation'));

			Route::get('/game/blockrain', array('uses' => 'GameController@getBlockrain', 'as' => 'getBlockrain'));

			/* FORUM ROUTES */
			Route::get('/forum/category/{forumid}/page/{pagenr}', array('uses' => 'HomeController@getCategory', 'as' => 'getCategory'));
			Route::get('/forum/bbcodes/list', array('uses' => 'HomeController@getBBCodes', 'as' => 'getBBcodes'));
			Route::get('/forum/thread/{threadid}/page/{pagenr}', array('uses' => 'HomeController@getThread', 'as' => 'getThread'));
			Route::get('/forum/thread/{threadid}/page/{pagenr}/{userSearch}', array('uses' => 'HomeController@getThread', 'as' => 'getThread'));
			Route::get('/forum/thread/loadPosters/{threadid}', array('uses' => 'HomeController@loadPosters', 'as' => 'loadPosters'));


			Route::get('/forum/category/{forumid}/new/thread', array('uses' => 'HomeController@getNewThread', 'as' => 'getNewThread'));

			Route::get('/forum/edit/thread/{threadid}', array('uses' => 'HomeController@getEditThread', 'as' => 'getEditThread'));

			/* USERCP ROUTES */
			Route::get('/usercp', array('uses' => 'UserController@getIndex', 'as' => 'getIndex'));

			Route::get('/usercp/clans/create', array('uses' => 'HomeController@getCreateClan', 'as' => 'getCreateClan'));

			/* USERCP SETTINGS */
			Route::get('/usercp/settings/account', array('uses' => 'UserController@getAccountSettings', 'as' => 'getAccountSettings'));
			Route::get('/usercp/settings/forum', array('uses' => 'UserController@getForumSettings', 'as' => 'getForumSettings'));
			Route::get('/usercp/settings/profile', array('uses' => 'UserController@getProfileSettings', 'as' => 'getProfileSettings'));

			Route::get('/usercp/pm', array('uses' => 'UserController@getPmIndex', 'as' => 'getPmIndex'));

			Route::get('/usercp/avatar', array('uses' => 'UserController@getEditAvatar', 'as' => 'getEditAvatar'));

			Route::get('/usercp/notices/{lastid}', array('uses' => 'LongpullController@loadNotification', 'as' => 'loadNotification'));

			Route::get('/usercp/notices/read/{notificationid}', array('uses' => 'UserController@readNotification', 'as' => 'readNotification'));

			Route::get('/usercp/notifications/{page?}', array('uses' => 'UserController@getNotifications', 'as' => 'getNotifications'));

			Route::get('/usercp/signature', array('uses' => 'UserController@getSignature', 'as' => 'getSignature'));

			Route::get('/usercp/header', array('uses' => 'UserController@getEditHeader', 'as' => 'getEditHeader'));

			Route::get('/usercp/credits', array('uses' => 'UserController@getCredits', 'as' => 'getCredits'));

			Route::get('/usercp/credits/status', array('uses' => 'UserController@getPaymentStatus', 'as' => 'getPaymentStatus'));

			Route::get('/usercp/badges', array('uses' => 'UserController@getUsersBadges', 'as' => 'getUsersBadges'));

			Route::get('/usercp/habbo', array('uses' => 'UserController@getEditHabbo', 'as' => 'getEditHabbo'));

			Route::get('/usercp/boxes', array('uses' => 'UserController@getOpenBoxes', 'as' => 'getOpenBoxes'));

			/* SHOP SHIT */
			Route::get('/usercp/shop', array('uses' => 'UserController@getShop', 'as' => 'getShop'));

			Route::get('/usercp/shop/owned', array('uses' => 'UserController@getOwned', 'as' => 'getOwned'));

            Route::get('/usercp/shop/box/open/{boxid}', array('uses' => 'UserController@openBox', 'as' => 'openBox'));

            Route::get('/usercp/shop/backgrounds/page/{nr}', array('uses' => 'UserController@getShopBackgrounds', 'as' => 'getShopBackgrounds'));

			Route::get('/usercp/shop/icons/page/{nr}', array('uses' => 'UserController@getShopIcons', 'as' => 'getShopIcons'));
            Route::get('/usercp/shop/stickers/page/{nr}', array('uses' => 'UserController@getShopStickers', 'as' => 'getShopStickers'));

			Route::get('/usercp/shop/effects/page/{nr}', array('uses' => 'UserController@getShopEffects', 'as' => 'getShopEffects'));

			Route::get('/usercp/shop/subs/page/{nr}', array('uses' => 'UserController@getShopSubs', 'as' => 'getShopSubs'));

			Route::get('/usercp/shop/themes/page/{nr}', array('uses' => 'UserController@getShopThemes', 'as' => 'getShopThemes'));

            Route::get('/usercp/shop/boxes/page/{nr}', array('uses' => 'UserController@getShopBoxes', 'as' => 'getShopThemes'));

			/* MODERATION ROUTES */
			Route::get('/forum/move/thread/{threadid}', array('uses' => 'ModerationController@getMoveThread', 'as' => 'getMoveThread'));

			Route::get('/forum/merge/{threadid}/with/{mergeid}', array('uses' => 'ModerationController@getMergeThreads', 'as' => 'getMergeThreads'));

			/* STAFF ROUTES */
			Route::group(['middleware' => ['staff:1']], function () {

				Route::get('/staff', array('uses' => 'StaffController@getIndex', 'as' => 'getIndex'));
				Route::get('/staff/region', array('uses' => 'StaffController@getChangeRegion', 'as' => 'getChangeRegion'));
				Route::get('/staff/request', array('uses' => 'StaffController@getRequestTHC', 'as' => 'getRequestTHC'));

				Route::group(['middleware' => ['staff:8192']], function(){
					Route::get('/staff/manage/analytics/{days}', array('uses'=> 'StaffController@getRadioAnalytics', 'as' => 'getRadioAnalytics'));
					Route::get('/staff/manage/analytics', array('uses'=> 'StaffController@getRadioAnalyticsPage', 'as'=>'getRadioAnalyticsPage'));
					Route::get('/staff/manage/radio', array('uses' => 'StaffController@getManageRadio', 'as' => 'getManageRadio'));
					Route::get('/staff/manage/trialradio', array('uses' => 'StaffController@getTrialRadio', 'as' => 'getTrialRadio'));
					Route::get('/staff/manage/kick', array('uses' => 'StaffController@getKickDJ', 'as' => 'getKickDJ'));

				});

				Route::group(['middleware' => ['staff:16384']], function() {
					Route::get('/staff/media/flagged/articles', array('uses' => 'StaffController@getFlaggedArticles', 'as' => 'getFlaggedArticles'));
				});

				/* RADIO ROUTES */
				Route::group(['middleware' => ['staff:2']], function () {
					Route::get('/staff/radio/timetable/{day}', array('uses' => 'StaffController@getRadioTimetable', 'as' => 'getRadioTimetable'));
					Route::get('/staff/radio/timetable', array('uses' => 'StaffController@getRadioTimetable', 'as' => 'getRadioTimetable'));
					Route::get('/staff/radio/request/page/{pagenr}', array('uses' => 'StaffController@getRadioRequests', 'as' => 'getRadioRequests'));
					Route::get('/staff/radio/connection', array('uses' => 'StaffController@getRadioDetails', 'as' => 'getRadioDetails'));
					Route::get('/staff/radio/djsays', array('uses' => 'StaffController@getDjSays', 'as' => 'getDjSays'));
					Route::get('/staff/radio/live', array('uses' => 'StaffController@getLiveStats', 'as' => 'getLiveStats'));
				});

				/* EVENT ROUTES */
				Route::group(['middleware' => ['staff:1048576']], function () {
					Route::get('/staff/event/timetable/{day}', array('uses' => 'StaffController@getEventTimetable', 'as' => 'getEventTimetable'));
					Route::get('/staff/event/timetable', array('uses' => 'StaffController@getEventTimetable', 'as' => 'getEventTimetable'));
				});

				/* MEDIA ROUTES */
				Route::group(['middleware' => ['staff:128']], function () {
					Route::get('/staff/media/article/add', array('uses' => 'StaffController@getAddArticle', 'as' => 'getAddArticle'));
					Route::get('/staff/media/article/addbadge', array('uses' => 'StaffController@getAddBadge', 'as' => 'getAddBadge'));
					Route::get('/staff/media/article/addbundle', array('uses' => 'StaffController@getAddBundle', 'as' => 'getAddBundle'));
					Route::get('/staff/media/article/addrare', array('uses' => 'StaffController@getAddRare', 'as' => 'getAddRare'));
					Route::get('/staff/media/article/edit/{articleid}', array('uses' => 'StaffController@getEditArticle', 'as' => 'getEditArticle'));
					Route::get('/staff/media/articles/page/{pagenr}', array('uses' => 'StaffController@getManageArticles', 'as' => 'getManageArticles'));
				});

				/* MANAGER ROUTES */
				Route::group(['middleware' => ['staff:32']], function () {
					Route::get('/staff/logs/radio/page/{pagenr}', array('uses' => 'StaffController@getRadioTimetableLog', 'as' => 'getRadioTimetableLog'));
					Route::get('/staff/logs/events/page/{pagenr}', array('uses' => 'StaffController@getEventsTimetableLog', 'as' => 'getEventsTimetableLog'));

					/* PERM SHOW ROUTES */
					Route::group(['middleware' => ['staff:64']], function () {
						Route::get('/staff/perm/manage', array('uses' => 'StaffController@getPermShows', 'as' => 'getPermShows'));
						Route::get('/staff/perm/add', array('uses' => 'StaffController@getAddPerm', 'as' => 'getAddPerm'));
						Route::get('/staff/perm/edit/{timetableid}', array('uses' => 'StaffController@getEditPerm', 'as' => 'getEditPerm'));
					});

					/* MANAGE EVENTS TYPES */
					Route::group(['middleware' => ['staff:524288']], function () {
						Route::get('/staff/events/manage', array('uses' => 'StaffController@getEventTypes', 'as' => 'getEventTypes'));
					});
				});

				/* GRAPHIC ROUTES */
				Route::group(['middleware' => ['staff:512']], function () {

					Route::get('/staff/graphic/gallery/page/{pagenr}', array('uses' => 'StaffController@getGallery', 'as' => 'getGallery'));
					Route::get('/staff/graphic/gallery/page/{pagenr}/search/{search}', array('uses' => 'StaffController@getGallery', 'as' => 'getGallery'));

					Route::group(['middleware' => ['staff:1024']], function () {
						Route::get('/staff/graphic/upload', array('uses' => 'StaffController@getUploadImage', 'as' => 'getUploadImage'));
					});
				});

				/* MOD ROUTES */
				Route::group(['middleware' => ['generalmod:1']], function () {
					Route::get('/staff/mod/users/search', array('uses' => 'StaffController@getSearchUsers', 'as' => 'getSearchUsers'));
					Route::get('/staff/mod/users/{username}/page/{page}', array('uses' => 'StaffController@getUserList', 'as' => 'getUserList'));
					Route::get('/staff/mod/users/banned', array('uses' => 'StaffController@getBannedUsers', 'as' => 'getBannedUsers'));
					Route::get('/admincp/users/accolade/{userid}', array('uses' => 'AdminController@getAccolade', 'as' => 'getAccolade'));
					Route::get('/admincp/users/accolade/edit/{accoladeid}', array('uses' => 'AdminController@getEditAccolade', 'as' => 'getEditAccolade'));
					Route::get('/staff/mod/infractions/page/{pagenr}', array('uses' => 'StaffController@infractionLog', 'as' => 'infractionLog'));
					Route::get('/staff/mod/users/banned', array('uses' => 'StaffController@listBannedUsers', 'as' => 'listBannedUsers'));

					Route::group(['middleware' => ['generalmod:128']], function () {
						Route::get('/staff/mod/creations', array('uses' => 'StaffController@getApprovingCreations', 'as' => 'getApprovingCreations'));
					});

					Route::group(['middleware' => ['generalmod:64']], function () {
						Route::get('/staff/mod/users/similar/{search}', array('uses' => 'StaffController@getUsersUsingSameIp', 'as' => 'getUsersUsingSameIp'));
						Route::get('/staff/mod/users/findsimilar', array('uses' => 'StaffController@getSearchSimUsers', 'as' => 'getSearchSimUsers'));
					});
				});

				/* ADMIN ROUTES */
				Route::group(['middleware' => ['staff:32768']], function () {
					Route::get('/staff/manage/jobs', array('uses' => 'StaffController@getJobs', 'as' => 'getJobs'));
				});
			});

			/* ONLY USERS WITH ACCESS TO ADMINCP CAN REACH THESE */
			Route::group(['middleware' => ['admincp:1']], function () {

				Route::group(['middleware' => ['admincp:68719476736']], function() {
					Route::get('/admincp/bets/page/{pagenr}', array('uses' => 'AdminController@getExistingBets', 'as' => 'getExistingBets'));
					Route::get('/admincp/bets/edit/{betid}', array('uses' => 'AdminController@getEditBet', 'as' => 'getEditBet'));
					Route::get('/admincp/bets/create', array('uses' => 'AdminController@getCreateBet', 'as' => 'getCreateBet'));
                    Route::get('/admincp/bets/logs/page/{pagenr}', array('uses' => 'AdminController@getBetLog', 'as' => 'getBetLog'));

                    Route::get('/admincp/clans/page/{pagenr}', array('uses' => 'AdminController@getAllClans', 'as' => 'getAllClans'));
                    Route::get('/admincp/clans/accolade/{clanid}', array('uses' => 'AdminController@getClanAccolade', 'as' => 'getClanAccolade'));
                    Route::get('/admincp/clans/accolade/edit/{accoladeid}', array('uses' => 'AdminController@getEditClanAccolade', 'as' => 'getEditClanAccolade'));
				});

				Route::group(['middleware' => ['admincp:8589934592']], function () {
					Route::get('/admincp/carousel', array('uses' => 'AdminController@getCarousel', 'as' => 'getCarousel'));
				});

				Route::group(['middleware' => ['admincp:17179869184']], function () {
			        Route::get('/admincp/bot', array('uses' => 'AdminController@getBotPage', 'as' => 'getBotPage'));
          			Route::get('/admincp/errors', array('uses' => 'AdminController@getErrorLog', 'as' => 'getErrorLog'));
				});

        		Route::get('/admincp', array('uses' => 'AdminController@getIndex', 'as' => 'getIndex'));

				Route::group(['middleware' => ['admincp:4294967296']], function () {
					Route::get('/admincp/points/issue', array('uses' => 'AdminController@getManagePoints', 'as' => 'getManagePoints'));
					Route::get('/admincp/points/requests', array('uses' => 'AdminController@getManagePointRequests', 'as' => 'getManagePointRequests'));
				});
				Route::group(['middleware' => ['admincp:2147483648']], function () {
					Route::get('/admincp/statistics', array('uses' => 'AdminController@getStats', 'as' => 'getStats'));
				});
				Route::group(['middleware' => ['admincp:1073741824']], function () {
					Route::get('/admincp/postingfest', array('uses' => 'AdminController@getPostingFestLog', 'as' => 'getPostingFestLog'));
					Route::get('/admincp/adminlog/page/{nr}', array('uses' => 'AdminController@getAdminLog', 'as' => 'getAdminLog'));
					Route::get('/admincp/modlog/page/{nr}', array('uses' => 'AdminController@getModLog', 'as' => 'getModLog'));
					Route::get('/admincp/pointslogs/page/{nr}', array('uses' => 'AdminController@getPointsLog', 'as' => 'getPointsLog'));
					Route::get('/admincp/voucherlog/page/{nr}', array('uses' => 'AdminController@getVoucherLog', 'as' => 'getVoucherLog'));
					Route::get('/admincp/radiodetailslog/page/{nr}', array('uses' => 'AdminController@getRadioDetailsLog', 'as' => 'getRadioDetailsLog'));
        		});
				Route::group(['middleware' => ['admincp:67108864']], function () {
					Route::get('/admincp/dailyquest', array('uses' => 'AdminController@getAddDailyQuest', 'as' => 'getAddDailyQuest'));
				});

				/* FORUM ROUTES */
				Route::group(['middleware' => ['admincp:2']], function () {
					Route::get('/admincp/forums', array('uses' => 'AdminController@getForums', 'as' => 'getForums'));
					Route::get('/admincp/forums/add', array('uses' => 'AdminController@getAddForum', 'as' => 'getAddForum'));
					Route::get('/admincp/forums/edit/{forumid}', array('uses' => 'AdminController@getEditForum', 'as' => 'getEditForum'));
					Route::get('/admincp/threads/{threadid}', array('uses' => 'AdminController@getThreadInfo', 'as' => 'getThreadInfo'));
					Route::get('/admincp/search/threads', array('uses' => 'AdminController@getSearchThreads', 'as' => 'getSearchThreads'));
				});

				Route::group(['middleware' => ['admincp:131072']], function () {
					Route::get('/admincp/site/rules', array('uses' => 'AdminController@getSiteRules', 'as' => 'getSiteRules'));
				});

				Route::group(['middleware' => ['admincp:134217728']], function () {
					Route::get('/admincp/site/partners', array('uses' => 'AdminController@getLinkPartners', 'as' => 'getLinkPartners'));
				});


				Route::group(['middleware' => ['admincp:268435456']], function () {
					Route::get('/admincp/site/sotw', array('uses' => 'AdminController@getSOTW', 'as' => 'getSOTW'));
					Route::get('/admincp/site/motm', array('uses' => 'AdminController@getMOTM', 'as' => 'getMOTM'));
					Route::get('/admincp/site/photo', array('uses' => 'AdminController@getPCMW', 'as' => 'getPCMW'));
				});

				Route::group(['middleware' => ['admincp:2048']], function () {
					Route::get('/admincp/prefixes', array('uses' => 'AdminController@getPrefixes', 'as' => 'getPrefixes'));
					Route::get('/admincp/prefixes/edit/{prefixid}', array('uses' => 'AdminController@getEditPrefix', 'as' => 'getEditPrefix'));
				});

				Route::group(['middleware' => ['admincp:34359738368']], function () {
					Route::get('/admincp/xplevels', array('uses' => 'AdminController@getXPLevels', 'as' => 'getXPLevels'));
					Route::get('/admincp/xplevels/edit/{xplevelid}', array('uses' => 'AdminController@getEditXPLevel', 'as' => 'getEditXPLevel'));

				});

				/* BADGES STUFF */
				Route::group(['middleware' => ['admincp:262144']], function () {
					Route::get('/admincp/badges/manage/page/{pagenr}', array('uses' => 'AdminController@getManageBadges', 'as' => 'getManageBadges'));
					Route::get('/admincp/badge/edit/{badgeid}', array('uses' => 'AdminController@getEditBadge', 'as' => 'getEditBadge'));
					Route::get('/admincp/badge/manage/{badgeid}', array('uses' => 'AdminController@getManageBadge', 'as' => 'getManageBadge'));
				});

				Route::group(['middleware' => ['admincp:536870912']], function () {
					Route::get('/admincp/twitter/twitterUserTimeLine', array('uses' => 'TwitterController@twitterUserTimeLine', 'as' => 'twitterUserTimeLine'));
					Route::post('/admincp/twitter/tweet', array('uses' => 'TwitterController@tweet', 'as' => 'post.tweet'));
				});

				/* SHOP ROUTES */
				Route::group(['middleware' => ['admincp:8192']], function () {
                    Route::get('/admincp/box', array('uses' => 'AdminController@addToBoxPage', 'as' => 'addToBoxPage'));
					Route::get('/admincp/list/stickers', array('uses' => 'AdminController@getStickers', 'as' => 'getStickers'));
					Route::get('/admincp/new/sticker', array('uses' => 'AdminController@getNewSticker', 'as' => 'getNewSticker'));
					Route::get('/admincp/edit/sticker/{stickerid}', array('uses' => 'AdminController@getEditSticker', 'as' => 'getEditSticker'));

					Route::group(['middleware' => ['admincp:65536']], function () {
						Route::get('/admincp/list/nameicons', array('uses' => 'AdminController@getNameIcons', 'as' => 'getNameIcons'));
						Route::get('/admincp/new/nameicon', array('uses' => 'AdminController@getNewNameIcon', 'as' => 'getNewNameIcon'));
						Route::get('/admincp/edit/nameicon/{iconid}', array('uses' => 'AdminController@getEditNameIcon', 'as' => 'getEditNameIcon'));
					});

                    Route::get('/admincp/list/backgrounds', array('uses' => 'AdminController@getBackgrounds', 'as' => 'getBackgrounds'));
                    Route::get('/admincp/new/background', array('uses' => 'AdminController@getNewBackground', 'as' => 'getNewBackground'));
                    Route::get('/admincp/edit/background/{backgroundid}', array('uses' => 'AdminController@getEditBackground', 'as' => 'getEditNameBackground'));

                    Route::get('/admincp/list/boxes', array('uses' => 'AdminController@getBoxes', 'as' => 'getBox'));
                    Route::get('/admincp/new/box', array('uses' => 'AdminController@getNewBox', 'as' => 'getNewBackground'));
                    Route::get('/admincp/edit/box/{boxid}', array('uses' => 'AdminController@getEditBox', 'as' => 'getEditNameBox'));



					Route::group(['middleware' => ['admincp:16384']], function () {
						Route::get('/admincp/list/nameeffects', array('uses' => 'AdminController@getNameEffects', 'as' => 'getNameEffects'));
						Route::get('/admincp/new/nameeffect', array('uses' => 'AdminController@getNewNameEffect', 'as' => 'getNewNameEffect'));
						Route::get('/admincp/edit/nameeffect/{effectid}', array('uses' => 'AdminController@getEditNameEffect', 'as' => 'getEditNameEffect'));
					});

					Route::group(['middleware' => ['admincp:32768']], function() {
						Route::get('/admincp/manage/voucher', array('uses' => 'AdminController@getManageVoucher', 'as' => 'getManageVoucher'));
					});

					Route::group(['middleware' => ['admincp:256']], function () {
						Route::get('/admincp/list/subscriptions', array('uses' => 'AdminController@getSubscriptions', 'as' => 'getSubscriptions'));
						Route::get('/admincp/subscription/new', array('uses' => 'AdminController@getNewSubscription', 'as' => 'getNewSubscription'));
						Route::get('/admincp/subscription/edit/{packageid}', array('uses' => 'AdminController@getEditSubscription', 'as' => 'getEditSubscription'));
					});

					Route::group(['middleware' => ['admincp:4194304']], function () {
						Route::get('/admincp/list/themes', array('uses' => 'AdminController@getThemes', 'as' => 'getThemes'));
						Route::get('/admincp/theme/new', array('uses' => 'AdminController@getNewTheme', 'as' => 'getNewTheme'));
						Route::get('/admincp/theme/edit/{themeid}', array('uses' => 'AdminController@getEditTheme', 'as' => 'getEditTheme'));
					});

					Route::group(['middleware' => ['admincp:4096']], function () {
						Route::get('/admincp/user/{userid}/subscriptions', array('uses' => 'AdminController@getUsersSubscriptions', 'as' => 'getUsersSubscriptions'));
					});
				});

				/* USERGROUP ROUTES */
				Route::group(['middleware' => ['admincp:8']], function () {
					Route::get('/admincp/usergroups', array('uses' => 'AdminController@getUsergroups', 'as' => 'getUsergroups'));
					Route::get('/admincp/usergroups/add', array('uses' => 'AdminController@getAddGroup', 'as' => 'getAddGroup'));
					Route::get('/admincp/usergroups/edit/{groupid}', array('uses' => 'AdminController@getEditGroup', 'as' => 'getEditGroup'));
					Route::get('/admincp/usergroups/edit/bar/{groupid}', array('uses' => 'AdminController@getEditGroupBar', 'as' => 'getEditGroupBar'));
					Route::get('/admincp/usergroups/select/{groupid}/{type}', array('uses' => 'AdminController@getSelectForum', 'as' => 'getSelectForum'));
					Route::get('/admincp/usergroup/{groupid}/edit/forumpermissions/{forumid}', array('uses' => 'AdminController@getEditForumpermissions', 'as' => 'getEditForumpermissions'));

					Route::group(['middleware' => ['admincp:32']], function () {
						Route::get('/admincp/usergroup/{groupid}/edit/adminpermissions', array('uses' => 'AdminController@getEditAdminPermissions', 'as' => 'getEditAdminPermissions'));
					});

					Route::group(['middleware' => ['admincp:0']], function () {
						Route::get('/admincp/notices/list', array('uses' => 'AdminController@getListNotices', 'as' => 'getListNotices'));
						Route::get('/admincp/notices/add', array('uses' => 'AdminController@getAddNotices', 'as' => 'getAddNotices'));
						Route::get('/admincp/notices/edit/{noticeid}', array('uses' => 'AdminController@getEditNotices', 'as' => 'getEditAdminPermissions'));
					});

					Route::group(['middleware' => ['admincp:2097152']], function() {
						Route::get('/admincp/usergroup/{groupid}/edit/generalmodperms', array('uses' => 'AdminController@getEditGeneralModPerms', 'as' => 'getEditGeneralModPerms'));
					});

					Route::group(['middleware' => ['admincp:64']], function () {
						Route::get('/admincp/usergroup/{groupid}/edit/moderationpermissions/{forumid}', array('uses' => 'AdminController@getEditModerationpermissions', 'as' => 'getEditModerationpermissions'));
					});

					Route::group(['middleware' => ['admincp:128']], function () {
						Route::get('/admincp/usergroup/{groupid}/edit/staffpermissions', array('uses' => 'AdminController@getEditStaffPermissions', 'as' => 'getEditStaffPermissions'));
					});

					Route::group(['middleware' => ['admincp:512']], function () {
						Route::get('/admincp/default/forum/perms', array('uses' => 'AdminController@getDefaultPerms', 'as' => 'getDefaultPerms'));
						Route::get('/admincp/default/forum/perms/{forumid}', array('uses' => 'AdminController@getDefaultPermsGroup', 'as' => 'getDefaultPermsGroup'));
					});
				});

				/* USER MANAGEMENT ROUTES */
				Route::group(['middleware' => ['admincp:4']], function () {
					Route::get('/admincp/users/search', array('uses' => 'AdminController@getSearchUsers', 'as' => 'getSearchUsers'));
					Route::get('/admincp/users/{username}/page/{page}', array('uses' => 'AdminController@getUserList', 'as' => 'getUserList'));
					Route::get('/admincp/users/edit/{userid}', array('uses' => 'AdminController@getEditUser', 'as' => 'getEditUser'));
					Route::get('/admincp/users/banned', array('uses' => 'AdminController@getBannedUsers', 'as' => 'getBannedUsers'));
				});

				/* WEBSITE SETTING ROUTES */
				Route::group(['middleware' => ['admincp:16']], function () {
					Route::get('/admincp/settings/bbcodes', array('uses' => 'AdminController@getBBcodes', 'as' => 'getBBcodes'));
					Route::get('/admincp/settings/bbcode/add', array('uses' => 'AdminController@getNewBBcode', 'as' => 'getNewBBcode'));
					Route::get('/admincp/settings/bbcode/edit/{bbcodeid}', array('uses' => 'AdminController@getEditBBcode', 'as' => 'getEditBBcode'));
				});

				Route::group(['middleware' => ['admincp:8388608']], function () {
					Route::get('/admincp/settings/automated', array('uses' => 'AdminController@getAutomatedThreads', 'as' => 'getAutomatedThreads'));
					Route::get('/admincp/settings/new/automated', array('uses' => 'AdminController@getNewAutomated', 'as' => 'getNewAutomated'));
					Route::get('/admincp/settings/edit/automated/{atid}', array('uses' => 'AdminController@getEditAutomated', 'as' => 'getEditAutomated'));
				});

				Route::group(['middleware' => ['admincp:16777216']], function () {
					Route::get('/admincp/settings/staff/list', array('uses' => 'AdminController@getManageStaffList', 'as' => 'getManageStaffList'));
				});

				Route::group(['middleware' => ['admincp:33554432']], function () {
					Route::get('/admincp/settings/maintenances', array('uses' => 'AdminController@getMaintenances', 'as' => 'getMaintenances'));
				});

				Route::group(['middleware' => ['admincp:2']], function () {
					Route::get('/admincp/settings/modforum', array('uses' => 'AdminController@getModForum', 'as' => 'getModForum'));
				});

				Route::group(['middleware' => ['admincp:134217728']], function () {
					Route::get('/admincp/settings/linkpartners', array('uses' => 'AdminController@getManageLinkPartners', 'as' => 'getManageLinkPartners'));
				});
			});
		});

		Route::get('/error/perm', array('uses' => 'HomeController@getPermError', 'as' => 'getErrorPerm'));
		Route::get('/error/banned', array('uses' => 'HomeController@getthreadBanned', 'as' => 'getthreadBanned'));
	});
	Route::get('/{any}', array('uses' => 'HomeController@getNotFound', 'as' => 'getNotFound'))->where('any', '.+');
});
Route::get('/{any}', array('uses' => 'HomeController@getApp', 'as' => 'getApp'))->where('any', '.+');
