<?php
namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Users extends Model{
    protected $fillable = ['name','telegramCode','apiToken','apiRefreshToken'];
    protected $table = 'users';
    public function setTelegramCode($id){
        if(empty($this->select()->where('telegramCode',$id)->get()->toArray())){
            $this->name = 'german';
            $this->email = '';
            $this->telegramCode = $id;
            $this->apiToken = '';
            $this->apiRefreshToken = '';
            $this->password = Hash::make($id);
            $this->save();
        }
        dump(Hash::make('1231254'));
    }
    public function getUsers(){

    }
}
