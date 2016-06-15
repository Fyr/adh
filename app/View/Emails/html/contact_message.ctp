<?
	$email = h($this->request->data('Contact.email'));
?>
Пользователь: <?=h($this->request->data('Contact.username'))?>, <a href="mailto:<?=$email?>"><?=$email?></a> <br/>
Текст сообщения:<br/>
<?=nl2br(h($this->request->data('Contact.body')))?>
