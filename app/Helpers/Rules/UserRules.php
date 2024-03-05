<?php

namespace App\Helpers;

use Config\Validation;

class UserRules extends Validation{
    public $create_user = [
        'name'=>[
            'rules'=>'required'
        ],
        'password'=>[
            'rules'=>'required'
        ],
        'email'=>[
            'rules'=>'required|unique'
        ]
    ];
    public $update_user = [
        'name'=>[
            'rules'=>'required'
        ],
        'password'=>[
            'rules'=>'required'
        ],
        'email'=>[
            'rules'=>'required|unique'
        ]
    ];
}