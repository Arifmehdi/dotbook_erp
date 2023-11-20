<?php

namespace Modules\CRM\Services;

use App\Models\Customer;
use App\Traits\IdGenerator;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Modules\CRM\Entities\Leads;
use Modules\CRM\Interfaces\LeadServiceInterface;

class LeadService implements LeadServiceInterface
{
    use IdGenerator;

    public function all()
    {
        $customers = Customer::where('is_lead', true)->get();

        return $customers;
    }

    public function store($attributes)
    {
        $attributes['contact_id'] = $attributes['contact_id'] ?? $this->generateCustomerId();
        $lead = Customer::create($attributes);

        return $lead;

        $photo_prefix = Carbon::now()->toDateString().'_'.$leads->id;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo_extension = $photo->getClientOriginalExtension();
            $photo_name = $photo_prefix.'.'.$photo_extension;
            Image::make($photo)->resize(250, 250)->save('uploads/customers/'.$photo_name);

            $edit_leads = Customer::find($leads->id);
            $edit_leads->photo = $photo_name;
            $edit_leads->save();
        }
        $leads_contact = new Leads();
        if (isset($request->c_name)) {
            $x = $request->c_name;
            foreach ($x as $key => $value) {
                $leads_contact->customer_id = $leads->id;
                $leads_contact->name = $request->c_name[$key];
                $leads_contact->email = $request->c_email[$key];
                $leads_contact->phone = $request->c_phone[$key];
                $leads_contact->sales_commission = $request->sal_com_per[$key];
                $leads_contact->department = $request->c_department[$key];
                $leads_contact->designation = $request->c_designation[$key];
                $leads_contact->allow_login = isset($request->c_allow_login[$key]) ? 1 : 0;
                $leads_contact->save();
            }
        }

        return $leads;
    }

    public function find($id)
    {
        $leads = Customer::find($id);

        return $leads;
    }

    public function update($attributes, $id)
    {
        $lead = Customer::find($id);
        $oldFile = $lead->photo;
        if (isset($attributes['photo']) && $oldFile) {
            if (\File::exists(\public_path('uploads/customers/'.$oldFile))) {
                unlink(\public_path('uploads/customers/'.$oldFile));
            }
        }
        $lead->update($attributes);

        return $lead;
    }

    public function destroy($id)
    {
        $leads = Customer::find($id);
        $file = $leads->photo;
        if (isset($file)) {
            unlink(\public_path('uploads/customers/'.$file));
        }
        $leads->delete();

        return $leads;
    }
}
