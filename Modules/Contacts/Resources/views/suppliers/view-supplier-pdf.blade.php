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
		<td style="width: 45%; vertical-align: top; ">
			<h5 style="font:700; font-size:15px;">{{$supplier?->name}}</h5>
			<p style="font:700; font-size:12px;" class="">{{$supplier?->phone}}</p>
			<p style="font:700; font-size:12px;" class="">{{$supplier?->address}}</p>
		</td>

		<td style="width: 35%; vertical-align: top; text-align:center"><p  style="font:700; font-size:12px;">Customer Details Info</p></td>

		<td style="width: 20%; text-align:right">
			@if(@$supplier?->supplierDetails?->supplier_file && $supplier?->supplierDetails?->supplier_file !=null)
				<img height="130" width="100" src="{{asset('uploads/supplier').'/'.$supplier?->supplierDetails?->supplier_file}}">
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
		<td style="width: 200px;">{{$supplier?->business_name}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Description :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->description}}</td>
	</tr>
</table>

<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red;">Details Information</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Print Name :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->print_name}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Ledger Name :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->print_ledger_name}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Date of Birth :</td>
		<td style="width: 200px;">{{ $supplier->date_of_birth ? date('Y-M-d', strtotime($supplier->date_of_birth)) : '' }}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Ledger Code :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->billing_account}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">NID No. :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->nid_no}}</td>
	</tr>
</table>



<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Bankn & TAX Info</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Bank Name :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->bank_name}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Bank A/C Number :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->bank_A_C_number}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Bank Branch :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->bank_branch}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Currency :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->bank_currency}}</td>
	</tr>


	<tr style="width: 1180px;">
		<td style="width: 200px;">TIN No :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->tax_number}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">TAX No :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->tin_number}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Category :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->tax_category}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Name :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->tax_name}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Address :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->tax_address}}</td>
	</tr>
</table>

<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Primary Contact Info & Address</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Mailing Name :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->contact_mailing_name}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Mobile :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->primary_mobile}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Email :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->contact_email}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Telephone No :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->contact_telephone}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Fax No :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->contact_fax}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Present :</td>
		<td style="width: 200px;">{{$supplier?->address}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Permanent :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->permanent_address}}</td>
	</tr>
</table>

<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Mailing & Shipping Info</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Mailing Name :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->mailing_name}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Address :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->mailing_address}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Mail Address :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->mailing_email}}</td>

	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Shipping Name :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->shipping_name}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Address :</td>
		<td style="width: 200px;">{{$supplier?->shipping_address}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Mobile No :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->shipping_number}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Mail Address :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->shipping_email}}</td>
	</tr>

</table>


<p><b>   </b></p>
<table style="padding-top: 50px;">
	<tr>
		<td colspan="3" style="color: red; ">Alternative Contact Info</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Name :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->alternative_name}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Mobile :</td>
		<td style="width: 200px;">{{$supplier?->known_person_phone}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Email :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->alternative_email}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Fax :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->alternative_fax}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Address :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->alternative_address}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Post Office :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->alternative_post_office}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Post Code :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->alternative_zip_code}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Police Station :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->alternative_police_station}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">State :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->alternative_state}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">City :</td>
		<td style="width: 200px;">{{$supplier?->supplierDetails?->alternative_city}}</td>
	</tr>

	<tr style="width: 1180px;">
		<td style="width: 200px;">Telephone No :</td>
		<td style="width: 200px;">{{$supplier?->landline}}</td>

		<td style="width: 50px; "></td>

		<td style="width: 200px;">Image :</td>
		<td style="width: 200px;">
			@if(@$supplier?->supplierDetails?->alternative_file && $supplier?->supplierDetails?->alternative_file !=null)
				<img height="60" width="50" class="alternative_file" src="{{ asset('uploads/supplier/alternative/'.$supplier?->supplierDetails?->alternative_file) }}" alt="">
			@else
				<img height="60" width="50" src="{{ asset('images/default.jpg')}}" alt="">
			@endif
		</td>
	</tr>
</table>

<p><b>   </b></p>

@foreach ($supplier?->supplierContactPersonDetails as $k => $contact_person)
<table style="padding-top: 50px; break-inside: avoid; margin-top: 50px;">
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

<p><b>{{$supplier?->business_name}} </b> &mdash; <a style="text-decoration: none" href="mailto::{{$supplier?->email}}">{{$supplier?->email}}</a> &mdash;<a  style="text-decoration: none" href="callto::{{ $supplier?->phone}}">{{ $supplier?->phone}}</a> </p>
</body>
</html>
