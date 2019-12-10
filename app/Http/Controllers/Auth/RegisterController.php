<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Cacciatore;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ModificaUtenteRequest;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*$this->middleware('guest');*/
        $this->middleware('auth');
    }



    public function _validatePhone(Request $request)
      {
         if ($request->has('ruolo') && $request->get('ruolo') == 'cacciatore' && $request->filled('login_capabilities')) 
         {
            $request->validate([
                'telefono' => ['required', new PhoneNumber]
            ]);
         }
      }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $ruolo = $request->has('ruolo') ? $request->get('ruolo') : 'admin';
        
        if ($request->has('ruolo') && $request->get('ruolo') == 'cacciatore') 
          {
          $this->_validatePhone($request);
          }
      
        $new_request['ruolo'] = $ruolo;
       
        $request->merge($new_request);
        
        $this->validator($request->all())->validate();    

        event(new Registered($user = $this->create($request->all())));


        if ($request->has('ruolo') && $request->get('ruolo') == 'cacciatore') 
          {
          return redirect('cacciatori')->with('status', 'Cacciatore creato correttamente!');
          }
        else
          {
          return redirect('utenti')->with('status', 'Utente con ruolo <b>'.$ruolo.'</b> creato correttamente!');
          }

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    { 
        $validation_rules = [
              'email' => 'required|string|email|max:255|unique:users',
              'password' => 'required|string|min:6|confirmed',
          ];


        $validation_messages =  [
            'username.required' => 'Lo username è obbligatorio',
            'username.unique' => 'Lo username è già utilizzato',
            'username.max' => 'Lo username deve essere al massimo :max caratteri',

            'email.required' => 'La mail è obbligatoria',
            'email.unique' => 'La mail è già utilizzata',
            
            'password.required' => 'La password è obbligatoria',
            'password.min' => 'Le password deve essere almeno :min caratteri',
            'password.confirmed' => 'Le password non coincidono',
            'data_nascita.required' => 'La data di nascita è obbligatoria',
            'data_nascita.date_format' => 'La data di nascita non ha un formato valido',
            ];

        /*aggiungo le regole di validazione per il cacciatore*/
        if ( array_key_exists('ruolo', $data) && $data['ruolo'] == 'cacciatore' ) 
          {
          $validation_rules['data_nascita'] = 'required|date_format:d/m/Y';
          $validation_rules['nome'] = 'required|string|max:255';
          $validation_rules['cognome'] = 'required|string|max:255';
          
          $validation_messages['nome.required'] = 'Il nome è obbligatorio';
          $validation_messages['cognome.required'] = 'Il cognome è obbligatorio';
          }
        else
          {
          $validation_rules['name'] = 'required|string|max:255';
          $validation_messages['name.required'] = 'Il nome è obbligatorio';
          
          }


        
        
          return Validator::make(
            $data,  
            $validation_rules,
            $validation_messages
          );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = null;
        DB::transaction(function() use ($data) {
           
           if (!array_key_exists('name', $data)) 
            {
            $name = $data['nome'].' '.$data['cognome']; 
            } 
          else 
            {
            $name = $data['name'];
            }

           $user =  User::create([
               'ruolo' => $data['ruolo'],
               'name' => $name,
               /*'username' => $data['username'],*/ 
               'email' => $data['email'],
               'password' => Hash::make($data['password']),
           ]);

          if ( !is_null($user) && array_key_exists('ruolo', $data) && $data['ruolo'] == 'cacciatore') 
            {
             $cacciatore = Cacciatore::create($data);
             $cacciatore->user_id = $user->id;
             $cacciatore->save();
             
            if(array_key_exists('squadre', $data))
              {
              $user->cacciatore->squadre()->sync($data['squadre']);
              }
            }

        });

        return $user;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function modificaUtente(ModificaUtenteRequest $request, $utente_id)
    { 
      $utente = User::find($utente_id);

      $ruolo = $request->has('ruolo') ? $request->ruolo : 'admin';

      if ($request->has('ruolo') && $request->get('ruolo') == 'cacciatore') 
        {
        $this->_validatePhone($request);
        $utente->name = $request->get('nome') . ' ' . $request->get('cognome');
        if($request->has('squadre'))
          {
          $utente->cacciatore->squadre()->sync($request->squadre);
          }
        }
      else 
        {
        $utente->name = $request->get('name');
        }
        
      $utente->email = $request->get('email');
      $utente->ruolo = $ruolo;
      
      // $utente->username = $request->get('username');

      if ($request->filled('login_capabilities')) 
        {
        $utente->login_capabilities = true;
        }
      else
        {
        $utente->login_capabilities = false;
        }

      if ($request->filled('password')) 
        {
        $utente->password = Hash::make($request->get('password'));
        }

      DB::transaction(function() use ($request, $utente) {

        $utente->save();

        if ( !is_null($utente) && $request->has('ruolo') && $request->get('ruolo') == 'cacciatore')
          {
            
          $cacciatore = $utente->cacciatore;
          /////////////////////////////////////////////////////////////////////
          // ho inserito il salvataggio della data come Carbon in un mutator //
          /////////////////////////////////////////////////////////////////////
          $cacciatore->fill($request->except('elimina'));
          $cacciatore->save();

          if ($request->filled('elimina') && $request->get('elimina') == 1) 
            {

            //$user = $cacciatore->utente;
            
            // Now, when you call the delete method on the model, the deleted_at column will be set to the current date and time. 
            // And, when querying a model that uses soft deletes, the soft deleted models will automatically be excluded from all query results.
            $cacciatore->delete();

            //$user->login_capabilities = false;
            //$user->save();
            } 
          }  

      });


      if ($request->has('ruolo') && $request->get('ruolo') == 'cacciatore') 
        {
        if ($request->filled('elimina') && $request->get('elimina') == 1) 
          {
          return redirect('cacciatori')->with('status', 'Cacciatore eliminato!');
          } 
        else 
          {
          return redirect('cacciatori')->with('status', 'Cacciatore modificato correttamente!');
          }  
        }
      else
        {
        return redirect('utenti')->with('status', 'Utente con ruolo <b>'.$ruolo.'</b> modificato correttamente!');
        }
    }
}
