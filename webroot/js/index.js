$(document).ready(function(){

    //バリデーション
    var valid = {
        rules: {
            title: {required: true},
            url: {required: true},
        },
        messages: {
            title: {required: '必須項目です'},
            url: {required: '必須項目です'},
        }
    };

    // 一覧画面のときに
    if (location.pathname === '/bukumania') {
        var url_list = JSON.parse($('#post').text());
        var html = create_list_dom(url_list);
        $('.url_list').html(html);
    }

    $('#post-add').click(function() {
        if ($('#modal-add').data('mode') == 'INSERT') {
            // バリデーション
            $('#bookmarksAdd').validate(valid);
            //失敗で戻る
            if (!$('#bookmarksAdd').valid()) {
                return false;
            };
            var url = $('#urlAdd').val();
            add_posted_url(url);
        } else if ($('#modal-add').data('mode') == 'UPDATE') {
            // バリデーション
            $('#bookmarksAdd').validate({
                rules: {title: {required: true}},
                messages: {title: {required: '必須項目です'}}
            });
            //失敗で戻る
            if (!$('#bookmarksAdd').valid()) {
                return false;
            };

            // タイトルとコメントの更新
            update_bookmark();
        }
    });

    $('#post-edit').click(function() {
        $('#modal-detail').modal('hide');

        // 元々の登録値を取得する
        var post_num = $('#modal-detail').data('num');
        var post_json = JSON.parse($('#post').text())[post_num];

        // 元々の登録値を入れる
        $('#titleAdd').val(post_json['title']);
        $('#urlAdd').val(post_json['bookmark_url']['url']);
        $('#urlAdd').prop('readonly', true);
        $('#descriptionAdd').val(post_json['description']);
        $('#idAdd').val(post_json['id']);

        $('#modal-add').data('mode', 'UPDATE');
        $('#modal-add').modal('show');
    });

    $(document).on('click', '.url_post', function() {
        var post_num = $(this).attr('id');
        var post_json = JSON.parse($('#post').text())[post_num];
        var html = create_post_dom(post_json);
        $('#modal-detail-content').html(html);
        $('#modal-detail').data('num', post_num);
        $('#modal-detail').modal('show');
    });

    $('#post-delete').click(function() {
        if (window.confirm('この投稿を削除しますか？')){
            // 元々の登録値を取得する
            var post_num = $('#modal-detail').data('num');
            var post_json = JSON.parse($('#post').text())[post_num];
            delete_bookmark(post_json['id']);
        }
    });

    $('#modal-add').on('hidden.bs.modal', function (e) {
        $('#titleAdd').val('');
        $('#urlAdd').val('');
        $('#urlAdd').prop('readonly', false);
        $('#descriptionAdd').val('');
    });
});

function create_list_dom(url_json) {
    var html = '';
    for(var num in url_json) {
        html += '<div class="url_post" id="'+num+'" style="border:0.5px solid #b1b6bc; padding:20px;">';
        html += create_post_dom(url_json[num]);
        html += '</div>';
    }
    return html;
}

function create_post_dom(post_json) {
    html  = '<p class="post-id" hidden>'+post_json['id']+'</p>'
    html += '<a href="'+post_json['bookmark_url']['url']+'" style="font-size:24px; margin-bottom:5px;">'+post_json['title']+'</a>';
    html += '<p>'+post_json['description']+'</p><div class="status row"><div class="col-3"><img src="/img/count.png" width="24px" alt="">';
    html += '<spam>カウント：'+post_json['count']+'回</span></div>';

    if (post_json['looked_status'] == 1) {
        var img_check = 'check_blue.png';
        var name_check = 'チェック済';
    } else {
        var img_check = 'check_gray.png';
        var name_check = 'チェック';
    }

    html += '<div class="col-3"><img src="/img/'+img_check+'" width="24px" alt=""><spam>'+name_check+'</span></div>';

    if (post_json['favorite_status'] == 1) {
        var img_favorite = 'favorite_yellow.png';
        var name_favorite = 'お気に入り済';
    } else {
        var img_favorite = 'favorite_gray.png';
        var name_favorite = 'お気に入り';
    }

    html += '<div class="col-3"><img src="/img/'+img_favorite+'" width="24px" alt=""><spam>'+name_favorite+'</span></div>';
    var d = new Date(post_json['modified']);
    var min = (d.getMinutes() < 10) ? "0" + d.getMinutes() : d.getMinutes();
    var sec = (d.getSeconds() < 10) ? "0" + d.getSeconds() : d.getSeconds();
    date = d.getFullYear()+'/'+d.getMonth()+'/'+d.getDate()+' '+d.getHours()+':'+min+':'+sec;
    html += '<div class="col-3"><spam>'+date+'</span></div></div>';

    return html;
}

function add_posted_url(url) {
    var check_url = split_url(url);
    $.ajax({
        type: 'POST',
        url: '/bookmarks/checkUrl',
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $('input[name=_csrfToken]').val());
        },
        dataType: 'json',
        data: {url: check_url}
    }).done(function(check) {
        if (check == 'insert') {
            add_bookmark_url(check_url);
        } else if (check.indexOf('count') != -1) {
            var comment = check == 'count' ? 'タイトルとコメントは保存されませんが、よろしいでしょうか？' : 'このURLは削除された項目ですが、復活させますか？' ;
            if (window.confirm(comment)){
                count_up_url(check_url);
            } else {
                return false;
            }
        }

        // 一覧の更新
        fetch_bookmark_list();

    }).fail( (jqXHR, textStatus, errorThrown) => {
        console.log("XMLHttpRequest : " + jqXHR.status);
        console.log("textStatus : " + textStatus);
        console.log("errorThrown : " + errorThrown);
    });
}

function split_url(url) {
    var splited_url = url.split('?')[0].split('#')[0];
    return splited_url;
}

function add_bookmark_url(url) {
    $.ajax({
        type: 'POST',
        url: '/bookmarks/addBookmark',
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $('input[name=_csrfToken]').val());
        },
        dataType: 'json',
        data: {
            url: url,
            title: $('#titleAdd').val(),
            description: $('#descriptionAdd').val(),
        }
    }).done(function(result) {
        if (result == true) {
            alert('登録しました！');
            $('#modal-add').modal('hide');
        } else {
            alert('error');
        }
        
    }).fail( (jqXHR, textStatus, errorThrown) => {
        console.log("XMLHttpRequest : " + jqXHR.status);
        console.log("textStatus : " + textStatus);
        console.log("errorThrown : " + errorThrown);
    });
}

function count_up_url(url) {
    $.ajax({
        type: 'POST',
        url: '/bookmarks/countUpBookmark',
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $('input[name=_csrfToken]').val());
        },
        dataType: 'json',
        data: {url: url}
    }).done(function(result) {
        if (result == true) {
            alert('カウント追加しました！');
            $('#modal-add').modal('hide');
        } else {
            alert('error');
        }
        
    }).fail( (jqXHR, textStatus, errorThrown) => {
        console.log("XMLHttpRequest : " + jqXHR.status);
        console.log("textStatus : " + textStatus);
        console.log("errorThrown : " + errorThrown);
    });
}

function fetch_bookmark_list() {
    $.ajax({
        type: 'POST',
        url: '/bookmarks/fetchBookmarkList',
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $('input[name=_csrfToken]').val());
        },
    }).done(function(json) {
        var url_list = JSON.parse(json);
        var html = create_list_dom(url_list);
        $('#post').text(json);
        $('.url_list').html(html);
    }).fail( (jqXHR, textStatus, errorThrown) => {
        console.log("XMLHttpRequest : " + jqXHR.status);
        console.log("textStatus : " + textStatus);
        console.log("errorThrown : " + errorThrown);
    });
}

function update_bookmark() {
    $.ajax({
        type: 'POST',
        url: '/bookmarks/updateBookmark',
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $('input[name=_csrfToken]').val());
        },
        dataType: 'json',
        data: {
            id: $('#idAdd').val(),
            title: $('#titleAdd').val(),
            description: $('#descriptionAdd').val(),
        }
    }).done(function(result) {
        if (result == true) {
            alert('投稿情報を更新しました！');
            $('#modal-add').modal('hide');
            // 一覧の更新
            fetch_bookmark_list();
        } else {
            alert('error');
        }
    }).fail( (jqXHR, textStatus, errorThrown) => {
        console.log("XMLHttpRequest : " + jqXHR.status);
        console.log("textStatus : " + textStatus);
        console.log("errorThrown : " + errorThrown);
    });
}

function delete_bookmark(id) {
    $.ajax({
        type: 'POST',
        url: '/bookmarks/updateBookmark',
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $('input[name=_csrfToken]').val());
        },
        dataType: 'json',
        data: {
            id: id,
            deleted: 1,
        }
    }).done(function(result) {
        if (result == true) {
            alert('投稿を削除しました');
            $('#modal-detail').modal('hide');
            // 一覧の更新
            fetch_bookmark_list();
        } else {
            alert('error');
        }
    }).fail( (jqXHR, textStatus, errorThrown) => {
        console.log("XMLHttpRequest : " + jqXHR.status);
        console.log("textStatus : " + textStatus);
        console.log("errorThrown : " + errorThrown);
    });
}
