<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserCompany;
use Auth;
use File;
use App\Models\Utility;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserToDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Session;
use Spatie\Permission\Models\Role;
use App\Models\Signature;
use Laracasts\Flash\Flash;



class  UserController extends Controller
{

    public function index()
    {
        $user = \Auth::user();
        if (\Auth::user()->can('manage user')) {
            $users = User::where('created_by', '=', $user->creatorId())->where('type', '!=', 'client')->get();

            return view('user.index')->with('users', $users);
        } else {
            return redirect()->back();
        }
    }

    public function saveSignature(Request $request)
{
    // Validation rules
    $validated = $request->validate([
        'signature' => 'required|image|mimes:png,jpeg,jpg',
    ]);

    // Check if the file is uploaded
    if ($request->hasFile('signature')) {
        $file = $request->file('signature');

        // Get original file name and extension
        $fileNameWithExtension = $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();

        // Get the image file's current dimensions
        list($width, $height) = getimagesize($file);

        // Set maximum dimensions for resizing
        $maxWidth = 200;  // Maximum width in pixels
        $maxHeight = 200; // Maximum height in pixels

        // Calculate the new dimensions while maintaining aspect ratio
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = (int) ($width * $ratio);
        $newHeight = (int) ($height * $ratio);

        // Create an image resource from the uploaded file based on its extension
        if ($fileExtension == 'jpeg' || $fileExtension == 'jpg') {
            $image = imagecreatefromjpeg($file);
        } elseif ($fileExtension == 'png') {
            $image = imagecreatefrompng($file);
        } else {
            return back()->with('error', 'Unsupported file type.');
        }

        // Create a new true color image with the new dimensions
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG images
        if ($fileExtension == 'png') {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
        }

        // Resize the image
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Compress the image (quality: 75 out of 100)
        $compressedImage = null;
        if ($fileExtension == 'jpeg' || $fileExtension == 'jpg') {
            // For JPG, we use imagejpeg() to compress the image
            ob_start();
            imagejpeg($resizedImage, null, 75); // 75 is the quality (1-100 scale)
            $compressedImage = ob_get_contents();
            ob_end_clean();
        } elseif ($fileExtension == 'png') {
            // For PNG, we use imagepng() to compress the image
            ob_start();
            imagepng($resizedImage, null, 6); // 0 to 9 compression level
            $compressedImage = ob_get_contents();
            ob_end_clean();
        }

        // Generate a unique name for the file
        $fileName = uniqid() . '.' . $fileExtension;

        // Define the path where the image will be saved
        $path = "documents";
        $pathFolder = public_path($path);

        // Check if the folder exists, if not, create it
        if (!file_exists($pathFolder)) {
            mkdir($pathFolder, 0755, true); // Create folder with proper permissions
        }

        // Check IP and save file locally or to S3
            // Save locally (for local testing)
            file_put_contents($pathFolder . '/' . $fileName, $compressedImage);
            $documentUrl = $path . "/" . $fileName;
        

        // Retrieve or create a new signature for the user
        $userId = Auth()->id();
        $signature = Signature::where('user_id', $userId)->first();

        if ($signature) {
            $signature->user_id = $userId;
            $signature->signature_data = $documentUrl;
            $signature->save();
        } else {
            Signature::create([
                'user_id' => $userId,
                'signature_data' => $documentUrl
            ]);
        }

        return back()->with('success', 'Signature uploaded and compressed successfully!');
    }

    return back()->with('error', 'No signature file uploaded.');
}

    public function changeSignature()
    {
        $userId = Auth()->id();
        $signature = Signature::where('user_id',$userId)->first();

        return view('users.signature',compact("signature"));
    }

    public function create()
    {

        $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();
        $user  = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'client')->get()->pluck('name', 'id');
        if (\Auth::user()->can('create user')) {
            return view('user.create', compact('roles', 'customFields'));
        } else {
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('create user')) {
            $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6',
                    'role' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }


            $objUser    = \Auth::user();
            $role_r                = Role::findById($request->role);
            $psw                   = $request->password;
            $request['password']   = Hash::make($request->password);
            $request['type']       = $role_r->name;
            $request['lang']       = !empty($default_language) ? $default_language->value : 'en';
            $request['created_by'] = \Auth::user()->creatorId();
            $user = User::create($request->all());
            $user->assignRole($role_r);

            if ($request['type'] != 'client')
                \App\Models\Utility::employeeDetails($user->id, \Auth::user()->creatorId());

            //Send Email

            $user->password = $psw;
            $user->type     = $role_r->name;


            $userArr = [
                'email' => $user->email,
                'password' =>  $user->password,
            ];
            $resp = Utility::sendEmailTemplate('new_user', [$user->id => $user->email], $userArr);
            return redirect()->route('users.index')->with('success', __('User successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
        } else {
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $user  = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->where('name', '!=', 'client')->get()->pluck('name', 'id');
        if (\Auth::user()->can('edit user')) {
            $user              = User::findOrFail($id);
            $user->customField = CustomField::getData($user, 'user');
            $customFields      = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();

            return view('user.edit', compact('user', 'roles', 'customFields'));
        } else {
            return redirect()->back();
        }
    }


    public function update(Request $request, $id)
    {

        if (\Auth::user()->can('edit user')) {
            if (\Auth::user()->type == 'company') {
                $user = User::findOrFail($id);
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $id,
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $role = Role::findById($request->role);
                $input = $request->all();
                $input['type'] = $role->name;

                $user->fill($input)->save();
                CustomField::saveData($user, $request->customField);

                $roles[] = $request->role;
                $user->roles()->sync($roles);

                return redirect()->route('users.index')->with(
                    'success',
                    'User successfully updated.'
                );
            } else {
                $user = User::findOrFail($id);
                $this->validate(
                    $request,
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $id,
                        'role' => 'required',
                    ]
                );

                $role          = Role::findById($request->role);
                $input         = $request->all();
                $input['type'] = $role->name;
                $user->fill($input)->save();
                Utility::employeeDetailsUpdate($user->id, \Auth::user()->creatorId());
                CustomField::saveData($user, $request->customField);

                $roles[] = $request->role;
                $user->roles()->sync($roles);

                return redirect()->route('users.index')->with(
                    'success',
                    'User successfully updated.'
                );
            }
        } else {
            return redirect()->back();
        }
    }


    public function destroy($id)
    {

        if (\Auth::user()->can('delete user')) {
            $user = User::find($id);
            if ($user) {
                if (\Auth::user()->type == 'company') {
                    if ($user->delete_status == 0) {
                        $user->delete_status = 1;
                    } else {
                        $user->delete_status = 0;
                    }
                    $user->save();
                }
                if (\Auth::user()->type == 'company') {
                    $employee = Employee::where(['user_id' => $user->id])->delete();
                    if ($employee) {
                        $delete_user = User::where(['id' => $user->id])->delete();
                        if ($delete_user) {
                            return redirect()->route('users.index')->with('success', __('User successfully deleted .'));
                        } else {
                            return redirect()->back()->with('error', __('Something is wrong.'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('Something is wrong.'));
                    }
                }

                return redirect()->route('users.index')->with('success', __('User successfully deleted .'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back();
        }
    }

    public function profile()
    {
        $userDetail              = \Auth::user();
        $userDetail->customField = CustomField::getData($userDetail, 'user');
        $customFields            = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'user')->get();
        $userId = Auth()->id();
        $signature = Signature::where('user_id',$userId)->first();

        return view('user.profile', compact('userDetail', 'customFields', 'signature'));
    }

    public function editprofile(Request $request)
    {

        $userDetail = \Auth::user();
        $user       = User::findOrFail($userDetail['id']);
        $this->validate(
            $request,
            [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
            ]
        );
        if ($request->hasFile('profile')) {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $dir        = storage_path('uploads/avatar/');
            $image_path = $dir . $userDetail['avatar'];

            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);
        }

        if (!empty($request->profile)) {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();
        CustomField::saveData($user, $request->customField);

        return redirect()->route('dashboard')->with(
            'success',
            'Profile successfully updated.'
        );
    }

    public function updatePassword(Request $request)
    {
        if (Auth::Check()) {
            $request->validate(
                [
                    'old_password' => 'required',
                    'password' => 'required|min:6',
                    'password_confirmation' => 'required|same:password',
                ]
            );
            $objUser          = Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if (Hash::check($request_data['old_password'], $current_password)) {
                $user_id            = Auth::User()->id;
                $obj_user           = User::find($user_id);
                $obj_user->password = Hash::make($request_data['password']);;
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            } else {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        } else {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }
    // User To do module
    public function todo_store(Request $request)
    {
        $request->validate(
            ['title' => 'required|max:120']
        );

        $post            = $request->all();
        $post['user_id'] = Auth::user()->id;
        $todo            = UserToDo::create($post);


        $todo->updateUrl = route(
            'todo.update',
            [
                $todo->id,
            ]
        );
        $todo->deleteUrl = route(
            'todo.destroy',
            [
                $todo->id,
            ]
        );

        return $todo->toJson();
    }

    public function todo_update($todo_id)
    {
        $user_todo = UserToDo::find($todo_id);
        if ($user_todo->is_complete == 0) {
            $user_todo->is_complete = 1;
        } else {
            $user_todo->is_complete = 0;
        }
        $user_todo->save();
        return $user_todo->toJson();
    }

    public function todo_destroy($id)
    {
        $todo = UserToDo::find($id);
        $todo->delete();

        return true;
    }

    // change mode 'dark or light'
    public function changeMode()
    {
        $usr = Auth::user();
        if ($usr->mode == 'light') {
            $usr->mode      = 'dark';
            $usr->dark_mode = 1;
        } else {
            $usr->mode      = 'light';
            $usr->dark_mode = 0;
        }
        $usr->save();

        return redirect()->back();
    }

    public function upgradePlan($user_id)
    {
        $user = User::find($user_id);

        $plans = Plan::get();

        return view('user.plan', compact('user', 'plans'));
    }
    public function activePlan($user_id, $plan_id)
    {

        $user       = User::find($user_id);
        $assignPlan = $user->assignPlan($plan_id);
        $plan       = Plan::find($plan_id);
        if ($assignPlan['is_success'] == true && !empty($plan)) {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $plan->price,
                    'price_currency' => isset(\Auth::user()->planPrice()['currency']) ? \Auth::user()->planPrice()['currency'] : '',
                    'txn_id' => '',
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'user_id' => $user->id,
                ]
            );

            return redirect()->back()->with('success', 'Plan successfully upgraded.');
        } else {
            return redirect()->back()->with('error', 'Plan fail to upgrade.');
        }
    }

    public function userPassword($id)
    {
        $eId        = \Crypt::decrypt($id);
        $user = User::find($eId);

        return view('user.reset', compact('user'));
    }

    public function userPasswordReset(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'password' => 'required|confirmed|same:password_confirmation',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $user                 = User::where('id', $id)->first();
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->route('users.index')->with(
            'success',
            'User Password successfully updated.'
        );
    }

    
}
