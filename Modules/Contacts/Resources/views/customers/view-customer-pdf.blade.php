<!DOCTYPE html>
<html>
<head>
	<style>
		table {
		  border-collapse: collapse;
		}
	</style>
</head>
<body>

<table style="padding-bottom: 50px;">
	<tr style="width: 1180px;">
		<td style="width: 45%; vertical-align: top;  ">
			<h5 style="font:700; font-size:15px;">{{$customer->name}}</h5>
			<p style="font:700; font-size:12px;" class="">{{$customer->phone}}</p>
			<p style="font:700; font-size:12px;" class="">{{$customer->address}}</p>

		</td>

		<td style="width: 35%; vertical-align: top; text-align:center"><p  style="font:700; font-size:12px;">Customer Details Info</p></td>

		<td style="width: 20%; text-align:right">
			@if(@$customer?->customerDetails?->customer_file && $customer?->customerDetails?->customer_file !=null)
				<img height="130" width="100" src="{{asset('uploads/customer').'/'.$customer?->customerDetails?->customer_file}}">
			@else
				<img height="130" width="100" src="{{ asset('images/default.jpg')}}" alt="">
			@endif
		</td>
	</tr>
</table>


<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Company Name & Description</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Company Name :</td>
		<td style="width: 200px;">{{$customer->business_name}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Description :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->description}}</td>
	</tr>
</table>

<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Details Information</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Customer Group :</td>
		<td style="width: 200px;">{{$customer->customer_group->group_name ?? ''}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">NID No. :</td>
		<td style="width: 200px;">{{$customer?->nid_no}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Print Name :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->print_name}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Ledger Name :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->print_ledger_name}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Date of Birth :</td>
		<td style="width: 200px;">{{$customer->date_of_birth ? date('Y-M-d', strtotime($customer->date_of_birth)) : ''}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Ledger Code :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->billing_account}}</td>
	</tr>
</table>



<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Bankn & TAX Info</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Bank Name :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->bank_name}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Bank A/C Number :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->bank_A_C_number}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Bank Branch :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->bank_branch}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Currency :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->bank_currency}}</td>
	</tr>


	<tr style="width: 1180px;">
		<td style="width: 200px;">TIN No :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->tax_number}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">TAX No :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->tin_number}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Category :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->tax_category}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Name :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->tax_name}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Address :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->tax_address}}</td>
	</tr>
</table>

<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Primary Contact Info & Address</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Mailing Name :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->contact_mailing_name}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Mobile :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->primary_mobile}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Email :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->contact_email}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Telephone No :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->contact_telephone}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Fax No :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->contact_fax}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Present :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->address}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Permanent :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->permanent_address}}</td>
	</tr>
</table>


<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Mailing & Shipping Info</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Mailing Name :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->mailing_name}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Address :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->mailing_address}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Mail Address :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->mailing_email}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Shipping Name :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->shipping_name}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Address :</td>
		<td style="width: 200px;">{{$customer->shipping_address}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Mobile No :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->shipping_number}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Mail Address :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->shipping_email}}</td>
	</tr>
</table>

<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Partner & Commissions</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Partner Name :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->partner_name}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Percentes (%) :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->percentes}}</td>
	</tr>
</table>
<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Alternative Contact Info</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Name :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->alternative_name}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Mobile :</td>
		<td style="width: 200px;">{{$customer->known_person_phone}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Email :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->alternative_email}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Fax :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->alternative_fax}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Address :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->alternative_address}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Post Office :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->alternative_post_office}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Post Code :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->alternative_zip_code}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Police Station :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->alternative_police_station}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">State :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->alternative_state}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">City :</td>
		<td style="width: 200px;">{{$customer?->customerDetails?->alternative_city}}</td>
	</tr>
	<tr style="width: 1180px;">
		<td style="width: 200px;">Telephone No :</td>
		<td style="width: 200px;">{{$customer->landline}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Image :</td>
		<td style="width: 200px;">
			@if(@$customer?->customerDetails?->alternative_file && $customer?->customerDetails?->alternative_file !=null)
				<img height="60" width="50" class="alternative_file" src="{{ asset('uploads/customer/alternative/'.$customer?->customerDetails?->alternative_file) }}" alt="">
			@else
				<img height="60" width="50" src="{{ asset('images/default.jpg')}}" alt="">
			@endif
		</td>
	</tr>
</table>


<p><b>   </b></p>

@foreach ($customer->customerContactPersons as $k=>$contact_person)
<table style="padding-top: 50px; break-inside: avoid;
margin-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Contact Parsones {{$k+1}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Name :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_name}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Phone :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_phon}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Alternative Phone :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_alternative_phone}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Telephone No :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_landline}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Post Office :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_post_office}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Police Station :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_police_station}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">City :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_city}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Designation :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_dasignation}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Email :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_email}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Fax :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_alternative_phone}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">Address :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_address}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Post Code :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_zip_code}}</td>
		<td style="width: 50px; "></td>
		<td style="width: 200px;">State :</td>
		<td style="width: 200px;">{{$contact_person->contact_person_state}}</td>
	</tr>
</table>
@endforeach


<p><b>{{$customer->business_name}} </b> &mdash; <a style="text-decoration: none" href="mailto::{{$customer->email}}">{{$customer->email}}</a> &mdash;<a  style="text-decoration: none" href="callto::{{ $customer->phone}}">{{ $customer->phone}}</a> </p>
</body>
</html>
