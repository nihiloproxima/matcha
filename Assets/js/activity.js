    function hide_all() {
        var actives = document.getElementsByClassName("is-active");
        for (i = 0; i < actives.length; i++) {
            actives[i].classList.remove("is-active");
        }
        document.getElementById("likes").classList.add("is-hidden");
        document.getElementById("visits").classList.add("is-hidden");
        document.getElementById("blacklist").classList.add("is-hidden");
        document.getElementById("match").classList.add("is-hidden");
        document.getElementById("userlikes").classList.add("is-hidden");
    }

    function show_visits() {
        hide_all();
        document.getElementById("li-visits").classList.add("is-active");
        document.getElementById("visits").classList.remove("is-hidden");
    }

    function show_userlikes() {
        hide_all();
        document.getElementById("li-userlikes").classList.add("is-active");
        document.getElementById("userlikes").classList.remove("is-hidden");
    }

    function show_likes() {
        hide_all();
        document.getElementById("li-likes").classList.add("is-active");
        document.getElementById("likes").classList.remove("is-hidden");
    }

    function show_blacklist() {
        hide_all();
        document.getElementById("li-blacklist").classList.add("is-active");
        document.getElementById("blacklist").classList.remove("is-hidden");
    }

    function show_match() {
        hide_all();
        document.getElementById("li-match").classList.add("is-active");
        document.getElementById("match").classList.remove("is-hidden");
    }

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    show_visits();