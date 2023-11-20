<div class="">
	<div class="row">
		<div class="col"></div>
		<div class="col-md-12">
			<div class="card">
				<div class="card-header header-elements-inline">
                    @if(auth()->user()->can('hrm_salary_settlement_delete'))
					    <a type="button" class="delete_last btn btn-sm btn-danger float-end px-2 delete" href="{{ route('hrm.delete.last.settlement', $employee->id) }}">{{ __('Delete Last Settlement') }}</a>
                    @endif
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-sm increments_table">
						<tbody>
                            <tr>
                                <th>{{ __('Settlement Date') }}</th>
                                <th>{{ __('Previous Basic') }}</th>
                                <th>{{ __('Previous Gross') }}</th>
                                <th>{{ __('Change Type') }}</th>
                                <th>{{ __('Change Amount') }}</th>
                                <th>{{ __('Amount Type') }}</th>
                                <th>{{ __('Inc/Dec Amount') }}</th>
                                <th>{{ __('Current Basic') }}</th>
                                <th>{{ __('Extra') }}</th>
                                <th>{{ __('Current Gross') }}</th>
                                <th>{{ __('Remarks') }}</th>
                            </tr>
                            @foreach($settlements as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                <td>
                                    @if($item->salary_type === 1 && $item->amount_type === 1 && $item->amount_type != 3)
                                        {{ $item->previous }}
                                    @endif

                                    @if($item->salary_type === 2 && $item->amount_type === 1 && $item->amount_type != 3)
                                        {{ $item->previous }}
                                    @endif

                                    @if($item->salary_type === 1 && $item->amount_type === 2 && $item->amount_type != 3)
                                        {{ $item->previous }}
                                    @endif

                                    @if($item->salary_type === 2 && $item->amount_type === 2 && $item->amount_type != 3)
                                        {{ $item->previous }}
                                    @endif
                                </td>
                                <td>
                                    @if($item->salary_type === 1 && $item->amount_type === 3 && $item->amount_type != 1 && $item->amount_type != 2 )
                                        {{ $item->previous }}
                                    @endif
                                    @if($item->salary_type === 2 && $item->amount_type === 3 && $item->amount_type != 1 && $item->amount_type != 2 )
                                        {{ $item->previous }}
                                    @endif
                                </td>
                                <td>
                                    @if($item->salary_type == 1 )
                                        <span class='text-success fw-bold'>Increment</span>
                                    @endif
                                    @if($item->salary_type == 2 )
                                        <span class='text-danger fw-bold'>Decrement</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->salary_type == 1 )
                                        @if($item->amount_type == 1 )
                                            {{  $item->how_much_amount  }}
                                        @else
                                            {!! $item->how_much_amount !!}
                                        @endif
                                    @endif

                                    @if($item->salary_type == 2 )
                                        @if($item->amount_type == 1 )
                                            {{ $item->how_much_amount }}
                                        @else
                                            {!! $item->how_much_amount !!}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($item->salary_type == 1 )
                                        @if($item->amount_type == 1 )
                                            {!! 'Fixed' !!}
                                        @else
                                            {!! 'Percent' !!}
                                        @endif
                                    @endif

                                    @if($item->salary_type == 2 )
                                        @if($item->amount_type == 1 )
                                            {!! 'Fixed' !!}
                                        @else
                                            {!! 'Percent' !!}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($item->salary_type === 1 && $item->amount_type === 1 && $item->amount_type != 3 && $item->amount_type != 2)
                                        {{ round($item->how_much_amount) }}
                                    @endif
                                    @if($item->salary_type === 1 && $item->amount_type === 2 && $item->amount_type != 3 && $item->amount_type != 1)
                                        {{ round($item->how_much_amount) }}
                                    @endif
                                    @if($item->salary_type === 2 && $item->amount_type === 1 && $item->amount_type != 3 && $item->amount_type != 2)
                                        {!! '(' . round($item->how_much_amount) . ')' !!}
                                    @endif
                                    @if($item->salary_type === 2 && $item->amount_type === 2 && $item->amount_type != 3 && $item->amount_type != 1)
                                        {!! '(' . $item->previous -  $item->after_updated . ')' !!}
                                    @endif
                                </td>
                                <td>
                                    @if($item->salary_type != 1 && $item->amount_type != 3)
                                        @php
                                            echo round($item->after_updated);
                                        @endphp
                                    @endif
                                    @if($item->salary_type != 2 && $item->amount_type != 3)
                                        @php
                                            echo round($item->after_updated);
                                        @endphp
                                    @endif
                                </td>
                                <td>
                                    @if($item->salary_type != 1 && $item->amount_type != 3)
                                        @php
                                            echo $employee->beneficialSalary;
                                        @endphp
                                    @elseif ($item->salary_type != 2 && $item->amount_type != 3)
                                        @php
                                            echo $employee->beneficialSalary;
                                        @endphp
                                    @endif
                                </td>
                                <td>{{ round($item->after_updated + $employee->beneficialSalary) . '.00' }} </td>
                                <td>{{ $item->remarks }}</td>
                            </tr>
                            @endforeach
                        </tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col"></div>
</div>
</div>
