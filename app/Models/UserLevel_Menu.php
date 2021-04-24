<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevel_Menu extends Model
{
    //
    protected $table = 'web_level_menu';
    // protected $primaryKey = 'empno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'code',
        'menu_code',
        'user_level'
    ];

    public static function user_menus($level){
        $menus  =   UserLevel_Menu::select('web_level_menu.*', 'web_menu.*')
                    ->where('user_level', $level)
                    ->leftJoin('web_menu', function($menu){
                        $menu->on('web_menu.menu_code', 'web_level_menu.menu_code')
                            ->on('web_menu.code', 'web_level_menu.code');
                    })->get();
        
        // $menus  =   UserLevel_Menu::where('user_level', $level)->get();

        return $menus;
    }
}
