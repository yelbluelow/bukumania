<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div style="margin:auto; text-align:center;">
    <h2 style="font-family:Bookman Old Style; margin-top:10px;">アカウント登録</h2>
    <?= $this->Html->image('book_128px.png', ['width' => '64ppx']) ?>
</div>
<div class="MUser form" style="width:300px; margin:auto; margin-top:10px;">
    <?= $this->Flash->render() ?>
    <?= $this->Form->create() ?>
        <fieldset>
            <?= $this->Form->control('account', ['required']) ?>
            <?= $this->Form->control('twitter_account') ?>
            <?= $this->Form->control('password', ['required']) ?>
        </fieldset>
    <?= $this->Form->button(__('登録'), ['class' => 'btn btn-warning']); ?>
    <?= $this->Form->end() ?>
</div>
