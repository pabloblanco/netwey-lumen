<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Commune;
use App\Models\Region;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use DateTime;

class CustomerController extends Controller
{
    public function show(Request $request)
    {
        $input = $request->all();
        $customer = Customer::where('dni', $input['data'])
                            ->orWhere('email', $input['data'])
                            ->join('regions', 'customers.id_reg', '=', 'regions.id_reg')
                            ->join('communes', 'customers.id_com', '=', 'communes.id_com')
                            ->select('customers.*', 'regions.description as region', 'communes.description as commune')
                            ->first();
        $user = auth()->user();
        if ($customer && $customer->status != 'trash' && $customer->status != 'I') {
            $address = $customer->address;
            if (!$address) {
                    $address = 'null';
            }
            $log = ['status' => 'success', 'message' => 'Request to find customer '.$input['data'].' by user '.$user->email];
            Log::create($log);
            return response()->json([
                'success' => true,
                'name' => $customer->name,
                'last_name' => $customer->last_name,
                'address' => $address,
                'region' => $customer->region,
                'commune' => $customer->commune
            ]);
        }else{
            $log = ['status' => 'fail', 'message' => 'User '.$user->email.' try to finding an unexistent, or previously deleted, over the Customer '.$input['data']];
            Log::create($log);
            return response()->json([
                'success' => false,
                'message' => 'Registro no existe or is inactive'
            ]);              
        }
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $region = Region::where('description', $input['region'])->first();
        $commune = false;
        if ($region){
            $commune = Commune::where('description', $input['commune'])
                                ->where('id_reg', $region->id_reg)
                                ->first();
        }
        $user = auth()->user();                            
        if ($commune && $region){
            $input = Arr::add($input, 'id_reg', $region->id_reg);
            $input = Arr::add($input, 'id_com', $commune->id_com);
            $input = Arr::add($input, 'status', 'A');
            $now = new DateTime();
            $input = Arr::add($input, 'date_reg', $now);
            try {
                Customer::create($input);       
                $log = ['status' => 'success', 'message' => 'Customer '.$input['dni'].' created by user '.$user->email];
                Log::create($log);
                return response()->json([
                    'success' => true,
                    'message' => 'Customer registered succefully'
                ]);            
            } catch (\Throwable $e) {
                $log = ['status' => 'fail', 'message' => 'Customer '.$input['dni'].', created by user '.$user->email.', failed. Error: '.$e->getMessage()];
                Log::create($log);
                return response()->json([
                    'success' => false,
                    'message' => 'Customer creation failed. Error: '.$e->getMessage()
                ],500);
                
            }
        }else{
            $log = ['status' => 'fail', 'message' => 'Creation of Customer '.$input['dni'].' posted by user '.$user->email.' fail. Commune or Region unexistent, or dont have a relation.'];
            Log::create($log);
            return response()->json([
                'success' => false,
                'message' => 'Commune or Region unexistent, or dont have a relation.'
            ]); 
        }           

    }

    public function delete(Request $request)
    {
        $input = $request->all();
        $customer = Customer::find($input['dni']);
        $user = auth()->user();
        if ($customer && $customer->status != 'trash') {
            $customer->status = 'trash';
            $customer->save();
            $log = ['status' => 'success', 'message' => 'Customer '.$input['dni'].' has been deleted by user '.$user->email];
            Log::create($log);
            return response()->json([
                'success' => true,
                'message' => 'Customer Deleted succefully'
            ]);
        }else{
            $log = ['status' => 'fail', 'message' => 'User '.$user->email.' try to delete an unexistent, or previously deleted, over the Customer '.$input['data']];
            Log::create($log);
            return response()->json([
                'success' => false,
                'message' => 'Registro no existe'
            ]);          
        }

           

    }
}
