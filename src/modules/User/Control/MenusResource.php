<?php

namespace MDTimezone\User\Control;

use MDTimezone\User\Model\Auth;

class MenusResource extends \Resourceful\RestfulWebAppResource {

    
    /**
     * List all available menus.
     * The URLs here refer to the client app.
     * @return type
     */
    private function menus() {
        return [['items' => [
                ['href' => '#/', 'descr' => 'Home', 'type' => 'single'],
                ['type' => 'multi', 'descr' => 'Administration',
                    'items' => [
                        ['href' => '#/users', 'descr' => 'Users',
                            'type' => 'single',
                            'perms' => [[Auth::PERM_IS_ADMIN], [Auth::PERM_IS_MANAGER]]]
                    ]
                ],
                ['type' => 'multi', 'descr' => 'Current User',
                    'items' => [
                        ['href' => '#/user/profile', 'descr' => 'Profile',
                            'type' => 'single',
                            'perms' => [[Auth::PERM_IS_LOGGED_IN]]],
                        ['href' => '#/user/logout', 'descr' => 'Logout',
                            'type' => 'single',
                            'perms' => [[Auth::PERM_IS_LOGGED_IN]]],
                    ]
                ]
        ]]];
    }

    /**
     * Recursively filter the availble menus given their permission bits.
     * @param type $menus
     */
    private function filterMenus($menus) {
        $res = [];
        foreach ($menus as $k => $v) {
            if (isset($v['perms'])) {
                if (!$this->auth->hasFullPermission($v['perms'])) {
                    continue;
                }
                unset($v['perms']);
            }
            if (isset($v['type']) && $v['type'] == 'multi') {
                $sub = $this->filterMenus($v['items']);
                if ($sub) {
                    $v['items'] = $sub;
                    $res[] = $v;                    
                }
            } else {
                $res[] = $v;
            }
        }
        return $res;
    }

    public function get() {
        $res = $this->filterMenus($this->menus());
        return $res;
    }
        
}