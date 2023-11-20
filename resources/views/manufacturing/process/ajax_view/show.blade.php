<div class="modal-header">
    <h6 class="modal-title" id="exampleModalLabel">{{ $process->product->name.' '.($process->variant ? $process->variant->variant_name : '').' ('.($process->variant ? $process->variant->variant_code : $process->product->product_code).')' }}</h6>
    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
</div>
<div class="modal-body">
    <table class="display data_tbl data__table">
        <thead>
            <tr class="bg-primary">
                <th class="text-startx text-white">@lang('menu.ingredients')</th>
                <th class="text-startx text-white">@lang('menu.quantity')</th>
                <th class="text-startx text-white">Cost Inc.Tax</th>
                <th class="text-startx text-white">@lang('menu.sub_total')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($process->ingredients as $ingredient)
                <tr>
                    <td class="text-start">
                        {{ $ingredient->product->name .' '.($ingredient->variant ? $ingredient->variant->variant_name : '') }}
                    </td>
                    <td class="text-start">{{ $ingredient->final_qty.' '.$ingredient->unit->name }}</td>
                    <td class="text-start">{{ $ingredient->unit_cost_inc_tax }}</td>
                    <td class="text-start">{{ $ingredient->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="display data_tbl data__table">
            <tr>
                <th colspan="3" class="text-end">@lang('menu.total') @lang('menu.ingredients') : {{json_decode($generalSettings->business, true)['currency'] }}</th>
                <th>{{ $process->total_ingredient_cost }}</th>
            </tr>
        </tfoot>
   </table>
    <br>
   <div class="row">
       <div class="col-6">
           <table class="">
               <tbody>
                    <tr>
                        <th class="text-startx">@lang('menu.wastage') : </th>
                        <td class="text-start"> {{ $process->wastage_percent.'%' }}</td>
                    </tr>
                    <tr>
                        <th class="text-startx">@lang('menu.total_output_quantity') : </th>
                        <td class="text-start"> {{ $process->total_output_qty.' '.$process->unit->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-startx">@lang('menu.instructions') : </th>
                        <td ></td>
                    </tr>
               </tbody>
           </table>
       </div>

        <div class="col-6">
            <table class="display data_tbl data__table">
                <tbody>
                    <tr>
                        <th class="text-startx">@lang('menu.additional_cost') : </th>
                        <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.$process->production_cost }}</td>
                    </tr>
                    <tr>
                        <th class="text-startx">@lang('menu.total_cost') </th>
                        <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.$process->total_cost }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
   </div>
</div>

<div class="modal-footer">
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-sm btn-success print_btn">@lang('menu.print')</button>
            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
        </div>
    </div>
 </div>
