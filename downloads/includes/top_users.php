<?php

defined('_IN_JOHNCMS') or die('Error: restricted access');

/** @var PDO $db */
$db = App::getContainer()->get(PDO::class);
$lng = core::load_lng('dl');
$url = $set['homeurl'] . '/downloads/';

// Топ юзеров
$textl = $lng['top_users'];
echo '<div class="phdr"><a href="?"><b>' . $lng['downloads'] . '</b></a> | ' . $textl . '</div>';
$req = $db->query("SELECT * FROM `download__files` WHERE `user_id` > 0 GROUP BY `user_id` ORDER BY COUNT(`user_id`)");
$total = $req->rowCount();

// Навигация
if ($total > $kmess) {
    echo '<div class="topmenu">' . Functions::displayPagination($url . '?act=top_users&amp;', $start, $total, $kmess) . '</div>';
}

// Список файлов
$i = 0;

if ($total) {
    $req_down = $db->query("SELECT *, COUNT(`user_id`) AS `count` FROM `download__files` WHERE `user_id` > 0 GROUP BY `user_id` ORDER BY `count` DESC " . $db->pagination());

    while ($res_down = $req_down->fetch()) {
        $user = $db->query("SELECT * FROM `user__` WHERE `id`=" . $res_down['user_id'])->fetch();
        echo (($i++ % 2) ? '<div class="list2">' : '<div class="list1">') .
            functions::displayUser($user, ['iphide' => 0, 'sub' => '<a href="' . $url . '?act=user_files&amp;id=' . $user['id'] . '">' . $lng['user_files'] . ':</a> ' . $res_down['count']]) . '</div>';
    }
} else {
    echo '<div class="menu"><p>' . $lng['list_empty'] . '</p></div>';
}

echo '<div class="phdr">' . $lng['total'] . ': ' . $total . '</div>';

// Навигация
if ($total > $kmess) {
    echo '<div class="topmenu">' . Functions::displayPagination($url . '?act=top_users&amp;', $start, $total, $kmess) . '</div>' .
        '<p><form action="' . $url . '" method="get">' .
        '<input type="hidden" value="top_users" name="act" />' .
        '<input type="text" name="page" size="2"/><input type="submit" value="' . _t('To Page') . ' &gt;&gt;"/></form></p>';
}

echo '<p><a href="' . $url . '">' . _t('Downloads') . '</a></p>';
