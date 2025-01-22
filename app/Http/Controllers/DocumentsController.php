<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendEmail;
use App\Models\MetaTag;
use App\Models\Documents;
use Illuminate\Http\Request;
use App\Models\DocumentComment;
use App\Models\DocumentCommentFile;
use App\Models\DocumentHasRole;
use App\Models\DocumentHasUser;
use App\Models\DocumentHistory;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Department;
use App\Repositories\DocumentsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\CreateDocumentsRequest;
use App\Http\Requests\UpdateDocumentsRequest;
use App\Repositories\DocumentHasRoleRepository;
use App\Repositories\DocumentHasUserRepository;
use App\Repositories\DocumentsCategoryRepository;
use Modules\WorkflowEngine\Models\Staff;
use App\Models\DocumentsCategory;
use App\Repositories\DocumentHasUserFileRepository;
use App\Models\ClickedLink;
use Illuminate\Support\Facades\Validator;
use App\Imports\FilesImport; // Create this import class
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request as IPRequest;
use App\Notifications\DocumentShared;
use App\Models\Notification as Notifications;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\SendDocumentSharedNotification;
use App\Models\DocumentHasUserFiles;
use Illuminate\Support\Facades\Queue;
use App\Models\Priority;
use App\Models\Signature;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;


class DocumentsController extends Controller
{


    /** @var DocumentRepository $documentRepository*/
    private $documentRepository;

    /** @var $userRepository UserRepository */
    private $userRepository;


    /** @var DocumentsCategoryRepository $documentsCategoryRepository*/
    private $documentsCategoryRepository;

    /** @var $roleRepository RoleRepository */
    private $roleRepository;

    private $documentHasUserRepository;

    private $documentHasRoleRepository;

    private $documentHasUserFileRepository;


    public function __construct(DocumentHasUserFileRepository $documentHasUserFileRepo, DocumentHasRoleRepository $documentHasRoleRepo, DocumentHasUserRepository $documentHasUserRepo, RoleRepository $roleRepo, DocumentsCategoryRepository $documentsCategoryRepo, DocumentsRepository $documentRepo,  UserRepository $userRepo)
    {

        $this->documentRepository = $documentRepo;
        $this->userRepository = $userRepo;
        $this->documentsCategoryRepository = $documentsCategoryRepo;
        $this->roleRepository = $roleRepo;
        $this->documentHasUserRepository = $documentHasUserRepo;
        $this->documentHasRoleRepository = $documentHasRoleRepo;
        $this->documentHasUserFileRepository = $documentHasUserFileRepo;
    }

    /**
     * Display a listing of the documents.
     */
    public function index(Request $request)
    {
        /* if (!checkPermission('create document')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */


        $userId = Auth::user()->id;

        $userIds = DocumentHasUser::get()->pluck('user_id');
        $loggedInUserId = auth()->id(); // Get the ID of the logged-in user

        $user_id = $userIds->contains($loggedInUserId);

        if (Auth::user()->hasRole('super-admin')) {
            // The logged-in user ID exists in the $userIds array
            //$documents = $this->documentRepository->paginate(10);
            $documents = \App\Models\Documents::query()
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_categories.id as category_id',
        'documents_categories.name as category_name',
        'documents_manager.created_at as document_created_at',
        'documents_manager.id as d_id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_categories.description as doc_description',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
       // 'documents_has_users.is_download',
       // 'documents_has_users.allow_share',
       // 'documents_has_users.user_id',
       // 'documents_has_users.assigned_by',
       // DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name')
    )
    ->latest('documents_manager.created_at')
    //->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
    ->groupBy('departments.name','documents_categories.description','documents_manager.document_url','documents_manager.title','documents_categories.id', 'documents_categories.name', 'documents_manager.created_at', 'documents_manager.id') // Include the nonaggregated column in the GROUP BY clause
    //->with('createdBy')
    ->get();

    $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });
   

        } else if (Auth::user()->level && Auth::user()->level->id == 20) {
            // The logged-in user ID exists in the $userIds array
            //$documents = $this->documentRepository->paginate(10);
            $documents = \App\Models\Documents::query()
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_categories.id as category_id',
        'documents_categories.name as category_name',
        'documents_manager.created_at as document_created_at',
        'documents_manager.id as d_id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_categories.description as doc_description',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
       // 'documents_has_users.is_download',
       // 'documents_has_users.allow_share',
       // 'documents_has_users.user_id',
       // 'documents_has_users.assigned_by',
       // DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name')
    )
    ->latest('documents_manager.created_at')
    ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
    ->groupBy('departments.name','documents_categories.description','documents_manager.document_url','documents_manager.title','documents_categories.id', 'documents_categories.name', 'documents_manager.created_at', 'documents_manager.id') // Include the nonaggregated column in the GROUP BY clause
    //->with('createdBy')
    ->get();

    $users1 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 19
    ');
    $users2 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 18
    ');
    
    $users3 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 17
    ');
    $users4 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 3
    ');
    
    $users5 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE employees.department_id = 19
    ');
    
    
    // Combine the results of all queries into one collection
    $userData = collect($users1)
        ->merge($users2)
        ->merge($users3)
        ->merge($users4)
        ->merge($users5)
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

   

        } else {
            // The logged-in user ID does not exist in the $userIds array
                $documents = \App\Models\Documents::query()
                ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_categories.id as category_id',
        'documents_categories.name as category_name',
        'documents_manager.created_at as document_created_at',
        'documents_manager.id as d_id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_categories.description as doc_description',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
         )
                ->where('documents_manager.created_by', $userId)
                ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
                ->latest('documents_manager.created_at')
                ->groupBy('documents_categories.description','documents_manager.document_url','documents_manager.title','documents_categories.id', 'documents_categories.name', 'documents_manager.created_at', 'documents_manager.id') // Include the nonaggregated column in the GROUP BY clause
                //->with('createdBy')
                ->get();


                if (Auth::user()->level && Auth::user()->level->id == 19) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 18) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 17) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 17
                ');
            
                $users3 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.department_id = ?
            ', [auth()->user()->staff->department_id]);
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->merge($users3)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 3) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
            
                $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.branch_id = ?
            ', [auth()->user()->staff->branch_id]);
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                
                } else{

                    if(auth()->user()->staff && auth()->user()->staff->branch_id == 23){
                        $users1 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE users.level_id = 17
                    ');
                    
                    $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE employees.department_id = ?
                ', [auth()->user()->staff->department_id]);
                        } else{
                            $users1 = DB::select('
                            SELECT users.id as id, users.name
                            FROM users
                            JOIN employees ON users.id = employees.user_id
                            WHERE users.level_id = 3
                        ');
                        
                        $users2 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE employees.branch_id = ?
                    ', [auth()->user()->staff->branch_id]);
                        }
                   
                    
                    
                    // Combine the results of all queries into one collection
                    $userData = collect($users1)
                        ->merge($users2)
                        ->map(function ($user) {
                            return [
                                'id' => $user->id,
                                'name' => $user->name,
                            ];
                        });
                }


        }


        $roles = $this->roleRepository->all()->pluck('name', 'id');
        // $roles->prepend('Select role', '');
        // $departments->prepend('Select department', '');
        /* $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });
 */
        $users = $userData->pluck('name', 'id');

        return view('documents.index', compact('documents', 'users', 'roles'));
    }

    public function storeClickedLink(Request $request)
{
    $userId = Auth::id();
    $linkUrl = $request->input('linkUrl');

    // Check if a record already exists for the given user and link_url
    $existingRecord = ClickedLink::where('user_id', $userId)
                                  ->where('link_url',  $linkUrl)
                                  ->first();

    if ($existingRecord) {
        // Update the existing record if it already exists
        $existingRecord->update([
            'updated_at' => now(), // Update the timestamp
        ]);
    } else {
        // Create a new record if it doesn't exist
        ClickedLink::create([
            'user_id' => $userId,
            'link_url' => $linkUrl,
        ]);
    }
}

public function fetchClickedLinks(Request $request)
{
    $userId = $userId = Auth::id();

    // Fetch clicked links for the user
    $clickedLinks = ClickedLink::where('user_id', $userId)->get();

    return response()->json($clickedLinks);
}
    public function showDepartementalDocuments(Request $request, $id)
{
    $documents = DB::table('documents_has_users')
        ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users.document_id')
        ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
        ->join('users', 'documents_has_users.user_id', '=', 'users.id')
        ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
        ->select(
            'documents_manager.category_id',
            'documents_categories.id',
            'documents_manager.title',
            'documents_has_users.created_at as createdAt',
            'documents_categories.name as category_name',
            'documents_has_users.start_date',
            'documents_has_users.end_date',
            'documents_has_users.allow_share',
            'documents_has_users.is_download',
            'documents_has_users.user_id',
            'documents_has_users.assigned_by',
            'documents_manager.document_url as document_url',
            'documents_manager.id as d_m_id',
            'documents_categories.id as d_m_c_id',
            'documents_categories.name as cat_name',
            'departments.name as dep_name',
            DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name'),
            DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.assigned_by) AS assigned_by_name'),
            DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
        )
        ->latest('documents_has_users.created_at')
        ->groupBy(
            'documents_manager.department_id',
            'documents_categories.id',
            'documents_has_users.start_date',
            'documents_has_users.end_date',
            'documents_manager.id',
            'documents_manager.title',
            'documents_manager.document_url',
            'documents_has_users.id',
            'documents_has_users.created_at',
            'documents_categories.name',
            'documents_manager.category_id',
            'documents_has_users.allow_share',
            'documents_has_users.is_download',
            'documents_has_users.user_id',
            'documents_has_users.assigned_by',
            'departments.name',
            'documents_manager.created_by',
        )
        ->where('documents_has_users.user_id', '=', auth()->user()->id)
        ->where('documents_manager.department_id', '=', $id)
        //->where('documents_manager.branch_id', '=', auth()->user()->staff->branch_id)
        ->limit(10)
        ->get();

    return response()->json($documents);
}

public function showDepartementalDocumentsByUser(Request $request, $id)
{
    $documents = DB::table('documents_has_users')
        ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users.document_id')
        ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
        ->join('users', 'documents_has_users.user_id', '=', 'users.id')
        ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
        ->select(
            'documents_manager.category_id',
            'documents_categories.id',
            'documents_manager.title',
            'documents_has_users.created_at as createdAt',
            'documents_categories.name as category_name',
            'documents_has_users.start_date',
            'documents_has_users.end_date',
            'documents_has_users.allow_share',
            'documents_has_users.is_download',
            'documents_has_users.user_id',
            'documents_has_users.assigned_by',
            'documents_manager.document_url as document_url',
            'documents_manager.id as d_m_id',
            'documents_categories.id as d_m_c_id',
            'documents_categories.name as cat_name',
            'departments.name as dep_name',
            DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name'),
            DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.assigned_by) AS assigned_by_name'),
            DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
        )
        ->latest('documents_has_users.created_at')
        ->groupBy(
            'documents_manager.department_id',
            'documents_categories.id',
            'documents_has_users.start_date',
            'documents_has_users.end_date',
            'documents_manager.id',
            'documents_manager.title',
            'documents_manager.document_url',
            'documents_has_users.id',
            'documents_has_users.created_at',
            'documents_categories.name',
            'documents_manager.category_id',
            'documents_has_users.allow_share',
            'documents_has_users.is_download',
            'documents_has_users.user_id',
            'documents_has_users.assigned_by',
            'departments.name',
            'documents_manager.created_by',
        )
        ->where('documents_has_users.user_id', '=', auth()->user()->id)
        //->where('documents_manager.branch_id', '=', auth()->user()->staff->branch_id)
        ->limit(10)
        ->get();

    return response()->json($documents);
}


    public function documentsByAudits()
    {
        if(\Auth::user()->can('view document audit trail'))
         {
        
        /* if (!checkPermission('create document')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */

       // if (Auth()->user()->hasRole('super-admin') || Auth()->user()->hasRole('MANAGING DIRECTOR')) {

        /* $documents = DB::table('documents_manager')
            ->join('audits', 'documents_manager.id', '=', 'audits.auditable_id')
            ->join('users', 'documents_manager.created_by', '=', 'users.id')
            ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select('documents_manager.*', 'documents_manager.id as id', 'audits.*', 'users.*', 'documents_manager.created_at as createdAt', 'documents_categories.name as category_name')
            ->where('audits.auditable_type', "App\Models\Documents")
            ->latest('documents_manager.created_at')
            ->get(); */
            $documents = DB::table('documents_manager')
            ->join('audits', 'documents_manager.id', '=', 'audits.auditable_id')
            ->join('users as created_by_user', 'documents_manager.created_by', '=', 'created_by_user.id')
            ->join('documents_has_users', 'documents_manager.id', '=', 'documents_has_users.document_id')
            ->join('users as assigned_to_user', 'documents_has_users.user_id', '=', 'assigned_to_user.id')
            ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select('documents_categories.id as d_c_id','documents_manager.*', 'documents_manager.id as id', 'audits.*', 'created_by_user.name as created_by_first_name', 'assigned_to_user.name as assigned_to_first_name', 'documents_manager.created_at as createdAt', 'documents_categories.name as category_name')
            ->where('audits.auditable_type', "App\Models\Documents")
            ->latest('documents_manager.created_at')
            ->distinct() // Ensure distinct results
            ->get();        

        // Now that you have the documents, you can load the category relationship
$documentIds = $documents->pluck('d_c_id')->toArray();
$categories = DocumentsCategory::whereIn('id', $documentIds)->get()->keyBy('id');
       /*  } else{
            
            return redirect()->back()->with('error', 'Permission denied for document audit trail access');
        } */

        return view('documents.document_audits', compact('documents','categories'));
    }
    else
    {
        return redirect()->back()->with('error', __('Permission denied.'));
    }
    }

    public function documentsVersion($id)
    {
        
       
        $documentHistory = DB::table('documents_histories')
            ->join('users', 'documents_histories.created_by', 'users.id')
            ->select('documents_histories.*', 'documents_histories.created_at as createdAt', 'users.name as firstName', 'documents_histories.document_url as doc_url')
            ->where('documents_histories.document_id', $id)
            ->get();

       

        return response()->json($documentHistory);
    }

    public function sharedUser(){
        

        $userId = Auth::user()->id;


        if (Auth::user()->hasRole('super-admin')) {

            /* $documents = DB::table('documents_has_users')
    ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users.document_id')
    ->join('users', 'documents_has_users.user_id', '=', 'users.id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select('documents_has_users.*', 'documents_manager.*', 'users.*', 'documents_has_users.created_at as createdAt', 'documents_categories.name as category_name', 'documents_manager.category_id as d_m_c_id')
    ->latest('documents_has_users.created_at')
    ->groupBy('documents_manager.document_url','documents_manager.category_id','documents_has_users.id','documents_has_users.created_at', 'documents_categories.name', 'documents_manager.category_id')
    ->get(); */
   
    $documents = DB::table('documents_has_users')
    ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users.document_id')
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('users', 'documents_has_users.user_id', '=', 'users.id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_manager.title',
        'documents_has_users.created_at as createdAt',
        'documents_categories.name as category_name',
        'documents_has_users.start_date',
        'documents_has_users.end_date',
        'documents_has_users.allow_share',
        'documents_has_users.is_download',
        'documents_has_users.user_id',
        'documents_has_users.assigned_by',
        'documents_manager.document_url as document_url',
        'documents_manager.id as d_m_id',
        'documents_categories.id as d_m_c_id',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.assigned_by) AS assigned_by_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
    )
    ->latest('documents_has_users.created_at')
    //->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
    ->groupBy(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_has_users.start_date',
        'documents_has_users.end_date',
        'documents_manager.id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_has_users.id',
        'documents_has_users.created_at',
        'documents_categories.name',
        'documents_has_users.allow_share',
        'documents_has_users.is_download',
        'documents_has_users.user_id',
        'documents_has_users.assigned_by',
        'departments.name',
        'documents_manager.created_by',
    )
    ->get();

    
    $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

        } else if (Auth::user()->level && Auth::user()->level->id == 20) {

   
    $documents = DB::table('documents_has_users')
    ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users.document_id')
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('users', 'documents_has_users.user_id', '=', 'users.id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_manager.title',
        'documents_has_users.created_at as createdAt',
        'documents_categories.name as category_name',
        'documents_has_users.start_date',
        'documents_has_users.end_date',
        'documents_has_users.allow_share',
        'documents_has_users.is_download',
        'documents_has_users.user_id',
        'documents_has_users.assigned_by',
        'documents_manager.document_url as document_url',
        'documents_manager.id as d_m_id',
        'documents_categories.id as d_m_c_id',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.assigned_by) AS assigned_by_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
    )
    ->latest('documents_has_users.created_at')
    ->where('documents_has_users.user_id', '=', $userId)
    ->groupBy(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_has_users.start_date',
        'documents_has_users.end_date',
        'documents_manager.id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_has_users.id',
        'documents_has_users.created_at',
        'documents_categories.name',
        'documents_has_users.allow_share',
        'documents_has_users.is_download',
        'documents_has_users.user_id',
        'documents_has_users.assigned_by',
        'departments.name',
        'documents_manager.created_by',
    )
    ->get();

    
    $users1 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 19
    ');
    $users2 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 18
    ');
    
    $users3 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 17
    ');
    $users4 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 3
    ');
    
    $users5 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE employees.department_id = 19
    ');
    
    // Combine the results of all queries into one collection
    $userData = collect($users1)
        ->merge($users2)
        ->merge($users3)
        ->merge($users4)
        ->merge($users5)
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });


        } else if (Auth::user()->level && Auth::user()->level->id == 19) {

            
   
    $documents = DB::table('documents_has_users')
    ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users.document_id')
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('users', 'documents_has_users.user_id', '=', 'users.id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_manager.title',
        'documents_has_users.created_at as createdAt',
        'documents_categories.name as category_name',
        'documents_has_users.start_date',
        'documents_has_users.end_date',
        'documents_has_users.allow_share',
        'documents_has_users.is_download',
        'documents_has_users.user_id',
        'documents_has_users.assigned_by',
        'documents_manager.document_url as document_url',
        'documents_manager.id as d_m_id',
        'documents_categories.id as d_m_c_id',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.assigned_by) AS assigned_by_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
    )
    ->latest('documents_has_users.created_at')
    ->where('documents_has_users.user_id', '=', $userId)
    ->groupBy(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_has_users.start_date',
        'documents_has_users.end_date',
        'documents_manager.id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_has_users.id',
        'documents_has_users.created_at',
        'documents_categories.name',
        'documents_has_users.allow_share',
        'documents_has_users.is_download',
        'documents_has_users.user_id',
        'documents_has_users.assigned_by',
        'departments.name',
        'documents_manager.created_by',
    )
    ->get();

    
    $users1 = DB::select('
            SELECT users.id as id, users.name
            FROM users
            JOIN employees ON users.id = employees.user_id
            WHERE users.level_id = 20
        ');
        
        // Combine the results of all queries into one collection
        $userData = collect($users1)
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                ];
            });


        } else if (Auth::user()->level && Auth::user()->level->id == 17) {

            
   
            $documents = DB::table('documents_has_users')
            ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users.document_id')
            ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
            ->join('users', 'documents_has_users.user_id', '=', 'users.id')
            ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select(
                'documents_manager.category_id',
                'documents_categories.id',
                'documents_manager.title',
                'documents_has_users.created_at as createdAt',
                'documents_categories.name as category_name',
                'documents_has_users.start_date',
                'documents_has_users.end_date',
                'documents_has_users.allow_share',
                'documents_has_users.is_download',
                'documents_has_users.user_id',
                'documents_has_users.assigned_by',
                'documents_manager.document_url as document_url',
                'documents_manager.id as d_m_id',
                'documents_categories.id as d_m_c_id',
                'documents_categories.name as cat_name',
                'departments.name as dep_name',
                DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name'),
                DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.assigned_by) AS assigned_by_name'),
                DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
            )
            ->latest('documents_has_users.created_at')
            ->where('documents_has_users.user_id', '=', $userId)
            ->groupBy(
                'documents_manager.category_id',
                'documents_categories.id',
                'documents_has_users.start_date',
                'documents_has_users.end_date',
                'documents_manager.id',
                'documents_manager.title',
                'documents_manager.document_url',
                'documents_has_users.id',
                'documents_has_users.created_at',
                'documents_categories.name',
                'documents_has_users.allow_share',
                'documents_has_users.is_download',
                'documents_has_users.user_id',
                'documents_has_users.assigned_by',
                'departments.name',
                'documents_manager.created_by',
            )
            ->get();
        
            
            $users1 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 20
    ');
    
    $users2 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 17
    ');

    $users3 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE employees.department_id = ?
', [auth()->user()->staff->department_id]);
   
    
    
    // Combine the results of all queries into one collection
    $userData = collect($users1)
        ->merge($users2)
        ->merge($users3)
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });
        
        
     } else if (Auth::user()->level && Auth::user()->level->id == 18) {

            
   
                    $documents = DB::table('documents_has_users')
                    ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users.document_id')
                    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
                    ->join('users', 'documents_has_users.user_id', '=', 'users.id')
                    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
                    ->select(
                        'documents_manager.category_id',
                        'documents_categories.id',
                        'documents_manager.title',
                        'documents_has_users.created_at as createdAt',
                        'documents_categories.name as category_name',
                        'documents_has_users.start_date',
                        'documents_has_users.end_date',
                        'documents_has_users.allow_share',
                        'documents_has_users.is_download',
                        'documents_has_users.user_id',
                        'documents_has_users.assigned_by',
                        'documents_manager.document_url as document_url',
                        'documents_manager.id as d_m_id',
                        'documents_categories.id as d_m_c_id',
                        'documents_categories.name as cat_name',
                        'departments.name as dep_name',
                        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name'),
                        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.assigned_by) AS assigned_by_name'),
                        DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
                    )
                    ->latest('documents_has_users.created_at')
                    ->where('documents_has_users.user_id', '=', $userId)
                    ->groupBy(
                        'documents_manager.category_id',
                        'documents_categories.id',
                        'documents_has_users.start_date',
                        'documents_has_users.end_date',
                        'documents_manager.id',
                        'documents_manager.title',
                        'documents_manager.document_url',
                        'documents_has_users.id',
                        'documents_has_users.created_at',
                        'documents_categories.name',
                        'documents_has_users.allow_share',
                        'documents_has_users.is_download',
                        'documents_has_users.user_id',
                        'documents_has_users.assigned_by',
                        'departments.name',
                        'documents_manager.created_by',
                    )
                    ->get();
                
                    
                    $users1 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 20
    ');
    
    // Combine the results of all queries into one collection
    $userData = collect($users1)
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }); 
                
     } else if (Auth::user()->level && Auth::user()->level->id == 3) {

            
   
        $documents = DB::table('documents_has_users')
        ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users.document_id')
        ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
        ->join('users', 'documents_has_users.user_id', '=', 'users.id')
        ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
        ->select(
            'documents_manager.category_id',
            'documents_categories.id',
            'documents_manager.title',
            'documents_has_users.created_at as createdAt',
            'documents_categories.name as category_name',
            'documents_has_users.start_date',
            'documents_has_users.end_date',
            'documents_has_users.allow_share',
            'documents_has_users.is_download',
            'documents_has_users.user_id',
            'documents_has_users.assigned_by',
            'documents_manager.document_url as document_url',
            'documents_manager.id as d_m_id',
            'documents_categories.id as d_m_c_id',
            'documents_categories.name as cat_name',
            'departments.name as dep_name',
            DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name'),
            DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.assigned_by) AS assigned_by_name'),
            DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
        )
        ->latest('documents_has_users.created_at')
        ->where('documents_has_users.user_id', '=', $userId)
        ->groupBy(
            'documents_manager.category_id',
            'documents_categories.id',
            'documents_has_users.start_date',
            'documents_has_users.end_date',
            'documents_manager.id',
            'documents_manager.title',
            'documents_manager.document_url',
            'documents_has_users.id',
            'documents_has_users.created_at',
            'documents_categories.name',
            'documents_has_users.allow_share',
            'documents_has_users.is_download',
            'documents_has_users.user_id',
            'documents_has_users.assigned_by',
            'departments.name',
            'documents_manager.created_by',
        )
        ->get();
    
        
        $users1 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 20
    ');

    $users2 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE employees.branch_id = ?
', [auth()->user()->staff->branch_id]);
   
    
    
    // Combine the results of all queries into one collection
    $userData = collect($users1)
        ->merge($users2)
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });
    
} else {
           

    //here
    $documents = DB::table('documents_has_users')
    ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users.document_id')
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('users', 'documents_has_users.user_id', '=', 'users.id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_manager.title',
        'documents_has_users.created_at as createdAt',
        'documents_categories.name as category_name',
        'documents_has_users.start_date',
        'documents_has_users.end_date',
        'documents_has_users.allow_share',
        'documents_has_users.is_download',
        'documents_has_users.user_id',
        'documents_has_users.assigned_by',
        'documents_manager.document_url as document_url',
        'documents_manager.id as d_m_id',
        'documents_categories.id as d_m_c_id',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.assigned_by) AS assigned_by_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
    )
    ->latest('documents_has_users.created_at')
    ->groupBy(
        'documents_categories.id',
        'documents_has_users.start_date',
        'documents_has_users.end_date',
        'documents_manager.id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_has_users.id',
        'documents_has_users.created_at',
        'documents_categories.name'
    )
    ->where('documents_has_users.user_id', '=', $userId)
    ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
    ->get();

    
    if(auth()->user()->staff && auth()->user()->staff->branch_id == 23){
        $users1 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 17
    ');
    
    $users2 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE employees.department_id = ?
', [auth()->user()->staff->department_id]);
        } else{
            $users1 = DB::select('
            SELECT users.id as id, users.name
            FROM users
            JOIN employees ON users.id = employees.user_id
            WHERE users.level_id = 3
        ');
        
        $users2 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE employees.branch_id = ?
    ', [auth()->user()->staff->branch_id]);
        }
   
    
    
    // Combine the results of all queries into one collection
    $userData = collect($users1)
        ->merge($users2)
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });
      
        
        }

        // Now that you have the documents, you can load the category relationship
$documentIds = $documents->pluck('d_m_c_id')->toArray();
$categories = DocumentsCategory::whereIn('id', $documentIds)->get()->keyBy('id');


        $roles = $this->roleRepository->all()->pluck('name', 'id');
        // $roles->prepend('Select role', '');
        // $departments->prepend('Select department', '');
       /*  $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }); */

        $users = $userData->pluck('name', 'id');

        return view('documents.document_shared_user', compact('documents', 'users', 'roles', 'categories'));
    }

    
    public function viewDocumentsByFileNo(Request $request){

        // Validate the input to ensure it's numeric
    $validated = $request->validate([
        'document_no' => 'nullable|numeric', // Optional, only validate if it's provided
    ]);

        $userId = Auth::user()->id;

        $userIds = DocumentHasUser::get()->pluck('user_id');
        $loggedInUserId = auth()->id(); // Get the ID of the logged-in user

        $user_id = $userIds->contains($loggedInUserId);

        $uId = $request->input('u_id');

        $document_no = $request->input('document_no');
   

        if (Auth::user()->hasRole('super-admin')) {

            $departments = Department::get();
            
            // The logged-in user ID exists in the $userIds array
            //$documents = $this->documentRepository->paginate(10);
            $documents_cat = DocumentsCategory::orderBy('created_at', 'desc')
            //->whereNull('parent_id')
            ->where('branch_id', Auth()->user()->staff->branch_id)
            ->where('department_id', Auth()->user()->staff->department_id)
            ->select(
                'id as catId',
                'created_at as createdAt',
                'name as cat_name',
                'description as cat_description'
            )
            ->get();
        
        $documents = Documents::orderBy('document_created_at', 'desc')
            ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
            ->select(
                'documents_manager.created_at as document_created_at',
                'documents_manager.id as d_id',
                'documents_manager.title',
                'documents_manager.document_url',
                'departments.name as dep_name',
                'documents_manager.document_no',
            )
            ->latest('documents_manager.created_at')
            ->groupBy('departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
            ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
            ->where('documents_manager.department_id', auth()->user()->staff->department_id)
            ->when(!empty($uId), function ($query) {
                return $query->where('documents_manager.status', '0');
            }) 
            ->when(!empty($document_no), function ($query) use ($document_no) {
                return $query->where('documents_manager.document_no', $document_no);
            })
            ->where('documents_manager.created_by', auth()->user()->id)
            ->get();
        
        // Merging both collections
        // Merge categories and documents into one collection
    
    $mergedDocuments = $documents_cat->map(function ($category) {
        return [
            'type' => 'category', // Mark as a category
            'catId' => $category->catId,
            'd_id' => null,
            'cat_name' => $category->cat_name,
            'description' => $category->cat_description,
            'createdAt' => $category->createdAt,
            'created_at' => $category->createdAt,
            'document_url' => null, // Categories don't have document URLs
            'dep_name' => null, // Categories don't have a department name
        ];
    });
        
    // Add documents to the merged collection
    if(!empty($uId)){
    $mergedDocuments = $documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'document_no' => $document->document_no,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
        ];
    });
}
if(empty($uId)){
    $mergedDocuments = $mergedDocuments->merge($documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'document_no' => $document->document_no,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
        ];
    }));
}

    // Sort the merged collection by created_at (most recent first)
    $mergedDocuments = $mergedDocuments->sortByDesc('created_at');

        
        // Optionally, you can sort the merged collection by created_at if needed
       // $mergedDocuments = $mergedDocuments->sortByDesc('document_created_at');
        
    $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

        $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)
        ->where('department_id', Auth()->user()->staff->department_id)->get();
        $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

   

        } else if (Auth::user()->level && Auth::user()->level->id == 20) {
            // The logged-in user ID exists in the $userIds array
            //$documents = $this->documentRepository->paginate(10);
            $departments = Department::get();
            $documents_cat = DocumentsCategory::orderBy('created_at', 'desc')
            //->whereNull('parent_id')
            ->where('branch_id', Auth()->user()->staff->branch_id)
            ->where('department_id', Auth()->user()->staff->department_id)
            ->select(
                'id as catId',
                'created_at as createdAt',
                'name as cat_name',
                'description as cat_description'
            )
            ->get();
        
        $documents = Documents::orderBy('document_created_at', 'desc')
            ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
            ->select(
                'documents_manager.created_at as document_created_at',
                'documents_manager.id as d_id',
                'documents_manager.title',
                'documents_manager.document_url',
                'departments.name as dep_name',
                'documents_manager.document_no',
            )
            ->latest('documents_manager.created_at')
            ->groupBy('departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
            ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
            ->where('documents_manager.department_id', auth()->user()->staff->department_id)
            ->when(!empty($uId), function ($query) {
                return $query->where('documents_manager.status', '0');
            })
            ->when(!empty($document_no), function ($query) use ($document_no) {
                return $query->where('documents_manager.document_no', $document_no);
            })
            ->where('documents_manager.created_by', auth()->user()->id)
            ->get();
        
        // Merging both collections
        // Merge categories and documents into one collection
    $mergedDocuments = $documents_cat->map(function ($category) {
        return [
            'type' => 'category', // Mark as a category
            'catId' => $category->catId,
            'd_id' => null,
            'cat_name' => $category->cat_name,
            'description' => $category->cat_description,
            'createdAt' => $category->createdAt,
            'created_at' => $category->createdAt,
            'document_url' => null, // Categories don't have document URLs
            'dep_name' => null, // Categories don't have a department name
        ];
    });

    // Add documents to the merged collection
    if(!empty($uId)){
        $mergedDocuments = $documents->map(function ($document) {
            return [
                'type' => 'document', // Mark as a document
                'd_id' => $document->d_id,
                'name' => $document->title,
                'document_no' => $document->document_no,
                'description' => null, // Documents don't have descriptions in this case
                'document_created_at' => $document->document_created_at,
                'created_at' => $document->document_created_at,
                'document_url' => $document->document_url,
                'dep_name' => $document->dep_name,
            ];
        });
    }
    if(empty($uId)){
        $mergedDocuments = $mergedDocuments->merge($documents->map(function ($document) {
            return [
                'type' => 'document', // Mark as a document
                'd_id' => $document->d_id,
                'name' => $document->title,
                'document_no' => $document->document_no,
                'description' => null, // Documents don't have descriptions in this case
                'document_created_at' => $document->document_created_at,
                'created_at' => $document->document_created_at,
                'document_url' => $document->document_url,
                'dep_name' => $document->dep_name,
            ];
        }));
    }

    // Sort the merged collection by created_at (most recent first)
    $mergedDocuments = $mergedDocuments->sortByDesc('created_at');

        

    $users1 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 19
    ');
    $users2 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 18
    ');
    
    $users3 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 17
    ');
    $users4 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 3
    ');
    
    $users5 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE employees.department_id = 19
    ');
    
    // Combine the results of all queries into one collection
    $userData = collect($users1)
        ->merge($users2)
        ->merge($users3)
        ->merge($users4)
        ->merge($users5)
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

   
        $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)
        ->where('department_id', Auth()->user()->staff->department_id)->get();
        $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

        } else {
            $departments = Department::where('id', Auth()->user()->staff->department_id)->get();
            // The logged-in user ID does not exist in the $userIds array
            $documents_cat = DocumentsCategory::orderBy('created_at', 'desc')
            //->whereNull('parent_id')
            ->where('branch_id', Auth()->user()->staff->branch_id)
            ->where('department_id', Auth()->user()->staff->department_id)
            ->select(
                'id as catId',
                'created_at as createdAt',
                'name as cat_name',
                'description as cat_description'
            )
            ->get();
        
        $documents = Documents::orderBy('document_created_at', 'desc')
            ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
            ->select(
                'documents_manager.created_at as document_created_at',
                'documents_manager.id as d_id',
                'documents_manager.title',
                'documents_manager.document_url',
                'departments.name as dep_name',
                'documents_manager.document_no',
            )
            ->latest('documents_manager.created_at')
            ->groupBy('departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
            ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
            ->where('documents_manager.department_id', auth()->user()->staff->department_id)
            ->where('documents_manager.created_by', auth()->user()->id)
            ->when(!empty($uId), function ($query) {
                return $query->where('documents_manager.status', '0');
            })
            ->when(!empty($document_no), function ($query) use ($document_no) {
                return $query->where('documents_manager.document_no', $document_no);
            })
            ->get();
        
        // Merging both collections
        // Merge categories and documents into one collection
    $mergedDocuments = $documents_cat->map(function ($category) {
        return [
            'type' => 'category', // Mark as a category
            'catId' => $category->catId,
            'd_id' => null,
            'cat_name' => $category->cat_name,
            'description' => $category->cat_description,
            'createdAt' => $category->createdAt,
            'created_at' => $category->createdAt,
            'document_url' => null, // Categories don't have document URLs
            'dep_name' => null, // Categories don't have a department name
        ];
    });

    // Add documents to the merged collection
    if(!empty($uId)){
        $mergedDocuments = $documents->map(function ($document) {
            return [
                'type' => 'document', // Mark as a document
                'd_id' => $document->d_id,
                'name' => $document->title,
                'document_no' => $document->document_no,
                'description' => null, // Documents don't have descriptions in this case
                'document_created_at' => $document->document_created_at,
                'created_at' => $document->document_created_at,
                'document_url' => $document->document_url,
                'dep_name' => $document->dep_name,
            ];
        });
    }
    if(empty($uId)){
        /* $mergedDocuments = $documents->map(function ($document) {
            return [
                'type' => 'document', // Mark as a document
                'd_id' => $document->d_id,
                'name' => $document->title,
                'description' => null, // Documents don't have descriptions in this case
                'document_created_at' => $document->document_created_at,
                'created_at' => $document->document_created_at,
                'document_url' => $document->document_url,
                'dep_name' => $document->dep_name,
            ];
        }); */
        $mergedDocuments = $documents_cat->map(function ($category) {
            return [
                'type' => 'category', // Mark as a category
                'catId' => $category->catId,
                'd_id' => null,
                'cat_name' => $category->cat_name,
                'description' => $category->cat_description,
                'createdAt' => $category->createdAt,
                'created_at' => $category->createdAt,
                'document_url' => null, // Categories don't have document URLs
                'dep_name' => null, // Categories don't have a department name
            ];
        });
    }

    // Sort the merged collection by created_at (most recent first)
    $mergedDocuments = $mergedDocuments->sortByDesc('created_at');

        
                if (Auth::user()->level && Auth::user()->level->id == 19) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 18) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 17) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 17
                ');
            
                $users3 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.department_id = ?
            ', [auth()->user()->staff->department_id]);
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->merge($users3)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 3) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
            
                $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.branch_id = ?
            ', [auth()->user()->staff->branch_id]);
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                
                } else{

                    if(auth()->user()->staff && auth()->user()->staff->branch_id == 23){
                        $users1 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE users.level_id = 17
                    ');
                    
                    $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE employees.department_id = ?
                ', [auth()->user()->staff->department_id]);
                        } else{
                            $users1 = DB::select('
                            SELECT users.id as id, users.name
                            FROM users
                            JOIN employees ON users.id = employees.user_id
                            WHERE users.level_id = 3
                        ');
                        
                        $users2 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE employees.branch_id = ?
                    ', [auth()->user()->staff->branch_id]);
                        }
                   
                    
                    
                    // Combine the results of all queries into one collection
                    $userData = collect($users1)
                        ->merge($users2)
                        ->map(function ($user) {
                            return [
                                'id' => $user->id,
                                'name' => $user->name,
                            ];
                        });
                }

                $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();
                $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

        }


        $roles = $this->roleRepository->all()->pluck('name', 'id');
        
        $users = $userData->pluck('name', 'id');

        // Fetch all priorities
        $priorities = Priority::all();
        $page_title = "File Archive";
        $title =  'Manage File Archive';
        $sub_title = 'File Archive';
//        $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

$sharedDocument = Documents::orderBy('documents_has_users.created_at', 'desc')
->join('users', 'users.id', '=', 'documents_manager.created_by')
->join('employees', 'employees.user_id', '=', 'users.id')
->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
->join('priorities', 'priorities.id', '=', 'documents_has_users.priority_id')
->select(
    'documents_manager.created_at as document_created_at',
    'documents_manager.id as d_id',
    'documents_manager.title',
    'documents_manager.document_url',
    'users.name',
    
    'users.avatar as profile_picture',
    'priorities.name as priority_name',
    'priorities.color_code as color_code',
    'documents_has_users.created_at as createdAt',
    'documents_has_users.lock_code',
)
//->latest('documents_manager.created_at')
->groupBy('documents_has_users.lock_code','priorities.color_code','documents_has_users.created_at','priorities.name','users.avatar','users.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
->where('documents_has_users.user_id', auth()->user()->id)
//->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
//->where('documents_manager.department_id', auth()->user()->staff->department_id)
//->orderBy('documents_manager.id' , 'desc')
->limit(3)
->get();

$total_files = DocumentsCategory::where('branch_id', auth()->user()->staff->branch_id)
        ->where('department_id', auth()->user()->staff->department_id)
        ->orderBy('id', 'desc')
        ->limit(3)
        ->get();

    $files = [];
   // foreach ($total_files as $category) {
        // Get the files in this category
        

        $categoryFiles = DocumentsCategory::orderBy('documents_categories.created_at', 'desc')
            ->join('documents_has_users_files', 'documents_has_users_files.category_id', '=', 'documents_categories.id')
            ->join('documents_manager', 'documents_manager.category_id', '=', 'documents_has_users_files.category_id')
        ->where('documents_has_users_files.user_id', auth()->user()->id)
        ->select('documents_has_users_files.lock_code','documents_categories.id as cId','documents_categories.name as cName','documents_has_users_files.category_id','documents_manager.id', 'documents_manager.document_url', 'documents_has_users_files.created_at', 'documents_manager.title', 'documents_has_users_files.user_id')
        ->limit(3)
        ->get();

        foreach ($categoryFiles as $file) {
            // Get the file size
            //$filePath = getFilePath($file->document_url);
            //$fileSize = $this->formatFileSize(filesize($filePath));
            $filePath = getFilePath($file->document_url);

    // Get the file size
    $fileSize = $this->formatFileSize($this->getFileSize($file->document_url)); // Use the updated function for file size


            // Get the number of members shared with
           // $sharedCount = $file->users()->count();  // Assuming relationship is defined
           $sharedCount = DocumentHasUserFiles::where('category_id', $file->category_id)->where('user_id', $file->user_id)->count();

            // Format the date
            $createdDate = Carbon::parse($file->created_at);
            $dateFormatted = $this->formatDate($createdDate);

            $fileUrl = $file->document_url;

            $files[] = [
                'category_name' => $file->cName,
                'file_title' => $file->title,
                'file_size' => $fileSize,
                'shared_count' => $sharedCount,
                'date' => $dateFormatted,
                'file_url' => $fileUrl,
                'catId' => $file->cId,
                'lock_code' => $file->lock_code,

            ];
        }
    //}

    $shared_documents1 = Documents::where('created_by', auth()->user()->id)
       ->limit(3)
        ->get();

    $shared_documents = [];
    //foreach ($shared_documents1 as $shared_documents2) {
        // Get the files in this category
// Get the files shared for this document, join with Documents table to get the document_url
$categoryFiles1 = DocumentHasUser::join('documents_manager', 'documents_manager.id', '=', 'documents_has_users.document_id')
->where('documents_has_users.user_id', auth()->user()->id)
//->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
//->where('documents_manager.department_id', auth()->user()->staff->department_id)
->select('documents_has_users.lock_code','documents_has_users.document_id as document_id','documents_manager.id as d_id','documents_manager.document_url', 'documents_has_users.created_at', 'documents_manager.title', 'documents_has_users.user_id')
->limit(3)
->orderBy('documents_manager.id', 'desc')
->get();        
foreach ($categoryFiles1 as $file1) {
            // Get the file size
            //$filePath1 = getFilePath($file1->document_url);
            //$fileSize1 = $this->formatFileSize(filesize($filePath1));
            // Get the file path
    $filePath1 = getFilePath($file1->document_url);

    // Get the file size
    $fileSize1 = $this->formatFileSize($this->getFileSize($file1->document_url)); // Use the updated function for file size


            // Get the number of members shared with
            //$sharedCount1 = $file1->users()->count();  // Assuming relationship is defined
            $sharedCount1 = DocumentHasUser::where('document_id', $file1->document_id)->where('user_id', $file1->user_id)->count();

            // Format the date
            $createdDate1 = Carbon::parse($file1->created_at);
            $dateFormatted1 = $this->formatDate($createdDate1);

            $fileUrl1 = $file1->document_url;

            $shared_documents[] = [
                'file_size' => $fileSize1,
                'shared_count' => $sharedCount1,
                'date' => $dateFormatted1,
                'file_url' => $fileUrl1,
                'd_id' => $file1->d_id,
                'lock_code' => $file1->lock_code,
            ];
        }
   // }

   $dCats = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();
   // Retrieve the last document category for the user's department and branch
$last_no = DocumentsCategory::where('department_id', Auth()->user()->staff->department_id)
->where('branch_id', Auth()->user()->staff->branch_id)
->latest('created_at')  // Ensure sorting by creation date, latest first
->first();

// Check if the last document category exists and if the 'name' is numeric
if ($last_no && is_numeric($last_no->name)) {
// Increment the last numeric name by 1
$fileNumber = (int) $last_no->name + 1;
} else {
// If not numeric or no previous category, set the default file number
$fileNumber = 10001;
}

        return view('documents.documents_by_files', compact('fileNumber','dCats','categoryFiles1','shared_documents','files','total_files','sharedDocument','sub_title','title','page_title','priorities','userData','departments','mergedDocuments','documents_categories1','documents_categories', 'documents', 'users', 'roles'));
    }

    

    private function getFileSize($documentPath)
{
    try {
        // For local files, you can use filesize directly
            // Check if the file exists before trying to get the size
            $filePath = public_path($documentPath);
            if (file_exists($filePath)) {
                return filesize($filePath); // Local file size
            } else {
                return 0; // File doesn't exist, return 0 size
            }
        
    } catch (\Exception $e) {
        // Handle any errors (e.g., S3 service down, etc.) and return 0
        return 0;
    }
}



    // Helper method to format file size
private function formatFileSize($bytes)
{
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

// Helper method to format dates as "Today", "Tomorrow", etc.
private function formatDate($date)
{
    $now = Carbon::now();

    if ($date->isToday()) {
        return 'Today';
    } elseif ($date->isTomorrow()) {
        return 'Tomorrow';
    } elseif ($date->isYesterday()) {
        return 'Yesterday';
    } else {
        return $date->diffForHumans();  // e.g., "2 days ago"
    }
}
    public function viewDocumentsByFileNoShared(Request $request){

        $userId = Auth::user()->id;

        $userIds = DocumentHasUser::get()->pluck('user_id');
        $loggedInUserId = auth()->id(); // Get the ID of the logged-in user

        $user_id = $userIds->contains($loggedInUserId);

     
            $departments = Department::where('id', Auth()->user()->staff->department_id)->get();

            // Priority ID from the request (if any)
    $priorityId = $request->input('priority_id');

    $prioritiesId1 = DocumentHasUserFiles::where('priority_id',  $priorityId)->first();
            // Default query for documents
            
    $documents_cat = DocumentsCategory::orderBy('documents_categories.created_at', 'desc')
    ->join('documents_has_users_files', 'documents_has_users_files.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_categories.created_at as createdAt',
        'documents_categories.id as catId',
        'documents_categories.name as cat_name',
        'documents_categories.description as cat_description',
        'documents_has_users_files.allow_share',
        'documents_has_users_files.is_download',
        'documents_has_users_files.priority_id', // Include priority_id in the select
        'documents_has_users_files.lock_code',
    )
    ->where('documents_has_users_files.user_id', auth()->user()->id)
    ->where(function ($query) {
        $query->whereNull('documents_has_users_files.start_date')
              ->orWhere(function ($query) {
                  $query->whereNotNull('documents_has_users_files.start_date')
                        ->whereNotNull('documents_has_users_files.end_date')
                        ->whereDate('documents_has_users_files.start_date', '<=', now())
                        ->whereDate('documents_has_users_files.end_date', '>=', now());
              });
    })
    ->when($priorityId, function ($query) use ($priorityId) {
        // Apply priority filter if priority_id is provided
        return $query->where('documents_has_users_files.priority_id', $priorityId);
    })
    ->latest('documents_categories.created_at')
    ->get();

    $prioritiesId = DocumentHasUser::where('priority_id',  $priorityId)->first();

    $pId = $request->input('p_id');
    $tId = $request->input('t_id');
$documents = Documents::orderBy('document_created_at', 'desc')
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
    ->select(
        'documents_manager.created_at as document_created_at',
        'documents_manager.id as d_id',
        'documents_manager.title',
        'documents_manager.document_url',
        'departments.name as dep_name',
        'documents_has_users.allow_share',
        'documents_has_users.is_download',
        'documents_has_users.priority_id', // Include priority_id in the select
        'documents_has_users.lock_code',
    )
    ->where(function ($query) {
        $query->whereNull('documents_has_users.start_date')
              ->orWhere(function ($query) {
                  $query->whereNotNull('documents_has_users.start_date')
                        ->whereNotNull('documents_has_users.end_date')
                        ->whereDate('documents_has_users.start_date', '<=', now())
                        ->whereDate('documents_has_users.end_date', '>=', now());
              });
    })
    ->when($pId, function ($query) use ($pId) {
        // Apply priority filter if priority_id is provided
        return $query->where('documents_manager.status', '1');
    })
    ->when($tId, function ($query) use ($tId) {
        // Apply priority filter if priority_id is provided
        return $query->where('documents_manager.status', '2');
    })
    ->latest('documents_manager.created_at')
    ->groupBy('documents_has_users.lock_code','documents_has_users.priority_id','documents_has_users.is_download', 'documents_has_users.allow_share', 'departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
    ->where('documents_has_users.user_id', auth()->user()->id)
    ->get();
        
        // Merging both collections
        // Merge categories and documents into one collection
        if(empty($request->input('c_id')) && empty($request->input('d_id')) && empty($request->input('p_id')) && empty($request->input('t_id'))){
            $mergedDocuments = $documents_cat->map(function ($category) {
        return [
            'type' => 'category', // Mark as a category
            'catId' => $category->catId,
            'allow_share' => $category->allow_share,
            'is_download' => $category->is_download,
            'd_id' => null,
            'cat_name' => $category->cat_name,
            'description' => $category->cat_description,
            'createdAt' => $category->createdAt,
            'created_at' => $category->createdAt,
            'document_url' => null, // Categories don't have document URLs
            'dep_name' => null, // Categories don't have a department name
            'lock_code' => $category->lock_code,
        ];
    });
        }
        if(!empty($request->input('p_id')) || !empty($request->input('t_id'))){
    $mergedDocuments = $documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    });
}

    $documentsCat = DocumentHasUserFiles::where('user_id', auth()->user()->id)->first();
    $documentId = DocumentHasUser::where('user_id',  auth()->user()->id)->first();

    if(!empty($documentsCat)){
        if(!empty($prioritiesId) && empty($prioritiesId1)){
// Add documents to the merged collection
// First, check if $documents_cat is empty.
if ($documents_cat->isEmpty()) {
    // If $documents_cat is empty, use $documents as fallback.
    $mergedDocuments = $documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    });
} else {
    // If $documents_cat is not empty, map it first.
    $mergedDocuments = $documents_cat->map(function ($document) {
        return [
            'type' => 'category_document', // Mark as a category document
            'd_id' => $document->d_id,
            'catId' => $document->catId,
            'cat_name' => $document->cat_name,
            'description' => $document->cat_description,
            'createdAt' => $document->createdAt,
            'created_at' => $document->createdAt,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'document_created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    });
    
    // Merge $documents_cat and $documents.
    $mergedDocuments = $mergedDocuments->merge($documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    }));
}

        }else{
            //here
            if(!empty($request->input('c_id'))){
            $mergedDocuments = $documents_cat->map(function ($document) {
                return [
                    'type' => 'document', // Mark as a document
                    'd_id' => $document->d_id,
                    'catId' => $document->catId,
                    'cat_name' => $document->cat_name,
                    'description' => $document->cat_description,
                    'createdAt' => $document->createdAt,
                    'created_at' => $document->createdAt,
                    'name' => $document->title,
                    'allow_share' => $document->allow_share,
                    'is_download' => $document->is_download,
                    'document_created_at' => $document->document_created_at,
                    'document_url' => $document->document_url,
                    'dep_name' => $document->dep_name,
                    'lock_code' => $document->lock_code,
                ];
            });
        }
        if(!empty($request->input('d_id'))){
            $mergedDocuments = $documents->map(function ($document) {
                return [
                    'type' => 'document', // Mark as a document
                    'd_id' => $document->d_id,
                    'name' => $document->title,
                    'allow_share' => $document->allow_share,
                    'is_download' => $document->is_download,
                    'description' => null, // Documents don't have descriptions in this case
                    'document_created_at' => $document->document_created_at,
                    'created_at' => $document->document_created_at,
                    'document_url' => $document->document_url,
                    'dep_name' => $document->dep_name,
                    'lock_code' => $document->lock_code,
                ];
            });
        }
        if(empty($request->input('c_id')) && empty($request->input('d_id')) && empty($request->input('p_id')) && empty($request->input('t_id'))){
            // First, check if $documents_cat is empty.
if ($documents_cat->isEmpty()) {
    // If $documents_cat is empty, use $documents as fallback.
    $mergedDocuments = $documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    });
} else {
    // If $documents_cat is not empty, map it first.
    $mergedDocuments = $documents_cat->map(function ($document) {
        return [
            'type' => 'category_document', // Mark as a category document
            'd_id' => $document->d_id,
            'catId' => $document->catId,
            'cat_name' => $document->cat_name,
            'description' => $document->cat_description,
            'createdAt' => $document->createdAt,
            'created_at' => $document->createdAt,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'document_created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    });
    
    // Merge $documents_cat and $documents.
    $mergedDocuments = $mergedDocuments->merge($documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    }));
}

        }

        }
        
}



if(!empty($request->input('d_id') && !empty($documentId))){
    // Add documents to the merged collection
    $mergedDocuments = $documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    });
}else if(!empty($request->input('c_id') && !empty($documentsCat))){
    // Add documents to the merged collection
    $mergedDocuments = $documents_cat->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'catId' => $document->catId,
            'cat_name' => $document->cat_name,
            'description' => $document->cat_description,
            'createdAt' => $document->createdAt,
            'created_at' => $document->createdAt,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'document_created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    });
}else{
    // First, check if $documents_cat is empty.
if ($documents_cat->isEmpty()) {
    if(empty($request->input('c_id')) && empty($request->input('d_id')) && empty($request->input('p_id')) && empty($request->input('t_id'))){

    // If $documents_cat is empty, use $documents as fallback.
    $mergedDocuments = $documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    });
}else{
    $mergedDocuments = $documents_cat->map(function ($document) {
        return [
            'type' => 'category_document', // Mark as a category document
            'd_id' => $document->d_id,
            'catId' => $document->catId,
            'cat_name' => $document->cat_name,
            'description' => $document->cat_description,
            'createdAt' => $document->createdAt,
            'created_at' => $document->createdAt,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'document_created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    });
}
} else {
    // If $documents_cat is not empty, map it first.
    $mergedDocuments = $documents_cat->map(function ($document) {
        return [
            'type' => 'category_document', // Mark as a category document
            'd_id' => $document->d_id,
            'catId' => $document->catId,
            'cat_name' => $document->cat_name,
            'description' => $document->cat_description,
            'createdAt' => $document->createdAt,
            'created_at' => $document->createdAt,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'document_created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    });
    
    // Merge $documents_cat and $documents.
    $mergedDocuments = $mergedDocuments->merge($documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    }));
}

}

    // Sort the merged collection by created_at (most recent first)
    $mergedDocuments = $mergedDocuments->sortByDesc('created_at');

        
                if (Auth::user()->level && Auth::user()->level->id == 19) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 18) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 17) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 17
                ');
            
                $users3 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.department_id = ?
            ', [auth()->user()->staff->department_id]);
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->merge($users3)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 3) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
            
                $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.branch_id = ?
            ', [auth()->user()->staff->branch_id]);
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                
                } else{

                    if(auth()->user()->staff && auth()->user()->staff->branch_id == 23){
                        $users1 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE users.level_id = 17
                    ');
                    
                    $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE employees.department_id = ?
                ', [auth()->user()->staff->department_id]);
                        } else{
                            $users1 = DB::select('
                            SELECT users.id as id, users.name
                            FROM users
                            JOIN employees ON users.id = employees.user_id
                            WHERE users.level_id = 3
                        ');
                        
                        $users2 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE employees.branch_id = ?
                    ', [auth()->user()->staff->branch_id]);
                        }
                   
                    
                    
                    // Combine the results of all queries into one collection
                    $userData = collect($users1)
                        ->merge($users2)
                        ->map(function ($user) {
                            return [
                                'id' => $user->id,
                                'name' => $user->name,
                            ];
                        });
                }

                $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->whereNull('parent_id')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();
                $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->whereNull('parent_id')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

        


        $roles = $this->roleRepository->all()->pluck('name', 'id');
        
        $users = $userData->pluck('name', 'id');

        // Fetch all priorities
        $priorities = Priority::all(); 

        $page_title = "My Files / Documents";
        $title =  'Manage Files / Documents';
        $sub_title = 'My Files / Documents';
//        $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

        return view('documents.documents_by_files_shared', compact('sub_title','title','page_title','priorities','userData','departments','mergedDocuments','documents_categories1','documents_categories', 'documents', 'users', 'roles'));
    }


    public function postDocumentsByFileNo(Request $request){
        
// Validate the request to ensure category_id is present
$request->validate([
    'category_id' => 'required|integer|exists:documents_categories,id', // Adjust based on your category table
]);
        
$userId = Auth::user()->id;

        $userIds = DocumentHasUser::get()->pluck('user_id');
        $loggedInUserId = auth()->id(); // Get the ID of the logged-in user

        $user_id = $userIds->contains($loggedInUserId);

        if (Auth::user()->hasRole('super-admin')) {
            // The logged-in user ID exists in the $userIds array
            //$documents = $this->documentRepository->paginate(10);
            $documents = \App\Models\Documents::query()
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_categories.id as category_id',
        'documents_categories.name as category_name',
        'documents_manager.created_at as document_created_at',
        'documents_manager.id as d_id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_categories.description as doc_description',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
       // 'documents_has_users.is_download',
       // 'documents_has_users.allow_share',
       // 'documents_has_users.user_id',
       // 'documents_has_users.assigned_by',
       // DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name')
    )
    ->latest('documents_manager.created_at')
    //->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
    ->groupBy('departments.name','documents_categories.description','documents_manager.document_url','documents_manager.title','documents_categories.id', 'documents_categories.name', 'documents_manager.created_at', 'documents_manager.id') // Include the nonaggregated column in the GROUP BY clause
    //->with('createdBy')
    ->where('documents_manager.department_id', auth()->user()->staff->department_id)
                 ->where('documents_manager.category_id', $request->category_id)
    ->get();

    $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });
   

        } else if (Auth::user()->level && Auth::user()->level->id == 20) {
            // The logged-in user ID exists in the $userIds array
            //$documents = $this->documentRepository->paginate(10);
            $documents = \App\Models\Documents::query()
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_categories.id as category_id',
        'documents_categories.name as category_name',
        'documents_manager.created_at as document_created_at',
        'documents_manager.id as d_id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_categories.description as doc_description',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
       // 'documents_has_users.is_download',
       // 'documents_has_users.allow_share',
       // 'documents_has_users.user_id',
       // 'documents_has_users.assigned_by',
       // DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name')
    )
    ->latest('documents_manager.created_at')
    ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
    ->groupBy('departments.name','documents_categories.description','documents_manager.document_url','documents_manager.title','documents_categories.id', 'documents_categories.name', 'documents_manager.created_at', 'documents_manager.id') // Include the nonaggregated column in the GROUP BY clause
    //->with('createdBy')
    ->where('documents_manager.department_id', auth()->user()->staff->department_id)
                 ->where('documents_manager.category_id', $request->category_id)
    ->get();

    $users1 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 19
    ');
    $users2 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 18
    ');
    
    $users3 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 17
    ');
    $users4 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE users.level_id = 3
    ');
    
    $users5 = DB::select('
        SELECT users.id as id, users.name
        FROM users
        JOIN employees ON users.id = employees.user_id
        WHERE employees.department_id = 19
    ');
    
    // Combine the results of all queries into one collection
    $userData = collect($users1)
        ->merge($users2)
        ->merge($users3)
        ->merge($users4)
        ->merge($users5)
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

   

        } else {
            // The logged-in user ID does not exist in the $userIds array
                $documents = \App\Models\Documents::query()
                ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_categories.id as category_id',
        'documents_categories.name as category_name',
        'documents_manager.created_at as document_created_at',
        'documents_manager.id as d_id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_categories.description as doc_description',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
         )
                ->where('documents_manager.created_by', $userId)
                ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
                ->latest('documents_manager.created_at')
                ->groupBy('documents_categories.description','documents_manager.document_url','documents_manager.title','documents_categories.id', 'documents_categories.name', 'documents_manager.created_at', 'documents_manager.id') // Include the nonaggregated column in the GROUP BY clause
                //->with('createdBy')
                 ->where('documents_manager.department_id', auth()->user()->staff->department_id)
                 ->where('documents_manager.category_id', $request->category_id)
                ->get();


                if (Auth::user()->level && Auth::user()->level->id == 19) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 18) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 17) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
                
                $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 17
                ');
            
                $users3 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.department_id = ?
            ', [auth()->user()->staff->department_id]);
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->merge($users3)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                } else if (Auth::user()->level && Auth::user()->level->id == 3) {
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 20
                ');
            
                $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.branch_id = ?
            ', [auth()->user()->staff->branch_id]);
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
                
                } else{

                    if(auth()->user()->staff && auth()->user()->staff->branch_id == 23){
                        $users1 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE users.level_id = 17
                    ');
                    
                    $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE employees.department_id = ?
                ', [auth()->user()->staff->department_id]);
                        } else{
                            $users1 = DB::select('
                            SELECT users.id as id, users.name
                            FROM users
                            JOIN employees ON users.id = employees.user_id
                            WHERE users.level_id = 3
                        ');
                        
                        $users2 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE employees.branch_id = ?
                    ', [auth()->user()->staff->branch_id]);
                        }
                   
                    
                    
                    // Combine the results of all queries into one collection
                    $userData = collect($users1)
                        ->merge($users2)
                        ->map(function ($user) {
                            return [
                                'id' => $user->id,
                                'name' => $user->name,
                            ];
                        });
                }


        }


        $roles = $this->roleRepository->all()->pluck('name', 'id');
        
        $users = $userData->pluck('name', 'id');

        $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

        $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

        return view('documents.documents_by_files', compact('documents_categories1','documents_categories', 'documents', 'users', 'roles'));
         
}



    public function sharedUserFile()
    {
        /* /* if (!checkPermission('create document')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */


        $userId = Auth::user()->id;


        if (Auth::user()->hasRole('super-admin')) {

            $documents = DB::table('documents_has_users_files')
    ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users_files.document_id')
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('users', 'documents_has_users_files.user_id', '=', 'users.id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_manager.title',
        'documents_has_users_files.created_at as createdAt',
        'documents_categories.name as category_name',
        'documents_has_users_files.start_date',
        'documents_has_users_files.end_date',
        'documents_has_users_files.allow_share',
        'documents_has_users_files.is_download',
        'documents_has_users_files.user_id',
        'documents_has_users_files.assigned_by',
        'documents_manager.document_url as document_url',
        'documents_manager.id as d_m_id',
        'documents_categories.id as d_m_c_id',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users_files.user_id) AS assigned_to_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users_files.assigned_by) AS assigned_by_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
    )
    ->latest('documents_has_users_files.created_at')
    //->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
    ->groupBy(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_has_users_files.start_date',
        'documents_has_users_files.end_date',
        'documents_manager.id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_has_users_files.id',
        'documents_has_users_files.created_at',
        'documents_categories.name',
        'documents_has_users_files.allow_share',
        'documents_has_users_files.is_download',
        'documents_has_users_files.user_id',
        'documents_has_users_files.assigned_by',
        'departments.name',
        'documents_manager.created_by',
    )
    ->get();

    
    $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

        } else if (Auth::user()->level && Auth::user()->level->id == 20) {

            /* $documents = DB::table('documents_has_users_files')
    ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users_files.document_id')
    ->join('users', 'documents_has_users_files.user_id', '=', 'users.id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select('documents_has_users_files.*', 'documents_manager.*', 'users.*', 'documents_has_users_files.created_at as createdAt', 'documents_categories.name as category_name', 'documents_manager.category_id as d_m_c_id')
    ->latest('documents_has_users_files.created_at')
    ->groupBy('documents_manager.document_url','documents_manager.category_id','documents_has_users_files.id','documents_has_users_files.created_at', 'documents_categories.name', 'documents_manager.category_id')
    ->get(); */
   
    $documents = DB::table('documents_has_users_files')
    ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users_files.document_id')
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('users', 'documents_has_users_files.user_id', '=', 'users.id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_manager.title',
        'documents_has_users_files.created_at as createdAt',
        'documents_categories.name as category_name',
        'documents_has_users_files.start_date',
        'documents_has_users_files.end_date',
        'documents_has_users_files.allow_share',
        'documents_has_users_files.is_download',
        'documents_has_users_files.user_id',
        'documents_has_users_files.assigned_by',
        'documents_manager.document_url as document_url',
        'documents_manager.id as d_m_id',
        'documents_categories.id as d_m_c_id',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users_files.user_id) AS assigned_to_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users_files.assigned_by) AS assigned_by_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
    )
    ->latest('documents_has_users_files.created_at')
    ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
    ->groupBy(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_has_users_files.start_date',
        'documents_has_users_files.end_date',
        'documents_manager.id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_has_users_files.id',
        'documents_has_users_files.created_at',
        'documents_categories.name',
        'documents_has_users_files.allow_share',
        'documents_has_users_files.is_download',
        'documents_has_users_files.user_id',
        'documents_has_users_files.assigned_by',
        'departments.name',
        'documents_manager.created_by',
    )
    ->get();

    
    $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

        } else {
           

    //here
    $documents = DB::table('documents_has_users_files')
    ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_users_files.document_id')
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('users', 'documents_has_users_files.user_id', '=', 'users.id')
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_manager.category_id',
        'documents_categories.id',
        'documents_manager.title',
        'documents_has_users_files.created_at as createdAt',
        'documents_categories.name as category_name',
        'documents_has_users_files.start_date',
        'documents_has_users_files.end_date',
        'documents_has_users_files.allow_share',
        'documents_has_users_files.is_download',
        'documents_has_users_files.user_id',
        'documents_has_users_files.assigned_by',
        'documents_manager.document_url as document_url',
        'documents_manager.id as d_m_id',
        'documents_categories.id as d_m_c_id',
        'documents_categories.name as cat_name',
        'departments.name as dep_name',
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users_files.user_id) AS assigned_to_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users_files.assigned_by) AS assigned_by_name'),
        DB::raw('(SELECT name FROM users WHERE users.id = documents_manager.created_by) AS created_by_name')
    )
    ->latest('documents_has_users_files.created_at')
    ->groupBy(
        'documents_categories.id',
        'documents_has_users_files.start_date',
        'documents_has_users_files.end_date',
        'documents_manager.id',
        'documents_manager.title',
        'documents_manager.document_url',
        'documents_has_users_files.id',
        'documents_has_users_files.created_at',
        'documents_categories.name',
        'documents_has_users_files.allow_share',
        'documents_has_users_files.is_download',
        'documents_has_users_files.user_id',
        'documents_has_users_files.assigned_by',
        'departments.name',
        'documents_manager.created_by',
        'documents_manager.category_id',
    )
    ->where('documents_has_users_files.user_id', '=', $userId)
    ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
    ->get();
   
    

    
        // Fetch roles
$roles2 = Role::whereIn('id', [2])->get();

// Get the users who have any of these roles
$users1 = User::whereHas('roles', function ($query) use ($roles2) {
    $query->whereIn('id', $roles2->pluck('id'));
})->get(['id', 'name']);

$users2 = DB::table('users')
    ->join('staff', 'employees.user_id', '=', 'users.id')
    ->join('departments', 'departments.id', '=', 'employees.department_id')
    ->select('users.id as id', 'users.name as first_name')
    ->where('employees.department_id', '=', Auth::user()->staff->department_id)
    ->where('employees.branch_id', '=', Auth::user()->staff->branch_id)
    ->get();

// Combine the data
$mergedUsers = $users2->merge($users1);

// Process the data for display
$userData = $mergedUsers->map(function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});

        
        }

        // Now that you have the documents, you can load the category relationship
$documentIds = $documents->pluck('d_m_c_id')->toArray();
$categories = DocumentsCategory::whereIn('id', $documentIds)->get()->keyBy('id');


        $roles = $this->roleRepository->all()->pluck('name', 'id');
        // $roles->prepend('Select role', '');
        // $departments->prepend('Select department', '');
       /*  $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }); */

        $users = $userData->pluck('name', 'id');

        return view('documents.document_shared_user_file', compact('documents', 'users', 'roles', 'categories'));
    }


    public function sharedRole()
    {
        /* /* if (!checkPermission('create document')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */



        // Get the currently authenticated user
        $user = Auth::user();
        // Get the role IDs of the user
        $roleIds = $user->getRoleNames()->map(function ($roleName) {
            return Role::where('name', $roleName)->first()->id;
        });

        // If the user has only one role, you can directly access it like this:
        $roleId = $roleIds->first();

        if (Auth::user()->hasRole('super-admin') || Auth::user()->level && Auth::user()->level->id == 20) {
            $documents = DB::table('documents_has_roles')
                ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_roles.document_id')
                ->join('roles', 'roles.id', '=', 'documents_has_roles.role_id')
                ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
                ->select(
                    'documents_has_roles.start_date as start_date',
                    'documents_has_roles.end_date as end_date',
                    'documents_manager.title',
                    'documents_manager.id as id',
                    'documents_manager.document_url',
                    'roles.name as role_name',
                    'documents_categories.name as category_name',
                    'documents_manager.category_id as category_id',
                )
                ->latest('documents_manager.created_at')
                ->get();
        } else {
            $documents = DB::table('documents_has_roles')
                ->join('documents_manager', 'documents_manager.id', '=', 'documents_has_roles.document_id')
                ->join('roles', 'roles.id', '=', 'documents_has_roles.role_id')
                ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
                ->select(
                    'documents_has_roles.start_date as start_date',
                    'documents_has_roles.end_date as end_date',
                    'documents_manager.title',
                    'documents_manager.id as id',
                    'documents_manager.document_url',
                    'roles.name as role_name',
                    'documents_categories.name as category_name',
                    'documents_manager.category_id as category_id',
                )
                ->latest('documents_manager.created_at')
                ->where('documents_has_roles.role_id', $roleId)
                ->get();
        }

        // Now that you have the documents, you can load the category relationship
$documentIds = $documents->pluck('document_id')->toArray();
$categories = DocumentsCategory::whereIn('id', $documentIds)->get()->keyBy('id');

        $roles = $this->roleRepository->all()->pluck('name', 'id');
        // $roles->prepend('Select role', '');
        // $departments->prepend('Select department', '');
        $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

        $users = $userData->pluck('name', 'id');


        return view('documents.document_shared_role', compact('documents', 'users', 'roles', 'categories'));
    }

    public function shareDocument(Request $request, $id)
    {
        /* if (Auth::user()->hasRole('super-admin') || Auth::user()->level && Auth::user()->level->id == 18) {
        $share_documents = DB::table('documents_manager')
            ->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
            ->join('users', 'documents_has_users.user_id', '=', 'users.id') // Join with the 'users' table
            ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select('documents_manager.*', 'documents_has_users.*', 'users.email as uemail', 'users.name as firstName', 'users.last_name as lastName', 'documents_manager.created_at as createdAt', 'documents_categories.name as category_name', 'documents_manager.document_url as doc_url', 'documents_manager.description as doc_desc', 'documents_has_users.is_download')
            ->latest('documents_manager.created_at')
            ->where('documents_manager.id', $id)
            ->get();
        }else{
            $share_documents = DB::table('documents_manager')
            ->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
            ->join('users', 'documents_has_users.user_id', '=', 'users.id') // Join with the 'users' table
            ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select('documents_manager.*', 'documents_has_users.*', 'users.email as uemail', 'users.name as firstName', 'users.last_name as lastName', 'documents_manager.created_at as createdAt', 'documents_categories.name as category_name', 'documents_manager.document_url as doc_url', 'documents_manager.description as doc_desc', 'documents_has_users.is_download')
            ->latest('documents_manager.created_at')
            ->where('documents_has_users.user_id', Auth()->user()->id)
            ->where('documents_manager.id', $id)
            ->get();
        } */
        $share_documents = DB::table('documents_manager')
        //->join('documents_has_roles', 'documents_has_roles.role_id', '=', 'roles.id')
        ->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
        ->join('users', 'documents_has_users.user_id', '=', 'users.id') // Join with the 'users' table
        ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
        //->join('roles', 'documents_has_roles.role_id', '=', 'roles.id')
        ->select('documents_manager.*', 'documents_has_users.*', 'users.email as uemail', 'users.name as firstName', 'users.last_name as lastName', 'documents_manager.created_at as createdAt', 'documents_categories.name as category_name', 'documents_manager.document_url as doc_url', 'documents_manager.description as doc_desc', 'documents_has_users.is_download')
        ->latest('documents_manager.created_at')
        ->where('documents_manager.id', $id)
        ->get();

        /* $share_documents123 = DB::table('documents_manager')
    // Join documents_has_users table
    ->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
    // Join users table to get user information
    ->join('users', 'documents_has_users.user_id', '=', 'users.id')
    // Join signatures table to get the signature_data
    ->join('signatures', 'signatures.user_id', '=', 'users.id')  // Join the signatures table on user_id
    // Join documents_categories table to get category information
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    // Select necessary fields from documents_manager, documents_has_users, users, and signatures
    ->select(
        'documents_manager.*',
        'documents_has_users.*',
        'users.email as uemail',
        'users.name as firstName',
        'users.last_name as lastName',
        'documents_manager.created_at as createdAt',
        'documents_categories.name as category_name',
        'documents_manager.document_url as doc_url',
        'documents_manager.description as doc_desc',
        'documents_has_users.is_download',
        'signatures.signature_data' // Select the signature_data from the signatures table
    )
    // Order by created_at
    ->latest('documents_manager.created_at')
    // Filter by document ID
    ->where('documents_manager.id', $id)
    ->get(); */

        return response()->json($share_documents);
        /* try {
    $share_documents = DB::table('documents_manager')
        ->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
        ->join('users', 'documents_has_users.user_id', '=', 'users.id')
        ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
        ->leftJoin('model_has_roles', function ($join) {
            $join->on('model_has_roles.model_id', '=', 'users.id')
                 ->where('model_has_roles.model_type', '=', 'App\User');
        })
        ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->join('documents_has_roles', 'documents_has_roles.document_id', '=', 'documents_manager.id')
        ->select('documents_manager.*', 'documents_has_users.*', 'users.email as uemail', 'users.name as firstName', 'users.last_name as lastName', 'roles.name as role_name', 'documents_manager.created_at as createdAt', 'documents_categories.name as category_name', 'documents_manager.document_url as doc_url', 'documents_manager.description as doc_desc')
        ->latest('documents_manager.created_at')
        ->where('documents_manager.id', $id)
        ->get();

    return response()->json($share_documents);
} catch (\Exception $e) {
    return response()->json(['error' => $e->getMessage()], 500);
} */
    }

    public function documentsComment(Request $request, $id)
    {
        $documentHistory = DB::table('documents_comments')
            ->join('users', 'documents_comments.created_by', '=', 'users.id')
            ->join('documents_manager', 'documents_comments.document_id', '=', 'documents_manager.id')
            ->select('documents_comments.*', 'documents_comments.created_at as createdAt', 'users.name as firstName', 'users.last_name as lastName', 'documents_manager.document_url as doc_url')
            ->where('documents_comments.document_id', $id)
            ->get();

        return response()->json($documentHistory);
    }

    public function sendEmail(Request $request)
    {
        try {
            $subject = $request->input('subject');
            $body = $request->input('body');
            $attachment = $request->input('attachment');

            // Send the email using the SendEmail Mailable
            Mail::to($request->input('to'))->send(new SendEmail($subject, $body, $attachment));

            return redirect()->back()->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email. Please try again later. ERROR:' . $e);
        }
    }

    // public function getusersbydept($deptid, $branchid)
    // {
    //     if (!is_array($deptid)) {
    //         $deptid = [$deptid];
    //     }
    //     $users = Staff::whereIn('department_id', $deptid)
    //         ->join('users', 'users.id', 'staff.id')
    //         ->where('employees.branch_id', $branchid)
    //         ->get();
    //     return response()->json($users);
    // }
    public function getusersbydept(Request $request)
    {
        $users = collect();
        $deptids = $request->input('deptid');
        $branchids = $request->input('branchid');

        foreach ($deptids as $deptid) {
            foreach ($branchids as $branchid) {
                if ($deptid == '' && $branchid == '') {
                    // Fetch all users
                    $users = $users->merge(
                        Staff::join('users', 'employees.user_id', 'users.id')->get()
                    );
                } elseif ($deptid == '') {
                    // Fetch users based on branch
                    $users = $users->merge(
                        Staff::where('branch_id', $branchid)
                            ->join('users', 'employees.user_id', 'users.id')->get()
                    );
                } elseif ($branchid == '') {
                    // Fetch users based on department
                    $users = $users->merge(
                        Staff::where('department_id', $deptid)
                            ->join('users', 'employees.user_id', 'users.id')->get()
                    );
                } else {
                    // Fetch users based on both department and branch
                    $users = $users->merge(
                        Staff::where('department_id', $deptid)
                            ->where('branch_id', $branchid)
                            ->join('users', 'employees.user_id', 'users.id')->get()
                    );
                }
            }
        }

        return response()->json($users);
    }






    public function documentsByUsers()
    {
        /* if (!checkPermission('create document')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */



        $documents = $this->documentRepository->paginate(10);

        return view('documents.document_user', compact('documents'));
    }

    public function documentsByRole(Request $request)
    {
        /* if (!checkPermission('create document')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */



        $documents = $this->documentHasRoleRepository->paginate(10);

        return view('documents.document_role', compact('documents'));
    }

    /**
     * Display a listing of the Document assigned to a specific user.
     */
    public function viewDocumentAssignedToUser()
    {
        $user = Auth::user();
        $memos_has_user = $this->documentHasUserRepository->findByUser($user->id)->paginate(10);

        return view('documentmanager::memos.memos_assigned_to_user')
            ->with(['user' => $user, 'memos_has_user' => $memos_has_user]);
    }

    /**
     * Show the form for creating a new Memo.
     */
    public function create()
    {
        /* if (!checkPermission('create document')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */

       
        //$categories = $this->documentsCategoryRepository->all()->pluck('name', 'id');
        if (Auth::user()->hasRole('super-admin') || Auth::user()->level && Auth::user()->level->id == 20) {
        $categories1 = DB::table('documents_categories')
            ->join('departments', 'departments.id', '=', 'documents_categories.department_id')
            //->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select('documents_categories.description as subject', 'departments.name as department_name', 'documents_categories.id as documents_category_id', 'documents_categories.name as category_name', 'documents_categories.description as documents_category_description')
            //->latest('documents_categories.created_at')
            ->get();
            $categories = $categories1->map(function ($category) {
                return [
                    'id' => $category->documents_category_id,
                    'name' => $category->department_name . ' / ' .  $category->category_name . ' / ' .  $category->subject,
                ];
            })->pluck('name', 'id')->toArray();
        }else{
            $categories1 = DB::table('documents_categories')
            ->join('departments', 'departments.id', '=', 'documents_categories.department_id')
            //->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select('documents_categories.description as subject', 'departments.name as department_name', 'documents_categories.id as documents_category_id', 'documents_categories.name as category_name', 'documents_categories.description as documents_category_description')
            ->where('departments.id', Auth()->user()->staff->department_id)
            ->get();
            $categories = $categories1->map(function ($category) {
                return [
                    'id' => $category->documents_category_id,
                    'name' => $category->department_name . ' / ' .  $category->category_name . ' / ' .  $category->subject,
                ];
            })->pluck('name', 'id')->toArray();
        }
            
        //$roles = Role::pluck('name', 'id')->all();
        $roles = $this->roleRepository->all()->pluck('name', 'id');
        // $roles->prepend('Select role', '');
        // $departments->prepend('Select department', '');
        $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

        $users = $userData->pluck('name', 'id');
        //$users->prepend('Select user', '');
        $dept = Department::get();
        // dd($dept);
        $office = Branch::get();


        return view('documents.create', compact(['categories', 'users', 'roles', 'dept', 'office']));
    }

    public function shareUserDDD(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        /* $document = DocumentHasUser::where('document_id', $request->shareuser_id)->first();
        if($document){
        $document->start_date = $request->start_date;
        $document->end_date = $request->end_date;
        $document->is_download = $request->is_download;
        $document->save();
        } */

        //$document = DocumentHasUser::where('document_id', $request->shareuser_id)->first();

        // Assign to user(s)
        $users = $input['users'];
        if ($users != null) {
            $logged_in_user = Auth::user();
            foreach ($users as $key => $user_id) {
                $input_fields['user_id'] = $user_id;
                $input_fields['document_id'] = $request->shareuser_id;
                $input_fields['assigned_by'] = $logged_in_user->id;
                $input_fields['start_date'] = $request->start_date;
                $input_fields['end_date'] = $request->end_date;
                $input_fields['is_download'] = $request->is_download;
                $input_fields['allow_share'] = $request->allow_share;


                $this->documentHasUserRepository->create($input_fields);

                /* try {
                $user->notify(new MemoAssignedToUser($memo));
            } catch (\Throwable $th) { 
            } */
            }
            DocumentComment::create([
                'created_by' => $user->id,
                'document_id' => $request->shareuser_id,
                'comment' => $request->comment,
            ]);
            //$this->_assignToUsers($users, $document);
        }
        Flash::success('Documents shared successfully.');

        return redirect()->back();
    }

    public function shareUser(Request $request)
{
    $validated = $request->validate([
        // Other validation rules for other fields
        'lock_code' => ['nullable', 'string', 'max:255'],  // Nullable lock_code (string, optional)
    ]);
    $user = Auth::user();
    $input = $request->all();

    $users = $input['users'];
    if ($users != null) {
        $logged_in_user = Auth::user();
        $documentTitle = ''; // Initialize this for the notification
        $documentURL = ''; 
        $lcd = $request->lock_code;
        

        foreach ($users as $user_id) {
            $input_fields = [
                'user_id' => $user_id,
                'document_id' => $request->shareuser_id,
                'assigned_by' => $logged_in_user->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'priority_id' => $request->priority_id,
                'is_download' => $request->is_download,
                'allow_share' => $request->allow_share,
                'lock_code' => $lcd,
            ];

            // Create the document assignment
            $documentHasUser = $this->documentHasUserRepository->create($input_fields);

            $document1 = Documents::find($request->shareuser_id);
            if(!empty($document1)){
            $document1->status = "1";
            $document1->save();
            }

            if(isset($request->priority_id) && $request->priority_id != 5){
            // Fetch the document title for notification
            $document = Documents::find($request->shareuser_id);
            if ($document) {
                $documentTitle = $document->title;
                $documentId = $request->shareuser_id;
                $logged_in_user1 = $logged_in_user->name;

                // Send notification
                $notifiableUser = User::find($user_id);
                //$lockCodeId = DocumentHasUser::find($docId);
                if ($notifiableUser) {
                    Session::put('lock_code', $request->lock_code);
                    $lcd1 = $lcd ?? 0;
                    try {
                            $notifiableUser->notify(new DocumentShared($user_id, $documentTitle, $logged_in_user1, $documentId, $lcd1));
                           
                    } catch (\Throwable $th) {
                        //Log::error("Sending notification with lockCode error = " . $this->lockCode); // Debugging log
                       // throw $th;  // This will requeue the job if there's an error
                    }
                    
                }
            }

            
            Notifications::create([
                'user_id' => $user_id,
                'message' => "A document has been shared with you.",
                'action_id' => $request->shareuser_id,
            ]);
        }

      // send reminder for critical priority
        if(isset($request->priority_id) && $request->priority_id == 4){
            // Insert the reminders and get the inserted IDs
$reminderIds = [];
$reminderIds[] = DB::table('reminders')->insertGetId([
    'subject' => 'Critical Priority For Your File/Document: Action Required',
    'message' => 'This is a critical priority notification. Please take immediate action to address the issue.',
    'frequency' => 'Once',
    'reminderstart_date' => Carbon::now(),
    'reminderend_date' => Carbon::now()->addDays(1),
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now(),
]);

$reminderIds[] = DB::table('reminders')->insertGetId([
    'subject' => 'Critical Priority For Your File/Document: Immediate Attention Needed',
    'message' => 'A critical issue has been detected, and immediate attention is required to prevent further complications.',
    'frequency' => 'Once',
    'reminderstart_date' => Carbon::now(),
    'reminderend_date' => Carbon::now()->addDays(2),
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now(),
]);

$reminderIds[] = DB::table('reminders')->insertGetId([
    'subject' => 'Urgent: System Critical Update Required',
    'message' => 'The system requires an urgent update to prevent service disruption. Please update the system as soon as possible.',
    'frequency' => 'Once',
    'reminderstart_date' => Carbon::now(),
    'reminderend_date' => Carbon::now()->addDays(3),
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now(),
]);

// Now, you can insert data into reminderusers using the reminder IDs
foreach ($reminderIds as $reminderId) {
    DB::table('reminderusers')->insert([
        [
            'reminder_id' => $reminderId,
            'user_id' => $user_id, // User ID 1 (Example user)
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'reminder_id' => $reminderId,
            'user_id' => $user_id, // User ID 2 (Example user)
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'reminder_id' => $reminderId,
            'user_id' => $user_id, // User ID 3 (Example user)
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]
    ]);
}
        }

        }

    

        // Add comment to document
        DocumentComment::create([
            'created_by' => $user->id,
            'document_id' => $request->shareuser_id,
            'comment' => $request->comment,
        ]);
    }

    Flash::success('Documents shared successfully.');
    return redirect()->back();
}

    public function shareUserFile(Request $request){
        $validated = $request->validate([
            // Other validation rules for other fields
            'lock_code' => ['nullable', 'string', 'max:255'],  // Nullable lock_code (string, optional)
        ]);
        $user = Auth::user();
        $input = $request->all();

        
        // Assign to user(s)
        $users = $input['users'];
        if ($users != null) {
            $logged_in_user = Auth::user();
            foreach ($users as $key => $user_id) {
                $input_fields['user_id'] = $user_id;
                $input_fields['document_id'] = $request->document_id;
                $input_fields['category_id'] = $request->shareuser_id;
                $input_fields['assigned_by'] = $logged_in_user->id;
                $input_fields['start_date'] = $request->start_date;
                $input_fields['end_date'] = $request->end_date;
                $input_fields['priority_id'] = $request->priority_id;
                $input_fields['is_download'] = $request->is_download;
                $input_fields['allow_share'] = $request->allow_share;
                $input_fields['lock_code'] = $request->lock_code;

                //$this->documentHasUserRepository->create($input_fields);
                $this->documentHasUserFileRepository->create($input_fields);

                $document1 = Documents::find($request->document_id);
            if(!empty($document1)){
            $document1->status = "1";
            $document1->save();
            }

                if(isset($request->priority_id) && $request->priority_id != 5){
                // Fetch the document title for notification
            $document = Documents::find($request->document_id);
            if ($document) {
                $documentTitle = $document->title;
                $documentId = $request->document_id;
                $logged_in_user1 = $logged_in_user->name;

                // Send notification
                $notifiableUser = User::find($user_id);
                if ($notifiableUser) {
                    $lcd1 = $request->lock_code ?? 0;
                    try {
                        $notifiableUser->notify(new DocumentShared($user_id, $documentTitle, $logged_in_user1, $documentId, $lcd1));
                       
                } catch (\Throwable $th) {
                    //Log::error("Sending notification with lockCode error = " . $this->lockCode); // Debugging log
                   // throw $th;  // This will requeue the job if there's an error
                }
                   
                }
            }


                Notifications::create([
                    'user_id' => $user_id,
                    'message' => "A file has been shared with you.",
                    'action_id' => $request->shareuser_id,
                ]);
            }
            // send reminder for critical priority
        if(isset($request->priority_id) && $request->priority_id == 4){
                     // Insert the reminders and get the inserted IDs
$reminderIds = [];
$reminderIds[] = DB::table('reminders')->insertGetId([
    'subject' => 'Critical Priority For Your File/Document: Action Required',
    'message' => 'This is a critical priority notification. Please take immediate action to address the issue.',
    'frequency' => 'Once',
    'reminderstart_date' => Carbon::now(),
    'reminderend_date' => Carbon::now()->addDays(1),
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now(),
]);

$reminderIds[] = DB::table('reminders')->insertGetId([
    'subject' => 'Critical Priority For Your File/Document: Immediate Attention Needed',
    'message' => 'A critical issue has been detected, and immediate attention is required to prevent further complications.',
    'frequency' => 'Once',
    'reminderstart_date' => Carbon::now(),
    'reminderend_date' => Carbon::now()->addDays(2),
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now(),
]);

$reminderIds[] = DB::table('reminders')->insertGetId([
    'subject' => 'Urgent: System Critical Update Required',
    'message' => 'The system requires an urgent update to prevent service disruption. Please update the system as soon as possible.',
    'frequency' => 'Once',
    'reminderstart_date' => Carbon::now(),
    'reminderend_date' => Carbon::now()->addDays(3),
    'created_at' => Carbon::now(),
    'updated_at' => Carbon::now(),
]);

// Now, you can insert data into reminderusers using the reminder IDs
foreach ($reminderIds as $reminderId) {
    DB::table('reminderusers')->insert([
        [
            'reminder_id' => $reminderId,
            'user_id' => $user_id, // User ID 1 (Example user)
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'reminder_id' => $reminderId,
            'user_id' => $user_id, // User ID 2 (Example user)
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'reminder_id' => $reminderId,
            'user_id' => $user_id, // User ID 3 (Example user)
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]
    ]);
}
        }

            }
            
            
            DocumentCommentFile::create([
                'created_by' => $user->id,
                'document_id' => $request->shareuser_id,
                'comment' => $request->comment,
            ]);
            //$this->_assignToUsers($users, $document);
        }


        Flash::success('File shared successfully.');

        return redirect()->back();
    }


         
    public function shareRole(Request $request)
    {

        $input = $request->all();

        //$document = DocumentHasRole::where('document_id', $request->sharerole_id)->first();

        $roles = $input['roles'];
        if ($roles != null) {
            $logged_in_user = Auth::user();
            foreach ($roles as $key => $role_id) {
                $role = Role::find($role_id); // Find the role by its ID
                $input_fields['role_id'] = $role_id;
                $input_fields['document_id'] = $request->sharerole_id;
                $input_fields['assigned_by'] = $logged_in_user->id;
                $input_fields['start_date'] = $request->start_date;
                $input_fields['end_date'] = $request->end_date;
                $input_fields['is_download'] = $request->is_download;

                $this->documentHasRoleRepository->create($input_fields);

                // Additional logic if needed

            }
        }

        //$this->documentHasRoleRepository->create($input_fields);
        Flash::success('Role permissions shared successfully.');

        return redirect(route('documents_manager.sharedrole'));
    }
    public function bulkUpload()
    {
        return view('documents.bulk_upload');
    }


    public function bulkUploadData(Request $request)
    {
        // Validation rules for CSV and files
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt,application/csv,application/vnd.ms-excel|max:2048', // CSV file validation
            'files.*' => 'required|mimes:pdf,jpeg,png,jpg|max:2048', // Other files validation
        ]);
    
        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        try {
            // Handle the CSV file
            $csvFile = $request->file('csv_file');
    
            // Handle other files (uploaded files)
            $uploadedFiles = $request->file('files');
    
            // Store files temporarily in an array for FilesImport
            $storedFiles = [];
            foreach ($uploadedFiles as $file) {
                $originalName = $file->getClientOriginalName();
    
                // Normalize the uploaded file name by replacing multiple spaces with a single space
    $normalizedName = preg_replace('/\s+/', ' ', $originalName); // Replace multiple spaces with a single one
    $normalizedName = preg_replace('/[^a-zA-Z0-9\s.]/', '', $normalizedName); // Remove special characters
    // Convert to lowercase (case-insensitive comparison)
   $normalizedName = strtolower($normalizedName);
   // Replace spaces with underscores (to match the uploaded file names)
   $normalizedName = str_replace(' ', '_', $normalizedName);

    // Store the normalized file name
    $storedFiles[$normalizedName] = $file;
            }
    
            // Pass the sanitized files and category_id to the import class
            $import = new FilesImport($storedFiles, $request->category_id);
    
            // Perform the import with the CSV file
            Excel::import($import, $csvFile);
    
            // Flash success message
            flash('Files uploaded and data saved successfully.')->success();
        } catch (\Exception $e) {
            // Flash error message on exception
            flash('An error occurred during file processing: ' . $e->getMessage())->error();
            return redirect()->back();
        }
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Files uploaded and processed successfully.');
    }
    

    /**
     * Store a newly created Memo in storage.
     */
    public function store(Request $request)
{
    $user = Auth::user();
    $input = $request->all();
    $input['created_by'] = $user->id;
    
    // Prepare document input
    $document_input = [];

    if (isset($input['category_id'])) {
        $document_input['category_id'] = $input['category_id'];
        $document_input['created_by'] = $user->id;
    }

    $path = "documents"; 
    $path_folder = public_path($path);

    // Process multiple files
    if ($request->hasFile('file')) {
        // Loop over each file
        foreach ($request->file('file') as $file) {
            //$title = str_replace(' ', '', $input['title']);
            $file_name = rand() . '.' . $file->getClientOriginalExtension();

            // Check if the request is from localhost or not
            $file->move($path_folder, $file_name);
                $document_url = $path . "/" . $file_name;

            // Add document URL to the document input
            

// Get the original filename without the extension
$filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

// Remove any non-alphanumeric characters (hyphens, dots, symbols) and replace with spaces
$sanitizedTitle = preg_replace('/[^a-zA-Z0-9]+/', ' ', $filename);

// Optionally, trim spaces and ensure there's no extra space at the start or end
$sanitizedTitle = trim($sanitizedTitle);

// Store the sanitized title
$document_input['title'] = $sanitizedTitle;
            $document_input['document_url'] = $document_url;
            $document_input['document_no'] = rand(11111, 99999);
            $document_input['department_id'] = Auth()->user()->staff->department_id;
            $document_input['branch_id'] = Auth()->user()->staff->branch_id;

            // Create document record in the database
            $document = $this->documentRepository->create($document_input);

            // Save document history
            DocumentHistory::create([
                'created_by' => $user->id,
                'document_id' => $document->id,
                'document_url' => $document_url,
            ]);

            DocumentComment::create([
                'created_by' => $user->id,
                'document_id' => $document->id,
                'comment' => $request->comment,
            ]);
        }
    }

    // Process meta tags
    $metaTags = $request->input('meta_tags');
    if ($metaTags) {
        foreach ($metaTags as $tag) {
            MetaTag::create([
                'name' => $tag,
                'document_id' => $document->id,
                'created_by' => $user->id,
            ]);
        }
    }

    Flash::success('Documents saved successfully.');
    return redirect()->back();
}

public function uploadPDF(Request $request)
{
    $user = Auth::user();
    $input = $request->all();
    $input['created_by'] = $user->id;

    // Get folder and its parents. Create if path does not exist
    $document_input = [];

    if (isset($input['category_id'])) {
        $document_input['category_id'] = $input['category_id'];
    }
    $document_input['created_by'] = $user->id;
    $document_input['title'] = $input['title'];
    $document_input['document_no'] = rand(11111, 99999);
    $document_input['department_id'] = Auth()->user()->staff->department_id;
    $document_input['branch_id'] = Auth()->user()->staff->branch_id;
    $document = $this->documentRepository->create($document_input);

    // Retrieve CKEditor content (HTML)
    $ckeditorContent = $request->input('document_content');  // Get the content from CKEditor

    // Replace image sources with fully qualified URLs (if they are not already)
    $ckeditorContent = preg_replace_callback('/<img[^>]+src=["\']([^"\']+)["\']/i', function($matches) {
        // Check if the image source is a relative URL or a path
        $imagePath = $matches[1];

        // If the image is local (i.e., a relative path), convert it to a full URL
        if (filter_var($imagePath, FILTER_VALIDATE_URL) === false) {
            // You may adjust the base URL accordingly
            $imagePath = url('documents/' . $imagePath);
        }

        return str_replace($matches[1], $imagePath, $matches[0]);
    }, $ckeditorContent);

    // Check if user has a signature and prepare the signature content
    $signatureHtml = '';
    if (isset($user->signature)) {
        $signature = Signature::where('user_id', $user->id)->first();
        $signatureUrl = getFileUrlNow($signature->signature_data);
        $signatureHtml = "
            <div class='mt-5'>
                <img src='{$signatureUrl}' style='width: 100px; height: auto;' />
                <p class='mt-3'>{$user->name}</p>
            </div>
        ";
    }

    // Combine CKEditor content with signature HTML
    $finalHtmlContent = $ckeditorContent . $signatureHtml;

    // Generate PDF using CKEditor HTML content with signature
    $pdf = Pdf::loadHTML($finalHtmlContent)  // Pass the HTML content to PDF generator
              ->setPaper('A4', 'portrait');  // Optionally set paper size

    // Create a view for the PDF
        // Localhost
        $path = public_path('documents');
        $fileName = 'document_' . time() . '.pdf';

        // Save PDF locally
        $pdf->save($path . '/' . $fileName);
        $s3Path = 'documents/' . $fileName;
    

    // Define the document URL
    $documentUrl = (IPRequest::ip() === '127.0.0.1' || IPRequest::ip() === '::1')
        ? url('documents/' . $fileName)
        : $s3Path;

    $doc = Documents::find($document->id);
    if ($doc) {
        $doc->document_url = $s3Path;
        $doc->save();
    }

    DocumentHistory::create([
        'created_by' => $user->id,
        'document_id' => $document->id,
        'document_url' => $s3Path,
    ]);

    DocumentComment::create([
        'created_by' => $user->id,
        'document_id' => $document->id,
        'comment' => $request->comment,
    ]);

    Flash::success('Document saved successfully.');

    return redirect()->back();
}


    public function uploadImage(Request $request)
{
    // Validate the incoming file to ensure it's an image
    $request->validate([
        'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validate image type and size
    ], [
        'upload.required' => 'No file was uploaded.',
        'upload.image' => 'The file must be an image.',
        'upload.mimes' => 'Only jpeg, png, jpg, gif, and svg image formats are allowed.',
        'upload.max' => 'The image size must not exceed 2MB.',
    ]);

    // Handle image upload
    if ($request->hasFile('upload')) {
        try {
            $file = $request->file('upload');
            $file_name = rand() . '.' . $file->getClientOriginalExtension(); // Generate a random file name

            // Determine the file path based on environment
            $path = 'documents';  // Path where you want to store the image
            $path_folder = public_path($path);  // This is where the local files will be stored

            // Ensure the directory exists, if not, create it
            if (!file_exists($path_folder)) {
                mkdir($path_folder, 0755, true);  // Create directory with write permissions
            }

            // Check if the request is from localhost or not
                // Save file locally (for localhost)
                $file->move($path_folder, $file_name);
                $document_url = $path . "/" . $file_name;

                // Get the URL for local
                //$url = getFileUrlNow($document_url);
                $url = asset($document_url);
            

            // Return the response expected by CKEditor (image URL for insertion)
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $url2 = $url;
        $msg = 'Image uploaded successfully';
        $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url2', '$msg')</script>";

        @header('Content-type: text/html; charset=utf-8');
        echo $response;
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            return response()->json(['uploaded' => false, 'error' => ['message' => 'Image upload failed: ' . $e->getMessage()]]);
        }
    }

    // If no file was uploaded or an error occurred
    return false;
}


    public function add(Request $request)
    {


        $user = Auth::user();
        $input = $request->all();
        // $input['created_by'] = $user->id;

        // Get folder and its parents. Create if path does not exist
        //$path = "documents";


        // Prepare document input
        $document_input = [];

        $doc = Documents::find($request->upload_id);

        /* $path_folder = public_path($path);
        // Save file
        $file = $request->file('file');
        $title = str_replace(' ', '', $doc->title);
        $file_name = $title . '_' . 'v1' . '_' . rand() . '.' . $file->getClientOriginalExtension();
        $file->move($path_folder, $file_name);
        $document_url = $path . "/" . $file_name; */
        if ($request->hasFile('file')) {
            $path = "documents"; 
$path_folder = public_path($path);

// Save file
$file = $request->file('file');
$file_name = rand() . '.' . $file->getClientOriginalExtension();

    
    $file->move($path_folder, $file_name);
    $document_url = $path . "/" . $file_name;
    // Now you can use $document_url as needed
  $document_input['document_url'] = $document_url;



        }
        $document_input['document_url'] = $document_url;
        $document = $this->documentRepository->update($document_input, $request->upload_id);

        DocumentHistory::create([
            'created_by' => $user->id,
            'document_id' => $document->id,
            'document_url' => $document_url,
        ]);


        Flash::success('New document version saved successfully.');

        return redirect(route('documents_manager.index'));
    }

    public function addComment(Request $request)
    {

        /* if (!checkPermission('create document')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */


        $user = Auth::user();
        $input = $request->all();

        DocumentComment::create([
            'created_by' => $user->id,
            'document_id' => $request->comment_id,
            'comment' => $request->comment,
        ]);


        Flash::success('New comment saved successfully.');

        return redirect()->back();
    }
    /**
     * assign memo to userss
     */

    public function assignToUsers(Request $request)
    {
        $input = $request->all();
        $memo_id = $input['memo_id'];
        $users = $input['users'];

        /* if (!checkPermission('assign memo to users')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */
        $memo = $this->documentRepository->find($memo_id);

        if (empty($memo)) {
            Flash::error('Memo not found');

            return redirect(route('memos.index'));
        }

        $this->_assignToUsers($users, $memo);

        Flash::success('Memo assigned successfully to user(s).');

        return redirect(route('memos.index'));
    }

    /**
     * Assign memo to departments
     */

    public function assignToDepartments(Request $request)
    {
        $input = $request->all();
        $memo_id = $input['memo_id'];
        $departments = $input['departments'];

        /* if (!checkPermission('assign memo to department')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */
        $memo = $this->documentRepository->find($memo_id);

        if (empty($memo)) {
            Flash::error('Memo not found');

            return redirect(route('memos.index'));
        }

        $this->_assignToRoles($departments, $memo);

        Flash::success('Memo assigned successfully to roles(s).');

        return redirect(route('memos.index'));
    }

    /**
     * Display a listing of the assigned users to a Memo.
     */
    public function assignedUsers(Request $request, $id)
    {
        /* if (!checkPermission('read user memo  Assigned')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */
        $memo = $this->documentRepository->find($id);

        if (empty($memo)) {
            Flash::error('Memo not found');

            return redirect(route('memos.index'));
        }

        $assigned_users = $memo->assignedUsers()->paginate();

        return view('documentmanager::memos.assigned_users')
            ->with(['memo' => $memo, 'assigned_users' => $assigned_users]);
    }

    /**
     * Display a listing of the assigned departments to a Memo.
     */
    public function assignedDepartments(Request $request, $id)
    {
        /*  if (!checkPermission(' read department memo Assigned')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */
        $memo = $this->documentRepository->find($id);

        if (empty($memo)) {
            Flash::error('Memo not found');

            return redirect(route('memos.index'));
        }

        $assigned_departments = $memo->assignedDepartments()->paginate();

        return view('documentmanager::memos.assigned_departments')
            ->with(['memo' => $memo, 'assigned_departments' => $assigned_departments]);
    }

    /**
     * Remove the specified department assignment from storage.
     *
     * @throws \Exception
     */
    public function deleteAssignedUser($user_id, $memo_id)
    {
        /* if (!checkPermission('delete user-memo assignment')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */
        $memo_has_user = $this->memoHasUserRepository->findByUserAndMemo($user_id, $memo_id);

        if (empty($memo_has_user)) {
            Flash::error('Assignment not found');

            return redirect(route('memos.assignedUsers', $memo_id));
        }

        $this->memoHasUserRepository->delete($memo_has_user->id);

        Flash::success('Assignment deleted successfully.');

        return redirect(route('memos.assignedUsers', $memo_id));
    }

    /**
     * Remove the specified department assignment from storage.
     *
     * @throws \Exception
     */
    public function deleteAssignedDepartment($department_id, $memo_id)
    {
        /* if (!checkPermission('delete department-memo assignment')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */
        $memo_has_department = $this->memoHasDepartmentRepository->findByDepartmentAndMemo($department_id, $memo_id);

        if (empty($memo_has_department)) {
            Flash::error('Assignment not found');

            return redirect(route('memos.assignedDepartments', $memo_id));
        }

        $this->memoHasDepartmentRepository->delete($memo_has_department->id);

        Flash::success('Assignment deleted successfully.');

        return redirect(route('memos.assignedDepartments', $memo_id));
    }

    /**
     * Display a listing of the Memo Versions.
     */
    public function memoVersions(Request $request, $id)
    {
        /* if (!checkPermission('read memo')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */
        $memo = $this->documentRepository->find($id);

        if (empty($memo)) {
            Flash::error('Memo not found');

            return redirect(route('memos.index'));
        }

        $memo_document = $this->documentRepository->find($memo->document_id);

        if (empty($memo_document)) {
            Flash::error("Memo's document not found");

            return redirect(route('memos.index'));
        }

        $memoVersions = $memo_document->documentVersions()->paginate(10);

        return view('documentmanager::memos.memo_versions.index')
            ->with(['memo' => $memo, 'memo_document' => $memo_document, 'documentVersions' => $memoVersions]);
    }


    /**
     * Display the specified Memo.
     */
    public function show($id)
{
    // Fetch the document along with the assigned user's details
    $document = DB::table('documents_has_users')
        ->join('documents_manager', 'documents_has_users.document_id', '=', 'documents_manager.id')
        ->join('users', 'documents_has_users.assigned_by', '=', 'users.id') // Join with users table
        ->select(
            'documents_has_users.assigned_by',
            'documents_manager.title',
            'documents_manager.description',
            'documents_manager.document_url',
            'documents_has_users.created_at',
            'users.name', // Get first name
            'users.last_name'    // Get last name
        )
        ->where('documents_manager.id', $id)
        ->first(); // Use first() to get a single record

    // Mark the notification as read
    Notifications::where('action_id', $id)
        ->where('user_id', Auth::id())
        ->update(['is_read' => true]);

    if (!$document) {
        Flash::error('Document not found');
        return redirect(route('documents_manager.index'));
    }

    return view('documents.show')->with('document', $document);
}

public function info($id)
{
    $userId = Auth::user()->id;

    // Mark the notification as read
    Notifications::where('action_id', $id)
        ->where('user_id', Auth::id())
        ->update(['is_read' => true]);

        /* $find = Documents::find($id);
        if(!$find){
return redirect(route('documents_manager.details', $id));
        } */

    $userIds = DocumentHasUser::get()->pluck('user_id');
    $loggedInUserId = auth()->id(); // Get the ID of the logged-in user

    $user_id = $userIds->contains($loggedInUserId);

    if (Auth::user()->hasRole('super-admin')) {

        $departments = Department::get();
        
        // The logged-in user ID exists in the $userIds array
        //$documents = $this->documentRepository->paginate(10);
        
    
    $document = Documents::orderBy('document_created_at', 'desc')
        ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
        ->select(
            'documents_manager.created_at as document_created_at',
            'documents_manager.id as d_id',
            'documents_manager.title',
            'documents_manager.document_url',
            'departments.name as dep_name'
        )
        ->latest('documents_manager.created_at')
        ->groupBy('departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
        ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
        ->where('documents_manager.department_id', auth()->user()->staff->department_id)
        ->where('documents_manager.id', $id)
        ->first();
    

    // Optionally, you can sort the merged collection by created_at if needed
   // $mergedDocuments = $mergedDocuments->sortByDesc('document_created_at');
    
$users1 = $this->userRepository->all();

    $userData = $users1->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    });

    $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->get();
    $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();



    } else if (Auth::user()->level && Auth::user()->level->id == 20) {
        // The logged-in user ID exists in the $userIds array
        //$documents = $this->documentRepository->paginate(10);
        $departments = Department::get();
        
    
    $document = Documents::orderBy('document_created_at', 'desc')
        ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
        ->select(
            'documents_manager.created_at as document_created_at',
            'documents_manager.id as d_id',
            'documents_manager.title',
            'documents_manager.document_url',
            'departments.name as dep_name'
        )
        ->latest('documents_manager.created_at')
        ->groupBy('departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
        ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
        ->where('documents_manager.department_id', auth()->user()->staff->department_id)
        ->where('documents_manager.id', $id)
        ->first();
    
    // Merging both collections
    // Merge categories and documents into one collection
    

$users1 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE users.level_id = 19
');
$users2 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE users.level_id = 18
');

$users3 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE users.level_id = 17
');
$users4 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE users.level_id = 3
');

$users5 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE employees.department_id = 19
');

// Combine the results of all queries into one collection
$userData = collect($users1)
    ->merge($users2)
    ->merge($users3)
    ->merge($users4)
    ->merge($users5)
    ->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    });


    $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->get();
    $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

    } else {
        $departments = Department::where('id', Auth()->user()->staff->department_id)->get();
        // The logged-in user ID does not exist in the $userIds array
        
    
    $document = Documents::orderBy('document_created_at', 'desc')
        ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
        ->select(
            'documents_manager.created_at as document_created_at',
            'documents_manager.id as d_id',
            'documents_manager.title',
            'documents_manager.document_url',
            'departments.name as dep_name'
        )
        ->latest('documents_manager.created_at')
        ->groupBy('departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
        ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
        ->where('documents_manager.department_id', auth()->user()->staff->department_id)
        ->where('documents_manager.id', $id)
        ->where('documents_manager.created_by', auth()->user()->id)
        ->first();
    
    // Merging both collections
    
    
            if (Auth::user()->level && Auth::user()->level->id == 19) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 18) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 17) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 17
            ');
        
            $users3 = DB::select('
            SELECT users.id as id, users.name
            FROM users
            JOIN employees ON users.id = employees.user_id
            WHERE employees.department_id = ?
        ', [auth()->user()->staff->department_id]);
           
            
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->merge($users2)
                ->merge($users3)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 3) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
        
            $users2 = DB::select('
            SELECT users.id as id, users.name
            FROM users
            JOIN employees ON users.id = employees.user_id
            WHERE employees.branch_id = ?
        ', [auth()->user()->staff->branch_id]);
           
            
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->merge($users2)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            
            } else{

                if(auth()->user()->staff && auth()->user()->staff->branch_id == 23){
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 17
                ');
                
                $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.department_id = ?
            ', [auth()->user()->staff->department_id]);
                    } else{
                        $users1 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE users.level_id = 3
                    ');
                    
                    $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE employees.branch_id = ?
                ', [auth()->user()->staff->branch_id]);
                    }
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
            }

            $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();
            $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

    }


    $roles = $this->roleRepository->all()->pluck('name', 'id');
    
    $users = $userData->pluck('name', 'id');

    $catID = $id; 

    $page_title = "Document";
        $title =  'Document';
        $sub_title = $document->document_url ? substr($document->document_url, 32) : '';

//        $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();
$auditLogs = DB::table('documents_manager')
            ->join('audits', 'documents_manager.id', '=', 'audits.auditable_id')
            ->join('users as created_by_user', 'documents_manager.created_by', '=', 'created_by_user.id')
            ->join('documents_has_users', 'documents_manager.id', '=', 'documents_has_users.document_id')
            ->join('users as assigned_to_user', 'documents_has_users.user_id', '=', 'assigned_to_user.id')
            ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select('documents_categories.id as d_c_id','documents_manager.*', 'documents_manager.id as id', 'audits.*', 'created_by_user.name as created_by_first_name', 'assigned_to_user.name as assigned_to_first_name', 'documents_manager.created_at as createdAt', 'documents_categories.name as category_name')
            ->where('audits.auditable_type', "App\Models\Documents")
            ->latest('documents_manager.created_at')
            ->where('documents_manager.id', $id)
            ->distinct() // Ensure distinct results
            ->get();        

        // Now that you have the documents, you can load the category relationship
$documentIds = $auditLogs->pluck('d_c_id')->toArray();
$categories = DocumentsCategory::whereIn('id', $documentIds)->get()->keyBy('id');

$share_documents = DB::table('documents_manager')
    // Join documents_has_users table
    ->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
    // Join users table to get user information
    ->join('users', 'documents_has_users.user_id', '=', 'users.id')
    // Join signatures table to get the signature_data
    ->join('signatures', 'signatures.user_id', '=', 'users.id')  // Join the signatures table on user_id
    // Join documents_categories table to get category information
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    // Select necessary fields from documents_manager, documents_has_users, users, and signatures
    ->select(
        'documents_manager.*',
        'documents_has_users.*',
        'users.email as uemail',
        'users.name as firstName',
        'documents_manager.created_at as createdAt',
        'documents_categories.name as category_name',
        'documents_manager.document_url as doc_url',
        'documents_manager.description as doc_desc',
        'documents_has_users.is_download',
        'documents_has_users.created_at as created',
        'signatures.signature_data' // Select the signature_data from the signatures table
    )
    // Order by created_at
    ->latest('documents_manager.created_at')
    // Filter by document ID
    ->where('documents_manager.id', $id)
    ->get();

    // Fetch all priorities
    $priorities = Priority::all();

    return view('documents.info', compact('priorities','share_documents','auditLogs','categories','page_title','title','sub_title','catID','userData','departments','document','documents_categories1','documents_categories', 'users', 'roles'));
}

public function infoShared($id)
{
    $userId = Auth::user()->id;

    // Mark the notification as read
    Notifications::where('action_id', $id)
        ->where('user_id', Auth::id())
        ->update(['is_read' => true]);

        $find = Documents::find($id);
        if(!$find){
return redirect(route('documents_manager.details.shared', $id));
        }

            if(!empty($find)){
            $find->status = "2";
            $find->save();
            }

    $userIds = DocumentHasUser::get()->pluck('user_id');
    $loggedInUserId = auth()->id(); // Get the ID of the logged-in user

    $user_id = $userIds->contains($loggedInUserId);

    
        $departments = Department::where('id', Auth()->user()->staff->department_id)->get();
        // The logged-in user ID does not exist in the $userIds array

        $document = Documents::orderBy('document_created_at', 'desc')
->join('departments', 'departments.id', '=', 'documents_manager.department_id')
->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
->select(
    'documents_manager.created_at as document_created_at',
    'documents_manager.id as d_id',
    'documents_manager.title',
    'documents_manager.document_url',
    'departments.name as dep_name',
    'documents_has_users.lock_code',
)
->where(function ($query) {
    $query->whereNull('documents_has_users.start_date') // Include documents where no start_date is set
          ->orWhere(function ($query) {
              $query->whereNotNull('documents_has_users.start_date')
                    ->whereNotNull('documents_has_users.end_date')
                    ->whereDate('documents_has_users.start_date', '<=', now()) // Ensure current date is after start_date
                    ->whereDate('documents_has_users.end_date', '>=', now()); // Ensure current date is before end_date
          });
})
->latest('documents_manager.created_at')
->groupBy('documents_has_users.lock_code','departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
->where('documents_has_users.user_id', auth()->user()->id)
->where('documents_manager.id', $id)
->first();

if(empty($document)){
    $document = Documents::orderBy('document_created_at', 'desc')
    ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
    ->join('documents_categories', 'documents_categories.id', '=', 'documents_manager.category_id')
    ->join('documents_has_users_files', 'documents_has_users_files.category_id', '=', 'documents_categories.id')
    ->select(
        'documents_manager.created_at as document_created_at',
        'documents_manager.id as d_id',
        'documents_manager.title',
        'documents_manager.document_url',
        'departments.name as dep_name',
        'documents_has_users_files.lock_code',
    )
    ->where(function ($query) {
        $query->whereNull('documents_has_users_files.start_date') // Include documents where no start_date is set
              ->orWhere(function ($query) {
                  $query->whereNotNull('documents_has_users_files.start_date')
                        ->whereNotNull('documents_has_users_files.end_date')
                        ->whereDate('documents_has_users_files.start_date', '<=', now()) // Ensure current date is after start_date
                        ->whereDate('documents_has_users_files.end_date', '>=', now()); // Ensure current date is before end_date
              });
    })
    ->latest('documents_manager.created_at')
    ->groupBy('documents_has_users_files.lock_code','departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
    ->where('documents_has_users_files.user_id', auth()->user()->id)
    ->where('documents_manager.id', $id)
    ->first();
}
    
    // Merging both collections
    
    
            if (Auth::user()->level && Auth::user()->level->id == 19) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 18) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 17) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 17
            ');
        
            $users3 = DB::select('
            SELECT users.id as id, users.name
            FROM users
            JOIN employees ON users.id = employees.user_id
            WHERE employees.department_id = ?
        ', [auth()->user()->staff->department_id]);
           
            
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->merge($users2)
                ->merge($users3)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 3) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
        
            $users2 = DB::select('
            SELECT users.id as id, users.name
            FROM users
            JOIN employees ON users.id = employees.user_id
            WHERE employees.branch_id = ?
        ', [auth()->user()->staff->branch_id]);
           
            
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->merge($users2)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            
            } else{

                if(auth()->user()->staff && auth()->user()->staff->branch_id == 23){
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 17
                ');
                
                $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.department_id = ?
            ', [auth()->user()->staff->department_id]);
                    } else{
                        $users1 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE users.level_id = 3
                    ');
                    
                    $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE employees.branch_id = ?
                ', [auth()->user()->staff->branch_id]);
                    }
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
            }

            $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();
            $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

    


    $roles = $this->roleRepository->all()->pluck('name', 'id');
    
    $users = $userData->pluck('name', 'id');

    $catID = $id;

    $page_title = "My Document";
        $title =  'My Document';
        $sub_title = '';

//        $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();
$auditLogs = DB::table('documents_manager')
            ->join('audits', 'documents_manager.id', '=', 'audits.auditable_id')
            ->join('users as created_by_user', 'documents_manager.created_by', '=', 'created_by_user.id')
            ->join('documents_has_users', 'documents_manager.id', '=', 'documents_has_users.document_id')
            ->join('users as assigned_to_user', 'documents_has_users.user_id', '=', 'assigned_to_user.id')
            ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select('documents_categories.id as d_c_id','documents_manager.*', 'documents_manager.id as id', 'audits.*', 'created_by_user.name as created_by_first_name', 'assigned_to_user.name as assigned_to_first_name', 'documents_manager.created_at as createdAt', 'documents_categories.name as category_name')
            ->where('audits.auditable_type', "App\Models\Documents")
            ->latest('documents_manager.created_at')
            ->where('documents_manager.id', $id)
            ->distinct() // Ensure distinct results
            ->get();        

        // Now that you have the documents, you can load the category relationship
$documentIds = $auditLogs->pluck('d_c_id')->toArray();
$categories = DocumentsCategory::whereIn('id', $documentIds)->get()->keyBy('id');

$share_documents = DB::table('documents_manager')
    // Join documents_has_users table
    ->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
    // Join users table to get user information
    ->join('users', 'documents_has_users.user_id', '=', 'users.id')
    // Join signatures table to get the signature_data
    ->join('signatures', 'signatures.user_id', '=', 'users.id')  // Join the signatures table on user_id
    // Join documents_categories table to get category information
    ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
    // Select necessary fields from documents_manager, documents_has_users, users, and signatures
    ->select(
        'documents_manager.*',
        'documents_has_users.*',
        'users.email as uemail',
        'users.name as firstName',
        'documents_manager.created_at as createdAt',
        'documents_categories.name as category_name',
        'documents_manager.document_url as doc_url',
        'documents_manager.description as doc_desc',
        'documents_has_users.is_download',
        'documents_has_users.created_at as created',
        'signatures.signature_data', // Select the signature_data from the signatures table
        'documents_has_users.lock_code',
    )
    // Order by created_at
    ->latest('documents_manager.created_at')
    // Filter by document ID
    ->where('documents_manager.id', $id)
    ->get();

    // Fetch all priorities
    $priorities = Priority::all();

    return view('documents.info_shared', compact('priorities','share_documents','auditLogs','categories','page_title','title','sub_title','catID','userData','departments','document','documents_categories1','documents_categories', 'users', 'roles'));
}


public function details($id)
{
    $userId = Auth::user()->id;

    // Mark the notification as read
    Notifications::where('action_id', $id)
        ->where('user_id', Auth::id())
        ->update(['is_read' => true]);

       /*  $find = Documents::where('category_id', $id)->first();
        if(!$find){
return redirect(route('documents_manager.info', $id));
        } */

    $userIds = DocumentHasUser::get()->pluck('user_id');
    $loggedInUserId = auth()->id(); // Get the ID of the logged-in user

    $user_id = $userIds->contains($loggedInUserId);

    if (Auth::user()->hasRole('super-admin')) {

        $departments = Department::get();
        
        // The logged-in user ID exists in the $userIds array
        //$documents = $this->documentRepository->paginate(10);
        $documents_cat = DocumentsCategory::orderBy('created_at', 'desc')
    ->where('branch_id', Auth()->user()->staff->branch_id)
    ->where('department_id', Auth()->user()->staff->department_id)
    ->where('department_id', Auth()->user()->staff->department_id);

    $documentsCat1 = DocumentsCategory::where('parent_id', $id)->first();
    // If $id is provided, filter by parent_id, otherwise ignore it.
    if (!empty($documentsCat1)) {
       // $documents_cat = $documents_cat->where('parent_id', $id);
    }
    $documents_cat = $documents_cat->where('parent_id', $id);

$documents_cat = $documents_cat->select(
    'id as catId',
    'created_at as createdAt',
    'name as cat_name',
    'description as cat_description'
)->get();
    
    $documents = Documents::orderBy('document_created_at', 'desc')
        ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
        ->select(
            'documents_manager.created_at as document_created_at',
            'documents_manager.id as d_id',
            'documents_manager.title',
            'documents_manager.document_url',
            'departments.name as dep_name'
        )
        ->latest('documents_manager.created_at')
        ->groupBy('departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
        ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
        ->where('documents_manager.department_id', auth()->user()->staff->department_id)
        ->where('documents_manager.category_id', $id)
        ->get();
    
    // Merging both collections
    // Merge categories and documents into one collection
$mergedDocuments = $documents_cat->map(function ($category) {
    return [
        'type' => 'category', // Mark as a category
        'catId' => $category->catId,
        'd_id' => null,
        'cat_name' => $category->cat_name,
        'description' => $category->cat_description,
        'createdAt' => $category->createdAt,
        'created_at' => $category->createdAt,
        'document_url' => null, // Categories don't have document URLs
        'dep_name' => null, // Categories don't have a department name
    ];
});

if (!empty($documentsCat1)) {
    // Add documents to the merged collection
    $mergedDocuments = $mergedDocuments->merge($documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
        ];
    }));
    
    }
    
    if (empty($documentsCat1)) {
        // Add documents to the merged collection
        $mergedDocuments = $documents->map(function ($document) {
            return [
                'type' => 'document', // Mark as a document
                'd_id' => $document->d_id,
                'name' => $document->title,
                'description' => null, // Documents don't have descriptions in this case
                'document_created_at' => $document->document_created_at,
                'created_at' => $document->document_created_at,
                'document_url' => $document->document_url,
                'dep_name' => $document->dep_name,
            ];
        });
        
        }

// Sort the merged collection by created_at (most recent first)
$mergedDocuments = $mergedDocuments->sortByDesc('created_at');

    
    // Optionally, you can sort the merged collection by created_at if needed
   // $mergedDocuments = $mergedDocuments->sortByDesc('document_created_at');
    
$users1 = $this->userRepository->all();

    $userData = $users1->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    });

    $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->get();
    $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();



    } else if (Auth::user()->level && Auth::user()->level->id == 20) {
        // The logged-in user ID exists in the $userIds array
        //$documents = $this->documentRepository->paginate(10);
        $departments = Department::get();
        $documents_cat = DocumentsCategory::orderBy('created_at', 'desc')
    ->where('branch_id', Auth()->user()->staff->branch_id)
    ->where('department_id', Auth()->user()->staff->department_id);

    $documentsCat1 = DocumentsCategory::where('parent_id', $id)->first();
    // If $id is provided, filter by parent_id, otherwise ignore it.
    if (!empty($documentsCat1)) {
       // $documents_cat = $documents_cat->where('parent_id', $id);
    }
    $documents_cat = $documents_cat->where('parent_id', $id);

$documents_cat = $documents_cat->select(
    'id as catId',
    'created_at as createdAt',
    'name as cat_name',
    'description as cat_description'
)->get();
    
    $documents = Documents::orderBy('document_created_at', 'desc')
        ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
        ->select(
            'documents_manager.created_at as document_created_at',
            'documents_manager.id as d_id',
            'documents_manager.title',
            'documents_manager.document_url',
            'departments.name as dep_name'
        )
        ->latest('documents_manager.created_at')
        ->groupBy('departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
        ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
        ->where('documents_manager.department_id', auth()->user()->staff->department_id)
        ->where('documents_manager.category_id', $id)
        ->get();
    
    // Merging both collections
    // Merge categories and documents into one collection
$mergedDocuments = $documents_cat->map(function ($category) {
    return [
        'type' => 'category', // Mark as a category
        'catId' => $category->catId,
        'd_id' => null,
        'cat_name' => $category->cat_name,
        'description' => $category->cat_description,
        'createdAt' => $category->createdAt,
        'created_at' => $category->createdAt,
        'document_url' => null, // Categories don't have document URLs
        'dep_name' => null, // Categories don't have a department name
    ];
});

if (!empty($documentsCat1)) {
    // Add documents to the merged collection
    $mergedDocuments = $mergedDocuments->merge($documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
        ];
    }));
    
    }
    
    if (empty($documentsCat1)) {
        // Add documents to the merged collection
        $mergedDocuments = $documents->map(function ($document) {
            return [
                'type' => 'document', // Mark as a document
                'd_id' => $document->d_id,
                'name' => $document->title,
                'description' => null, // Documents don't have descriptions in this case
                'document_created_at' => $document->document_created_at,
                'created_at' => $document->document_created_at,
                'document_url' => $document->document_url,
                'dep_name' => $document->dep_name,
            ];
        });
        
        }

// Sort the merged collection by created_at (most recent first)
$mergedDocuments = $mergedDocuments->sortByDesc('created_at');

    

$users1 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE users.level_id = 19
');
$users2 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE users.level_id = 18
');

$users3 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE users.level_id = 17
');
$users4 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE users.level_id = 3
');

$users5 = DB::select('
    SELECT users.id as id, users.name
    FROM users
    JOIN employees ON users.id = employees.user_id
    WHERE employees.department_id = 19
');

// Combine the results of all queries into one collection
$userData = collect($users1)
    ->merge($users2)
    ->merge($users3)
    ->merge($users4)
    ->merge($users5)
    ->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    });


    $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->get();
    $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

    } else {
        $departments = Department::where('id', Auth()->user()->staff->department_id)->get();
        // The logged-in user ID does not exist in the $userIds array
        

// Apply condition for parent_id, where it should match $id or be null
/* $documents_cat = $documents_cat->where(function ($query) use ($id) {
    $query->where('parent_id', $id)
          ->orWhereNull('parent_id');
}); */

$documents_cat = DocumentsCategory::orderBy('created_at', 'desc')
    ->where('branch_id', Auth()->user()->staff->branch_id)
    ->where('department_id', Auth()->user()->staff->department_id)
    ->where('department_id', Auth()->user()->staff->department_id);

    $documentsCat1 = DocumentsCategory::where('parent_id', $id)->first();
    // If $id is provided, filter by parent_id, otherwise ignore it.
    if (!empty($documentsCat1)) {
       // $documents_cat = $documents_cat->where('parent_id', $id);
    }
    $documents_cat = $documents_cat->where('parent_id', $id);

$documents_cat = $documents_cat->select(
    'id as catId',
    'created_at as createdAt',
    'name as cat_name',
    'description as cat_description'
)->get();
    
    $documents = Documents::orderBy('document_created_at', 'desc')
        ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
        ->select(
            'documents_manager.created_at as document_created_at',
            'documents_manager.id as d_id',
            'documents_manager.title',
            'documents_manager.document_url',
            'departments.name as dep_name'
        )
        ->latest('documents_manager.created_at')
        ->groupBy('departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
        ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
        ->where('documents_manager.department_id', auth()->user()->staff->department_id)
        ->where('documents_manager.category_id', $id)
        ->where('documents_manager.created_by', auth()->user()->id)
        ->get();
    
    // Merging both collections
    // Merge categories and documents into one collection
$mergedDocuments = $documents_cat->map(function ($category) {
    return [
        'type' => 'category', // Mark as a category
        'catId' => $category->catId,
        'd_id' => null,
        'cat_name' => $category->cat_name,
        'description' => $category->cat_description,
        'createdAt' => $category->createdAt,
        'created_at' => $category->createdAt,
        'document_url' => null, // Categories don't have document URLs
        'dep_name' => null, // Categories don't have a department name
    ];
});

if (!empty($documentsCat1)) {
// Add documents to the merged collection
$mergedDocuments = $mergedDocuments->merge($documents->map(function ($document) {
    return [
        'type' => 'document', // Mark as a document
        'd_id' => $document->d_id,
        'name' => $document->title,
        'description' => null, // Documents don't have descriptions in this case
        'document_created_at' => $document->document_created_at,
        'created_at' => $document->document_created_at,
        'document_url' => $document->document_url,
        'dep_name' => $document->dep_name,
    ];
}));

}

if (empty($documentsCat1)) {
    // Add documents to the merged collection
    $mergedDocuments = $documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'name' => $document->title,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
        ];
    });
    
    }

// Sort the merged collection by created_at (most recent first)
$mergedDocuments = $mergedDocuments->sortByDesc('created_at');

    
            if (Auth::user()->level && Auth::user()->level->id == 19) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 18) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 17) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 17
            ');
        
            $users3 = DB::select('
            SELECT users.id as id, users.name
            FROM users
            JOIN employees ON users.id = employees.user_id
            WHERE employees.department_id = ?
        ', [auth()->user()->staff->department_id]);
           
            
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->merge($users2)
                ->merge($users3)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 3) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
        
            $users2 = DB::select('
            SELECT users.id as id, users.name
            FROM users
            JOIN employees ON users.id = employees.user_id
            WHERE employees.branch_id = ?
        ', [auth()->user()->staff->branch_id]);
           
            
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->merge($users2)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            
            } else{

                if(auth()->user()->staff && auth()->user()->staff->branch_id == 23){
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 17
                ');
                
                $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.department_id = ?
            ', [auth()->user()->staff->department_id]);
                    } else{
                        $users1 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE users.level_id = 3
                    ');
                    
                    $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE employees.branch_id = ?
                ', [auth()->user()->staff->branch_id]);
                    }
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
            }

            $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();
            $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

    }


    $roles = $this->roleRepository->all()->pluck('name', 'id');
    
    $users = $userData->pluck('name', 'id');

    $catID = $id;

    $cid = DocumentsCategory::find($id);
    $page_title = $cid->name ." / ". $cid->description;
        $title =  'Manage Document';
        $sub_title = 'Sub-Files / Document';

        // Fetch all priorities
        $priorities = Priority::all();
        // Retrieve the last document category for the user's department and branch
$last_no = DocumentsCategory::where('department_id', Auth()->user()->staff->department_id)
->where('branch_id', Auth()->user()->staff->branch_id)
->latest('created_at')  // Ensure sorting by creation date, latest first
->first();

// Check if the last document category exists and if the 'name' is numeric
if ($last_no && is_numeric($last_no->name)) {
// Increment the last numeric name by 1
$fileNumber = (int) $last_no->name + 1;
} else {
// If not numeric or no previous category, set the default file number
$fileNumber = 10001;
}
//        $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

    return view('documents.details', compact('fileNumber','priorities','page_title','title','sub_title','catID','userData','departments','mergedDocuments','documents_categories1','documents_categories', 'documents', 'users', 'roles'));
}


public function detailsShared($id)
{
    $userId = Auth::user()->id;

    // Mark the notification as read
    Notifications::where('action_id', $id)
        ->where('user_id', Auth::id())
        ->update(['is_read' => true]);

        $find = Documents::where('category_id', $id)->first();
        if(!$find){
return redirect(route('documents_manager.info.shared', $id));
        }

    $userIds = DocumentHasUser::get()->pluck('user_id');
    $loggedInUserId = auth()->id(); // Get the ID of the logged-in user

    $user_id = $userIds->contains($loggedInUserId);

        $departments = Department::where('id', Auth()->user()->staff->department_id)->get();
        // The logged-in user ID does not exist in the $userIds array
       // $documents_cat = DocumentsCategory::with('subcategories')->orderBy('created_at', 'desc');
    //->where('branch_id', Auth()->user()->staff->branch_id)
    //->where('department_id', Auth()->user()->staff->department_id);

    $documentsCat1 = DocumentsCategory::where('id', $id)->first();
    // If $id is provided, filter by parent_id, otherwise ignore it.
    if (!empty($documentsCat1)) {
       // $documents_cat = $documents_cat->where('parent_id', $id);
    }
    $documents_cat = DocumentsCategory::orderBy('documents_categories.created_at', 'desc')
    ->join('documents_has_users_files', 'documents_has_users_files.category_id', '=', 'documents_categories.id') // Join documents to user file links
    ->select(
        'documents_categories.created_at as createdAt',
        'documents_categories.id as catId',
        'documents_categories.name as cat_name',
        'documents_categories.description as cat_description',
        'documents_has_users_files.allow_share',
        'documents_has_users_files.is_download',
        'documents_has_users_files.lock_code',
    )
    ->where('documents_has_users_files.user_id', auth()->user()->id)  // Filter by the authenticated user's ID
    ->where(function ($query) {
        $query->whereNull('documents_has_users_files.start_date') // Include documents where no start_date is set
              ->orWhere(function ($query) {
                  $query->whereNotNull('documents_has_users_files.start_date')
                        ->whereNotNull('documents_has_users_files.end_date')
                        ->whereDate('documents_has_users_files.start_date', '<=', now()) // Ensure current date is after start_date
                        ->whereDate('documents_has_users_files.end_date', '>=', now()); // Ensure current date is before end_date
              });
    })
    ->where('documents_has_users_files.category_id', $id)
    ->latest('documents_categories.created_at') // Order by document creation date
    ->get();
    
$documents = Documents::orderBy('document_created_at', 'desc')
->join('departments', 'departments.id', '=', 'documents_manager.department_id')
->join('documents_categories', 'documents_categories.id', '=', 'documents_manager.category_id')
->join('documents_has_users_files', 'documents_has_users_files.category_id', '=', 'documents_categories.id')
->select(
    'documents_manager.created_at as document_created_at',
    'documents_manager.id as d_id',
    'documents_manager.title',
    'documents_manager.document_url',
    'documents_has_users_files.allow_share',
    'documents_has_users_files.is_download',
    'departments.name as dep_name',
    'documents_has_users_files.lock_code',
)
->where(function ($query) {
    $query->whereNull('documents_has_users_files.start_date') // Include documents where no start_date is set
          ->orWhere(function ($query) {
              $query->whereNotNull('documents_has_users_files.start_date')
                    ->whereNotNull('documents_has_users_files.end_date')
                    ->whereDate('documents_has_users_files.start_date', '<=', now()) // Ensure current date is after start_date
                    ->whereDate('documents_has_users_files.end_date', '>=', now()); // Ensure current date is before end_date
          });
})
->latest('documents_manager.created_at')
->groupBy('documents_has_users_files.lock_code','departments.name','documents_has_users_files.is_download','documents_has_users_files.allow_share','documents_manager.document_url','documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
->where('documents_has_users_files.user_id', auth()->user()->id)
->where('documents_has_users_files.category_id', $id)
->get();
    
$documents22 = Documents::orderBy('document_created_at', 'desc')
        ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
        ->join('documents_has_users', 'documents_has_users.document_id', '=', 'documents_manager.id')
        ->select(
            'documents_manager.created_at as document_created_at',
            'documents_manager.id as d_id',
            'documents_manager.title',
            'documents_manager.document_url',
            'departments.name as dep_name',
            'documents_has_users.lock_code',
           // 'documents_has_users.allow_share',
     // 'documents_has_users.is_download',
        )
        ->latest('documents_manager.created_at')
        ->groupBy('documents_has_users.lock_code','departments.name','documents_manager.document_url','departments.name', 'documents_manager.document_url', 'documents_manager.title', 'documents_manager.created_at', 'documents_manager.id')
       // ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
        //->where('documents_manager.department_id', auth()->user()->staff->department_id)
        ->where('documents_manager.category_id', $id)
        //->where('documents_manager.created_by', auth()->user()->id)
        ->get();

    // Merging both collections
    // Merge categories and documents into one collection
    $mergedDocuments = $documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'name' => $document->title,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
            'lock_code' => $document->lock_code,
        ];
    });

if (!empty($documentsCat1)) {
    /* $mergedDocuments = $documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'name' => $document->title,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
        ];
    }); */
    // Add documents to the merged collection
    /* $mergedDocuments = $mergedDocuments->merge($documents->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            'allow_share' => $document->allow_share,
            'is_download' => $document->is_download,
            'name' => $document->title,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
        ];
    })); */

    /* $mergedDocuments = $mergedDocuments->merge($documents22->map(function ($document) {
        return [
            'type' => 'document', // Mark as a document
            'd_id' => $document->d_id,
            //'allow_share' => $document->allow_share,
            //'is_download' => $document->is_download,
            'name' => $document->title,
            'description' => null, // Documents don't have descriptions in this case
            'document_created_at' => $document->document_created_at,
            'created_at' => $document->document_created_at,
            'document_url' => $document->document_url,
            'dep_name' => $document->dep_name,
        ];
    })); */
    
    }
    
    if (empty($documentsCat1)) {
        // Add documents to the merged collection
        $mergedDocuments = $documents->map(function ($document) {
            return [
                'type' => 'document', // Mark as a document
                'd_id' => $document->d_id,
                'allow_share' => $document->allow_share,
                'is_download' => $document->is_download,
                'name' => $document->title,
                'description' => null, // Documents don't have descriptions in this case
                'document_created_at' => $document->document_created_at,
                'created_at' => $document->document_created_at,
                'document_url' => $document->document_url,
                'dep_name' => $document->dep_name,
                'lock_code' => $document->lock_code,
            ];
        });

        $mergedDocuments = $documents22->map(function ($document) {
            return [
                'type' => 'document', // Mark as a document
                'd_id' => $document->d_id,
                'allow_share' => $document->allow_share,
                'is_download' => $document->is_download,
                'name' => $document->title,
                'description' => null, // Documents don't have descriptions in this case
                'document_created_at' => $document->document_created_at,
                'created_at' => $document->document_created_at,
                'document_url' => $document->document_url,
                'dep_name' => $document->dep_name,
                'lock_code' => $document->lock_code,
            ];
        });
        
        }

// Sort the merged collection by created_at (most recent first)
$mergedDocuments = $mergedDocuments->sortByDesc('created_at');

    
            if (Auth::user()->level && Auth::user()->level->id == 19) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 18) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 17) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
            
            $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 17
            ');
        
            $users3 = DB::select('
            SELECT users.id as id, users.name
            FROM users
            JOIN employees ON users.id = employees.user_id
            WHERE employees.department_id = ?
        ', [auth()->user()->staff->department_id]);
           
            
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->merge($users2)
                ->merge($users3)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            } else if (Auth::user()->level && Auth::user()->level->id == 3) {
                $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 20
            ');
        
            $users2 = DB::select('
            SELECT users.id as id, users.name
            FROM users
            JOIN employees ON users.id = employees.user_id
            WHERE employees.branch_id = ?
        ', [auth()->user()->staff->branch_id]);
           
            
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->merge($users2)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
            
            } else{

                if(auth()->user()->staff && auth()->user()->staff->branch_id == 23){
                    $users1 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE users.level_id = 17
                ');
                
                $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.department_id = ?
            ', [auth()->user()->staff->department_id]);
                    } else{
                        $users1 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE users.level_id = 3
                    ');
                    
                    $users2 = DB::select('
                    SELECT users.id as id, users.name
                    FROM users
                    JOIN employees ON users.id = employees.user_id
                    WHERE employees.branch_id = ?
                ', [auth()->user()->staff->branch_id]);
                    }
               
                
                
                // Combine the results of all queries into one collection
                $userData = collect($users1)
                    ->merge($users2)
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    });
            }

            $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();
            $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();



    $roles = $this->roleRepository->all()->pluck('name', 'id');
    
    $users = $userData->pluck('name', 'id');

    $catID = $id;
    $page_title = "My Documents";
        $title =  'Manage Documents';
        $sub_title = 'My Documents';
        // Fetch all priorities
        $priorities = Priority::all();
//        $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();

    return view('documents.details_shared', compact('priorities','page_title','title','sub_title','catID','userData','departments','mergedDocuments','documents_categories1','documents_categories', 'documents', 'users', 'roles'));
}

    /**
     * Show the form for editing the specified Document.
     */
    public function edit($id)
    {
        /* if (!checkPermission('update document')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */
        $document = $this->documentRepository->find($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('documents_manager.index'));
        }
        $categories = $this->documentsCategoryRepository->all()->pluck('name', 'id');
        //$roles = Role::pluck('name', 'id')->all();
        $roles = $this->roleRepository->all()->pluck('name', 'id');
        // $roles->prepend('Select role', '');
        // $departments->prepend('Select department', '');
        $users1 = $this->userRepository->all();

        $userData = $users1->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
            ];
        });

        $users = $userData->pluck('name', 'id');
        $single_user = User::find($id);
        $single_doc = DocumentHasUser::where('document_id', $id)->get();
        $single_role = DocumentHasRole::where('document_id', $id)->get();
        $single_metas = MetaTag::where('document_id', $id)->get();

        return view('documents.edit', compact('document', 'categories', 'users', 'roles', 'single_role', 'single_doc', 'single_metas'));
    }

    /**
     * Update the specified Document in storage.
     */
    public function update($id, Request $request)
    {
        /* if (!checkPermission('update document')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */
        $input = $request->all();
        $document = $this->documentRepository->find($id);
        $document_id = $document->id;

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('documents_manager.index'));
        }


        if ($request->hasFile('file')) {
            $path = "documents"; 
$path_folder = public_path($path);

// Save file
$file = $request->file('file');
$title = str_replace(' ', '', $input['title']);
$file_name = $title . '_' . 'v1' . '_' . rand() . '.' . $file->getClientOriginalExtension();

   
    $file->move($path_folder, $file_name);
    $document_url = $path . "/" . $file_name;
    // Now you can use $document_url as needed
  $document_input['document_url'] = $document_url;



        }

        // Save document

        $document_input['title'] = $input['title'];
        $document_input['description'] = $input['description'];
        $document_input['category_id'] = $input['category_id'];

        $user = Auth::user();


        $document = $this->documentRepository->update($document_input, $document_id);

        $document_history = DocumentHistory::where('document_id', $document_id)->first();
        if ($document_history) {
            $document_history->created_by = $user->id;
            if ($request->hasFile('file')) {
                $document_history->document_url =  $document_url;
            }
            $document_history->save();
        }


        MetaTag::where('document_id', $document_id)->delete();
        DocumentHasRole::where('document_id', $document_id)->delete();
        DocumentHasUser::where('document_id', $document_id)->delete();

        // Assign to roles(s)
        $roleId = $user->roles->pluck('id')->all();
        $userId = $user->id;

        $roles = $roleId;//$input['roles'];
        if ($roles != null) {
            $this->_assignToRoles($roles, $document);
        }
        // Assign to user(s)
        $users = [$userId]; // Convert $userId to an array
        if ($users != null) {
            $this->_assignToUsers($users, $document);
        }

        // Retrieve meta tags from the request
        $metaTags = $request->input('meta_tags');
        // Iterate over each meta tag and save it to the database
        foreach ($metaTags as $tag) {
            MetaTag::create([
                'name' => $tag,
                'document_id' => $document->id,
                'created_by' => $user->id,
            ]);
            /*  $meta_tag = MetaTag::where('document_id', $document_id)->first();
       if ($meta_tag) {
       $meta_tag->created_by = $user->id;
       $meta_tag->name =  $tag;
       $meta_tag->save();
       } */
        }



        Flash::success('Document updated successfully.');

        return redirect(route('documents_manager.index'));
    }

    /**
     * Remove the specified Document from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        /* if (!checkPermission('delete document')) {
            Flash::error('Permission denied');

            return redirect()->back();
        } */
        $document = $this->documentRepository->find($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('documents_manager.index'));
        }

        $this->documentRepository->delete($id);
        $document_history = DocumentHistory::where('document_id', $id)->delete();
        MetaTag::where('document_id', $id)->delete();
        DocumentHasRole::where('document_id', $id)->delete();
        DocumentHasUser::where('document_id', $id)->delete();


        Flash::success('Document deleted successfully.');

        return redirect(route('documents_manager.index'));
    }





    public function _assignToRoles($roles, $document)
    {
        $logged_in_user = Auth::user();
        foreach ($roles as $key => $role_id) {
            $input_fields['role_id'] = $role_id;
            $input_fields['document_id'] = $document->id;
            $input_fields['assigned_by'] = $logged_in_user->id;


            $this->documentHasRoleRepository->create($input_fields);


            /* try {
                Notification::send($department->users, new MemoAssignedToDepartment($department, $memo));
            } catch (\Throwable $th) {
            } */
        }
    }

    public function _assignToUsers($users, $document)
    {
        $logged_in_user = Auth::user();
        foreach ($users as $key => $user_id) {
            $input_fields['user_id'] = $user_id;
            $input_fields['document_id'] = $document->id;
            $input_fields['assigned_by'] = $logged_in_user->id;



            $this->documentHasUserRepository->create($input_fields);

            /* try {
                $user->notify(new MemoAssignedToUser($memo));
            } catch (\Throwable $th) {
            } */
        }
    }

    public function _assignedToRoles($roles, $document)
    {
        $logged_in_user = Auth::user();
        foreach ($roles as $key => $role_id) {
            $input_fields['role_id'] = $role_id;
            $input_fields['document_id'] = $document->id;
            $input_fields['assigned_by'] = $logged_in_user->id;

            $document_has_role = DocumentHasRole::where('document_id', $document->id)->first();
            if ($document_has_role) {
                $document_has_role->assigned_by = $logged_in_user->id;
                $document_has_role->role_id =  $role_id;
                $document_has_role->save();
            }
            //$this->documentHasRoleRepository->create($input_fields);


            /* try {
                Notification::send($department->users, new MemoAssignedToDepartment($department, $memo));
            } catch (\Throwable $th) {
            } */
        }
    }

    public function _assignedToUsers($users, $document)
    {
        $logged_in_user = Auth::user();
        foreach ($users as $key => $user_id) {
            $input_fields['user_id'] = $user_id;
            $input_fields['document_id'] = $document->id;
            $input_fields['assigned_by'] = $logged_in_user->id;

            $document_has_user = DocumentHasUser::where('document_id', $document->id)->first();
            if ($document_has_user) {
                $document_has_user->assigned_by = $logged_in_user->id;
                $document_has_user->user_id =  $user_id;
                $document_has_user->save();
            }
            //$this->documentHasUserRepository->create($input_fields);

            /* try {
                $user->notify(new MemoAssignedToUser($memo));
            } catch (\Throwable $th) {
            } */
        }
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids'); // Get the IDs from the request

        // Validate IDs
        if (empty($ids)) {
            return response()->json(['message' => 'No documents selected'], 400);
        }

        // Delete the documents with the provided IDs
        Documents::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'Documents deleted successfully']);
    }

    public function document_manager(Request $request){
        
        // Validate the request to ensure category_id is present
        $request->validate([
            'category_id' => 'required|integer|exists:documents_categories,id', // Adjust based on your category table
        ]);
                
        $userId = Auth::user()->id;
        
                $userIds = DocumentHasUser::get()->pluck('user_id');
                $loggedInUserId = auth()->id(); // Get the ID of the logged-in user
        
                $user_id = $userIds->contains($loggedInUserId);
        
                if (Auth::user()->hasRole('super-admin')) {
                    // The logged-in user ID exists in the $userIds array
                    //$documents = $this->documentRepository->paginate(10);
                    $documents = \App\Models\Documents::query()
            ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
            ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select(
                'documents_categories.id as category_id',
                'documents_categories.name as category_name',
                'documents_manager.created_at as document_created_at',
                'documents_manager.id as d_id',
                'documents_manager.title',
                'documents_manager.document_url',
                'documents_categories.description as doc_description',
                'documents_categories.name as cat_name',
                'departments.name as dep_name',
               // 'documents_has_users.is_download',
               // 'documents_has_users.allow_share',
               // 'documents_has_users.user_id',
               // 'documents_has_users.assigned_by',
               // DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name')
            )
            ->latest('documents_manager.created_at')
            //->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
            ->groupBy('departments.name','documents_categories.description','documents_manager.document_url','documents_manager.title','documents_categories.id', 'documents_categories.name', 'documents_manager.created_at', 'documents_manager.id') // Include the nonaggregated column in the GROUP BY clause
            //->with('createdBy')
            ->where('documents_manager.department_id', auth()->user()->staff->department_id)
                         ->where('documents_manager.category_id', $request->category_id)
            ->get();
        
            $users1 = $this->userRepository->all();
        
                $userData = $users1->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
           
        
                } else if (Auth::user()->level && Auth::user()->level->id == 20) {
                    // The logged-in user ID exists in the $userIds array
                    //$documents = $this->documentRepository->paginate(10);
                    $documents = \App\Models\Documents::query()
            ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
            ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select(
                'documents_categories.id as category_id',
                'documents_categories.name as category_name',
                'documents_manager.created_at as document_created_at',
                'documents_manager.id as d_id',
                'documents_manager.title',
                'documents_manager.document_url',
                'documents_categories.description as doc_description',
                'documents_categories.name as cat_name',
                'departments.name as dep_name',
               // 'documents_has_users.is_download',
               // 'documents_has_users.allow_share',
               // 'documents_has_users.user_id',
               // 'documents_has_users.assigned_by',
               // DB::raw('(SELECT name FROM users WHERE users.id = documents_has_users.user_id) AS assigned_to_name')
            )
            ->latest('documents_manager.created_at')
            ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
            ->groupBy('departments.name','documents_categories.description','documents_manager.document_url','documents_manager.title','documents_categories.id', 'documents_categories.name', 'documents_manager.created_at', 'documents_manager.id') // Include the nonaggregated column in the GROUP BY clause
            //->with('createdBy')
            ->where('documents_manager.department_id', auth()->user()->staff->department_id)
                         ->where('documents_manager.category_id', $request->category_id)
            ->get();
        
            $users1 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 19
            ');
            $users2 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 18
            ');
            
            $users3 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 17
            ');
            $users4 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE users.level_id = 3
            ');
            
            $users5 = DB::select('
                SELECT users.id as id, users.name
                FROM users
                JOIN employees ON users.id = employees.user_id
                WHERE employees.department_id = 19
            ');
            
            // Combine the results of all queries into one collection
            $userData = collect($users1)
                ->merge($users2)
                ->merge($users3)
                ->merge($users4)
                ->merge($users5)
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                    ];
                });
        
           
        
                } else {
                    // The logged-in user ID does not exist in the $userIds array
                        $documents = \App\Models\Documents::query()
                        ->join('departments', 'departments.id', '=', 'documents_manager.department_id')
            ->join('documents_categories', 'documents_manager.category_id', '=', 'documents_categories.id')
            ->select(
                'documents_categories.id as category_id',
                'documents_categories.name as category_name',
                'documents_manager.created_at as document_created_at',
                'documents_manager.id as d_id',
                'documents_manager.title',
                'documents_manager.document_url',
                'documents_categories.description as doc_description',
                'documents_categories.name as cat_name',
                'departments.name as dep_name',
                 )
                        ->where('documents_manager.created_by', $userId)
                        ->where('documents_manager.branch_id', auth()->user()->staff->branch_id)
                        ->latest('documents_manager.created_at')
                        ->groupBy('documents_categories.description','documents_manager.document_url','documents_manager.title','documents_categories.id', 'documents_categories.name', 'documents_manager.created_at', 'documents_manager.id') // Include the nonaggregated column in the GROUP BY clause
                        //->with('createdBy')
                         ->where('documents_manager.department_id', auth()->user()->staff->department_id)
                         ->where('documents_manager.category_id', $request->category_id)
                        ->get();
        
        
                        if (Auth::user()->level && Auth::user()->level->id == 19) {
                            $users1 = DB::select('
                            SELECT users.id as id, users.name
                            FROM users
                            JOIN employees ON users.id = employees.user_id
                            WHERE users.level_id = 20
                        ');
                        
                        // Combine the results of all queries into one collection
                        $userData = collect($users1)
                            ->map(function ($user) {
                                return [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                ];
                            });
                        } else if (Auth::user()->level && Auth::user()->level->id == 18) {
                            $users1 = DB::select('
                            SELECT users.id as id, users.name
                            FROM users
                            JOIN employees ON users.id = employees.user_id
                            WHERE users.level_id = 20
                        ');
                        
                        // Combine the results of all queries into one collection
                        $userData = collect($users1)
                            ->map(function ($user) {
                                return [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                ];
                            });
                        } else if (Auth::user()->level && Auth::user()->level->id == 17) {
                            $users1 = DB::select('
                            SELECT users.id as id, users.name
                            FROM users
                            JOIN employees ON users.id = employees.user_id
                            WHERE users.level_id = 20
                        ');
                        
                        $users2 = DB::select('
                            SELECT users.id as id, users.name
                            FROM users
                            JOIN employees ON users.id = employees.user_id
                            WHERE users.level_id = 17
                        ');
                    
                        $users3 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE employees.department_id = ?
                    ', [auth()->user()->staff->department_id]);
                       
                        
                        
                        // Combine the results of all queries into one collection
                        $userData = collect($users1)
                            ->merge($users2)
                            ->merge($users3)
                            ->map(function ($user) {
                                return [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                ];
                            });
                        } else if (Auth::user()->level && Auth::user()->level->id == 3) {
                            $users1 = DB::select('
                            SELECT users.id as id, users.name
                            FROM users
                            JOIN employees ON users.id = employees.user_id
                            WHERE users.level_id = 20
                        ');
                    
                        $users2 = DB::select('
                        SELECT users.id as id, users.name
                        FROM users
                        JOIN employees ON users.id = employees.user_id
                        WHERE employees.branch_id = ?
                    ', [auth()->user()->staff->branch_id]);
                       
                        
                        
                        // Combine the results of all queries into one collection
                        $userData = collect($users1)
                            ->merge($users2)
                            ->map(function ($user) {
                                return [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                ];
                            });
                        
                        } else{
        
                            if(auth()->user()->staff && auth()->user()->staff->branch_id == 23){
                                $users1 = DB::select('
                                SELECT users.id as id, users.name
                                FROM users
                                JOIN employees ON users.id = employees.user_id
                                WHERE users.level_id = 17
                            ');
                            
                            $users2 = DB::select('
                            SELECT users.id as id, users.name
                            FROM users
                            JOIN employees ON users.id = employees.user_id
                            WHERE employees.department_id = ?
                        ', [auth()->user()->staff->department_id]);
                                } else{
                                    $users1 = DB::select('
                                    SELECT users.id as id, users.name
                                    FROM users
                                    JOIN employees ON users.id = employees.user_id
                                    WHERE users.level_id = 3
                                ');
                                
                                $users2 = DB::select('
                                SELECT users.id as id, users.name
                                FROM users
                                JOIN employees ON users.id = employees.user_id
                                WHERE employees.branch_id = ?
                            ', [auth()->user()->staff->branch_id]);
                                }
                           
                            
                            
                            // Combine the results of all queries into one collection
                            $userData = collect($users1)
                                ->merge($users2)
                                ->map(function ($user) {
                                    return [
                                        'id' => $user->id,
                                        'name' => $user->name,
                                    ];
                                });
                        }
        
        
                }
        
        
                $roles = $this->roleRepository->all()->pluck('name', 'id');
                
                $users = $userData->pluck('name', 'id');
        
                $documents_categories1 = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();
        
                $documents_categories = DocumentsCategory::orderBy('id' ,'desc')->where('branch_id', Auth()->user()->staff->branch_id)->where('department_id', Auth()->user()->staff->department_id)->get();
        
                return view('documents.files', compact('documents_categories1','documents_categories', 'documents', 'users', 'roles'));
                 
        }
        
}
