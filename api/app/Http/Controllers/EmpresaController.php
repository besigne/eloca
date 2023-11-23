<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\Empresa;
use Exception;

class EmpresaController extends Controller
{
    private $empresas;

    public function __construct(){
        $this->empresas = Empresa::all();
    }

    public function index($id = null)
    {
        if($id){
            return $this->empresas->where('id', $id)->first();
        }
        return $this->empresas;
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'codigo'        => 'required',
                'empresa'       => 'required',
                'sigla'         => 'required',
                'razao_social'  => 'required'
            ]);

            if(!Empresa::where('codigo', $validatedData['codigo'])->exists()){
                $empresa = Empresa::create($validatedData);
                return Response::json($empresa, 201);
            }            
            return Response::json("The code already exists in the database");
        
        } catch (Exception $e) {
            return Response::json($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'codigo'        => 'required',
                'empresa'       => 'required',
                'sigla'         => 'required',
                'razao_social'  => 'required'
            ]);
            $empresa = Empresa::where('id', $id)->first();
            if($empresa->exists()){
                $empresa->codigo        = $validatedData['codigo'];
                $empresa->empresa       = $validatedData['empresa'];
                $empresa->sigla         = $validatedData['sigla'];
                $empresa->razao_social  = $validatedData['razao_social'];
                $empresa->save();
            } else {
                throw new Exception;
            }

            return Response::json($empresa);
        } catch (Exception $e) {
            return Response::json($e->getMessage());
        }
    }

    public function destroy($id)
    {
        if(Empresa::where('id', $id)->exists()){
            Empresa::where('id', $id)->delete();
            return Response::json("Deleted");
        }
        return Response::json("nothing to delete");
    }
}
