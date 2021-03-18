<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where(['status' => 1])->get();
        return view('user/index')->with('users', $users);
    }

    public function show()
    {
        return view('user/show');
    }

    public function create(Request $request)
    {
        $rules = array(
            'email' 	    => 'required|unique:users,email|regex:/^[\S]+$/',
            'name' 		    => 'required|min:2',
            'password'      => 'required|min:8',
            'date_of_birth' => 'required|date'
        );

        $messages = array(
            'name.required'         => 'O campo nome é obrigatório.',
            'email.required'        => 'O campo email é obrigatório.',
            'email.regex'           => 'Email inválido.',
            'email.unique'          => 'Email inválido.',
            'senha.required'        => 'O campo senha é obrigatório.',
            'date_of_birth.required'  => 'O campo data de nascimento é obrigatório.'
        );
        $this->validate($request, $rules, $messages);

        $create = array(
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => $request->password,
            'date_of_birth' => $request->date_of_birth,
            'status'        => 1
        );

        User::create($create);

        session()->flash('msg', 'O usuário foi cadastrado com sucesso!');

        return redirect()->route('index');
    }

    public function update(Request $request)
    {
        $rules = array(
            'email' 	=> 'required|regex:/^[\S]+$/',
            'name' 		=> 'required|min:2',
            'date_of_birth' => 'required|date'
        );

        $messages = array(
            'name.required'  => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.regex'    => 'Email inválido.',
            'date_of_birth.required'  => 'O campo data de nascimento é obrigatório.'
        );
        $this->validate($request, $rules, $messages);

        $update = array(
            'name'           => $request->name,
            'email'          => $request->email,
            'date_of_birth'  => $request->date_of_birth,
        );

        User::where(['id' => $request->id])->update($update);

        session()->flash('msg', 'O usuário foi atualizado com sucesso!');

        return redirect()->route('index');
    }

    public function edit(Request $request)
    {
        $user = User::where(['id' => $request->id])->first();

        return view('user/edit')->with('user', $user);
    }

    public function delete(Request $request)
    {
        $user = User::where(['id' => $request->id])->update(['status' => 0]);

        return true;
    }
}
