<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div style="margin:auto; text-align:center;">
    <h1 style="font-family:Bookman Old Style;">BUKUMANIA</h1>
    <?= $this->Html->image('book_128px.png', ['width' => '64px']) ?>
</div>
<div class="MUser form" style="width:300px; margin:auto; margin-top:10px;">
    <?= $this->Flash->render() ?>
    <?= $this->Form->create() ?>
        <fieldset>
            <legend style='border-bottom:2px solid #fce903;'>Login Page</legend>
            <?= $this->Form->control('account') ?>
            <?= $this->Form->control('password') ?>
        </fieldset>
    <?= $this->Form->button(__('Login'), ['class' => 'btn btn-warning']); ?>
    <?= $this->HTML->link('ユーザー登録はこちら', ['controller' => 'Users', 'action' => 'addUser']) ?>
    <?= $this->Form->end() ?>
</div>
