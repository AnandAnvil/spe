<?php
// lib/php/plugins/users.php 20150101 - 20170306
// Copyright (C) 2015-2017 Mark Constable <markc@renta.net> (AGPL-3.0)

class Plugins_Users extends Plugin
{
    protected
    $tbl = 'users',
    $in = [
        'id'        => null,
        'grp'       => null,
        'acl'       => null,
        'login'     => '',
        'fname'     => '',
        'lname'     => '',
        'altemail'  => '',
        'webpw'     => '',
        'anote'     => '',
    ];
    
    protected function read_all() : array
    {
error_log(__METHOD__);

        if (util::is_acl(0)) { // superadmin
            return db::read('*', '', '', 'ORDER BY `updated` DESC');
        } elseif (util::is_acl(1)) { // normal admin
            return db::read('*', 'grp', $_SESSION['usr']['id'], 'ORDER BY `updated` DESC');
        } else {
            return db::read('*', 'id', $_SESSION['usr']['id'], 'ORDER BY `updated` DESC');
        }
    }

    protected function switch_user()
    {
error_log(__METHOD__);

        if (util::is_adm() and !is_null($this->g->in['i'])) {
            $_SESSION['usr'] = db::read('id,acl,grp,login,fname,lname,webpw,cookie', 'id', $this->g->in['i'], '', 'one');
            util::log('Switch to user: ' . $_SESSION['usr']['login'], 'success');
        } else util::log('Not authorized to switch users');
        $this->g->in['i'] = null;
        return $this->read();
    }
}

?>
