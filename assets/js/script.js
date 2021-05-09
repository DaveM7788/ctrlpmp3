var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;

$(document).click(function(click) {
	var target = $(click.target);
	if (!target.hasClass("item") && !target.hasClass("optionsButton")) {
		hideOptionsMenu();
	}
});

$(window).scroll(function() {
	hideOptionsMenu();
});

$(document).on("change", "select.playlist", function() {
	var select = $(this);
	var playlistId = $(this).val();
	var songId = $(this).prev(".songId").val();
	$.post("includes/handlers/ajax/addToPlaylist.php", {playlistId: playlistId, songId: songId})
	.done(function() {
		hideOptionsMenu();
		select.val("");
	});
}); 

function formatTime(seconds) {
	var time = Math.round(seconds);
	var minutes = Math.floor(time / 60); // rounds down
	var seconds = time - (minutes * 60);

	var extraZero;
	if (seconds < 10) {
		extraZero = "0";
	}
	else {
		extraZero = "";
	}

	return minutes + ":" + extraZero + seconds;
}

function updateTimeProgressBar(audio) {
	$(".progressTime.current").text(formatTime(audio.currentTime));
	var progress = audio.currentTime / audio.duration * 100;
	$(".playbackBar .progress").css("width", progress + "%");
}

function updateVolumeProgressBar(audio) {
	var volume = audio.volume * 100;
	$(".volumeBar .progress").css("width", volume + "%");
}

function openPage(url) {
	if (timer != null) {
		clearTimeout(timer); // prevents search.php from running after navigating away
	}

	if (url.indexOf("?") == -1) {  // doesn't have ?
		url += "?";
	}
	var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
	$("#mainContent").load(encodedUrl);
	$("body").scrollTop(0);  // auto scroll to top when changing page
	history.pushState(null, null, url);  // makes user think the page has changed
}

function playFirstSong() {
	setTrack(tempPlaylist[0], tempPlaylist, true);
}

function removeFromPlaylist(button, playlistId) {
	var songId = $(button).prevAll(".songId").val();
	$.post("includes/handlers/ajax/removeFromPlaylist.php", {playlistId: playlistId, songId: songId})
	.done(function(error) {
		// when ajax returns
		if (error != "") {
			alert(error);
			return;
		}
		openPage("playlist.php?id=" + playlistId);
	});
}

function createPlaylist() {
	var popup = prompt("Please enter the name of your playlist");
	if (popup != null) {
		$.post("includes/handlers/ajax/createPlaylist.php", {name: popup, username: userLoggedIn})
		.done(function(error) {
			// when ajax returns
			if (error != "") {
				alert(error);
				return;
			}
			openPage("yourMusic.php");
		});
	}
}

function deletePlaylist(playlistId) {
	var prompt = confirm("Are you sure you want to delete this playlist?");
	if (prompt) {
		$.post("includes/handlers/ajax/deletePlaylist.php", {playlistId: playlistId})
		.done(function(error) {
			// when ajax returns
			if (error != "") {
				alert(error);
				return;
			}
			openPage("yourMusic.php");
		});
	}
}

function syncMusic() {
	$.post("includes/handlers/ajax/syncMusic.php")
	.done(function(error) {
		if (error != "") {
			alert(error);
			return;
		}
	});
}

function deleteMusicDB() {
	$.post("includes/handlers/ajax/deleteMusicDB.php")
	.done(function(error) {
		if (error != "") {
			alert(error);
			return;
		}
	});
}

function hideOptionsMenu() {
	// hide options menu on scroll
	var menu = $(".optionsMenu");
	if (menu.css("display") != "none") {
		menu.css("display", "none");
	}
}

function showOptionsMenu(button) {
	var songId = $(button).prevAll(".songId").val();
	var menu = $(".optionsMenu");
	var menuWidth = menu.width();
	menu.find(".songId").val(songId);
	var scrollTop = $(window).scrollTop();  // distance from top of window to top of document
	var elementOffset = $(button).offset().top;  // distance from top of document
	var top = elementOffset - scrollTop;
	var left = $(button).position().left;
	menu.css({"top": top + "px", "left": left - menuWidth + "px", "display": "inline"});
}

function logout() {
	$.post("includes/handlers/ajax/logout.php", function() {
		location.reload();
	});
}

function updateEmail(emailClass) {
	var emailValue = $("." + emailClass).val();
	$.post("includes/handlers/ajax/updateEmail.php", {email: emailValue, username: userLoggedIn})
	.done(function(response) {
		$("." + emailClass).nextAll(".message").text(response);
	});
}

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
	var oldPassword = $("." + oldPasswordClass).val();
	var newPassword1 = $("." + newPasswordClass1).val();
	var newPassword2 = $("." + newPasswordClass2).val();
	$.post("includes/handlers/ajax/updatePassword.php", 
		{oldPassword: oldPassword, newPassword1: newPassword1, newPassword2: newPassword2, username: userLoggedIn})
	.done(function(response) {
		$("." + oldPasswordClass).nextAll(".message").text(response);
	});
}

function Audio() {
	this.currentlyPlaying;
	this.audio = document.createElement('audio');

	this.audio.addEventListener("ended", function() {
		nextSong();
	});

	this.audio.addEventListener("canplay", function() {
		// 'this' refers to the object the event was called on (audio)
		var duration = formatTime(this.duration);
		$(".progressTime.remaining").text(duration);

	});

	this.audio.addEventListener("timeupdate", function() {
		if (this.duration) {
			updateTimeProgressBar(this);
		}
	});

	this.audio.addEventListener("volumechange", function() {
		updateVolumeProgressBar(this);
	});

	this.setTrack = function(track) {
		this.currentlyPlaying = track;
		this.audio.src = track.path;
	}

	this.play = function() {
		this.audio.play();
	}

	this.pause = function() {
		this.audio.pause();
	}

	this.setTime = function(seconds) {
		this.audio.currentTime = seconds;
	}
}

// ---------- Ctrl-P -----------