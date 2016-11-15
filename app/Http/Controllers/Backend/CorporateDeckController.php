<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\Backend\CorporateDeck\ManageCorporateDeckRequest;
use App\Http\Requests\Backend\CorporateDeck\UpdateCorporateDeckRequest;
use App\Http\Requests\Backend\CorporateDeck\StoreCorporateDeckRequest;
use App\Repositories\Backend\CorporateDeck\CorporateDeckRepositoryContract;
use Illuminate\Http\Request;
use App\Models\CorporateDeck;

/**
 * Class CorporateDeckController
 * @package App\Http\Controllers\Backend
 */
class CorporateDeckController extends Controller
{
    
    /**
     * @var CorporateDeckRepositoryContract
     */
    protected $corporateDeck;
        
    /**
     * @param CorporateDeckRepositoryContract $corporateDeck
     */
    public function __construct(CorporateDeckRepositoryContract $corporateDeck)
    {
        $this->corporateDeck = $corporateDeck;
    }
    
    /**
     * @param ManageCorporateDeckRequest $request
     * @return mixed
     */
    public function get(ManageCorporateDeckRequest $request) {
        return Datatables::of($this->corporateDeck->getForDataTable())            
            ->addColumn('actions', function($corporateDeck) {
                return $corporateDeck->action_buttons;
            })
            ->make(true);
    }
    
	/**
     * @param ManageCorporateDeckRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageCorporateDeckRequest $request)
    {        
        return view('backend.corporate-deck.index');
    }
    
	/**
     * @param ManageCorporateDeckRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ManageCorporateDeckRequest $request)
    {
        $corporateDeck = new CorporateDeck();
        return view('backend.corporate-deck.create')
            ->with('corporateDeck', $corporateDeck);
    }
    
    /**
     * @param CorporateDeck $corporate_deck
     * @param ManageCorporateDeckRequest $request
     * @return mixed
     */
    public function edit(CorporateDeck $corporate_deck, ManageCorporateDeckRequest $request)
    {               
        return view('backend.corporate-deck.edit')
            ->with(['corporate_deck' => $corporate_deck]);
    }
    
    /**
     * @param CorporateDeck $corporate_deck
     * @param UpdateCorporateDeckRequest $request
     * @return mixed
     */
    public function update(CorporateDeck $corporate_deck, UpdateCorporateDeckRequest $request)
    {
        $this->corporateDeck->update($corporate_deck, $request);
        return redirect()->route('admin.corporate-deck.index')->withFlashSuccess(trans('alerts.backend.corporate-deck.updated'));
    }

    /**
     * @param StoreCorporateDeckRequest $request
     * @return mixed
     */
    public function store(StoreCorporateDeckRequest $request)
    {
        $this->corporateDeck->create($request);
        
        return redirect()->route('admin.corporate-deck.index')->withFlashSuccess(trans('alerts.backend.corporate-deck.created'));
    }
    
    /**
     * @param CorporateDeck $corporateDeck
     * @param ManageCorporateDeckRequest $request
     * @return mixed
     */
    public function destroy(CorporateDeck $corporate_deck, ManageCorporateDeckRequest $request)
    {
        $this->corporateDeck->destroy($corporate_deck);
        return redirect()->back()->withFlashSuccess(trans('alerts.backend.corporate-deck.deleted'));
    }

}