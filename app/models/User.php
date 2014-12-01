<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
        
        public static function idUPCUser($redsUpcs)
        {
            $result = "";
            $users = User::all();
            $readsUpcs = OrdenEsD::UPCFolioNotEnd();
            //return Response::json($users);
            foreach ($users as $user){
                foreach ($readsUpcs as $upcRead){
                    if($user->upc == $upcRead->upc){
                        //$result = $user->first_name." ".$user->last_name;
                        $result = $user->id;
                        break;
                    }
                }
                if(strlen($result)>0)
                    break;
            }                
            return $result;
        }        

}
