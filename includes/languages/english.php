<?php

function lang($phrase)
{
    static $lang = array(
            'HOME_ADMIN' => 'Admin',
            'CATEGORIES' => 'Categories',
            'EDIT_PROFILE' => 'Edit Profile',
            'SETTINGS' => 'Settings',
            'LOG_OUT' => 'Logout',
            'ITEMS' => 'Items',
            'MEMBERS' => 'Members',
            'HOME' => 'Home',
            'STATISTICS' => 'Statistics',
            'LOGS' => 'Logs',
            'COMMENTS' => 'Comments'
    );

    return $lang[$phrase];
}