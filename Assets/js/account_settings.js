function getBase64value(file, element) {
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function() {
        element.value = reader.result;
    };
    reader.onerror = function(error) {
        console.log('Error: ', error);
    };
}

function getBase64(file, element) {
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onloadend = (err) => {
        if (err.target.result.length > 5)
            element.src = err.target.result;
    }
}

function show_public() {
    public = document.getElementById("public");
    account = document.getElementById("account");

    li_public = document.getElementById("li-public");
    li_account = document.getElementById("li-account");

    account.style.display = "none";
    public.style.display = "block";

    li_public.classList.add("is-active");
    li_account.classList.remove("is-active");
}

function show_account() {
    public = document.getElementById("public");
    account = document.getElementById("account");

    li_public = document.getElementById("li-public");
    li_account = document.getElementById("li-account");

    account.style.display = "block";
    public.style.display = "none";

    li_public.classList.remove("is-active");
    li_account.classList.add("is-active");
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

function check_files() {
    var files = document.getElementById("files_input").files;
    var div = document.getElementById("new_pictures_preview");
    $('.new_images_preview').remove();
    if (files.length > 5) {
        document.getElementById("files_input").value = "";
        alert("You can only upload 5 max images! Please try again!")
    } else if (files.length + $('.pictures_preview').length > 5) {
        document.getElementById("files_input").value = "";
        alert("You can only upload 5 max images! Please delete you old picture if you want to add more.")
    } else {
        document.getElementById("new_images").innerHTML = "New images :<br/>";
        for (i = 0; i < files.length; i++) {
            var bloc = document.createElement("div");
            var img = new Image();
            bloc.classList.add("image", "is-96x96", "new_images_preview");
            bloc.style.margin = "10px";
            bloc.style.padding = "10px";
            getBase64(files[i], img);
            bloc.appendChild(img);
            div.appendChild(bloc);
        }
    }
}

function delete_picture(picId) {
    $.post("/index.php/user/delete_picture", {
        id: picId
    }, function(data) {
        document.getElementById("picture-" + picId).remove();
    });
}

$(document).ready(function() {
    $(".tag-select").select2({
        tags: true
    });
});