<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Exception;

class ClienteController extends Controller
{
    private $clientes;

    public function __construct(){
        $this->clientes = Cliente::all();
    }

    public function index($id = null)
    {
        if($id){
            return $this->clientes->where('id', $id)->first();
        }
        return $this->clientes;
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'empresa'       => 'required',
                'codigo'        => 'required',
                'razao_social'  => 'required|string|max:255',
                'tipo'          => 'required|in:PJ,PF',
                'cpf_cnpj'      => 'nullable|string|max:14',
            ]);

            $cliente = Cliente::create($validatedData);
            
            return Response::json(['input' => $request->all()], 201);
        } catch (Exception $e) {
            return response()->json([$e->getMessage(), 'input' => $request->all()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'empresa'       => 'required',
                'codigo'        => 'required',
                'razao_social'  => 'required|string|max:255',
                'tipo'          => 'required|in:PJ,PF',
                'cpf_cnpj'      => 'nullable|string|max:14',
            ]);
            
            $cliente = Cliente::where('id', $id)->first();
            if($cliente->exists()){
                $cliente->empresa       = $validatedData['empresa'];
                $cliente->codigo        = $validatedData['codigo'];
                $cliente->razao_social  = $validatedData['razao_social']; 
                $cliente->tipo          = $validatedData['tipo']; 
                $cliente->cpf_cnpj      = $validatedData['cpf_cnpj']; 
                $cliente->save();
            }  else {
                throw new Exception;
            }
            
            return Response::json($cliente);
        } catch (Exception $e) {
            return Response::json($e);
        }
    }

    public function destroy($id)
    {
        if(Cliente::where('id', $id)->exists()){
            Cliente::where('id', $id)->delete();
            return Response::json("Deleted");
        }
        return Response::json("nothing to delete");
    } 
}
