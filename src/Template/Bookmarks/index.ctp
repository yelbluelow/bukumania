<?php
/**
 * @var \App\View\AppView $this
 */
?>
<!-- 登録モーダル -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" data-mode="INSERT">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex justify-content-start" style="margin-bottom:16px;">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <?= $this->Form->create('bookmarksAdd', ['id' => 'bookmarksAdd']); ?>
                    <div class="row form-group">
                        <label class="col-sm-3 required" for="titleAdd">タイトル<span class="text-danger"> *</span></label>
                        <input class="col-sm-9 form-control" id="titleAdd" name="title" placeholder="タイトル">
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-3 required" for="urlAdd">URL<span class="text-danger"> *</span></label>
                        <input class="col-sm-9 form-control" id="urlAdd" name="url" placeholder="URL">
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-3" for="descriptionAdd">コメント</label>
                        <textarea class="col-sm-9 form-control" id="descriptionAdd" name="description" placeholder="コメント"></textarea>
                    </div>
                    <input class="form-control" id="idAdd" hidden>
                <?= $this->Form->end(); ?>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-primary" id="post-add">登録する</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 詳細モーダル -->
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" data-num="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex justify-content-start" style="margin-bottom:16px;">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div id="modal-detail-content"></div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-primary btn-modal" id="post-edit">編集</button>
                    <button type="button" class="btn btn-sm btn-danger btn-modal" id="post-delete">削除</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- メイン -->
<text id="post" hidden><?= $bookmarks; ?></text>
<h1>Home</h1>
<div class="row">
    <div class="col-sm-3">
        <div class="row">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add">新規＋</button>
        </div>
        <div class="row" style="background-color:#e6ecf0; border-radius:10px; margin-top:10px;">
            <p>tag関連のやつを</p>
            <p>ここに載せたい</p>
        </div>
    </div>
    <div class="col-sm-9 url_list">
    </div>
</div>
