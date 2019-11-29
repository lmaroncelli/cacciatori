<?php

namespace App\Http\Controllers\Admin;

use App\Documento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LoginController;

class DocumentiController extends LoginController
{




  public function formUpload()
    {
    $doc = new Documento;

    return view('admin.documenti.form', compact('doc'));
    }

  public function upload(DocumentoRequest $request)
    {

      $doc_array = $request->except(['fileToUpload','squadre']);
      $ext = $request->fileToUpload->getClientOriginalExtension();
      $doc_array['ext'] = $ext;
      $fileName = time()."_".$request->fileToUpload->getClientOriginalName(); 
      $request->fileToUpload->storeAs('public',$fileName);

      $doc_array['file'] = $fileName;

      $doc = Documento::create($doc_array);

      $squadre = is_null($request->get('squadre')) ? 0 : $request->get('squadre');
      $doc->squadre()->sync($squadre);


      return redirect('admin/documenti')->with('status', 'Documento creato correttamente!');

    }

  public function index()
    {
      //////////////////
      // ordinamento  //
      //////////////////
      $order_by='created_at';
      $order = 'desc';
      $ordering = 0;


      if ($this->request->filled('order_by'))
        {
        $order_by=$this->request->get('order_by');
        $order = $this->request->get('order');
        $ordering = 1;
        }

      $columns = [
          'titolo' => 'Titolo',
          'argomento' => 'Argomento',
          'tipo' => 'Tipo',
          'created_at' => 'Caricato il'
      ];
  

    $documenti = Documento::listaDocumenti($order_by, $order, $paginate = 15, $limit = 0);

    return view('admin.documenti.index', compact('documenti', 'order_by','order','ordering','columns') );
    }


  // public function modifica($documento_id = 0)
  //   {
  //   $doc = Documento::find($documento_id);
    
  //   $associazioni_associate = $doc->associazioni->pluck('id','nome')->toArray();
  //   if(!count($associazioni_associate))
  //     {
  //     $associazioni_associate = ['0' => 'Tutte'];
  //     }
  //   else
  //     {
  //     if(array_key_exists(0, $associazioni_associate))
  //       {
  //         unset($associazioni_associate[0]);
  //       }
  //     }
  //   $assos = Utility::getAssociazioni();

  //   return view('admin.documenti.form', compact('doc','associazioni_associate','assos'));
  //   }

 

  // public function aggiorna(DocumentoRequest $request, $documento_id = 0)
  //   {
  //   $doc = Documento::find($documento_id);
  //   $doc->update($request->except('associazioni'));
    
  //   $associazioni = is_null($request->get('associazioni')) ? 0 : $request->get('associazioni');
  //   $doc->associazioni()->sync($associazioni);

  //   return redirect('admin/documenti')->with('status', 'Documento modificato correttamente!');
  //   }

  


  // public function elimina($documento_id)
  // {   
  //     $documento = Documento::find($documento_id);
  //     $file = $documento->file;
  //     $documento->delete();

  //     Storage::delete('public/'.$file);

  //     return redirect('admin/documenti')->with('status', 'Documento eliminato!');
  // }


}
