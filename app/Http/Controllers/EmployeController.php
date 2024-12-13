<?php

namespace App\Http\Controllers;
use App\Models\Employe;


use Illuminate\Http\Request;

class EmployeController extends Controller
{
    protected $xmlValidationService;

    public function __construct(XmlValidationService $xmlValidationService)
    {
        $this->xmlValidationService = $xmlValidationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employes = Employe::all();
        return view('employes.index', compact('employes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function store(Request $request)
//    {
//        $employe = Employe::create($request->all());
//        return redirect()->route('employes.index');
//    }
    public function store(Request $request) {
        $xmlContent = $request->getContent();
        $xsdPath = storage_path('schemas/projet.xsd');

        try {
            validateXML($xmlContent, $xsdPath);
            $xml = simplexml_load_string($xmlContent);

            $employe = new Employe();
            $employe->id = (int) $xml->id;
            $employe->nom = (string) $xml->nom;
            $employe->skills = implode(',', $xml->skills->skill ?? []);
            $employe->indisponibilite = implode(',', $xml->indisponibilite->date ?? []);
            $employe->save();

            return response()->xml(['message' => 'Employee created successfully'], 201);
        } catch (Exception $e) {
            return response()->xml(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $employe = Employe::findOrFail($id);

        $xml = new SimpleXMLElement('<Employe/>');
        $xml->addChild('id', $employe->id);
        $xml->addChild('nom', $employe->nom);

        $skills = $xml->addChild('skills');
        foreach (explode(',', $employe->skills) as $skill) {
            $skills->addChild('skill', $skill);
        }

        $indisponibilite = $xml->addChild('indisponibilite');
        foreach (explode(',', $employe->indisponibilite) as $date) {
            $indisponibilite->addChild('date', $date);
        }

        return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employe = Employe::findOrFail($id);
        return view('employes.edit', compact('employe'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employe = Employe::findOrFail($id);
        $employe->update($request->all());
        return redirect()->route('employes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $employe = Employe::findOrFail($id);
        $employe->delete();

        return response()->xml(['message' => 'Employee deleted successfully'], 200);
    }
}
