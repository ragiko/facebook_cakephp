<h2>マイページ</h2>
<img src="https://graph.facebook.com/<?php echo $facebookId;?>/picture?width=150" alt="" />
<p><?php echo $user['User']['id']; ?></p>
<p><?php echo $user['User']['name']; ?></p>
<p><?php echo $this->Html->link($user['User']['link']); ?></p>
<p><?php echo $this->Html->link('トップページ', ['controller' => 'shops', 'action' => 'index']); ?></p>
<p><?php echo $this->Html->link('ログアウト', ['controller' => 'users', 'action' => 'logout']); ?></p>
