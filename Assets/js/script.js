var socket = io.connect('https://localhost:5000');

$(function() {

    let userid = document.getElementById("userid");

    if (userid != null) {
        userid = userid.innerHTML;
        socket.emit('join-notif', {
            id: userid
        });

        socket.on('new-notification', (data) => {
            handleNotifications(data);
        })

        socket.on('new-msg-notif', (data) => {
            document.getElementById("messages-button").classList.add("bp3-intent-primary");
        })

        socket.on('no-unread-msg', (data) => {
            document.getElementById("messages-button").classList.remove("bp3-intent-primary");
        })
    }
})

function readNotif(notifId) {
    $.post('/index.php/notification/read_notification', {
        id: notifId
    }, (data) => {
        document.getElementById('notif' + notifId).classList.remove('is-info', 'unread');
        var childs = $("#notifs-body").children();
        unreadNotifs = 0;
        for (i = 0; i < childs.length; i++) {
            if (childs[i].classList.contains('unread'))
                unreadNotifs = 1;
        }
        if (unreadNotifs == 0) {
            document.getElementById("notifications-button").classList.remove("bp3-intent-primary");
        }
    });
}

$('#get-another-quote-button').on('click', function(e) {
    e.preventDefault();
    $.ajax({
        url: 'http://quotesondesign.com/wp-json/posts?filter[orderby]=rand&filter[posts_per_page]=1',
        success: function(data) {
            var post = data.shift(); // The data is an array of posts. Grab the first one.
            var quote = post.content.substring(3, post.content.length - 5);
            $('#quote-content').html(quote);
        },
        cache: false
    });
});

function handleNotifications(data) {
    if (data.status == "unread") {
        document.getElementById("notifications-button").classList.add("bp3-intent-primary");
    }

    $("#notifs-body").prepend('<div id="notif' + data.id + '" onclick="readNotif(' + data.id + ')" style="margin:5px" class="notification">' +
        data.object + '<br/>' +
        '<a href="/index.php/profile/' + data.content.substr(0, data.content.indexOf(' ')) + '">' +
        data.content + '</a>' +
        '</div>');
    if (data.status == 'unread') {
        document.getElementById('notif' + data.id).classList.add('is-info', 'unread');
    }
}

function deleteNotif(el) {
    $.post('/index.php/notification/delete', {
        id: el.id
    }, () => {
        parent = $(el).parent().closest();
        parent.remove();
    })
}

function kill_user(userid) {
    $.post('/index.php/admin/kill_user', {
        id: userid
    }, (data) => {
        console.log(data);
        $("#report-" + userid).remove();
    })
}

function generateUsers() {
    $('#generate_button').prop('disabled', true);
    document.getElementById("waiting").style.display = "block";
    let number = document.getElementById("generate_number").value;
    console.log(number);
    $.post('/index.php/admin/hydratation/', { number: number }, (data) => {
        show_stats();
        document.getElementById("waiting").style.display = "none";
    })
}

function clearResults() {
    var res = document.getElementById("results");
    while (res.firstChild) {
        res.removeChild(res.firstChild);
    }
}

function clearSuggestions() {
    var res = document.getElementById("suggest_results");
    while (res.firstChild) {
        res.removeChild(res.firstChild);
    }
}

function parseTags(tags) {
    var res = "";
    for (var i = 0; i < tags.length; i++) {
        res += "<span class='badge badge-info'>" + tags[i].name + "</span> ";
    }
    return (res);
}

function getSelectValues(select) {
    var result = [];
    var options = select && select.options;
    var opt;

    for (var i = 0, iLen = options.length; i < iLen; i++) {
        opt = options[i];
        if (opt.selected) {
            result.push(opt.value || opt.text);
        }
    }
    return result;
}

function treatSuggestResults(user) {
    parseTags(user.shared_tags)
    parent = document.getElementById("suggest_results");

    var card = document.createElement("div");
    card.classList.add("col-md-4");
    card.style.margin = "15px 0 15px 0";
    card.innerHTML = '<div class="col-md-12 bp3-card"><img class="card-img-top" src="/' + user.path + '"><div class="card-body"><h5 class="card-title"><a href="/index.php/profile/' + user.username + '">' + user.username + '</a> (' + user.age + ')</h5><p class="card-text">' +
        user.bio.substring(0, 50) + '...</p><p>' + user.shared_tags.length + ' shared tags</p>' + parseTags(user.shared_tags) + '<p class="card-text"><small class="text-muted">Popularity : ' + user.popularity_score + ' pts</small>' +
        '<p> Matching: ' + user.score + '%</p><p>' + user.locality + ' (' + user.distance + ')</p></div></div>';
    parent.appendChild(card);
}

function treatResults(user) {
    parseTags(user.shared_tags)
    parent = document.getElementById("results");

    var card = document.createElement("div");
    card.classList.add("col-md-4");
    card.style.margin = "15px 0 15px 0";
    card.innerHTML = '<div class="col-md-12 bp3-card"><img class="card-img-top" src="/' + user.path + '"><div class="card-body"><h5 class="card-title"><a href="/index.php/profile/' + user.username + '">' + user.username + '</a> (' + user.age + ')</h5><p class="card-text">' +
        user.bio.substring(0, 50) + '...</p><p>' + user.shared_tags.length + ' shared tags</p>' + parseTags(user.shared_tags) + '<p class="card-text"><small class="text-muted">Popularity : ' + user.popularity_score + ' pts</small></p><p>' + user.locality + ' (' + user.distance + ')</p></div></div>';
    parent.appendChild(card);
}

function perform_suggested() {
    clearSuggestions();
    var ageMin = document.getElementsByName('age_min')[0].value;
    var ageMax = document.getElementsByName('age_max')[0].value;
    var city = document.getElementsByName('city')[0].value;
    var popularityMin = document.getElementsByName('popularity_min')[0].value;
    var popularityMax = document.getElementsByName('popularity_max')[0].value;
    var distance = document.getElementsByName('distance')[0].value;
    var sort = document.getElementsByName('sort')[0].value;
    var tags = getSelectValues(document.getElementById('tag-select'));
    $.get('/index.php/home/perform', {
        age_min: ageMin,
        age_max: ageMax,
        city: city,
        popularity_min: popularityMin,
        popularity_max: popularityMax,
        sort: sort,
        tags: tags,
        distance: distance
    }, (data) => {
        obj = JSON.parse(data);
        for (i = 0; i < obj.length; i++) {
            treatSuggestResults(obj[i]);
        }
        document.getElementById("suggest_results").scrollIntoView();
    });
}

function perform(sort) {
    $("#sort_menu").show();
    clearResults();
    var ageMin = document.getElementsByName('age_min')[0].value;
    var ageMax = document.getElementsByName('age_max')[0].value;
    var gender = document.getElementsByName('gender')[0].value;
    var city = document.getElementsByName('city')[0].value;
    var popularityMin = document.getElementsByName('popularity_min')[0].value;
    var popularityMax = document.getElementsByName('popularity_max')[0].value;
    var distance = document.getElementsByName('distance')[0].value;
    var tags = getSelectValues(document.getElementById('tag-select'));
    $.get('/index.php/search/perform', {
        gender: gender,
        age_min: ageMin,
        age_max: ageMax,
        city: city,
        popularity_min: popularityMin,
        popularity_max: popularityMax,
        sort: sort,
        tags: tags,
        distance: distance
    }, (data) => {
        document.getElementById("waiting").style.display = "none";
        obj = JSON.parse(data);
        // console.log(data);
        for (i = 0; i < obj.length; i++) {
            treatResults(obj[i]);
        }
    });
    document.getElementById("waiting").style.display = "block";
}

function update_profile_pic() {
    var files = document.getElementById("profil-pic-upload").files;
    if (files.length > 0) {
        var input = document.createElement('input');
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'image');
        getBase64value(files[0], input);
        input.src.toDataURL;
        document.getElementById("form2").appendChild(input);
        var submit = document.getElementById("profil-pic");
        submit.style.display = "block";
    }
}

function selectPp(picId) {
    var old = document.getElementsByClassName("selected");
    for (i = 0; i < old.length; i++) {
        old[i].classList.remove("selected");
    }
    var selected = document.getElementById(picId);
    selected.classList.add("selected");
}

function savePp() {
    var picId = document.getElementsByClassName("selected")[0].id;
    $.post("/index.php/user/set_profile_picture", {
        id: picId
    }, function(data) {
        document.getElementById("profile_pic").style.backgroundImage = "url('/" + data + "')";
    });
}

function blacklistUser(id) {
    $.post('/index.php/user/blacklist', {
        id: id
    }, (data) => {
        document.getElementById("blacklist_user").classList.add("bp3-disabled", "bp3-intent-success");
        document.getElementById("blacklist_user").classList.remove("bp3-intent-warning");
        document.getElementById("blacklist_user").innerText = "User blacklisted."
    });
}

function unblacklist(id) {
    $.post('/index.php/user/unblacklist', {
        id: id
    }, (data) => {
        document.getElementById("blacklist_count").innerHTML -= 1;
        document.getElementById("blacklist-" + id).remove();
    });
}

function like(sender, uid) {
    $.post("/index.php/user/like_user", {
        'sender': sender,
        'user_id': uid
    }, function(data) {
        if (data == "liked") {
            likeBtn.classList.add("is-danger");
            likeBtn.classList.remove("is-info");
            likeBtn.innerText = "Liked!";
            document.getElementById('heart').style.display = "block";
            setTimeout(() => {
                document.getElementById('heart').style.display = "none";
            }, 3000);
            $('#contact_btn').hide();
            new_notification(sender, uid, "like");
        } else if (data == "match") {
            likeBtn.classList.add("is-danger");
            likeBtn.classList.remove("is-info");
            likeBtn.innerText = "It's a match!";
            document.getElementById('heart').style.display = "block";
            setTimeout(() => {
                document.getElementById('heart').style.display = "none";
            }, 3000);
            new_notification(sender, uid, "match");
            $('#contact_btn').show();
        } else if (data == "unliked") {
            likeBtn.classList.add("is-info");
            likeBtn.classList.remove("is-danger");
            likeBtn.innerHTML = "Like";
            $('#contact_btn').hide();
            new_notification(sender, uid, "unlike")
        }
    });
}

function new_notification(sender, uid, type) {
    $.post("/index.php/notification/new_notification", {
        sender: sender,
        user_id: uid,
        type: type
    }, function(data) {
        if (data)
            socket.emit('send-notification', JSON.parse(data));
    });
}

function sendReport(userid, target) {
    $.post('/index.php/user/report_user', {
        userId: userid,
        targetId: target
    }, (data) => {
        if (data == "ok") {
            reportbtn = document.getElementById("report-btn");
            reportbtn.classList.remove("bp3-intent-danger");
            reportbtn.classList.add("bp3-intent-success", "bp3-disabled");
            reportbtn.innerHTML = "Report sent";
            reportbtn.disabled = true;
        }
    })
}

function timeDifference(current, previous) {
    var msPerMinute = 60 * 1000;
    var msPerHour = msPerMinute * 60;
    var msPerDay = msPerHour * 24;
    var msPerMonth = msPerDay * 30;
    var msPerYear = msPerDay * 365;
    var elapsed = current - previous;
    if (elapsed < msPerMinute) {
        return Math.round(elapsed / 1000) + ' seconds ago';
    } else if (elapsed < msPerHour) {
        return Math.round(elapsed / msPerMinute) + ' minutes ago';
    } else if (elapsed < msPerDay) {
        return Math.round(elapsed / msPerHour) + ' hours ago';
    } else if (elapsed < msPerMonth) {
        return 'approximately ' + Math.round(elapsed / msPerDay) + ' days ago';
    } else if (elapsed < msPerYear) {
        return 'approximately ' + Math.round(elapsed / msPerMonth) + ' months ago';
    } else {
        return 'approximately ' + Math.round(elapsed / msPerYear) + ' years ago';
    }
}

var now = new Date(Date.now()).getTime();
var pouet = document.getElementsByClassName("timestamp");

for (var i = 0; i < pouet.length; i++) {
    // var creation_date = pouet[i].innerHTML;
    var date = pouet[i].innerHTML;

    // Split timestamp into [ Y, M, D, h, m, s ]
    var t = date.split(/[- :]/);
    var d = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
    var d = d.getTime();

    pouet[i].innerHTML = timeDifference(now, d);
}

function contact(user1_id, user2_id) {
    $.post('/index.php/chat/new_chat', {
        user1_id: user1_id,
        user2_id: user2_id
    }, (data) => {
        if (data == "ok") {
            window.location = "/index.php/chat";
        }
    })
}

function initializeAutocomplete(id) {
    var element = document.getElementById(id);

    if (element) {
        var autocomplete = new google.maps.places.Autocomplete(element, {
            types: ['geocode']
        });
        google.maps.event.addListener(autocomplete, 'place_changed', onPlaceChanged);
    }
}

function onPlaceChanged() {
    var place = this.getPlace();
    // console.log(place);  // Uncomment this line to view the full object returned by Google API.
    var formatted_address = document.getElementById("formatted_address");
    formatted_address.value = place.formatted_address;
    document.getElementById("lat").value = place.geometry.location.lat();
    document.getElementById("lng").value = place.geometry.location.lng();
    for (var i in place.address_components) {
        var component = place.address_components[i];

        for (var j in component.types) {
            // Some types are ["country", "political"]
            var type_element = document.getElementById(component.types[j]);

            if (type_element) {
                type_element.value = component.long_name;
            }
        }
    }
}

$('#settings').on('show.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
});

// Add slideUp animation to Bootstrap dropdown when collapsing.
$('#settings').on('hide.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
});

$('#notifications').on('show.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
});

// Add slideUp animation to Bootstrap dropdown when collapsing.
$('#notifications').on('hide.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
});

function switchTheme() {
    $.get('/index.php/user/get_theme', (data) => {
        var mode = '';
        if (data == "light") {
            mode = "dark";
            setDarkTheme();
        } else {
            mode = "light";
            setLightTheme();
        }
        $.post('/index.php/user/set_theme', {
            theme: mode
        }, (data) => {});
    })
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(savePosition);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function savePosition(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    $.post('/index.php/user/save_location', {
        lat: latitude,
        lng: longitude,
        type: 'js'
    }, function(data) {
        // Uncomment to see current address
        // console.log(data);
    });
}

function setDarkTheme() {
    body = document.getElementsByTagName("body")[0];
    body.style.backgroundColor = "#273542";
    body.classList.add("bp3-dark");
    document.getElementById("theme_switch").innerText = "Light Theme";
}

function setLightTheme() {
    body = document.getElementsByTagName("body")[0];
    body.style.backgroundColor = "#ecf0f1";
    body.classList.remove("bp3-dark");
    document.getElementById("theme_switch").innerText = "Dark Theme";
}

window.onload = () => {
    $.get('/index.php/user/get_theme', (data) => {
        if (data == "light") {
            setLightTheme();
        }
    })
}

function error() {
    // console.log("Can't retrieve user's location.");
}

function options() {
    // console.log("options");
}

$(document).ready(function() {
    $(".tag-select").select2({});
    $("#sort_menu").hide();
});

id = navigator.geolocation.watchPosition(savePosition, error, options);

google.maps.event.addDomListener(window, 'load', function() {
    initializeAutocomplete('user_input_autocomplete_address');
});

var likes = document.getElementById("likes");
var likeBtn = document.getElementById("likeBtn");

var active = document.getElementsByClassName("carousel-inner")[0];
if (active) {
    active = active.firstElementChild;
    active.classList.add("active");
}

let searchBar = document.getElementById('livesearch_user');
if (searchBar != null) {
    searchBar.addEventListener("keyup", (event) => {
        if (event.keyCode === 13) {
            event.preventDefault();
            $.get('/index.php/admin/liveuser', { username: searchBar.value }, (data) => {
                $('#display').children().remove();
                obj = JSON.parse(data);
                if (obj.length < 1) {
                    $('#display').append('<p>No user found</p>');
                } else {
                    obj.forEach((element) => {
                        $('#display').append('<div class="bp3-card col-md-6"><p>' + element.username + '</p>' +
                            '<form action="/index.php/admin/ban_user" method="post" class="has-text-right">' +
                            '<input class="bp3-input mt-2" type="number" name="time" placeholder="Ban time in hours">' +
                            '<button type="submit" class="bp3-button bp3-intent-warning mt-2">Ban user</button>' +
                            '<input type="hidden" name="userid" value="' + element.id + '"></form></div>');
                    })
                }
            })
        }
    })
};