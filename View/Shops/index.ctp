<h1>店舗一覧</h1>
<?php
echo $this->Form->create('Users', ['action' => 'login', 'method' => 'post']);
echo $this->Form->button('Facebookログイン');
?>